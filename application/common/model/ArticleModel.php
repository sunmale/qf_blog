<?php

namespace app\common\model;
use think\Db;
use think\Log;

class ArticleModel extends BaseModel
{
    //指定完整数据表名(仅供Db:table()使用)
      protected $name = 'article';           //指定数据表名

     //关闭自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    //获取器(存在的字段)
   /* public function getStatusAttr($value)
    {
        $status = [1=>'正常',0=>'禁用'];
        return $status[$value];
    }*/
    //数据完成

    //获取器（定义不存在的字段）
    public function getStatusNameAttr($value,$data)
    {
        $status = [1=>'正常',0=>'草稿'];
        return $status[$data['status']];
    }


    public function getReprintNameAttr($value,$data)
    {
        $status = [1=>'原创',2=>'转载'];
        return $status[$data['is_reprint']];
    }


    //关联文章类型
    public  function articleType(){
        return $this->belongsTo('ArticleTypeModel','typeid','id');
    }


    //文章关联评论
    public function comments()
    {
        return $this->hasMany('MessageModel','article_id');
    }


    /** 通过条件查询指定文章数据（如果唯一值不是null 查询指定一条记录）
     * @param $map  //查询条件
     * @param  null $unique   //是否唯一
     * @auth 晴枫  @time  2017-09-05 10:33
     * @return array|false|int|\PDOStatement|string|\think\Collection|\think\Model
     */
    public  function  selectInfoByMap($map,$unique=null){
        try {
            if (!empty($unique)) {
                $result =$this->where($map)->find();
            } else {
                $result = $this->where($map)->select();
            }
            return $result;
        } catch (\Exception $e) {
            Log::error('通过条件查询指定文章数据出现错误，位置 common/ArticleModel/selectInfoByMap,出错原因:'.$e->getMessage());
            return -1;
        }
    }





    /** 通过条件分页查询文章数据
     * @param $map  //查询条件
     *@param  $page   //查询条件
     * @auth 晴枫
     * @time  2017-09-06 23:01
     * @return array|false|int|\PDOStatement|string|\think\Collection|\think\Model
     */
    public  function  selectPageByMap($map,$page,$fields=[]){
        try {
            $result  =  $this->where($map)->page($page['pageIndex'],$page['pageSize'])->order('create_time desc')->select();
            return $result;
        } catch (\Exception $e) {
            Log::error('通过条件分页查询文章数据出现错误，位置 common/AdminModel/selectPageByMap,出错原因:'.$e->getMessage());
            return -1;
        }
    }



    /** 通过条件获取查询的文章数据总数
     * @param $map  //查询条件
     * @auth 晴枫
     * @time  2017-09-06 23:01
     * @return array|false|int|\PDOStatement|string|\think\Collection|\think\Model
     */
    public  function  selectCountByMap($map){
        try {
            $result  =  $this->where($map)->count();
            return $result;
        } catch (\Exception $e) {
            Log::error('通过条件获取查询的文章数据总数出现错误，位置 common/AdminModel/selectCountByMap,出错原因:'.$e->getMessage());
            return -1;
        }
    }


    /** 插入一条新的文章内容
     * @param $data  //插入的数据信息
     * @auth 晴枫  @time  2017-09-06  11：45
     * @return array|false|int|\PDOStatement|string|\think\Collection|\think\Model
     */
    public  function  insertArticle($data){
        try {
            $result  =  $this->save($data);
           return $result;
        } catch (\Exception $e) {
            Log::error('插入一条新的文章内容出现错误，位置 common/ArticleModel/insertArticle,出错原因:'.$e->getMessage());
            return -1;
        }
    }



    /** 通过ID修改文章信息
     * @param $data  //需要修改的文章数据信息
     * @auth 晴枫
     * @time 2017-09-11  10：27
     * @return array|false|int|\PDOStatement|string|\think\Collection|\think\Model
     */
    public  function  editArticleById($data){
        try {
            $result  =  $this->save($data,['id'=>$data['id']]);
            return $result;
        } catch (\Exception $e) {
            Log::error('通过ID修改文章信息出现错误，位置 common/ArticleModel/editArticleById,出错原因:'.$e->getMessage());
            return -1;
        }
    }


    /**
     * 通过id删除文章信息
     * @param $id    // 文章的id
     * @return int 返回的值进行判断是否删除
     *  @auth 晴枫  sunmale
     *  @time  2017-09-11 13:48
     */
    public  function  delArticleById($id){
        try{
            $res =  $this->where('id',$id)->delete();
            return $res;
        }catch (\Exception $e){
            Log::info('通过id删除文章信息出现错误.错误位置common/ArticleModel/delArticleById. 错误原因:' .$e->getMessage());
            return -1;
        }
    }





    /**
     * 通过当前ID查询上一篇文章跟下一篇文章
     * @param $id
     * @param string $type
     * @return int
     */
    public  function  selectPrevAndNext($id,$type='prev'){
         try{
             $res = array();
             if($type=='prev'){
                $res =  $this->field('id,title')->where('id','<',$id)->where('status',1)->order('id desc')->limit(1)->find();
             }
             if($type=='next'){
                 $res =  $this->field('id,title')->where('id','>',$id)->where('status',1)->order('id asc')->limit(1)->find();
             }
             return $res;
         }catch (\Exception $e){
             Log::info('通过当前ID查询上一篇文章跟下一篇文章出现错误.错误位置common/ArticleModel/selectPrevAndNext. 错误原因:' .$e->getMessage());
             return -1;
         }
    }



    /**
     * 通过日期年月给文章进行归档
     * @return false|int|\PDOStatement|string|\think\Collection
     */
    public  function  selectArticleDate(){
        try{
            $res =  $this->field(['id','FROM_UNIXTIME(create_time, "%Y-%m")'=>'date','FROM_UNIXTIME(create_time, "%Y年-%m月")'=>'date_show'])->where('status',1)->group('date')->order('date desc')->select();
            return $res;
        }catch (\Exception $e){
            Log::info('通过日期年月给文章进行归档出现错误.错误位置common/ArticleModel/selectArticleDate. 错误原因:' .$e->getMessage());
            return -1;
        }
    }


    /**
     * 查询热门文章
     * @return false|int|\PDOStatement|string|\think\Collection
     */
    public  function  selectHotArticle(){
        try{
            $res =  $this->field('id,title')->where('status',1)->order('view desc')->limit(5)->select();
            return $res;
        }catch (\Exception $e){
            Log::info('查询热门文章出现错误.错误位置common/ArticleModel/selectHotArticle. 错误原因:' .$e->getMessage());
            return -1;
        }




    }



}

