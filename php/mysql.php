<?php
require("sql.php");
// 连接数据库
	global $conn;
	$conn= new mysqli($host,$sql_username,$sql_password,$sql_database);
	mysqli_set_charset($conn,"utf8");
	if($conn->connect_error){
		die("连接失败".$conn->connect_error);
	}
	// echo "<script>console.log('Connect complete.')</script>";
/*
//表结构
"CREATE TABLE YBS_info(
num INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
ybid VARCHAR(50) NOT NULL UNIQUE,
stuid VARCHAR(50) NOT NULL,
password VARCHAR(50) NOT NULL,
name TEXT,
school_name TEXT,
college_name TEXT,
class_name TEXT,
reg_date TIMESTAMP
)";
//注释结束
*/
$conn->query("use YBS_info;");
function closeSql(){
	global $conn;
	$conn->close();
}
?>