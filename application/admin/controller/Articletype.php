<?php
namespace app\admin\controller;
use app\common\model\ArticleTypeModel;



class Articletype extends Base
{


    /**
     * 树形菜单展示
     */
    public  function  index(){
        $article_type = new ArticleTypeModel();
        $list  =$article_type->selectArticleTypeList();
        foreach ($list as $k=>$v){
            $v['statusName'] = $v['status_name'];
            $list[$k] = $v;
        }
        $res = article_type_to_tree($list);
        $json = json_encode($res);
        $this->assign([
            'json'=>$json
        ]);
        return $this->fetch();
    }





    /**
     * 新增文章分类
     * @return mixed
     */
    public function  add()
    {
        $article_type = new ArticleTypeModel();
         if(request()->isGet()){
            $id =  request()->param('pid');
             $article_type_info = $article_type->selectArticleTypeInfoById($id);
             $this->assign([
                 'article_type'=>$article_type_info
             ]);
             return   $this->fetch();
         }
          if(request()->isAjax()){
              $data = request()->post();
              if (empty($data['pid'])) {
                  $data['pid'] = 0;
              }
             $res =   $article_type->insertArticleType($data);
              if($res == -1){
                  return json(['code'=>-1,'msg'=>'添加文章分类出现错误，请联系管理员']);
              }else{
                  if($res){
                      return json(['code'=>1,'msg'=>'添加文章分类成功','url'=> url('Articletype/index')]);
                  }else{
                      return json(['code'=>-1,'msg'=>'添加文章分类失败']);
                  }
              }

          }
    }





    /**
     * 修改菜单权限
     * @return mixed
     */
    public function edit()
    {
        $article_type = new ArticleTypeModel();
        if (request()->isGet()) {
            $id = $_GET['id'];
            $article_type_info = $article_type->selectArticleTypeInfoById($id);
            $p_article_type_info = $article_type->selectArticleTypeInfoById($article_type_info['pid']);   //父类菜单
            $this->assign([
                'article_type' => $article_type_info,
                'p_article_type' => $p_article_type_info
            ]);
            return $this->fetch();
        }
        if (request()->isAjax()) {
            $data = request()->post();
            $res = $article_type->editArticleTypeById($data);
            if($res== -1){
                return json(['code' => '-1', 'msg' => '修改文章分类出现错误，请联系管理员']);
            }else{
                if ($res) {
                    return json(['code' => '1', 'msg' => '修改文章分类成功']);
                } else {
                    return json(['code' => '-1', 'msg' => '修改文章分类失败']);
                }
            }


        }
    }





    /**
     * 删除文章分类
     */
     public  function  delete(){
         $article_type = new ArticleTypeModel();
         if (request()->isAjax()) {
             $id = request()->param('id');
             $res = $article_type->deleteArticleTypeId($id);
             if ($res == -1) {
                 return json(['code' =>'-1','msg' => '程序出错异常!']);
             } else if ($res == -2){
                 return json(['code' =>'-1','msg' => '必须先删除子文章分类']);
             }else{
                 return json(['code' =>'1','msg' => '删除文章分类成功']);

             }
         }
     }




}


