<?php
	/**
	 * 网站接入使用Auth认证接口进行授权
	 * 授权流程先通过浏览器重定向到授权服务器取得授权码（code）后
	 * 再从服务器使用接口调用获取到对应用户的访问令牌
	 * 
	 */
    

	/**
	 * 包含SDK
	 */
	require("../classes/yb-globals.inc.php");

	//配置文件
	require_once 'config.php';
	
	//初始化
	$api = YBOpenApi::getInstance()->init($config['AppID'], $config['AppSecret'], $config['CallBack']);
	$au  = $api->getAuthorize();
	
	//网站接入获取access_token，未授权则跳转至授权页面
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
	<a href="apitest.php?token=<?=$token?>">点击查看通用接口调用测试页面</a>
	<p></p>
	<a target="_blank" href="https://o.yiban.cn/wiki/index.php?page=%E7%BD%91%E7%AB%99%E6%8E%A5%E5%85%A5%E5%BC%80%E5%8F%91%E6%8C%87%E5%8D%97">网站接入开发指南</a>
</body>
</html>