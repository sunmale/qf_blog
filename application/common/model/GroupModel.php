<?php
namespace app\common\model;
use think\Db;
use think\Model;
use think\Log;
class GroupModel extends Model
{

    protected $name = 'auth_group';           //指定数据表名

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

    //多对多关联管理员
    public function admin()
    {
        return $this->belongsToMany('AdminModel','\\app\\common\\model\\GroupAccessModel','uid','group_id');
    }


    /** 通过条件查询所有用户组（如果唯一值不是null 查询指定一条记录）
     * @param $map  //查询条件
     * @param  null $unique   //是否唯一
     * @auth 晴枫
     * @time  2017-09-06 13:31
     * @return array|false|int|\PDOStatement|string|\think\Collection|\think\Model
     */
     public  function  selectByMap($map,$unique=null){
         try {
             if (!empty($unique)) {
                 $result =$this->where($map)->find();
             } else {
                 $result = $this->where($map)->select();
             }
             return $result;
         } catch (\Exception $e) {
             Log::error('通过条件查询所有用户组出现错误，位置 common/GroupModel/selectByMap,出错原因:'.$e->getMessage());
             return -1;
         }
     }

    /** 通过条件分页查询用户组
     * @param $map  //查询条件
      *@param  $page   //查询条件
     * @auth 晴枫
     * @time  2017-09-06 18:25
     * @return array|false|int|\PDOStatement|string|\think\Collection|\think\Model
     */
    public  function  selectPageByMap($map,$page){
        try {
            $result  =  $this->where($map)->page($page['pageIndex'],$page['pageSize'])->order('create_time desc')->select();
            return $result;
        } catch (\Exception $e) {
            Log::error(' 通过条件分页查询用户组出现错误，位置 common/GroupModel/selectPageByMap,出错原因:'.$e->getMessage());
            return -1;
        }
    }


    /** 通过条件获取查询的用户组总数
     * @param $map  //查询条件
     * @auth 晴枫
     * @time  2017-09-06 18:25
     * @return array|false|int|\PDOStatement|string|\think\Collection|\think\Model
     */
    public  function  selectCountByMap($map){
        try {
            $result  =  $this->where($map)->count();
            return $result;
        } catch (\Exception $e) {
            Log::error('通过条件获取查询的用户组总数出现错误，位置 common/GroupModel/selectCountByMap,出错原因:'.$e->getMessage());
            return -1;
        }
    }


    /**
     * 添加新的一个用户组
     * @param $data   //参数值
     * @auth 晴枫/sunmale
     * @time  2017-09-06 14:55
     * @return false|int
     * @author 晴枫  sunmale
     */
    public  function  insertGroup($data){
        try{
            $res = $this->save($data);
            return $res;
        }catch (\Exception $e){
            Log::info('添加新的一个用户组出现错误.错误位置common/GroupModel/insertGroup,错误原因:' .$e->getMessage());
            return -1;
        }
    }

    /**
     * 通过ID修改一个用户组信息
     * @param $data   //参数值
     * @auth 晴枫/sunmale
     * @time  2017-09-11 14:37
     * @return false|int
     * @author 晴枫  sunmale
     */
    public  function  editGroupById($data){
        try{
            $res = $this->save($data,['id'=>$data['id']]);
            return $res;
        }catch (\Exception $e){
            Log::info('通过ID修改一个用户组信息出现错误.错误位置common/GroupModel/editGroupById,错误原因:' .$e->getMessage());
            return -1;
        }
    }



    /**
     * 根据id删除用户组信息
     * @param $id   //指定id
     * @return int
     */
    public  function  deleteGroupById($id){
        try{
            $group_access = new GroupAccessModel();
            // 启动事务
            Db::startTrans();
            //先删除之前的用户所属的用户组信息
            $list =   $group_access->getAdminsByGroupId($id);
            if(!empty($list)){
                $this->get($id)->admin()->detach($list);
            }
            $res =  $this->get($id)->delete();
            if ($res) {
                Db::commit();
                return $res;
            }
            return $res;
        }catch (\Exception $e){
            Log::info('根据id删除用户组信息出现错误.错误位置common/GroupModel/deleteGroupById. 错误原因:' .$e->getMessage());
            return -1;
        }
    }





}