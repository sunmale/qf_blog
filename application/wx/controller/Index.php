<?php
namespace app\wx\controller;
use app\common\model\UserModel;
use think\Controller;

class Index extends  Controller
{


    //接入微信后台认证入口  （开发者模式）
      public  function  index(){
         $config = config('qf_blog_wx');
          $token = $config['wx_token'];
         define("TOKEN", $token);
         $wechatObj = new  Wechat();
         if (!isset($_GET['echostr'])) {
             $wechatObj->responseMsg();
         }else{
             $wechatObj->valid();
         }
     }



    /**
     * 图灵机器人自动回复
     */
     public  function  autoReply($content,$openid){
         settype($content,'string');
         $config = config('tl_robot');
         $data['key'] = $config['tl_appkey'];
         $data['info'] = $content;
         $data['userid'] = $openid;
         $json = json_encode($data,JSON_UNESCAPED_UNICODE);
          $url = $config['tl_apiurl'];
         $res_json =   https_request($url,$json);
        $res =  json_decode($res_json,true);
         //文本类
         if($res['code']==100000){
             $content = $res['text'];
         }
         //链接类
         if($res['code']==200000){
             $content = $res['text']."\n".$res['url'];
         }
         //新闻类
         if($res['code']==302000){
             $content = $res['code'];
         }
         //菜谱类
         if($res['code']==308000){
             $content = $res['code'];
         }

       return $content ;
     }


    /**
     * 自定义缓存seesion 用来储存一些会话数据
     * @param $key  //缓存键名称
     * @param $value  //需要缓存的值
     * @param $unique  //该用户唯一标识
     */
     public  function  setSession($key,$value,$unique){
         $path = BASE_PATH .DS.'cache' . DS . 'qf_session';
         if(!is_dir($path)){
             mkdir($path,0777,true);
         }
         $filename =   md5($unique.$key);
         $filePath = $path . DS .  'sess_'.$filename;
         self::set_php_file($filePath,$value);
     }




    /**
     * 获取自定义session
     * @param $key  //缓存键名称
     * @param $unique  //该用户唯一标识
     * @return bool|mixed
     */
    public  function  getSession($key,$unique){
        $path = BASE_PATH .DS.'cache' . DS . 'qf_session';
        $filename =   md5($unique.$key);
        $filePath = $path . DS .  'sess_'.$filename;
         if(!is_file($filePath)){
             return  false;
         }else{
           $content =    self::get_php_file($filePath);
           $data = unserialize($content);
           return $data;
         }
    }



    /**
     * 删除自定义session
     * @param $key  //缓存键名称
     * @param $unique  //该用户唯一标识
     * @return bool|mixed
     */
    public  function  delSession($key,$unique){
        $path = BASE_PATH .DS.'cache' . DS . 'qf_session';
        $filename =   md5($unique.$key);
        $filePath = $path . DS .  'sess_'.$filename;
        if(!is_file($filePath)){
            return  false;
        }else{
             unlink($filePath);
        }
    }




    /** 匹配输入的字符串
     * @param $content   //输入的字符串
     * @param $match    //需要匹配的字符串
     * @return bool   //返回值   是否匹配到
     */
     public  function matchString($content,$match){
         $result = strpos($content,$match);
         if(is_bool($result)){
             return false;
         }else if(is_int($result)){
             return true;
         }
     }





     public  function  bindAccount($content,$openid){
         $user = new UserModel();
         $array = explode(':',$content);
         $user_map['email'] = $array[1];
         //查询该openid是否被绑定
         $open_map['wx_openid'] = $openid;
         $open_res  =  $user->selectInfoByMap($open_map,1);
         if($open_res && $open_res->toArray()!=-1){
             return '你已经绑定了邮箱 : '.$open_res['email'];
         }
         //查询该邮箱是否可以绑定
        $email_res  =  $user->selectInfoByMap($user_map,1);
        if($email_res && $email_res->toArray()!=-1){
            //可以绑定该邮箱
            $update_user_data['wx_openid'] = $openid;
            $update_user_data['id'] =$email_res['id'];
            $update_user_res =  $user->editUserById($update_user_data);
             if($update_user_res==-1){
                 return '出现异常';
             }else{
                 return "账号绑定成功\n信息如下:\n邮箱:$email_res[email]\n昵称:$email_res[nickname]";
             }
        }else{
            if(empty($email_res)){
                return  '绑定的邮箱不存在!';
            }
             if($email_res==-1){
                 return  '程序异常!';
             }
        }

     }





    //获取文件内容
    public  static function get_php_file($filename) {
        return  trim(file_get_contents($filename));
    }


    //写入文件内容
    public  static function set_php_file($filename,$content) {
        $content = serialize($content);
          file_put_contents($filename, $content);
    }










}





