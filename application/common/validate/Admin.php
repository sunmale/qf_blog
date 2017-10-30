<?php

namespace app\common\validate;

use think\Validate;

class Admin extends Validate
{
//验证规则
    protected $rule = [
        'username' => 'require|min:4|max:12|token',
        'password' => 'require|min:6|max:16',
        'email'=>'require|email'
    ];
//字段别名
    protected $field = [
        'username' => '用户名',
        'password' => '密码',
        'email' => '邮箱',
    ];
//自定义提示信息
    protected $message = [
           'username.require' => '用户名必须填写',
           'username.min'    => '用户名不能少于4个字符',
           'username.max' => '用户名不能多于12个字符',
    ];
//验证场景
    protected $scene = [
        'login' => ['username', 'password'],
        'register' => ['username', 'password','email'],
    ];


}