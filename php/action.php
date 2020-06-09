<?php
// 接口形式 返回数据为 json格式数组
header("Content-type:text/html;charset=utf-8");
include_once("kb-cj.php");
//post访问 返回json
if($_SERVER["REQUEST_METHOD"]=="GET"){
	if($_GET["username"]!=""&&$_GET["password"]!=""){
		$fp=fopen(dirname(__FILE__)."/COOKIE/cookie_".$_GET["username"].".txt","w");
		fclose($fp);
		getJson($_GET["username"],$_GET["password"]);
		//unlink(dirname(__FILE__)."/COOKIE/cookie_".$_GET["username"].".txt");//用于删除用户本次 使用cookie
	}
}
?>
<!-- 
1.用获取的账号密码进行请求  2.curl访问教务处页面 获取数据 3.后端进行处理获得的数据并返回前端 4.对返回数据进行渲染 -->