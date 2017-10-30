<?php

namespace util;
use think\Db;

class Auth
{

    //默认配置
    protected $_config = array(
        'AUTH_ON'           => true,    // 认证开关
        'AUTH_TYPE'         => 1,    // 认证方式，1为实时认证；2为登录认证。
        'AUTH_GROUP'        => 'R_Auth_Group',    // 用户组数据表名
        'AUTH_GROUP_ACCESS' => 'R_Auth_Group_Access',   // 用户-用户组关系表
        'AUTH_RULE'         => 'R_Auth_Rule',    // 权限规则表
        'AUTH_USER'         => 'R_User',   // 用户信息表
    );

    public function __construct()
    {
      //  $prefix                             = config('DB_PREFIX');
     //   $this->_config['AUTH_GROUP']        = $prefix . $this->_config['AUTH_GROUP'];
      //  $this->_config['AUTH_RULE']         = $prefix . $this->_config['AUTH_RULE'];
     //   $this->_config['AUTH_USER']         = $prefix . $this->_config['AUTH_USER'];
      //  $this->_config['AUTH_GROUP_ACCESS'] = $prefix . $this->_config['AUTH_GROUP_ACCESS'];
        if (config('AUTH_CONFIG')) {
            //可设置配置项 AUTH_CONFIG, 此配置项为数组。
            $this->_config = array_merge($this->_config, config('AUTH_CONFIG'));
        }
    }



    /**
     * @param $name  //需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param $uid   //认证用户的id
     * @param int $type   执行check的模式
     * @param string $mode
     * @param string $relation   如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @return bool    通过验证返回true;失败返回false
     */
    public function check($name, $uid, $type = 1, $mode = 'url', $relation = 'or')
    {

        if (!$this->_config['AUTH_ON']) {
            return true;
        }

        $authList = $this->getAuthList($uid, $type); //获取用户需要验证的所有有效规则列表

        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = array($name);
            }
        }
        $list = array(); //保存验证通过的规则名
        if ('url' == $mode) {
            $REQUEST = unserialize(strtolower(serialize($_REQUEST)));
        }
        foreach ($authList as $auth) {
            $query = preg_replace('/^.+\?/U', '', $auth);
            if ('url' == $mode && $query != $auth) {
                parse_str($query, $param); //解析规则中的param
                $intersect = array_intersect_assoc($REQUEST, $param);
                $auth      = preg_replace('/\?.*$/U', '', $auth);
                if (in_array($auth, $name) && $intersect == $param) {
                    //如果节点相符且url参数满足
                    $list[] = $auth;
                }
            } else if (in_array($auth, $name)) {
                $list[] = $auth;
            }
        }
        if ('or' == $relation and !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ('and' == $relation and empty($diff)) {
            return true;
        }
        return false;
    }

    /**
     * 根据用户id获取用户组,返回值为数组
     * @param  uid int     用户id
     * @return array       用户所属的用户组 array(
     *     array('uid'=>'用户id','group_id'=>'用户组id','title'=>'用户组名称','rules'=>'用户组拥有的规则id,多个,号隔开'),
     *     ...)
     */
    public function getGroups($uid)
    {
        static $groups = array();
        if (isset($groups[$uid])) {
            return $groups[$uid];
        }
        $user_groups = Db::table($this->_config['AUTH_GROUP_ACCESS'] . ' a')
            ->join($this->_config['AUTH_GROUP'] . " g", "g.id=a.group_id")
            ->where("a.uid='$uid' and g.status='1'")
            ->field('uid,group_id,title,rules')->select();
        $groups[$uid] = $user_groups ?: array();
        return $groups[$uid];
    }

    /**
     * 获得权限列表
     * @param $uid
     * @param $type
     * @return array|mixed
     */
    protected function getAuthList($uid, $type)
    {
        static $_authList = array(); //保存用户验证通过的权限列表
        $t       = implode(',', (array) $type);
        if (isset($_authList[$uid . $t])) {
            return $_authList[$uid . $t];
        }
         $authList_id =  session('authList'.$uid);
        if (2 == $this->_config['AUTH_TYPE'] && isset($authList_id)) {
            return $authList_id;
        }

        //读取用户所属用户组
        $groups = $this->getGroups($uid);

        $ids    = array(); //保存用户所属用户组设置的所有权限规则id
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        $ids = array_unique($ids);
        if (empty($ids)) {
            $_authList[$uid . $t] = array();
            return array();
        }

        $map = array(
            'id'     => array('in', $ids),
            'type'   => $type,
            'status' => 1,
        );

        //读取用户组所有权限规则
        $rules =  Db::table($this->_config['AUTH_RULE'])->where($map)->field('condition,name')->select();


        //循环规则，判断结果。
        $authList = array(); //
        foreach ($rules as $rule) {
            if (!empty($rule['condition'])) {
                //根据condition进行验证
                $user = $this->getUserInfo($uid); //获取用户信息,一维数组
                $command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $rule['condition']);
                //dump($command);//debug
                 @(eval('$condition=(' . $command . ');'));
                if ($condition) {
                    $authList[] = strtolower($rule['name']);
                }
            } else {
                //只要存在就记录
                $authList[] = strtolower($rule['name']);
            }
        }
        $_authList[$uid . $t] = $authList;
        if (2 == $this->_config['AUTH_TYPE']) {
            //规则列表结果保存到session
           //  $_SESSION['_AUTH_LIST_' . $uid . $t] = $authList;
            session('authList'.$uid,$authList);

        }
        return array_unique($authList);
    }



    /**
     * 获得用户资料,根据自己的情况读取数据库
     */
  /*  protected function getUserInfo($uid)
    {
        static $userinfo = array();
        if (!isset($userinfo[$uid])) {
            $userinfo = Db::table($this->_config['AUTH_USER'])->where(array('id' => $uid))->find();
        }
        return $userinfo;
    }*/


    protected function getUserInfo($uid)
    {
        static $userinfo = array();
        if (!isset($userinfo[$uid])) {
            $userinfo[$uid] = Db::table($this->_config['AUTH_USER'])->where(array('id' =>$uid))->find();
        }
        return $userinfo[$uid];
    }



}
