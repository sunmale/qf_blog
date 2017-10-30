<?php
namespace app\index\controller;

use app\common\model\SaidModel;

class Said extends Base
{

    /**
     * 显示我的微语录
     * @return mixed
     */
    public function index()
    {
        $said = new SaidModel();
        if(request()->isAjax()){
            $map['status'] = 1;
            $page['pageIndex'] = request()->param('page');
            $page['pageSize'] = 5;
            $said_list = $said->selectPageByMap($map,$page);
            $said_count = $said->selectCountByMap($map);
            $page['pageCount'] = ceil($said_count/$page['pageSize']);
            foreach ($said_list as $k=>$v){
                //把转换成显示状态
                $v['tran_time']  = tran_time(strtotime($v['create_time']));
                $said_list[$k] = $v;
            }
            $data['data'] = $said_list;
            $data['pageCount'] =  $page['pageCount'];
            return json($data);
        }
        if(request()->isGet()){
            return $this->fetch();
        }
    }

}
