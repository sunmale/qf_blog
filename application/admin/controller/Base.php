<?php
namespace app\admin\controller;
use think\Controller;
use think\Log;
use think\Request;
use util\Auth;

class Base extends  Controller
{

    /**
     * 初始化页面
     */
    public function _initialize()
    {
        $admin = session('qf_blog_admin');
        if(!$admin){
            $this->redirect('admin/Login/index');
        }else{
            $auth = new Auth();
            $request =  Request::instance();
            $url =  $request->module().'/'.$request->controller().'/'.$request->action();

            $not_check = array('admin/Index/index','admin/Index/main','admin/Login/index','admin/Admin/loginout','admin/Api/getrulelist');
            //当前操作的请求 模块名/方法名
            if($admin['is_admin']!=1){
                if(!in_array($url,$not_check)){
                    $res =  $auth->check($url,$admin['id']);
                    if(!$res){
                        // echo '没有权限';
                        $this->error('你没有该操作权限',$url);
                    }
                }
           }
        }

        //读取配置信息
         $config['qf_blog_version'] = config('blog.qf_blog_version');
        $this->assign([
            'config'=>$config
        ]);

    }



    /**
     * 授权登录信息是否获取到   用户非法操作
     */
    public function base()
    {
        $user = session('qf_blog_admin');
        if (empty($user)) {
            echo '没有查找到个人信息，请联系管理员';
            exit();
        } else {
            return $user;
        }
    }




}
