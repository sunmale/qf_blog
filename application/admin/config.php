<?php
//配置文件
return [

  //权限配置
    'AUTH_CONFIG'=>[
        'AUTH_ON'           => true,    // 认证开关
        'AUTH_TYPE'         => 1,    // 认证方式，1为实时认证；2为登录认证。
        'AUTH_GROUP'        => 'qf_auth_group',    // 用户组数据表名
        'AUTH_GROUP_ACCESS' => 'qf_auth_group_access',   // 用户-用户组关系表
        'AUTH_RULE'         => 'qf_auth_rule',    // 权限规则表
        'AUTH_USER'         => 'qf_admin',   // 用户信息表
    ]


];