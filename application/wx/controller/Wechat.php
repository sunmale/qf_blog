<?php
namespace app\wx\controller;
use think\Controller;

class Wechat extends  Controller
{

    //验证消息
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    //检查签名
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
            return true;
        }else{
            return false;
        }
    }


    //响应消息
    public function responseMsg()
    {
         $postStr =   file_get_contents( "php://input");
       //  $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                case "image":
                    $result = $this->receiveImage($postObj);
                    break;
                case "location":
                    $result = $this->receiveLocation($postObj);
                    break;
                case "voice":
                    $result = $this->receiveVoice($postObj);
                    break;
                case "video":
                    $result = $this->receiveVideo($postObj);
                    break;
                case "link":
                    $result = $this->receiveLink($postObj);
                    break;
                default:
                    $result = "unknown msg type: ".$RX_TYPE;
                    break;
            }
            echo $result;
        }else {
            echo "";
            exit;
        }
    }

    //接收事件消息
    public function receiveEvent($object)
    {

        $openid  =   $object->FromUserName;    //发起信息的openid
        settype($openid,'string');

        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
                $url = config('blog.qf_blog_url').'/index/User/login_wx/wx_openid/'.$openid;
                $content = "欢迎关注晴枫个人博客
                \n以下是全部功能\n\n"."\t1.<a href='$url'>晴枫博客</a>
                \n\n"."\t2.进入聊天机器人
                \n\n"."\t3.退出聊天机器人
                \n\n\t4.绑定账号";
                $content .= (!empty($object->EventKey))?("\n来自二维码场景 ".str_replace("qrscene_","",$object->EventKey)):"";
                break;
            case "unsubscribe":
                $content = "取消关注";
                break;
            case "SCAN":
                $content = "扫描场景 ".$object->EventKey;
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "V_register_code":

                        break;
                    case "V_my_code":

                        // $content = "123";
                        break;
                    case "V_day_spread":
                        $openid =  $object->FromUserName;

                        break;

                    case "V_week_spread":
                        $openid =  $object->FromUserName;

                        break;
                    case "V_report_select":
                        $openid =  $object->FromUserName;
                        break;

                    default:
                        $content = "点击菜单：".$object->EventKey;
                        break;
                }
                break;
            case "LOCATION":
                $content = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;
                break;
            case "VIEW":
                $content = "跳转链接 ".$object->EventKey;
                break;
            default:
                $content = "receive a new event: ".$object->Event;
                break;
        }
        $result = $this->transmitText($object, $content);
        return $result;
    }


    //接收文本消息
    private function receiveText($object)
    {
        $index = new Index();

        $openid  =   $object->FromUserName;    //发起信息的openid
        settype($openid,'string');
        $content = $object->Content; // 用户输入的文本 （包括管理员 代理商 用户）

        /**
         * 匹配账号绑定功能
         */
        $matchRes = $index->matchString($content,config('matchString.bind_account'));
        if ($matchRes) {
            $res_content = $index->bindAccount($content,$openid);
            $result = $this->transmitText($object, $res_content);
            return $result;
        }
        switch ($object->Content)
        {
            case "功能":
                $url = config('blog.qf_blog_url').'/index/User/login_wx/wx_openid/'.$openid;
                $content = "全部功能\n\n"."\t1.<a href='$url'>晴枫博客</a>\n\n"."\t2.进入聊天机器人\n\n"."\t3.退出聊天机器人\n\n\t4.绑定账号";
                break;
            case "2":
                $openid =  $object->FromUserName;
                settype($openid,'string');
                $index->setSession('openid',$openid,$openid);
                $content = "欢迎进入聊天机器人，可以跟机器人进行聊天对话";
                break;
            case "3":
                $openid =  $object->FromUserName;
                settype($openid,'string');
              $index->delSession('openid',$openid);
                $content = "已经退出聊天机器人";
                break;
            case "4":
                $content = "请输入格式 绑定账号:你的邮箱。\n 注意 :号必须是英文下的。";
                break;
            default:
                $openid =  $object->FromUserName;
                settype($openid,'string');
               $result =  $index->getSession('openid',$openid);
               if($result){
                   //调用图灵机器人接口跟机器人进行聊天
                   $res =  $index->autoReply($object->Content,$openid);
                   $content =$res;
               }else{
                   $content = "该关键字没有被匹配，如需完整功能请输入 功能  即可";
               }
                break;
        }
        if(is_array($content)){
            if (isset($content[0]['PicUrl'])){
                $result = $this->transmitNews($object, $content);
                return $result;
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
                return $result;
            }
        }else{
            $result = $this->transmitText($object, $content);
            return $result;
        }
    }



    //接收图片消息
    private function receiveImage($object)
    {
        $content = array("MediaId"=>$object->MediaId);
        $result = $this->transmitImage($object, $content);
        return $result;
    }

    //接收位置消息
    private function receiveLocation($object)
    {
        $content = "你发送的是位置，纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //接收语音消息
    private function receiveVoice($object)
    {
        if (isset($object->Recognition) && !empty($object->Recognition)){
            $content = "你刚才说的是：".$object->Recognition;
            $result = $this->transmitText($object, $content);
        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }

        return $result;
    }

    //接收视频消息
    private function receiveVideo($object)
    {
        $content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"", "Description"=>"");
        $result = $this->transmitVideo($object, $content);
        return $result;
    }

    //接收链接消息
    private function receiveLink($object)
    {
        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //回复文本消息
    private function transmitText($object, $content)
    {
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

    //回复图片消息
    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
    <MediaId><![CDATA[%s]]></MediaId>
</Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[image]]></MsgType>
        $item_str
        </xml>";

        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复语音消息
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
    <MediaId><![CDATA[%s]]></MediaId>
</Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);

        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[voice]]></MsgType>
        $item_str
        </xml>";

        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复视频消息
    private function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
    <MediaId><![CDATA[%s]]></MediaId>
    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
</Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[video]]></MsgType>
        $item_str
        </xml>";

        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复图文消息
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $newsTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>
        <Content><![CDATA[]]></Content>
        <ArticleCount>%s</ArticleCount>
        <Articles>
        $item_str</Articles>
        </xml>";

        $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    //回复音乐消息
    private function transmitMusic($object, $musicArray)
    {
        $itemTpl = "<Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[music]]></MsgType>
        $item_str
        </xml>";

        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }








}
