<?php

namespace app\common\model;
use think\Db;
use think\Log;

class ArticleTypeModel extends BaseModel
{
    protected $name = 'article_type';           //指定数据表名
    //开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    //数据完成
    protected $insert = ['status' =>1];
    protected $update = [];
    //获取器（定义不存在的字段）
    public function getStatusNameAttr($value,$data)
    {
        $status = [
            1=>'正常',
            0=>'禁用'
        ];
        return $status[$data['status']];
    }


    /** 通过条件查询指定文章分类数据（如果唯一值不是null 查询指定一条记录）
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
            Log::error('通过条件查询指定文章分类数据出现错误，位置 common/ArticleTypeModelModel/selectInfoByMap,出错原因:'.$e->getMessage());
            return -1;
        }
    }



    /**返回所有的文章分类集合
     * @auth 晴枫  sunmale
     * @time 2017-09-06 13:31
     * @return false|int|mixed|\PDOStatement|string|\think\Collection
     */
    public  function  selectArticleTypeList(){
        try{
            $list=   $this->order('sort asc')->select();
            return $list;
        }catch (\Exception $e){
            Log::info('返回所有的文章分类集合出现错误.错误位置common/ArticleTypeModelModel/selectArticleTypeList. 错误原因:' .$e->getMessage());
            return -1;
        }
    }


    /**
     * 通过指定的id获取文章分类
     * @param $id   //菜单的id
     * @return array|false|\PDOStatement|string|Model  返回数据集合
     * @auth 晴枫  sunmale
     * @time 2017-09-13 10:40
     */
    public  function  selectArticleTypeInfoById($id){
        try{
            $res =  $this->where('id',$id)->find();
            return $res;
        }catch (\Exception $e){
            Log::info('通过指定的id获取文章分类出现错误.错误位置common/ArticleTypeModelModel/selectArticleTypeInfoById. 错误原因:' .$e->getMessage());
            return -1;
        }
    }


    /**
     * 新增一条文章分类记录
     * @param $data  //新增加的数据
     * @return int|string  返回值的类型
     * @auth 晴枫  sunmale
     * @time 2017-09-12 22:34
     * @auth 晴枫  sunmale
     */
    public  function  insertArticleType($data){
        try{
            $res = $this->save($data);
            return $res;
        }catch(\Exception $e){
            Log::info('新增一条文章分类记录出现错误。错误位置common/ArticleTypeModelModel/insertArticleType. 错误原因:' .$e->getMessage());
            return -1;
        }
    }






    /**
     * 通过id修改文章分类信息
     * @param $data
     * @return false|int
     * @auth 晴枫  sunmale
     * @time 2017-09-13 11:08
     */
    public  function  editArticleTypeById($data){
        try{
            $res  = $this->save($data,['id'=>$data['id']]);
            return $res;
        }catch (\Exception $e){
            Log::info('通过id修改文章分类信息出现错误.错误位置common/ArticleTypeModelModel/editArticleTypeById. 错误原因:' .$e->getMessage());
            return -1;
        }
    }



    /**
     * 通过菜单id删除该菜单
     * 删除条件  如果删除某个菜单  他存在子菜单  必须先删除子菜单
     * @param $id
     * @auth 晴枫  sunmale
     * @time 2017-09-13 11:24
     * @return int
     */
    public  function  deleteArticleTypeId($id){
        try{
            $rule =  $this->selectArticleTypeInfoById($id);  //将要删除的集合
            $list = $this->selectArticleTypeList();   //所有菜单集合
            foreach ($list as $k=>$v){
                if($v['pid'] == $rule['id']){
                    return -2;   //返回信息
                    break;
                }
            }
            $res = $this->where('id',$id)->delete();
            return $res;
        }catch (\Exception $e){
            Log::info('通过菜单id删除该菜单出现错误.错误位置common/ArticleTypeModelModel//deleteArticleTypeId. 错误原因:' .$e->getMessage());
            return -1;
        }
    }






















}