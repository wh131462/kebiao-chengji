<?php
// 接口形式 返回数据为 json格式数组
header("Content-type:text/html;charset=utf-8");
include_once("kb-cj.php");
//post访问 返回json
if($_SERVER["REQUEST_METHOD"]=="GET"){
	if($_GET["username"]!=""&&$_GET["password"]!=""){
		getJson($_GET["username"],$_GET["password"]);
	}
}
?>