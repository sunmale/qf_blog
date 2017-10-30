<?php
namespace app\index\controller;

use app\common\model\ArticleModel;
use app\common\model\ArticleTagModel;
use app\common\model\ArticleTypeModel;
use app\common\model\UserModel;
use qf\Oauth;
use think\Cache;
use think\Controller;
use think\Log;

class Base extends Controller
{


    /**
     * 初始化
     */
    public function _initialize()
    {



        //读取一些配置全局显示
        $config['qf_blog_version'] = config('blog.qf_blog_version');

        //登陆用户信息
        $user = $this->base();

        //文章分类现实
        $article_type_list = $this->getArticleTypeForNav();

        //获得文章标签
        $article_tag_list = $this->getArticleTags();

        //获取文章归档
        $article_date_list = $this->getArticleDate();

        //获取热门文章
        $article_hot_list = $this->getHotArticle();

        //获取博客基本设置
        $system_base=$this->getBaseSystemInfo();

        $this->assign([
            'config' => $config,
            'user' => $user,
            'article_type_list' => $article_type_list,
            'article_tag_list'=>$article_tag_list,
            'article_date_list'=>$article_date_list,
           'article_hot_list'=>$article_hot_list,
            'system'=>$system_base
        ]);

    }



    /**
     * 授权登录信息是否获取到   用户非法操作
     */
    public function base()
    {
        $user = session('qf_blog_user');
        return $user;
    }



    /**
     * 得到文章分类给页面公共现实
     */
    public function getArticleTypeForNav()
    {
        $article_type = new ArticleTypeModel();
        $list = $article_type->selectArticleTypeList();
        $list = article_type_to_tree($list);
        return $list;
    }


    /**
     * 得到所有文章标签
     * @return array|false|int|\PDOStatement|string|\think\Collection|\think\Model
     */
    public  function  getArticleTags(){
        $article_tag = new ArticleTagModel();
        $map['status'] = 1;
        $list = $article_tag->selectInfoByMap($map);
        return $list;
    }



    /**
     * 得到所有文章归档
     * @return array|false|int|\PDOStatement|string|\think\Collection|\think\Model
     */
    public  function  getArticleDate(){
        $article = new  ArticleModel();
        $list = $article->selectArticleDate();
        return $list;
    }


    /**
     * 得到热门文章
     * @return false|int|\PDOStatement|string|\think\Collection
     */
    public  function  getHotArticle(){
        $article = new  ArticleModel();
        $list = $article->selectHotArticle();
        return $list;
    }


    /**
     * 得到博客基本配置
     * @return mixed
     */
    public  function  getBaseSystemInfo(){
        //自定义缓存设置
        $cacheData['type'] = 'file';
        $cacheData['diff'] = 'path';
        $cacheData['path'] = BASE_PATH.'/cache/';
        setCache($cacheData);
        $res =cache('qf_blog_base_system');
       $res['diff_date'] =    ( time() - strtotime($res['create_date']) ) / (60*60*24) ;
        $res['diff_date'] = (int)$res['diff_date'];
        return $res;
    }

        //通过qq快捷登录获取信息
        public  function  getUserInfoByQQ($code){
              $user_info = $this->base();    //获取用户是否登录
             $user = new UserModel();
             $qq_config= config('oauth.qq');
            $res  = Oauth::getInstance()->init($qq_config)->oauth_qq($code);
            //未登录
            if(empty($user_info)){
                //查询用户
                $map['qq_openid'] = $res['openid'];
                $select_res = $user->selectInfoByMap($map,1);
                if( $select_res &&  $select_res->toArray()!=-1 ){
                    //说明该用户存在直接登录
                   /* if($select_res['nickname']!=$res['nickname']){
                        $select_res['nickname'] = $res['nickname'];
                    }*/
                    session('qf_blog_user',$select_res);
                    $this->redirect('index/Index/index');
                }else{
                    //注册一个新的用户
                    $data['qq_openid'] = $res['openid'];
                    $data['headurl'] = $res['figureurl_qq_1'];
                    $data['nickname'] = $res['nickname'];
                    $add_res =  $user->insertUser($data);
                    if($add_res==-1){
                        $this->error('出现错误！');
                    }else{
                        $insert_map['qq_openid'] = $res['openid'] ;
                        $insert_res = $user->selectInfoByMap($insert_map,1);
                        session('qf_blog_user',$insert_res);
                        $this->redirect('index/Index/index');
                    }
                }
            }else{
                //已经使用邮箱登录了，用于绑定qq
                $update_user_data['qq_openid'] = $res['openid'];
                $update_user_data['id'] =$user_info['id'];
                $update_user_res =  $user->editUserById($update_user_data);
                if($update_user_res==-1){
                    $this->error('程序异常','index/Index/index');
                }else{
                    $user_info['qq_openid'] = $res['openid'];
                    session('qf_blog_user',$user_info);
                    $this->success('绑定QQ成功','index/Index/index');
                }
            }

    }


}



