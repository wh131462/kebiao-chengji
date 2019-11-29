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