<?php
namespace app\admin\controller;
use think\Controller;

class Upload extends  Controller
{



    /**
     * 上传一张图片
     */
    public function uploadOneImage()
    {
        $up_file  = request()->file('article_image');
        if($up_file){
            $info = $up_file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $data['url'] = config('blog.qf_blog_url') . DS .'uploads'. DS . $info->getSaveName();
            }else{
                // 上传失败获取错误信息
                $data['err_mag'] =  $up_file->getError();
            }
        }
        $data['errno'] = 1;
        return json($data);
    }



}

