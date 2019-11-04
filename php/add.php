<?php
require("mysql.php");
// 只用于数据库存储
function addMem($ybid,$stuid,$password,$name,$school_name,$college_name,$class_name){
	// 存入数据库 完整版
	$add="INSERT INTO YBS_info (ybid,stuid,password,name,school_name,college_name,class_name) VALUES('$ybid','$stuid',''$password',''$name',''$school_name',''$college_name','$class_name');";
	global $conn;
	if($conn->query($add)==TRUE){
		return "Ins Success.";
	}else{
		return "Ins Failed.";
	}
}
function add($ybid,$stuid,$password){
	// 存入数据库  存储数据的简易版本 获取校级权限之后 可以删除此数据
	$add="INSERT INTO YBS_info (ybid,stuid,password) VALUES(".$ybid.",".$stuid.",".$password.");";
	global $conn;
	if($conn->query($add)==TRUE){
		echo "Ins success";;
		closeSql();
		return "Ins Success.";
	}else{
		echo "Ins failed". $conn->error;
		closeSql();
		return "Ins Failed.";
	}
}
?>