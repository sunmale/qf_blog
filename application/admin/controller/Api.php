<?php
namespace app\admin\controller;
use app\common\model\RuleModel;

class Api extends Base
{
    /**
     * @return mixed
     * 显示用户信息
     */
    public function getRuleList()
    {
        $user = $this->base();
        $rule =  new RuleModel();
        if(request()->isGet()){
            //获取左侧显示菜单
            $ruleList = $rule->getRulesByAdminIdForNav($user['id']);
            $data['ret'] = 200;
            $data['msg']='返回成功';
            $data['data'] =$ruleList;
            $json =  json_encode($data);
            echo $json;
        }
    }




}
