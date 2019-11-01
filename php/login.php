<?php
require_once("YBAPI-classes/yb-globals.inc.php");
require_once 'config.php';
// 初始化易班API
$api = YBOpenApi::getInstance()->init($config['AppID'], $config['AppSecret'], $config['CallBack']);
$au  = $api->getAuthorize();
//网站接入获取access_token，未授权则跳转至授权页面
$info = $au->getToken();
$api->bind($token);
$url 		= 'user/me';
$param 		= array();
$isPOST		= false;
$applyToken = true;
$result = YBOpenApi::getInstance()->request($url, $param, $isPOST, $applyToken);
var_dump($result);
?>