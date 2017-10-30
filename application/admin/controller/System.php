<?php
namespace app\admin\controller;

use app\common\model\SystemModel;

class System extends Base
{



    /**
     * 网站系统管理
     * @return mixed
     */
    public  function  index(){
        $cacheData['type'] = 'file';
        $cacheData['diff'] = 'path';
        $cacheData['path'] = BASE_PATH.'/cache';
        setCache($cacheData);
       $base_system =  cache('qf_blog_base_system');

        $this->assign([
            'base_system'=>$base_system
        ]);
        return $this->fetch();
    }



    /**
     * 新增配置
     * @return \think\response\Json
     */
    public  function  add_base(){
        if(request()->isAjax()){
             $data = request()->post();
            // $res = $system->insertSystem($data);
               $cacheData['type'] = 'file';
              $cacheData['diff'] = 'path';
              $cacheData['path'] = BASE_PATH.'/cache';
              setCache($cacheData);
              cache('qf_blog_base_system', $data, 0);
                return json(['code'=>1,'msg'=>'操作成功']);
        }
    }


}
