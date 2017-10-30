<?php
namespace app\admin\controller;


use app\common\model\RuleModel;

class Rule extends Base
{


    /**
     * 树形菜单展示
     */
    public  function  index(){
        $rule = new RuleModel();
        $list  = $rule->selectRuleList();
        foreach ($list as $k=>$v){
            $v['statusName'] = $v['status_name'];
            $list[$k] = $v;
        }
        $res = rule_to_tree($list);
        $json = json_encode($res);
        $this->assign([
            'json'=>$json
        ]);
        return $this->fetch();
    }


    /**
     * 新增菜单功能
     * @return mixed
     */
    public function  add()
    {
        $rule = new RuleModel();
         if(request()->isGet()){
            $id =  request()->param('pid');
             $rule = $rule->selectRuleInfoById($id);
             $this->assign([
                 'rule'=>$rule
             ]);
             return   $this->fetch();
         }
          if(request()->isAjax()){
              $data = request()->post();
              if (empty($data['pid'])) {
                  $data['pid'] = 0;
              }
             $res =   $rule->insertRule($data);
              if($res == -1){
                  return json(['code'=>-1,'msg'=>'添加菜单出现错误，请联系管理员']);
              }else{
                  if($res){
                      return json(['code'=>1,'msg'=>'添加菜单成功','url'=> url('Rule/index')]);
                  }else{
                      return json(['code'=>-1,'msg'=>'添加菜单失败']);
                  }
              }

          }

    }


    /**
     * 修改菜单权限
     * @return mixed
     */
    public function edit()
    {
        $rule = new RuleModel();
        if (request()->isGet()) {
            $id = $_GET['id'];
            $ruleInfo = $rule->selectRuleInfoById($id);
            $pid_rule = $rule->selectRuleInfoById($ruleInfo['pid']);   //父类菜单
            $this->assign([
                'rule' => $ruleInfo,
                'pid_rule' => $pid_rule
            ]);
            return $this->fetch();
        }
        if (request()->isAjax()) {
            $data = request()->post();
            $res = $rule->editRuleById($data);
            if($res== -1){
                return json(['code' => '-1', 'msg' => '修改菜单出现错误，请联系管理员']);
            }else{
                if ($res) {
                    return json(['code' => '1', 'msg' => '修改菜单成功']);
                } else {
                    return json(['code' => '-1', 'msg' => '修改菜单失败']);
                }
            }


        }
    }

    /**
     * 删除菜单
     */
     public  function  delete(){
         $rule = new RuleModel();
         if (request()->isAjax()) {
             $id = request()->param('id');
             $res = $rule->deleteRuleById($id);
             if ($res == -1) {
                 return json(['code' =>'-1','msg' => '程序出错异常!']);
             } else if ($res == -2){
                 return json(['code' =>'-1','msg' => '必须先删除子菜单']);
             }else{
                 return json(['code' =>'1','msg' => '删除菜单成功']);

             }
         }
     }




}


