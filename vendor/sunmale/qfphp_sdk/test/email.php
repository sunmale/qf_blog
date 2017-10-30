<?php
/**
 * Created by PhpStorm.
 * User: PVer
 * Date: 2017/9/8
 * Time: 11:32
 */
use qf\Mail;

//composer加载
 // require  '../vendor/autoload.php';

//直接下载加载
require  '../Auto_load.php';

$user['email'] = '1982127547@qq.com';
$user['name'] = 'sunamle';
$data['title'] = '请激活晴枫博客后台管理员账号';
$data['html'] = '欢迎注册晴枫博客后台管理员';
$result =  Mail::getInstance()->sendMail($user,$data);
    if($result){
        echo 'send succuess';
    }else{
        echo 'send faild';
    }




