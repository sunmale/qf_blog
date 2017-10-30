<?php
namespace app\admin\controller;

use app\common\model\AdminModel;
use app\common\model\GroupAccessModel;
use app\common\model\GroupModel;
use think\Db;
use think\Loader;

class Admin extends Base
{

    /**
     * @return mixed
     * 显示用户信息
     */
    public function index()
    {
        $admin = new AdminModel();
        if (request()->isGet()) {
            $data = request()->param();
            if (!empty($data['page'])) {
                if (!empty($data['key'])) {
                    $map['username'] = ['like', "%$data[key]%"];
                } else {
                    $map = [];
                }
                $page['pageIndex'] = request()->param('page');
                $page['pageSize'] = request()->param('limit');
                $list = $admin->selectPageByMap($map, $page);
                foreach ($list as $k => $v) {
                    $v['statusName'] = $v['status_name'];
                    $list[$k] = $v;
                }
                $result['code'] = 0;
                $result['msg'] = "";
                $result['count'] = $admin->selectCountByMap($map);
                $result['data'] = $list;
                return json($result);
            } else {
                return $this->fetch();
            }
        }
    }



    /**
     * 添加管理员的方法
     * @return mixed
     */
    public function add()
    {
        $group = new GroupModel();
        $admin = new AdminModel();
        if (request()->isGet()) {
            $map = [];
            $groupList = $group->selectByMap($map);
            $this->assign('groupList', $groupList);
            return $this->fetch();
        }
        if (request()->isAjax()) {
            $data = request()->post();
            $data['password'] = md5($data['password']);
            $username_map['username'] = $data['username'];
            //判断用户名是否已经存在了
            $s_res = $admin->selectInfoByMap($username_map,1);
            if($s_res && $s_res->toArray()!=-1){
                return json(['code' => -1, 'msg' => '该用户已经存在了']);
            }

            //判断邮箱是否已经注册
            $map_email['email'] = $data['email'];
            //判断用户名是否已经存在了
            $em_res = $admin->selectInfoByMap($map_email,1);
            if ( $em_res && $em_res->toArray() != -1) {
                return json(['code' => -1, 'msg' => '该邮箱已经存在了']);
            }
            //数据验证
            $validate = Loader::validate('Admin');
            if (!$validate->scene('register')->check(request()->param())) {
                $errInfo = $validate->getError();
                return json(['code' => -1, 'msg' => $errInfo]);
            }
            //开启事务
            Db::startTrans();
            //可以注册
            //释放掉表单令牌数据
            unset($data['__token__']);
            $data['faceurl'] = config('blog.qf_blog_url') .'/static/common/images/face/'.rand(0,10).'.jpg';
            $res = $admin->insertAdmin($data);
            if ($res == -1) {
                return json(['code' => -1, 'msg' => '注册出现错误，请联系管理员']);
            } else {
                if ($res) {
                    //发送邮件通知该管理员激活账号
                    $user['email'] = $data['email'];
                    $user['name'] = $data['username'];
                    $data['title'] = '请激活晴枫博客后台管理员账号';
                    $blog_url = config('blog.qf_blog_url');
                    $data['html'] = '<div><b><font size="1">亲爱的'. $user['name'].',欢迎注册晴枫博客管理后台管理员，请点击下面链接进行邮件激活。</font></b></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size="2">&nbsp;&nbsp;<b>&nbsp;&nbsp;
                    <a href="'.$blog_url.'/admin/Login/active_email?admin_id='.$admin['id'].'">点击激活</a></b></font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                    $res = sendMail($user, $data);
                    if (!$res) {
                        //回滚事务
                        Db::rollback();
                        return json(['code' => -1, 'msg' => '邮件发送失败，注册失败！']);
                    }
                    //提交事务
                    Db::commit();
                    return json(['code' => 1, 'msg' => '注册管理员成功']);
                } else {
                    return json(['code' => -1, 'msg' => '注册管理员失败']);
                }
            }
        }
    }



    /**
     * 修改管理员信息
     */
    public function edit()
    {
        $admin = new  AdminModel();
        $group = new GroupModel();
        $group_access = new GroupAccessModel();
        if (request()->isGet()) {
            $id = input('param.id');
            $admin_map['id'] = $id;
            $user_info = $admin->selectInfoByMap($admin_map, 1);
            $group_map = [];
            $groutList = $group->selectByMap($group_map);
            $list = $group_access->getGroupsByAdminId($id);
            $this->assign([
                'user' => $user_info,
                'groupList' => $groutList,
                'groupArray' => $list
            ]);
            return $this->fetch();
        }
        if (request()->isAjax()) {
            $data = request()->post();
            $res = $admin->editAdminById($data);
            if ($res == -1) {
                return json(['code' => -1, 'msg' => '程序异常']);
            } else {
                return json(['code' => 1, 'msg' => '修改管理员信息成功', 'url' => url('Admin/index')]);
            }
        }
    }



    /**
     * 删除用户信息
     */
    public function delete()
    {
        $admin = new AdminModel();
        if (request()->isAjax()) {
            $id = request()->param('id');
            $res = $admin->delAdminById($id);
            if ($res == -1) {
                return json(['code' => '-1', 'msg' => '删除管理员出现错误，请联系管理员']);
            } else {
                if ($res) {
                    return json(['code' => '1', 'msg' => '删除管理员成功', 'url' => url('Admin/index')]);
                } else {
                    return json(['code' => '-1', 'msg' => '删除管理员失败']);
                }
            }
        }
    }



       /**
     * 修改密码
     * @return mixed
     */
    public function updatePassword()
    {

        $user = session('qf_blog_admin');

        $admin = new AdminModel();
        if (request()->isGet()) {
            $this->assign('admin', $user);
            return $this->fetch();
        }

        if (request()->isAjax()) {
            $data = request()->post();
            $data['update_time'] = time();
            $data['password'] = md5($data['password']);
            unset($data['confirm_password']);
            $res = $admin->editAdminById($data);
            if($res==-1){
                return json(['code'=>-1,'msg'=>'程序异常！']);
            }else{
                return json(['code'=>1,'msg'=>'修改密码成功！']);
            }

        }
    }




    //注销用户
    public function  loginOut(){
        if(request()->isAjax()){
            session('qf_blog_admin',null);
            return json(['code'=>1,'msg'=>'注销用户成功']);
        }
    }




}
