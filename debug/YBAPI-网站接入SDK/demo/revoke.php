<?php
//回收授权操作
$token = isset($token) ? $token : $_GET['token'];

/**
 * 包含SDK
 */
require("../classes/yb-globals.inc.php");

// 配置文件
require_once 'config.php';

$api = YBOpenApi::getInstance()->init($config['AppID'], $config['AppSecret'], $config['CallBack']);
$api->bind($token);
?>
<p>调用取消用户授权后的返回结果</p>
<p>
<?php 
$res = $api->request('oauth/revoke_token', array('client_id'=>$api->getConfig('appid')), true);
var_dump($res);
?>
</p>
<p><?php if ($res['status']==200) {?>撤销授权成功，下次访问DEMO时会重新要求用户授权<?php }?></p>
<a href="index.php">返回DEMO首页</a>