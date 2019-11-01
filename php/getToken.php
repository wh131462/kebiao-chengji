<?php
// 本页面为易班token获取网页 回调地址设置为本页面 可以用来访问获取其他详细信息
	require_once("YBAPI-classes/yb-globals.inc.php");
	require_once("config.php");
	// 先进行初始化
	$api = YBOpenApi::getInstance()->init($config['appid'], $config['seckey'], $config['backurl']);
	$au  = $api->getAuthorize();
	$info = $au->getToken();
		if(!$info['status']) {//授权失败
		    echo $info['msg'];
		    die;
		}
	$token = $info['token'];//网站接入获取的token
	
?>
<html>
<body>
	<p><?php if (isset($token)&&$token){?>授权成功，点击下方链接查看通用接口测试<?php }?></p>
	<a href="getinfo.php?token=<?php echo $token?>">点击查看通用接口调用测试页面</a>
</body>
</html>