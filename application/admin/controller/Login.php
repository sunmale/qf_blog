<?php

namespace app\admin\controller;
use app\common\model\AdminModel;
use think\Cache;
use think\captcha\Captcha;
use think\Controller;
use think\Loader;
use think\Log;

class Login extends Controller
   {

      /**
       * 登录页面
       */
    public  function  index(){
        //设置缓存
        /*        $cacheData['type'] = 'file';
                $cacheData['diff'] = 'path';
                $cacheData['path'] = BASE_PATH.'/static/cache/admin/';
                setCache($cacheData);
                cache('qf_blog_admin', $res, 300);*/
         return $this->fetch();

    }

    /**
     * 登录方法
     */
    public function login()
    {
        $user = new AdminModel();
        if (request()->isAjax()) {
            $data['username'] = $_POST['username'];
            $data['password'] = md5($_POST['password']);
            //验证验证码是否正确
            $code = $_POST['code'];
            $captcha = new Captcha();
            if (!$captcha->check($code)) {
                return json(['code' => -1, 'msg' => "验证码不正确"]);
            }
            //数据验证
            $validate = Loader::validate('Admin');
            if(!$validate->scene('login')->check(request()->post())){
                return json(['code' => -1, 'msg' => $validate->getError()]);
            }

                $res = $user->selectInfoByMap($data,1);
                if(empty($res)){
                    return json(['code' => -1, 'msg' => "账号密码不正确,登录失败"]);
                }
                if ( $res && $res->toArray() != -1 ) {
                    if ($res['is_active']==0) {
                        //发送邮件通知该管理员激活账号
                        $blog_url = config('blog.qf_blog_url');
                        $user['email'] = $res['email'];
                        $user['name'] = $res['username'];
                        $data['title'] = '请激活晴枫博客后台管理员账号';
                        $data['html'] = '<div><b><font size="1">亲爱的'. $user['name'].',欢迎注册晴枫博客管理后台管理员，请点击下面链接进行邮件激活。</font></b></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size="2">&nbsp;&nbsp;<b>&nbsp;&nbsp;
                    <a href="'.$blog_url.'/admin/Login/active_email?admin_id='.$res['id'].'">点击激活</a></b></font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                        sendMail($user, $data);
                        return json(['code' => -1, 'msg' => "账号需要激活才能使用，去邮箱激活吧!"]);
                    } else if ($res['status']==0) {
                        return json(['code' => -1, 'msg' => "账号处于禁用状态!"]);
                    }else{
                        //把用户信息保存到session中
                        session('qf_blog_admin',$res);
                        return json(['code' => 1, 'url' => url('admin/Index/index'), 'msg' => '登录成功,正在跳转...']);
                    }
                } else {
                    return json(['code' => -1, 'msg' => "程序异常！"]);
                }
        }
    }



    /**
     * @return mixed
     * 生成验证码
     */
    public function checkVerify()
    {
        $config =  [
            // 验证码字体大小
            'fontSize'    =>    30,
            'codeSet' => '0123456789',
            'useZh'=>false,
            // 验证码位数
            'length'      => 4,
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }







    /**
     * 邮件激活
     */
    public  function  active_email (){
        $admin = new AdminModel();
        $admin_info['id']  =request()->param('admin_id');
        $admin_info['is_active'] =1;
       $res =  $admin->editAdminById($admin_info);
       if($res==-1){
           $this->error('激活失败，请重新激活!','admin/Login/index');
       }else{
           $this->success('账号激活成功','admin/Login/index');
       }
    }





    /**
     * 空操作
     */
    public  function  _empty(){
        return $this->fetch('Error/404');
        // $this->error('空操作，正在跳转','Index/main');
    }

}




