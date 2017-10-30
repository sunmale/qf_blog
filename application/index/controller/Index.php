<?php
namespace app\index\controller;

use app\common\model\ArticleModel;

class Index extends  Base
{


/**
 * 首页展示
 * @return mixed
 */
public function index()
{
    $article = new ArticleModel();
    if(request()->isGet()){
        $page_index = request()->param('page');
        $search = request()->param('search');
        $tag = request()->param('tag');
        $type_id = request()->param('typeid');
        $date = request()->param('date');

        $map['status'] =1;
        if(empty($page_index)){
            $page['pageIndex']  =1;
        }else{
            $page['pageIndex'] = $page_index;
        }
        //搜索文章
        if(!empty($search)){
            $map['title'] = ['like',"%$search%"];
        }

        //标签搜索
        if(!empty($tag)){
            $map['tags'] = ['like',"%$tag%"];
        }

        //类型搜索
        if(!empty($type_id)){
            $map['typeid'] = $type_id;
        }

        //日期搜索
        if(!empty($date)){
            $map['date'] = $date;
        }

        $page['pageSize'] =6;
        $list =   $article->selectPageByMap($map,$page);
        $page['pageCount'] = $article->selectCountByMap($map);
       foreach ($list as $k=>$v){
           $v['tran_time'] = tran_time(strtotime($v['create_time']));
           $list[$k] = $v;
       }

      $this->assign([
          'article_list'=>$list,
          'page'=>$page,
          'article_search'=>$search,
          'article_tag'=>$tag,
          'article_type_id'=>$type_id,
          'article_date'=>$date
      ]);
        return $this->fetch();
    }
 }



/**
 * 详情页面
 */
 public  function  detail(){
     $article = new ArticleModel();
      if(request()->isGet()){
          $map['id'] = request()->param('id');
         $article_info =  $article->selectInfoByMap($map,1);
         $prev_article =  $article->selectPrevAndNext(request()->param('id'),'prev');
         $next_article =  $article->selectPrevAndNext(request()->param('id'),'next');
         //一对一关联获取分类名称
          $article_info['typeName'] =  $article->get(request()->param('id'))->articleType->title;
         //修改查看次数
          $article_edit_map['id'] = $map['id'];
          $article_edit_map['view'] = $article_info['view']+1;
          $article->editArticleById($article_edit_map);

          //浏览次数+1
          $article_info['view'] = $article_info['view']+1;

          //文章关联评论
          $article_info['common_count']  = $article->get(request()->param('id'))->comments()->where('status',1)->count();

         $this->assign([
             'article'=>$article_info,
             'prev'=>$prev_article,
             'next'=>$next_article
         ]);
          return $this->fetch();
      }
 }




//关于页面
 public  function  about(){

    return $this->fetch();
 }




}
