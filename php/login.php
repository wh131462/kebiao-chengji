<?php
require_once("YBAPI-classes/yb-globals.inc.php");
// 初始化易班API
$api = YBOpenApi::getInstance()->init();
// $token=$api->->getAuthorize()->getToken();
// $api->bind($token);
id 11800302 se
$url 		= 'user/me';
$param 		= array();
$isPOST		= false;
$applyToken = true;
$result = YBOpenApi::getInstance()->request($url, $param, $isPOST, $applyToken);
echo $result;
?>