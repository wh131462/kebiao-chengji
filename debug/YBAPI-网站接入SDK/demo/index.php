<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<title>易班开放平台DEMO</title>
	<style>	* { line-height: 32px; } </style>
</head>
<body>
	<div>
		<p style="font-weight: bold;">
			网站接入SDK使用DEMO
		</p>
		<p style="font-weight: bold;">
			1、<a href="https://o.yiban.cn/manage/index" target="_blank">开放平台管理中心</a>左侧导航栏中选择"网站接入"中的网站（若无应用则创建新网站接入）
		</p>
		<p style="font-weight: bold;">
			2、设置网站中的"授权回调地址"为此DEMO中authorize.php所在的URL
		</p>
		<p style="font-weight: bold;">
			3、修改此DEMO中config.php文件，填写应用信息(Appid和AppSecret),CallBack填网站接入的授权回调地址
		</p>
		<p style="font-weight: bold;">
			以上步骤操作完成后点击下方链接测试DEMO
		</p>
	</div>
	<div>
		<a href="authorize.php">网站接入DEMO</a>
	</div>
</body>
</html>