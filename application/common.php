<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件




/**
 * 获取操作系统
 */
function get_os() {
    $os = '';
    $Agent = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/win/i', $Agent) && strpos($Agent, '95')) {
        $os = 'Win 95';
    } elseif (preg_match('/win 9x/i', $Agent) && strpos($Agent, '/4.90/i')) {
        $os = 'Win ME';
    } elseif (preg_match('/win/i', $Agent) && preg_match('/98/i', $Agent)) {
        $os = 'Win 98';
    } elseif (preg_match('/win/i', $Agent) && preg_match('/nt 5.0/i', $Agent)) {
        $os = 'Win 2000';
    } elseif (preg_match('/win/i', $Agent) && preg_match('/nt 6.0/i', $Agent)) {
        $os = 'Win Vista';
    } elseif (preg_match('/win/i', $Agent) && preg_match('/nt 6.1/i', $Agent)) {
        $os = 'Win 7';
    } elseif (preg_match('/win/i', $Agent) && preg_match('/nt 5.1/i', $Agent)) {
        $os = 'Win XP';
    } elseif (preg_match('/win/i', $Agent) && preg_match('/nt 6.2/i', $Agent)) {
        $os = 'Win 8';
    } elseif (preg_match('/win/i', $Agent) && preg_match('/nt 6.3/i', $Agent)) {
        $os = 'Win 8.1';
    } elseif (preg_match('/win/i', $Agent) && preg_match('/nt 10/i', $Agent)) {
        $os = 'Win 10';
    } elseif (preg_match('/win/i', $Agent) && preg_match('/nt/i', $Agent)) {
        $os = 'Win NT';
    } elseif (preg_match('/win/i', $Agent) && preg_match('/32/i', $Agent)) {
        $os = 'Win 32';
    } elseif (preg_match('/Mi/i', $Agent)) {
        $os = '小米';
    } elseif (preg_match('/Android/i', $Agent) && preg_match('/LG/i', $Agent)) {
        $os = 'LG';
    } elseif (preg_match('/Android/i', $Agent) && preg_match('/M1/i', $Agent)) {
        $os = '魅族';
    } elseif (preg_match('/Android/i', $Agent) && preg_match('/MX4/i', $Agent)) {
        $os = '魅族4';
    } elseif (preg_match('/Android/i', $Agent) && preg_match('/M3/i', $Agent)) {
        $os = '魅族';
    } elseif (preg_match('/Android/i', $Agent) && preg_match('/M4/i', $Agent)) {
        $os = '魅族';
    } elseif (preg_match('/Android/i', $Agent) && preg_match('/Huawei/i', $Agent)) {
        $os = '华为';
    } elseif (preg_match('/Android/i', $Agent) && preg_match('/HM201/i', $Agent)) {
        $os = '红米';
    } elseif (preg_match('/Android/i', $Agent) && preg_match('/KOT/i', $Agent)) {
        $os = '红米4G版';
    } elseif (preg_match('/Android/i', $Agent) && preg_match('/NX5/i', $Agent)) {
        $os = '努比亚';
    } elseif (preg_match('/Android/i', $Agent) && preg_match('/vivo/i', $Agent)) {
        $os = 'Vivo';
    } elseif (preg_match('/Android/i', $Agent)) {
        $os = 'Android';
    } elseif (preg_match('/linux/i', $Agent)) {
        $os = 'Linux';
    } elseif (preg_match('/unix/i', $Agent)) {
        $os = 'Unix';
    } elseif (preg_match('/iPhone/i', $Agent)) {
        $os = '苹果';
    } else if (preg_match('/sun/i', $Agent) && preg_match('/os/i', $Agent)) {
        $os = 'SunOS';
    } elseif (preg_match('/ibm/i', $Agent) && preg_match('/os/i', $Agent)) {
        $os = 'IBM OS/2';
    } elseif (preg_match('/Mac/i', $Agent) && preg_match('/PC/i', $Agent)) {
        $os = 'Macintosh';
    } elseif (preg_match('/PowerPC/i', $Agent)) {
        $os = 'PowerPC';
    } elseif (preg_match('/AIX/i', $Agent)) {
        $os = 'AIX';
    } elseif (preg_match('/HPUX/i', $Agent)) {
        $os = 'HPUX';
    } elseif (preg_match('/NetBSD/i', $Agent)) {
        $os = 'NetBSD';
    } elseif (preg_match('/BSD/i', $Agent)) {
        $os = 'BSD';
    } elseif (preg_match('/OSF1/i', $Agent)) {
        $os = 'OSF1';
    } elseif (preg_match('/IRIX/i', $Agent)) {
        $os = 'IRIX';
    } elseif (preg_match('/FreeBSD/i', $Agent)) {
        $os = 'FreeBSD';
    } elseif (preg_match('/Mac OS/i', $Agent)) {
        $os = 'Mac OS';
    }else if ($os == ''){
        $os = '未知';
    }
    return $os;
}


/**
 * 通过新浪接口通过ip获得具体地址
 */
function get_address_by_ip($ip){
    $loc = "";
    $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$ip;
    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_ENCODING ,'utf8');
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
    $location = curl_exec($ch);
    $location = json_decode($location);
    curl_close($ch);
    if(!empty($location->ret)) {
        if($location->province=='北京' || $location->province=='上海' || $location->province=='重庆' || $location->province=='天津' ){
            $loc = $location->province.'市';
        }else{
            $loc = $location->province.'省'.$location->city.'市';
        }
        // $loc = $location->province.$location->city.$location->district.$location->isp;
    }else{
        if( ($ip=="0.0.0.0") || ($ip=="127.0.0.1")  ){
            $loc ="本地";
        }else{
            $loc ="未知";
        }
    }
    return  $loc;
    // return $loc;
}




/**
 * 发送邮件通知
 * @param $user
 * @param $data
 * @param string $type
 * @return bool
 */
function sendMail($user,$data,$type='qq'){
    //加载自定义配置
    $options = config('mail');
    $result =   \qf\Mail::getInstance()->init($options)->sendMail($user,$data,$type);
    return $result;
}





/**
 * 设置缓存方式跟路径
 * @param $data
 */
function setCache($data){
     if($data['type']=='file'){
         $data['diff'] = 'path';
     }else if($data['type']=='redis'){
         $data['diff'] = 'host';
     }
    $config = [
        // 缓存类型为File
        'type'  =>$data['type'],
        // 缓存有效期为永久有效
        'expire'=>  0,
        //缓存前缀
        'prefix'=>'qf',
        // 指定缓存目录
        $data['diff'] => $data['path']     // BASE_PATH.'/static/cache/admin/',
    ];
     \think\Cache::init($config);
}






/**
 * curl模拟http请求
 * @param $url
 * @param null $data
 * @return mixed
 */
 function https_request($url, $data = null)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        if(is_array($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }else if(json_decode($data)){
             //是json格式
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
        }
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}


/**
 * 判断是否移动端
 * @return bool
 */
function jump_is_mobile()
{
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    }
    if (isset ($_SERVER['HTTP_VIA']))
    {
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
        );
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        }
    }
    if (isset ($_SERVER['HTTP_ACCEPT']))
    {
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        }
    }
    return false;
}
