<?php

namespace app\common\validate;

use think\Validate;

class User extends Validate
{

//验证规则
    protected $rule = [
        'email' => 'require|email|token',
        'password' => 'require|min:6|max:12',
    ];
//字段别名
    protected $field = [
        'password' => '密码',
        'email' => '邮箱',
    ];
//自定义提示信息
    protected $message = [
           'password.require' => '密码必须填写',
           'password.min'    => '密码不能少于6个字符',
           'password.max' => '密码不能多于12个字符',
    ];
//验证场景
    protected $scene = [
        'login' => ['email', 'password'],
        'reg' => ['email', 'password'],
    ];


}