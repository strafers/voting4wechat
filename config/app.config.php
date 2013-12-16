<?php
//设置输出字符头
@header("Content-type: text/html; charset=utf-8");

$GLOBALS['config']['site_name'] = 'site_name';
$GLOBALS['config']['site_domain'] = $_SERVER['HTTP_HOST'];
$GLOBALS['config']['site_url'] = 'http://'.$GLOBALS['config']['site_domain'];

$GLOBALS['config']['api_server'] = $GLOBALS['config']['site_url']."/index.php";

$GLOBALS['config']['weixin_appid'] = '';
$GLOBALS['config']['weixin_appsecret'] = '';
$GLOBALS['config']['weixin_token'] = '';

//首次关注信息
$GLOBALS['config']['subscribe'] = '感谢关注';

//中奖信息
$GLOBALS['config']['weixin_lucky_msg'] = '中奖信息';

//进行中奖消息推送
$GLOBALS['config']['wxPush'] = fasle;

//是否开启微信公众平台服务器配置验证
$GLOBALS['config']['wxValid'] = false;
?>