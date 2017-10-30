<?php
namespace app\common\model;
use think\Model;
use think\Log;


class BaseModel extends Model
{

    /*protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }*/



    /** 通过条件查询指定数据（如果唯一值不是null 查询指定一条记录）
     * @param $map  //查询条件
     * @param  null $unique   //是否唯一
     * @auth 晴枫  @time  2017-09-05 10:33
     * @return array|false|int|\PDOStatement|string|\think\Collection|\think\Model
     */
/*    public  function  selectInfoByMap($map,$unique=null){
        try {
            if (!empty($unique)) {
                $result =$this->where($map)->find();
            } else {
                $result = $this->where($map)->select();
            }
            return $result;
        } catch (\Exception $e) {
            return -1;
        }
    }*/




}