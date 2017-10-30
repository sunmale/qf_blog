<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;


//绑定到index模块

    Route::rule('','index/Index/index');
    Route::rule('search/:search','index/Index/index');
    Route::rule('search/:search/page/:page','index/Index/index');

    Route::rule('tag/:tag','index/Index/index');
    Route::rule('tag/:tag/page/:page','index/Index/index');

    Route::rule('date/:date','index/Index/index');
    Route::rule('date/:date/page/:page','index/Index/index');

    Route::rule('typeid/:typeid','index/Index/index');
    Route::rule('typeid/:typeid/page/:page','index/Index/index');


/*    Route::rule('search','index/Index/index');
    Route::rule('search/page/:page','index/Index/index');*/
    Route::rule('page/:page','index/Index/index');

    Route::rule('/:id','index/Index/detail','get',[],['id'=>'\d+']);
    Route::rule('about','index/Index/about');
    Route::rule('said','index/Said/index');
    Route::rule('login','index/User/login');
    Route::rule('reg','index/User/reg');
Route::rule('loginout','index/User/loginout');
    //激活邮件路由
    Route::rule('send_email','index/User/send_email');

    Route::rule('message','index/Message/index');
    Route::rule('message/add','index/Message/add');









/*return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];*/
