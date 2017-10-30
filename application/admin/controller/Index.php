<?php
namespace app\admin\controller;
use app\common\model\AdminModel;

class Index extends Base
{
     public function index()
     {
          return $this->fetch();
     }
    public function main()
    {
        return $this->fetch();
    }



    /**
     * 网站系统管理
     * @return mixed
     */
    public  function  system(){
         return $this->fetch();
    }





}
