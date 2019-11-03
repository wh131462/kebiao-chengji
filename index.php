<?php
$token=$_GET["token"]==""?"":$_GET["token"];
if (isset($token)&&$token){
		// 获取token成功
		require("php/YBAPI-classes/yb-globals.inc.php");
		// 配置文件
		require_once 'php/config.php';
		//初始化配置信息，并获取token
		$api = YBOpenApi::getInstance()->init($config['appid'], $config['seckey'], $config['backurl']);
		$api->bind($token);
		//获取用户真实信息
		$info=$api->request('user/verify_me');
		// 获取到真实信息之后  进行和数据库的比对
		require_once("php/mysql.php");
		if
		$sql="SELECT ybid,stuid,password FROM YBS_info WHERE ybid=\"".$info["yb_userid"]."\";";//查询账号密码
		$result=mysqli_query($conn,$sql);
		$res=mysqli_fetch_array($result,MYSQLI_ASSOC);
		if($res["ybid"]==null){
			// 第一次登陆 要求输入学号密码
			require_once("php/add.php");
			require_once("php/html.php");
			// 想办法将html中的 password获取到  便完成了php登陆易班的版本
			echo "<script>vue.</script>";
			add($info[yb_userid],$info[yb_studentid],$info[password],$info[yb_realname],$info[yb_schoolname],$info[yb_collegename],$info[yb_classname]);
		}else{
			require("php/html.php");
			echo "<script>vue.lghidden=true;
			vue.username=".$res["stuid"].";
			vue.password=".$res["password"].";
			vue.login();</script>";
		}
		closeSql();
?>

<?php
}
else{
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
		header('location: index.php?token='.$token);
}
?>

