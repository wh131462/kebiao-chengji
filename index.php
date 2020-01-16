<?php
$token=$_GET["token"]==""?"":$_GET["token"];
$username=$_GET["username"]==null?"":$_GET["username"];
$password=$_GET["password"]==null?"":$_GET["password"];
setcookie("type", $_GET["type"], time()+1800);
if($username!=""){
	//在交互之后存储信息  只有信息正确才会访问到此
	require_once("php/add.php");
	// 获取token成功
	require_once("php/YBAPI-classes/yb-globals.inc.php");
	// 配置文件
	require_once('php/config.php');
	//初始化配置信息，并获取token
	$api = YBOpenApi::getInstance()->init($config['appid'], $config['seckey'], $config['backurl']);
	$api->bind($token);
	//获取用户真实信息
	$info=$api->request('user/me');
	//此时为添加数据库的时刻
	add($info["info"]["yb_userid"],$username,$password);
}else{
	//第一次访问 到此 如果有存档 则直接用存储信息进行查询 无 则进行输入要求存档
	if (isset($token)&&$token){
			$type=$_GET["type"]==null?"":$_GET["type"];
			// 获取token成功
			require_once("php/YBAPI-classes/yb-globals.inc.php");
			// 配置文件
			require_once('php/config.php');
			//初始化配置信息，并获取token
			$api = YBOpenApi::getInstance()->init($config['appid'], $config['seckey'], $config['backurl']);
			$api->bind($token);
			//获取用户真实信息
			$info=$api->request('user/me');
			// 获取到真实信息之后  进行和数据库的比对
			require_once("php/mysql.php");
			$sql="SELECT ybid,stuid,password FROM YBS_info WHERE ybid=".$info["info"]["yb_userid"].";";//查询账号密码
			$sqlRes=mysqli_query($conn,$sql);
			$res=mysqli_fetch_array($sqlRes,MYSQLI_ASSOC);
			if($res["ybid"]==null){
				// 第一次登陆 要求输入学号密码
				if($type=="kebiao"){
					include_once("php/kbhtml.php");
					echo "<script type=\"text/javascript\" src=\"js/kbSubToSelf.js\"></script>";
					include_once("php/htmlend.php");
				}else if($type=="chengji"){
					include_once("php/cjhtml.php");
					echo "<script type=\"text/javascript\" src=\"js/cjSubToSelf.js\"></script>";
					include_once("php/htmlend.php");
				}else{
					include_once("php/html.php");
					echo "<script type=\"text/javascript\" src=\"js/SubToSelf.js\"></script>";
					include_once("php/htmlend.php");
				}
				
			}else{
				if($type=="kebiao"){
					include_once("php/kbhtml.php");
					echo "<script type=\"text/javascript\" src=\"js/kbwork.js\"></script>";
					echo "<script>vue.lghidden=true;
					vue.username=\"".$res["stuid"]."\";
					vue.password=\"".$res["password"]."\";
					vue.login();</script>";
					include_once("php/htmlend.php");
				}else if($type=="chengji"){
					include_once("php/cjhtml.php");
					echo "<script type=\"text/javascript\" src=\"js/cjwork.js\"></script>";
					echo "<script>vue.lghidden=true;
					vue.username=\"".$res["stuid"]."\";
					vue.password=\"".$res["password"]."\";
					vue.login();</script>";
					include_once("php/htmlend.php");
				}else{
					include_once("php/html.php");
					echo "<script type=\"text/javascript\" src=\"js/work.js\"></script>";
					echo "<script>vue.lghidden=true;
					vue.username=\"".$res["stuid"]."\";
					vue.password=\"".$res["password"]."\";
					vue.login();</script>";
					include_once("php/htmlend.php");
				}
			}
			closeSql();
	}else{
			// 本页面为易班token获取网页 回调地址设置为本页面 可以用来访问获取其他详细信息
			require_once("php/YBAPI-classes/yb-globals.inc.php");
			require_once("php/config.php");
			// 先进行初始化
			$api = YBOpenApi::getInstance()->init($config['appid'], $config['seckey'], $config['backurl']);
			$au  = $api->getAuthorize();
			$info = $au->getToken();
			if(!$info['status']) {//授权失败
			    echo $info['msg'];
			    die;
			}
			$token = $info['token'];//网站接入获取的token
			header('location: index.php?token='.$token."&type=".$_COOKIE["type"]);
	}
}
?>