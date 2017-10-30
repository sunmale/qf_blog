<?php


namespace  qf\oauth;

class Qq   {
    /**
     * 获取requestCode的api接口
     * @var string
     */
    private  static  $GetRequestCodeURL = 'https://graph.qq.com/oauth2.0/authorize';
    /**
     * 获取access_token的api接口
     * @var string
     */
    private  static $GetAccessTokenURL = 'https://graph.qq.com/oauth2.0/token';
    /**
     * 获取用户授权的openid
     * @var string
     */
    private  static $getOpenIdURL = 'https://graph.qq.com/oauth2.0/me';
    /**
     * 得到user_info接口的信息
     * @var string
     */
    private static  $getUserInfoURL = 'https://graph.qq.com/user/get_user_info';


    /**
     * 通过qq快速登录获取用户信息
     * @param $code
     * @param $data
     * @return mixed
     */
     public   static  function  getUserInfo($code,$data){
         $access_token =  self::getAccessToken($code,$data);
         $user_url =  self::$getOpenIdURL."?access_token=$access_token";
         $res_json  =  self::https_request($user_url);
         $res = json_decode(trim(substr($res_json, 9), " );\n"), true);
         $user_info_url =  self::$getUserInfoURL."?access_token=$access_token&oauth_consumer_key=$data[appid]&openid=$res[openid]";
         $user_info_json  =  self::https_request($user_info_url);
         $user_info =   json_decode($user_info_json,true);
         $user_info['openid'] = $res['openid'];
         return $user_info;
     }


    /**
     * 获取需要的access_token
     * @param $code
     * @param $data
     * @return mixed
     * @throws \Exception
     */
     public static function  getAccessToken($code,$data){
         $url =  self::$GetAccessTokenURL."?grant_type=authorization_code&client_id=$data[appid]&client_secret=$data[secret]&code=$code&redirect_uri=$data[callback_url]";
          $token_json  =  self::https_request($url);
         parse_str($token_json, $token_array);
         if ($token_array['access_token'] && $token_array['expires_in']) {
             return $token_array['access_token'];
         }else{
             throw  new \Exception('qq快捷登录获取accessToken失败');
         }
     }


    /**
     * 通过Url获取code
     * @param $data
     * @return string
     */
     public static function  getCode($data){
         $url =  self::$GetRequestCodeURL."?client_id=$data[appid]&redirect_uri=$data[callback_url]&response_type=code&scope=get_user_info&state=1#wechat_redirect";
         return $url ;
     }



    /**
     * curl模拟http请求
     * @param $url
     * @param null $data
     * @return mixed
     */
   public  static function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }





    //获取文件内容
    public  static function get_php_file($filename) {
        return  trim(file_get_contents($filename));
    }



    //写入文件内容
    public  static function set_php_file($filename, $content) {
        file_put_contents($filename, $content);
    }



}

