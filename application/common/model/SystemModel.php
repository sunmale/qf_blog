<?php

namespace app\common\model;
use think\Db;
use think\Log;

class SystemModel extends BaseModel
{
    //指定完整数据表名(仅供Db:table()使用)
      protected $name = 'system';           //指定数据表名
     //关闭自动写入时间戳字段
    protected $autoWriteTimestamp = false;


    /** 插入一条新的系统配置信息
     * @param $data  //插入的用户数据信息
     * @auth 晴枫  @time
     * 2017-09-15  15:45
     * @return array|false|int|\PDOStatement|string|\think\Collection|\think\Model
     */
    public  function  insertSystem($data){
        try {
            $result  =  $this->save($data);
            return $result;
        } catch (\Exception $e) {
            Log::error('插入一条新的会员信息出现错误，位置 common/UserModel/insertUser,出错原因:'.$e->getMessage());
            return -1;
        }
    }





}