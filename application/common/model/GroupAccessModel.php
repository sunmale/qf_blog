<?php

namespace app\common\model;
use think\Db;
use think\Log;
class GroupAccessModel extends BaseModel
{
    protected $name = 'auth_group_access';           //指定数据表名


    /**
     * 通过管理员ID获取所有的用户组
     * @param $uid   //管理员ID
     * @return array|int
     * @auth 晴枫
     * @time  2017-09-11 10:14
     */
    public  function  getGroupsByAdminId($uid){
        try{
            $data = array();
            $list=    $this->field('group_id')->where("uid",$uid)->select();
            if(!empty($list)){
                foreach ($list as $v){
                    array_push($data, $v['group_id']);
                }
            }
            return $data;
        }catch (\Exception $e){
            Log::info('通过管理员ID获取所有的用户组出现错误.错误位置common/GroupAccessModel/getGroupsByAdminId. 错误原因:' .$e->getMessage());
            return -1;
        }
    }



    /**
     * 通过用户组ID获取所有的管理员ID
     * @param $group_id   //用户组ID
     * @return array|int
     * @auth 晴枫
     * @time  2017-09-11 15:26
     */
    public  function  getAdminsByGroupId($group_id){
        try{
            $data = array();
            $list=    $this->field('uid')->where("group_id",$group_id)->select();
            if(!empty($list)){
                foreach ($list as $v){
                    array_push($data, $v['uid']);
                }
            }
            return $data;
        }catch (\Exception $e){
            Log::info('通过用户组ID获取所有的管理员ID出现错误.错误位置common/GroupAccessModel/getAdminsByGroupId. 错误原因:' .$e->getMessage());
            return -1;
        }
    }





}