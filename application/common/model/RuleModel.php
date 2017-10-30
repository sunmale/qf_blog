<?php

namespace app\common\model;
use think\Db;
use think\Log;

class RuleModel extends BaseModel
{

    protected $name = 'auth_rule';           //指定数据表名

    //开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    //数据完成
    protected $insert = ['status' =>1,'type'=>1];
    protected $update = [];

    //获取器（定义不存在的字段）
    public function getStatusNameAttr($value,$data)
    {
        $status = [
            1=>'正常',
            0=>'禁用'
        ];
        return $status[$data['status']];
    }


    /**返回所有的菜单集合
     * @auth 晴枫  sunmale
     * @time 2017-09-06 13:31
     * @return false|int|mixed|\PDOStatement|string|\think\Collection
     */
    public  function  selectRuleList(){
        try{
            $list=   $this->order('sort asc')->select();
            return $list;
        }catch (\Exception $e){
            Log::info('返回所有的菜单集合出现错误.错误位置common/RuleModel/selectRuleList. 错误原因:' .$e->getMessage());
            return -1;
        }
    }

    /**
     * 通过指定的id获取菜单详细内容
     * @param $id   //菜单的id
     * @return array|false|\PDOStatement|string|Model  返回数据集合
     * @auth 晴枫  sunmale
     * @time 2017-09-13 10:40
     */
    public  function  selectRuleInfoById($id){
        try{
            $res =  $this->where('id',$id)->find();
            return $res;
        }catch (\Exception $e){
            Log::info('通过菜单id查询对象出现错误.错误位置common/RuleModel/selectRuleInfoById. 错误原因:' .$e->getMessage());
            return -1;
        }
    }


    /**
     * 通过用户组获取去权限菜单集合
     * @param $array  //数组对象
     *  * @auth 晴枫  sunmale
     * @time 2017-09-11 13:31
     * @return false|mixed|\PDOStatement|string|\think\Collection
     */
    public  function  selectRuleByGroups($array){
        try{
            $list= $this->where("id","in",$array)->select();
            return $list;
        }catch (\Exception $e){
            Log::info(' 通过用户组获取去权限菜单集合出现错误.错误位置common/RuleModel/selectRuleByGroups. 错误原因:' .$e->getMessage());
            return -1;
        }
    }


    /**
     * 新增一条菜单记录
     * @param $data  //新增加的菜单信息
     * @return int|string  返回值的类型
     * @auth 晴枫  sunmale
     * @time 2017-09-12 22:34
     * @auth 晴枫  sunmale
     */
    public  function  insertRule($data){
        try{
            $res = $this->save($data);
            return $res;
        }catch(\Exception $e){
            Log::info('新增菜单出现错误.错误位置i错误位置common/RuleModel/insertRule. 错误原因:' .$e->getMessage());
            return -1;
        }
    }

    /**
     * 通过id修改菜单信息
     * @param $data
     * @return false|int
     * @auth 晴枫  sunmale
     * @time 2017-09-13 11:08
     */
    public  function  editRuleById($data){
        try{
            $res  = $this->save($data,['id'=>$data['id']]);
            return $res;
        }catch (\Exception $e){
            Log::info('通过id修改菜单信息出现错误.错误位置common/RuleModel/editRuleById. 错误原因:' .$e->getMessage());
            return -1;
        }
    }



    /**
     * 通过菜单id删除该菜单
     * 删除条件  如果删除某个菜单  他存在子菜单  必须先删除子菜单
     * @param $id
     * @auth 晴枫  sunmale
     * @time 2017-09-13 11:24
     * @return int
     */
    public  function  deleteRuleById($id){
        try{
            $rule =  $this->selectRuleInfoById($id);  //将要删除的集合
            $list = $this->selectRuleList();   //所有菜单集合
            foreach ($list as $k=>$v){
                if($v['pid'] == $rule['id']){
                    return -2;   //返回信息
                    break;
                }
            }
            $res = $this->where('id',$id)->delete();
            return $res;
        }catch (\Exception $e){
            Log::info('通过菜单id删除该菜单出现错误.错误位置common/RuleModel//deleteRuleById. 错误原因:' .$e->getMessage());
            return -1;
        }
    }



    /**
     *后台左侧菜单使用
     * 根据用户id查询自己的菜单
     * @param $id  //用户id
     * @auth 晴枫  sunmale
     * @time 2017-09-11 17:33
     *@return array|false|int|mixed|\PDOStatement|string|\think\Collection  返回数据信息
     */
    public  function  getRulesByAdminIdForNav($id){
        try{
            $title="";
            $resultList = array();
            //获取所有对象组
            $admin = new AdminModel();
            //判断是否是管理员
            $admin_map['id'] = $id;
            $info = $admin->selectInfoByMap($admin_map,1);
            if($info['is_admin']==1){
                $resultList = $this->selectRuleList();
            }else{
                //通过数组获取所有组集合
                $group_list =  $admin->get($id)->groups()->select();
                if($group_list){
                    foreach ($group_list as $k=>$v){
                        $title  .=    $v['rules'] .",";
                    }
                    $title =  substr($title,0,strlen($title)-1);  //去除最后一个字符
                    $arr =  explode(",",$title);     //得到所有权限的数组
                    $resultList=   $this->selectRuleByGroups($arr);    //通过数组查询相应的权限集合

                }
            }
            foreach ($resultList as $k=>$v) {
                if( $v['name']!="#"){
                    $v['name'] =url($v['name']);
                }else{
                    $v['name']="";
                }
                $resultList[$k] =$v;
            }

            $resultList =  nav_left_tree($resultList,0,0,2);
            return $resultList;
        }catch (\Exception $e){
            Log::info('根据用户id查询自己的菜单出现错误.错误位置common/RuleModel/getRulesByAdminIdForNav. 错误原因:' .$e->getMessage());
            return -1;
        }
    }




















    /**
     *
     * 根据用户id查询自己的菜单
     * @param $id 用户id
     * @return array  返回数据信息
     */
    public  function  getRulesById($id){
        $title="";
        //获取所有对象组
        $group_access =  new GroupAccessModel();
        $group = new GroupModel();
        $array = $group_access->getGroupsById($id);
        //通过数组获取所有组集合
        $list =  $group->getGroupByArray($array);
        if($list){
            foreach ($list as $k=>$v){
                $title  .=    $v['rules'] .",";
            }
            $title =  substr($title,0,strlen($title)-1);  //去除最后一个字符
            $arr =  explode(",",$title);     //得到所有权限的数组
            $resultList=   $this->selectRuleByArray($arr);    //通过数组查询相应的权限集合
            foreach ($resultList as $k=>$v) {
                if( $v['name']!="#"){
                    $v['name'] = url($v['name']);
                }
                $resultList[$k] =$v;
            }
            //  $resultList =  getAllCaiDan($resultList,0,0,2);
            return $resultList;
        }
    }





}