<?php
$token = isset($token) ? $token : $_GET['token'];
// 本页面用获取来的token进行操作 读库 并判断是存库还是进行直接跳转
/**
 * 包含SDK
 */

require("../classes/yb-globals.inc.php");
// 配置文件
require_once 'config.php';
//初始化配置信息，并获取token
$api = YBOpenApi::getInstance()->init($config['appid'], $config['seckey'], $config['backurl']);
$api->bind($token);
// 获取用户真实信息
$info=$api->request('user/verify_me');

echo $info;
?>