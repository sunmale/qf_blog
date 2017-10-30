<?php

namespace app\common\model;
use think\Log;

class ArticleTagModel extends BaseModel
{
    protected $name = 'article_tag';           //指定数据表名
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


    /** 通过条件查询指定文章标签数据（如果唯一值不是null 查询指定一条记录）
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
            Log::error('通过条件查询指定文章标签数据出现错误，位置 common/ArticleTagModel/selectInfoByMap,出错原因:'.$e->getMessage());
            return -1;
        }
    }






}