<?php 
// 重置账户
include_once("sql.php");
include_once("mysql.php");
if($_SERVER["REQUEST_METHOD"]=="POST"){
	// db YBS_info tb YBS_info
	$database="YBS_info";
	$table="YBS_info";
	connect($host,$sql_username,$sql_password);
	$return=deleteData($database,$table,"stuid",$_POST["username"]);
	if($return=="删除数据成功~"){
		$return["return"]="success";
		$json=json_encode($return);
		echo $json;
	}else{
		$return["return"]="failed";
		$json=json_encode($return);
		echo $json;
	}
}else{
	echo "The page is not found.";
}
?>