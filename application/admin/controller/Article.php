<?php
namespace app\admin\controller;
use app\common\model\ArticleModel;
use app\common\model\ArticleTagModel;
use app\common\model\ArticleTypeModel;
use think\Db;
use think\Loader;

class Article extends Base
{

    /**
     * @return mixed
     * 显示用户信息
     */
    public function index()
    {
        $article = new ArticleModel();
        if (request()->isGet()) {
            $data = request()->param();
            if (!empty($data['page'])) {
                if (!empty($data['key'])) {
                    $map['title'] = ['like', "%$data[key]%"];
                } else {
                    $map = [];
                }
                $page['pageIndex'] = request()->param('page');
                $page['pageSize'] = request()->param('limit');
                $list = $article->selectPageByMap($map, $page);
                foreach ($list as $k => $v) {
                    $v['statusName'] = $v['status_name'];
                    $list[$k] = $v;
                }
                $result['code'] = 0;
                $result['msg'] = "";
                $result['count'] = $article->selectCountByMap($map);
                $result['data'] = $list;
                return json($result);
            } else {
                return $this->fetch();
            }
        }
    }


    /**
     * 添加管理员的方法
     * @return mixed
     */
    public function add()
    {
        $article = new ArticleModel();
        $article_type = new ArticleTypeModel();
        $article_tag = new ArticleTagModel();
        if (request()->isGet()) {
            $article_type_map['pid'] = ['neq',0];
            $article_type_info = $article_type->selectInfoByMap($article_type_map);
            $article_type_tag_map = [] ;
            $article_tag_info =  $article_tag->selectInfoByMap($article_type_tag_map);
             $this->assign([
                 'article_type'=>$article_type_info,
                 'article_tag'=>$article_tag_info
             ]);
            return $this->fetch();
        }
        if (request()->isAjax()) {
            $user = $this->base();
            $data = request()->post();
            $data['tags'] = implode(',',$data['tags']);
            $data['user_id'] = $user['id'];
            $data['date'] = date('Y-m',time());
            //可以注册
            //释放掉表单令牌数据
            unset($data['__token__']);
            $res = $article->insertArticle($data);
            if ($res == -1) {
                return json(['code' => -1, 'msg' => '出现错误，请联系管理员']);
            } else {
                return json(['code' => 1, 'msg' => '新增文章成功']);
            }
        }
    }





    /**
     * 修改管理员信息
     */
    public function edit()
    {
        $article = new ArticleModel();
        $article_type = new ArticleTypeModel();
        $article_tag = new ArticleTagModel();
        if (request()->isGet()) {
            $id = input('param.id');
            $article_map['id'] = $id;
            $article_info = $article->selectInfoByMap($article_map, 1);
            $article_type_map['pid'] = ['neq',0];
            $article_type_info = $article_type->selectInfoByMap($article_type_map);
            $article_type_tag_map = [] ;
            $article_tag_info =  $article_tag->selectInfoByMap($article_type_tag_map);
            $article_tag_array =  explode(',',$article_info['tags']);
            $this->assign([
                'article' => $article_info,
                'article_type' => $article_type_info,
               'article_tag' => $article_tag_info,
                'article_tag_array'=>$article_tag_array
            ]);
            return $this->fetch();
        }
        if (request()->isAjax()) {
            $user = $this->base();
            $data = request()->post();
            $data['tags'] = implode(',',$data['tags']);
            $data['user_id'] = $user['id'];
            //可以注册
            //释放掉表单令牌数据
            unset($data['__token__']);
            $res = $article->editArticleById($data);
            if ($res == -1) {
                return json(['code' => -1, 'msg' => '出现错误，请联系管理员']);
            } else {
                return json(['code' => 1, 'msg' => '修改文章成功']);
            }
        }
    }




    /**
     * 根据ID删除文章信息
     */
    public function delete()
    {
        $article = new ArticleModel();
        if (request()->isAjax()) {
            $id = request()->param('id');
            $res = $article->delArticleById($id);
            if ($res == -1) {
                return json(['code' => '-1', 'msg' => '删除文章出现错误，请联系管理员']);
            } else {
                if ($res) {
                    return json(['code' => '1', 'msg' => '删除文章成功', 'url' => url('Article/index')]);
                } else {
                    return json(['code' => '-1', 'msg' => '删除文章失败']);
                }
            }
        }
    }









}
