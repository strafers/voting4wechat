<?php
if (!defined('IN'))
    die('bad request');
include_once (AROOT . 'controller' . DS . 'app.class.php');

class weixinController extends appController
{
    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        if(c("wxValid"))
        {
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];
            $echoStr = $_GET["echostr"];
            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

            die( $this->valid($echoStr,$signature,$timestamp,$nonce) );
        }

        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $GLOBALS['fromUsername'] = $postObj->FromUserName;
        $GLOBALS['toUsername'] = $postObj->ToUserName;
        $event = $postObj->Event;
        $eventKey = $postObj->EventKey;
        $keyword = z(t($postObj->Content));

        //订阅事件，发送欢迎词
        if ($event == "subscribe") {
            $text = c("subscribe");
            die($this->creat_xml_response($text));
        }

        //自定义菜单的点击事件
        if($event == "CLICK")
        {
            switch ($eventKey) {
                case 'V1001_RANK_LUCKY':
                    $votecount = 10 - get_user_count(g("fromUsername"));
                    $msg = "您还剩余{$votecount}票";
                    die($this->creat_xml_response($msg));
                    break;
                case 'V1001_INREODUCE':

                    $title = "";
                    $description ="";
                    $picUrl = "";
                    $url ="";
                    die($this->creat_pic_response($title,$description,$picUrl,$url));

                    break;
                default:
                    break;
            }

        }

        //进行投票操作
        $openId =  g("fromUsername");

        if($keyword != "" ){
            $workId =  strtoupper ($keyword);
            $text = send_vote($openId,$workId);
        }

    }


    /**
     * 创建XML格式的response
     * @fromUsername - 消息发送方微信号
     * @toUsername - 消息接收方微信号
     * @contentStr - 需要发送的文本内容
     * @return xml
     */
    function creat_xml_response($contentStr)
    {
        $msgType = "text";
        $time = time();
        $textTpl = "<xml>
    				<ToUserName><![CDATA[%s]]></ToUserName>
    				<FromUserName><![CDATA[%s]]></FromUserName>
    				<CreateTime>%s</CreateTime>
    				<MsgType><![CDATA[%s]]></MsgType>
    				<Content><![CDATA[%s]]></Content>
    				<FuncFlag>0</FuncFlag>
    				</xml>";

        $resultStr = sprintf($textTpl, g('fromUsername'), g('toUsername'), $time, $msgType,
            $contentStr);
        return $resultStr;

    }

    /**
     * 创建XML格式的图文response
     * @fromUsername - 消息发送方微信号
     * @toUsername - 消息接收方微信号
     * @contentStr - 需要发送的文本内容
     * @return xml
     */
    function creat_pic_response($title,$description,$picUrl,$url)
    {
        $msgType = "news";
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <ArticleCount>1</ArticleCount>
                    <Articles>
                        <item>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url>
                        </item>
                    </Articles>
                    </xml> ";

        $resultStr = sprintf($textTpl, g('fromUsername'), g('toUsername'), $time, $msgType,
            $title,$description,$picUrl,$url);
        return $resultStr;

    }

    function valid($echoStr, $signature, $timestamp, $nonce)
    {
        //valid signature , option
        if ($this->checkSignature($signature, $timestamp, $nonce)) {
            return $echoStr;
        }

    }

    function checkSignature($signature, $timestamp, $nonce)
    {
        $token = c('weixin_token');
        $tmpArr = array(
            $token,
            $timestamp,
            $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}

?>