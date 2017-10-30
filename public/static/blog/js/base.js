layui.config({
    base: '/static/blog/js/'
}).use(['carousel', 'form','element','laypage','layedit','util','comm','edit'], function(){
    var $= layui.jquery,
        carousel = layui.carousel,
        form = layui.form,
        laypage = layui.laypage,
        layedit = layui.layedit,
        util = layui.util,
        element = layui.element, //导航的hover效果、二级菜单等功能，需要依赖element模块
        comm = layui.comm;
        edit = layui.edit;

    //判断手机端
    var is_mobile =  comm.browserRedirect();

//自定义验证规则
form.verify({
   user_nikcname: function(value){
        if(value.length<=0){
            return '昵称不能为空';
        }
    },
    user_password: function(value){
        if(!/(.+){6,12}$/.test(value)){
        return '密码必须6到12位';
        }
    },
    user_repassword: function(value){
        if(!/(.+){6,12}$/.test(value)){
            return '密码必须6到12位';
        }else  if( value!=  $(":input[name='password']").val() ){
            return '重复密码必须跟密码一致';
        }
    },
    bind_user_password: function(value){
        if(!/(.+){6,12}$/.test(value)){
            return '密码必须6到12位';
        }
    },
    user_code:function (value) {
        if(value.length<=0){
            return '验证码不能为空';
        }
    }

});



/**
 * 注册用户
 */
form.on('submit(reg_user)', function(data){
    var reg_index = layer.msg('注册中，请稍候',{icon: 16,time:false,shade:0.8});
     $.post("reg",data.field,function (res) {
         if(res.code==1){
             layer.msg(res.msg,{
                 time:1000,
                 icon:1
             },function () {
                 //发送激活邮件
                 window.location.href =res.url;
                 $.post("send_email",{email:data.field.email},function () {
                 });
             });
         }else {
             layer.close(reg_index);
             layer.msg(res.msg,{
                 icon:2
             });
         }
     });
    return false;
});


/**
 * 用户登陆
 */
form.on('submit(login_user)', function(data){
    var lgoin_index = layer.msg('登录中，请稍候',{icon: 16,time:false,shade:0.8});
    $.post("login",data.field,function (res) {
        if(res.code==1){
            layer.close(lgoin_index);
            layer.msg(res.msg,{
                time:1000,
                icon:1
            },function () {
                window.location.href =res.url;
            });
        }else if (res.code ==-2){
            layer.msg(res.msg,{
                icon:2
            });
            //需要激活邮件
            layer.close(lgoin_index);
            $.post("send_email",{email:data.field.email},function () {
            });
        }else {
            layer.close(lgoin_index);
            layer.msg(res.msg,{
                icon:2
            });
        }
    });
    return false;
});



//绑定qq
    $('.bind_qq').on('click',function () {
        layer.confirm('确定绑定QQ？',{icon:3, title:'提示信息'},function(index){
            window.location.href= '/index/User/login_qq';
            layer.close(index);
        });
    });


    if(is_mobile=='mobile'){
        //绑定邮箱显示
        $('.bind_email').on('click',function () {
            layer.open({
                type: 1,
                title:'邮箱绑定',
                content: $('#bind_email_view'),
                area: ['100%', '40%']
            });
        });
    }else{
        //绑定邮箱显示
        $('.bind_email').on('click',function () {
            layer.open({
                type: 1,
                title:'邮箱绑定',
                content: $('#bind_email_view'),
                area: ['400px', '260px']
            });
        });
    }
    /**
     * 绑定邮箱操作
     */
    form.on('submit(bind_email)', function(data){
        var bind_email_index = layer.msg('绑定邮箱中，请稍候',{icon: 16,time:false,shade:0.8});
        $.post("/index/User/bind_email",data.field,function (res) {
            if(res.code==1){
                layer.close(bind_email_index);
                layer.msg(res.msg,{
                    time:1000,
                    icon:1
                },function () {
                     window.location.reload();
                    $.post("send_email",{email:data.field.email},function () {
                    });
                });
            }else {
                layer.close(bind_email_index);
                layer.msg(res.msg,{
                    icon:2
                });
            }
        });
         return false;
    });




//用户退出登陆
    $('.loginout').on('click',function () {
        layer.confirm('确定退出登陆？',{icon:3, title:'提示信息'},function(index){
             window.location.href= '/loginout';
            layer.close(index);
        });
    });




/*搜索文章查询*/
 $('#article_search').keydown(function(event) {
         if (event.keyCode == 13) {
             var search = $(this).val();
             if(search.length<=0){
                 layer.msg('搜索内容不能为空');
                 return false;
             }
             window.location.href = '/index.php/search/'+search;
             return false;
         }
 });



    //监听导航点击
  /*  element.on('nav(demo)', function(elem){
        layer.msg(elem.text());
    });*/
    //常规轮播
    carousel.render({
        elem: '#qf_blog_index_carousel', //指向容器选择器，如：elem: '#id'。也可以是DOM对象
        width:'100%',
        height:'300'
    });
    if(is_mobile=='mobile'){
        $('.qf_blog_header div.layui-container ul').addClass('layui-nav-tree');
        $('.qf_blog_header div.layui-container ul').addClass('layui-inline');
        $('.qf_blog_header i.qf_blog_nav_more').css('display','inline-block');
        //常规轮播
        carousel.render({
            elem: '#qf_blog_index_carousel', //指向容器选择器，如：elem: '#id'。也可以是DOM对象
            width:'100%',
            height:'240'
        });
    }


    //适应手机端导航
    var nav_status = false;
    $('.qf_blog_nav_more').on('click',function () {
        if(nav_status){
            $('.qf_blog_nav').hide();
            $('.qf_blog_mask').removeClass('layui-show');
            $('.qf_blog_mask').addClass('layui-hide');
            nav_status =false;
        }else{
            $('.qf_blog_nav').show();
            $('.qf_blog_mask').removeClass('layui-hide');
            $('.qf_blog_mask').addClass('layui-show');
            nav_status = true;
        }
    });


    //触摸遮罩隐藏导航显示
    $('.qf_blog_mask').on('click',function () {
        $('.qf_blog_nav').hide();
        $('.qf_blog_mask').removeClass('layui-show');
        $('.qf_blog_mask').addClass('layui-hide');
        nav_status =false;
    });


    var nav_search = false;
    $('.qf_blog_nav_search').on('click',function () {
        if(nav_search){
            $('.qf_blog_nav_search_show').addClass('layui-hide');
            $('.qf_blog_nav_search_show').removeClass('layui-show');
            nav_search =false;
        }else{
            $('.qf_blog_nav_search_show').addClass('layui-show');
            $('.qf_blog_nav_search_show').removeClass('layui-hide');
            nav_search = true;
        }
    });



    //加载留言编辑器
    edit.layEditor({
        elem: '.message_editor'
    });

    //提交留言
    $('.message_submit').on('click',function () {
        var user_id =  $('.user_id').html();
        var content =  $.trim($('.message_editor').val());
       var param =  $('.hidden_message_param').text();
       var text =  $('.hidden_message_text').text();
        if("undefined" === typeof(user_id) ){
            layer.msg('请先登录',{
                icon:2,
                time:2000
            });
            return false;
        }

        if(content.length<=0 && text.length==0){
            layer.msg('留言不能为空',{
                icon:2,
                time:2000
            });
            return false;
        }

        var position = content.lastIndexOf(text);//查找最后一个出现的位置
         if(text.length!=0 && position==-1){
             layer.msg('留言不能为空',{
                 icon:2,
                 time:2000
             });
             return false;
         }

        content = edit.content(content);

      //   layer.alert(content);
       // return false;
        //提交
        var mess_index = layer.msg('留言中，请稍候',{icon: 16,time:false,shade:0.8});
        $.post('message/add',{content:content,param:param},function (res) {
            if(res.code==1){
                layer.close(mess_index);
                layer.msg(res.msg,{
                    time:1000,
                    icon:1
                },function () {
                    window.location.reload();
                });
            }else {
                layer.close(mess_index);
                layer.msg(res.msg,{
                    icon:2
                });
            }
        });
    });




    //加载留言编辑器
    edit.layEditor({
        elem: '.article_common_editor'
    });

    //提交文章评论
    $('.article_common_submit').on('click',function () {
        var user_id =  $('.user_id').html();
        var content =  $.trim($('.article_common_editor').val());
        var param =  $('.hidden_article_common_param').text();
        var text =  $('.hidden_article_common_text').text();

        var id = $('#article_detail_id').text();
        if("undefined" === typeof(user_id) ){
            layer.msg('请先登录',{
                icon:2,
                time:2000
            });
            return false;
        }
        if(content.length<=0 && text.length==0){
            layer.msg('评论不能为空',{
                icon:2,
                time:2000
            });
            return false;
        }

        var position = content.lastIndexOf(text);//查找最后一个出现的位置
        if(text.length!=0 && position==-1){
            layer.msg('留言不能为空',{
                icon:2,
                time:2000
            });
            return false;
        }

        content = edit.content(content);

        //提交
        var common_index = layer.msg('发表评论中，请稍候',{icon: 16,time:false,shade:0.8});
        $.post('message/add',{content:content,article_id:id,param:param},function (res) {
            if(res.code==1){
                layer.close(common_index);
                layer.msg(res.msg,{
                    time:1000,
                    icon:1
                },function () {
                    window.location.reload();
                });
            }else {
                layer.close(common_index);
                layer.msg(res.msg,{
                    icon:2
                });
            }
        });
    });



    //右下角固定Bar
    util.fixbar({
        click: function(type){
        }
    });



    /**
     * 显示个人公众号的二维码
     */
    $('.qf_blog_wx').hover(function (){
        $(".qf_blog_wx_code").show();
    },function (){
        $(".qf_blog_wx_code").hide();
    });


});