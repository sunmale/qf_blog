<?php

namespace app\common\model;
use think\Db;
use think\Log;

class AdminModel extends BaseModel
{
    //指定完整数据表名(仅供Db:table()使用)
      protected $name = 'admin';           //指定数据表名

     //关闭自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    //获取器(存在的字段)
   /* public function getStatusAttr($value)
    {
        $status = [1=>'正常',0=>'禁用'];
        return $status[$value];
    }*/

    //数据完成
    protected $insert = ['status' =>1];

    //获取器（定义不存在的字段）
    public function getStatusNameAttr($value,$data)
    {
        $status = [1=>'正常',0=>'禁用'];
        return $status[$data['status']];
    }


    //定义管理员跟用户组多对多关联
    public  function  groups(){
        return $this->belongsToMany('GroupModel','\\app\\common\\model\\GroupAccessModel','group_id','uid');
    }

    /** 通过条件查询指定用户数据（如果唯一值不是null 查询指定一条记录）
     * @param $map  //查询条件
     * @param  null $unique   //是否唯一
     * @auth 晴枫  @time  2017-09-05 10:33\
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
            Log::error('通过条件查询指定用户数据出现错误，位置 common/AdminModel/selectInfoByMap,出错原因:'.$e->getMessage());
            return -1;
        }
    }



    /** 通过条件分页查询管理员
     * @param $map  //查询条件
     *@param  $page   //查询条件
     * @auth 晴枫
     * @time  2017-09-06 23:01
     * @return array|false|int|\PDOStatement|string|\think\Collection|\think\Model
     */
    public  function  selectPageByMap($map,$page){
        try {
            $result  =  $this->where($map)->page($page['pageIndex'],$page['pageSize'])->order('create_time desc')->select();
            return $result;
        } catch (\Exception $e) {
            Log::error('通过条件分页查询管理员出现错误，位置 common/AdminModel/selectPageByMap,出错原因:'.$e->getMessage());
            return -1;
        }
    }



    /** 通过条件获取查询的管理员总数
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
            Log::error('通过条件获取查询的管理员总数出现错误，位置 common/AdminModel/selectCountByMap,出错原因:'.$e->getMessage());
            return -1;
        }
    }


    /** 插入一条新的管理员信息
     * @param $data  //插入的用户数据信息
     * @auth 晴枫  @time  2017-09-06  11：45
     * @return array|false|int|\PDOStatement|string|\think\Collection|\think\Model
     */
    public  function  insertAdmin($data){
        try {
            //判断是否给勾选管理员用户组
            if(!empty($data['groups'])){
                $groups =  (array)$data['groups'];
                unset($data['groups']);
            }
            //开启事务
            Db::startTrans();
            $result  =  $this->save($data);
            if(!empty($groups)){
                //关联添加中间表数据
                $acc_res = $this->get($this->getAttr('id'))->groups()->saveAll($groups);
            }else{
                $acc_res = 1;
            }
            if($result && $acc_res){
                Db::commit();
                return $result;
            }else{
                Db::rollback();
                return $result;
            }
        } catch (\Exception $e) {
            Log::error('插入一条新的管理员信息出现错误，位置 common/AdminModel/insertAdmin,出错原因:'.$e->getMessage());
            return -1;
        }
    }



    /** 通过ID修改管理员信息
     * @param $data  //需要修改的用户数据信息
     * @auth 晴枫
     * @time 2017-09-11  10：27
     * @return array|false|int|\PDOStatement|string|\think\Collection|\think\Model
     */
    public  function  editAdminById($data){
         $group_access = new GroupAccessModel();
        try {
            //判断是否给勾选管理员用户组
            if(!empty($data['groups'])){
                $groups =  (array)$data['groups'];
                unset($data['groups']);
            }
            //开启事务
            Db::startTrans();
            //先删除之前的用户所属的用户组信息
          $list =   $group_access->getGroupsByAdminId($data['id']);
            if(!empty($list)){
                $this->get($data['id'])->groups()->detach($list);
            }
            $result  =  $this->save($data,['id'=>$data['id']]);
            if(!empty($groups)){
                //关联添加中间表数据
                $acc_res = $this->get($data['id'])->groups()->saveAll($groups);
            }else{
                $acc_res = 1;
            }
            if($result && $acc_res){
                Db::commit();
                return $result;
            }else{
                Db::rollback();
                return $result;
            }
        } catch (\Exception $e) {
            Log::error('通过ID修改管理员信息出现错误，位置 common/AdminModel/editAdminById,出错原因:'.$e->getMessage());
            return -1;
        }
    }


    /**
     * 通过id删除管理员信息
     * @param $id    // 管理员的id
     * @return int 返回的值进行判断是否删除
     *  @auth 晴枫  sunmale
     *  @time  2017-09-11 13:48
     */
    public  function  delAdminById($id){
        try{
            $group_access = new GroupAccessModel();
            // 启动事务
            Db::startTrans();
            //先删除之前的用户所属的用户组信息
            $list =   $group_access->getGroupsByAdminId($id);
            if(!empty($list)){
                $this->get($id)->groups()->detach($list);
            }
            $res =  $this->get($id)->delete();
            if ($res) {
                Db::commit();
                return $res;
            }
        }catch (\Exception $e){
            Log::info('通过id删除管理员信息出现错误.错误位置common/AdminModel/delAdminById. 错误原因:' .$e->getMessage());
            return -1;
        }
    }



}