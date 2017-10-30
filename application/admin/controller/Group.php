<?php
namespace app\admin\controller;
use app\common\model\GroupModel;
use app\common\model\RuleModel;

class Group extends Base
{

    /**
     * 显示用户组内容
     * @return mixed  返回内容
     */
    public function index()
    {
        $group = new GroupModel();
        if(request()->isGet()){
            $data = request()->param();
            if(!empty($data['page'])){
                if(!empty($data['key'])){
                   $map['title'] =  ['like',"%$data[key]%"];
                }else{
                    $map = [];
                }
                $page['pageIndex'] = request()->param('page');
                $page['pageSize'] = request()->param('limit');
                $list = $group->selectPageByMap($map,$page);
                foreach ($list as $k => $v) {
                    $v['statusName'] = $v['status_name'];
                    $list[$k] = $v;
                }
                $result['code'] =0;
                $result['msg'] ="";
                $result['count'] =$group->selectCountByMap($map);
                $result['data'] = $list;
                return json($result);
            }else{
                return $this->fetch();
            }
        }
    }


    /**
     * 新增用户组功能
     * @return mixed
     */
    public function  add()
    {
         $group = new GroupModel();
        if(request()->isGet()){
            return $this->fetch();
        }
        if(request()->isAjax()){
          $data  = request()->post();
            $res=   $group->insertGroup($data);
            if($res==-1){
                return  json(['code'=>'-1','msg'=>'新增用户组出现错误，请联系管理员' ]);
            }else{
                if($res){
                    return  json(['code'=>'1','msg'=>'新增用户组成功','url'=>url('Group/index')]);
                }else{
                    return  json(['code'=>'-1','msg'=>'新增用户组失败' ]);
                }
            }
        }
    }


    /**
     * 修改用户组信息
     * @return mixed
     */
    public  function  edit(){
        $group =  new GroupModel();
        if(request()->isGet()){
            $id =request()->param('id');
            $group_map['id'] = $id;
            $result =  $group->selectByMap($group_map,1);
           $this->assign([
               'group'=>$result
           ]);
            return $this->fetch();
        }
        if(request()->isAjax()){
                $data = request()->post();
                $res = $group->editGroupById($data);
                if($res == -1){
                    return json(['code' => '-1', 'msg' => '修改用户组出现错误，请联系管理员']);
                }else{
                    if ($res) {
                        return json(['code' => '1', 'msg' => '修改用户组成功','url'=>url('Group/index')]);
                    } else {
                        return json(['code' => '-1', 'msg' => '修改用户组失败']);
                    }
                }
        }
    }


    /**
     * 删除用户组
     */
    public  function  delete(){
        $group =  new GroupModel();
        if(request()->isAjax()){
            $id =   request()->param('id');
            $res=  $group->deleteGroupById($id);
            if($res == -1){
                return  json(['code'=>'-1','msg'=>'删除用户组出现错误，请联系管理员' ]);
            }else{
                if($res){
                    return  json(['code'=>'1','msg'=>'删除用户组成功']);
                }else{
                    return  json(['code'=>'-1','msg'=>'删除用户组失败' ]);
                }
            }
        }
    }





    /**
     * 给用户组分配权限
     * @return mixed
     */
    public  function  rule_group(){

        $group = new  GroupModel();
        $rule = new RuleModel();

        if(request()->isGet()){
            $id =  request()->param('id');
            //得到用户组对象
            $group_map['id'] = $id;
            $group_info =  $group->selectByMap($group_map,1);
            $group_info['rules'] = explode(',',$group_info['rules']);
            $list =  $rule->selectRuleList();
            //得到level菜单
            $ruleGroups = nav_left_tree($list,0,0,3);
            $this->assign([
                'group' => $group_info,
                'ruleGroups' => $ruleGroups
            ]);
            return $this->fetch();
        }

        if(request()->isAjax()){
            $id  =request()->param('id');
            $json  =request()->param('json');
            $data['id'] = $id;
           $array =   json_decode($json,true);
           $data['rules'] = implode(",",$array);
           $res =  $group->editGroupById($data);
           if($res == -1){
               return  json(['code'=>'-1','msg'=>"分配权限出现错误，请联系管理员"]);
           }else{
               if($res){
                   return  json(['code'=>'1','msg'=>"分配权限成功"]);
               }else{
                   return  json(['code'=>'1','msg'=>"分配权限失败"]);
               }
           }
        }
    }



    public  function  tree(){
        $group = new  GroupModel();
        $rule = new RuleModel();
        if(request()->isGet()){
            $id =  request()->param('id');
            //得到用户组对象
            $group_map['id'] = $id;
            $group_info =  $group->selectByMap($group_map,1);
            $group_info['rules'] = explode(',',$group_info['rules']);
            $list =  $rule->selectRuleList();
            foreach ($list as $k=>$v){
                $v['open'] =true;
                $v['name'] = $v['title'];
                $v['pId'] = $v['pid'];
                if(in_array($v['id'],$group_info['rules'])){
                    $v['checked'] = true;
                }
                $list[$k] = $v;
            }
            $this->assign([
                'group'=>$group_info,
                'json'=>json_encode($list)
            ]);
            return $this->fetch();
        }

        if(request()->isAjax()){
            $id  =request()->param('id');
            $json  =request()->param('json');
            $data['id'] = $id;
            $array =   json_decode($json,true);
            $data['rules'] = implode(",",$array);
            $res =  $group->editGroupById($data);
            if($res == -1){
                return  json(['code'=>'-1','msg'=>"分配权限出现错误，请联系管理员"]);
            }else{
                if($res){
                    return  json(['code'=>'1','msg'=>"分配权限成功"]);
                }else{
                    return  json(['code'=>'1','msg'=>"分配权限失败"]);
                }
            }

        }

    }


}
