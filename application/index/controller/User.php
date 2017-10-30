<?php
namespace app\index\controller;
use app\common\model\UserModel;
use comm\http;
use qf\Oauth;
use think\captcha\Captcha;
use think\Controller;
use think\Loader;
use think\Log;

class User extends Base
{


    /**
     * 登陆页面
     * @return mixed
     */
    public function login()
    {
        $user = new UserModel();

        //如果已经登陆就不用登陆了
        $qf_blog_user = $this->base();
        if(!empty($qf_blog_user)){
             $this->redirect('index/Index/index');
        }

        if(request()->isGet()){
            return $this->fetch();
        }
        if(request()->isAjax()){
            $data['username'] = $_POST['email'];
            $data['password'] = md5($_POST['password']);
            //验证验证码是否正确
            $code = $_POST['code'];
            $captcha = new Captcha();
            if (!$captcha->check($code)) {
                return json(['code' => -1, 'msg' => "验证码不正确"]);
            }
            //数据验证
            $validate = Loader::validate('User');
            if(!$validate->scene('login')->check(request()->post())){
                $errInfo = $validate->getError();
                return json(['code' => -1, 'msg' => $errInfo]);
            }
            $res = $user->selectInfoByMap($data, 1);

            if(empty($res)){
                return json(['code' => -1, 'msg' => "账号密码不正确,登录失败"]);
            }
            if ($res->toArray() == -1) {
                return json(['code' => -1, 'msg' => "程序异常！"]);
            } else {
                 if ($res['is_active']==0) {
                     //发送激活邮件信息
                    return json(['code' => -2, 'msg' => "账号需要激活才能使用，去邮箱激活吧!"]);
                } else if ($res['status']==0) {
                    return json(['code' => -1, 'msg' => "账号处于禁用状态!"]);
                }else{
                    //把用户信息保存到session中
                    session('qf_blog_user',$res);
                    return json(['code' => 1, 'url' => url('index/Index/index'), 'msg' => '登录成功,正在跳转...']);
                }
            }
        }
    }



    /**
     * 注册页面
     * @return mixed
     */
    public function reg()
    {
        $user = new UserModel();
        if(request()->isGet()){
            return $this->fetch();
        }
        if(request()->isAjax()){
            $data =request()->post();
            $data['password'] = md5($data['password']);
            $data['username'] =$data['email'];
            //验证验证码是否正确
            $code = $data['code'];
            $captcha = new Captcha();
            if (!$captcha->check($code)) {
                return json(['code' => -1, 'msg' => "验证码不正确"]);
            }
            //判断邮箱是否已经注册
            $map_email['email'] = $data['email'];
            //判断用户名是否已经存在了
            $em_res = $user->selectInfoByMap($map_email);
            if ($em_res == -1) {
                return json(['code' => -1, 'msg' => '查询出现错误，请联系管理员']);
            } else {
                if ($em_res) {
                    return json(['code' => -1, 'msg' => '该邮箱已经注册了']);
                }
            }
            //数据验证
            $validate = Loader::validate('User');
            if(!$validate->scene('reg')->check(request()->post())){
                $errInfo = $validate->getError();
                return json(['code' => -1, 'msg' => $errInfo]);
            }
            //释放掉表单令牌数据
            unset($data['__token__']);
            unset($data['code']);
            unset($data['repass']);
            //获取头像
            $data['headurl'] = config('blog.qf_blog_url') .'/static/common/images/face/'.rand(0,10).'.jpg';
            //可以注册

            Log::info(var_export($data,true));
          $res =   $user->insertUser($data);
          if($res==-1){
              return json(['code' => -1, 'msg' => '注册出现异常','url'=>url("index/User/reg")]);
          }else{
              return json(['code' => 1, 'msg' => '注册成功,请激活邮件!','url'=>url("index/User/login")]);
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
     * 发送激活邮件
     */
    public  function send_email(){
         $user = new UserModel();
         $blog_url = config('blog.qf_blog_url');
         $user['email'] = request()->param('email');
         $map_email['email'] =$user['email'];
        //判断用户名是否已经存在了
         $res = $user->selectInfoByMap($map_email,1);
          $user['name'] = $res['nickname'];
          $data['title'] = '请激活晴枫博客注册账号';
          $data['html'] = '<div><b><font size="1">亲爱的'. $user['name'].',欢迎注册晴枫博客会员，请点击下面链接进行邮件激活。</font></b></div>
                           <div>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                           <div>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size="2">&nbsp;&nbsp;<b>&nbsp;&nbsp;
                           <a href="'.$blog_url.'/index/User/active_email?user_id='.$res['id'].'">点击激活</a></b></font></div>
                           <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>';
          sendMail($user, $data);
    }



    /**
     * 邮件激活
     */
    public  function  active_email (){
        $user = new UserModel();
        $user_info['id']  =request()->param('user_id');
        $user_info['is_active'] =1;
        $res =  $user->editUserById($user_info);
        if($res==-1){
            $this->error('激活失败，请重新激活!','index/User/login');
        }else{
            $this->success('账号激活成功','index/User/login');
        }
    }


    //qq快捷登录
     public  function  login_qq(){
        $qq_config= config('oauth.qq');
        $url  = Oauth::getInstance()->init($qq_config)->getCodeByRequestUrl('qq');
         $this->redirect($url);
     }


    /**
     * qq授权回调地址
     */
     public  function  user_info_qq(){
         if(!empty(request()->get('code'))){
             $this->getUserInfoByQQ(request()->get('code'));
         }
     }



    /**
     * 微信登录
     */
     public function login_wx(){
         $user = new UserModel();
         $openid  =request()->param('wx_openid');
         if(!empty($openid)){
             $map['wx_openid'] = $openid;
             //判断用户名是否已经存在了
             $res = $user->selectInfoByMap($map,1);
             if($res && $res->toArray()!=-1){
                 session('qf_blog_user',$res);
                 $this->success('登录成功','index/Index/index');
             }else{
                 if(empty($res)){
                     $this->redirect('index/Index/index');
                 }else if ($res==-1){
                     $this->error('出现异常','index/Index/index');
                 }

             }
         }else{
             $this->redirect('index/Index/index');
         }
     }

    /**
     * 绑定邮箱
     */
     public  function  bind_email(){
         $user_info = $this->base();    //获取用户是否登录
         $user = new UserModel();
         $data = request()->post();
         //判断邮箱是否已经注册
         $map_email['email'] = $data['email'];
         //判断用户名是否已经存在了
         $em_res = $user->selectInfoByMap($map_email,1);
         if ($em_res && $em_res->toArray() != -1) {
             return json(['code' => -1, 'msg' => '该邮箱已经注册了']);
         } else {
              if($em_res==-1){
                  return json(['code' => -1, 'msg' => '程序异常']);
              }
         }
         //已经使用qq登录了，用于绑定邮箱
         $update_user_data['email'] = $data['email'];
         $update_user_data['username'] = $data['email'];
         $update_user_data['password'] = md5($data['bind_user_password']);
         $update_user_data['id'] =$user_info['id'];
         $update_user_res =  $user->editUserById($update_user_data);
         if($update_user_res==-1){
             return json(['code' => -1, 'msg' => '程序异常']);
         }else{
             $user_info['email'] = $data['email'];
             session('qf_blog_user',$user_info);
             return json(['code' => 1, 'msg' => '绑定邮箱成功,去邮箱激活即可']);
         }
     }




    /**
     * 注销会员信息
     */
    public  function  loginout(){
        session('qf_blog_user',null);
        $this->redirect('index/Index/index');

    }


}
