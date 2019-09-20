<?php
// 接口形式 返回数据为 json格式数组
header("Content-type:text/html;charset=utf-8");
//引入html解析库
include_once("simple_html_dom.php");
//获取课表信息网页  
function getHtml($username,$password){
	// cookie 存放路径
	$cookie_jar_index=dirname(__FILE__)."/cookie.txt";
	$mainUrl = "http://172.16.129.117/"; //根url路径, 登录表单所在的path
	$kbUrl = "http://172.16.129.117/web_xsxk/xfz_xsxk_xh.aspx"; //课表所在的path　带着session去访问就能拿到课表的html
	$cjUrl = "http://172.16.129.117/web_cjgl/cx_cj_xh.aspx"; //成绩所在的path　带着session去访问就能拿到成绩的html
	// 返回数组信息
	$return=[];
	// 模拟登录需要发送以下信息
	$login=array("__VIEWSTATE"=>'/wEPDwUKMTI4NTg4NzczNw9kFgICAw9kFgQCAQ8PFgIeBFRleHQFMuS7iuWkqeaYrzoxOS0yMC0x5a2m5pyfICDnrKww5ZGoICAgMjAxOeW5tDnmnIgx5pelZGQCCw8WAh4JaW5uZXJodG1sZWRkV/4PnrPXqB8vW2LM5TDTgm2vbKQ=',
	"__VIEWSTATEGENERATOR"=>'8A3D921F',
	"__EVENTVALIDATION"=>'/wEdAAZM3DYnfH80uu3JlBUip0gsR1LBKX1P1xh290RQyTesRVwK8/1gnn25OldlRNyIedlIxghH0jev8NKg8v7JEe6mTIBERddB7WWz31lD9JIgUQDxgssP0lqr+WxJYU9W6nuoK8PYi9Bii1sLyF1Mn6NFU03dRw==',
	"UserName"=>$username,
	"Password"=>$password,
	"getpassword"=>"登陆");
	// 第一步 登录账号
	$ch = curl_init (); 
	curl_setopt($ch, CURLOPT_URL, $mainUrl); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 500); 
	curl_setopt($ch,CURLOPT_NOBODY,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar_index);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); 
	curl_setopt($ch, CURLOPT_POSTFIELDS,$login); 
	$loginOut=curl_exec($ch);
	// 登录状态返回码
	$loginHttpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
	curl_close($ch);
	// echo "<script>一级页面状态码：console.log(".$httpCode.");</script>";
	if($loginHttpCode==200){
		//第二步 带着session 去获取课表HTML和成绩
		$ch1 = curl_init (); 
		curl_setopt($ch1, CURLOPT_URL, $kbUrl); 
		curl_setopt($ch1, CURLOPT_TIMEOUT, 500); 
		curl_setopt($ch1,CURLOPT_NOBODY,0);
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER,1); 
		curl_setopt($ch1, CURLOPT_COOKIEFILE, $cookie_jar_index);  
		$kbOut=curl_exec($ch1);
		$kbHttpCode = curl_getinfo($ch1,CURLINFO_HTTP_CODE);
		curl_close($ch1);
		if($kbHttpCode==200){
			array_push($return,$kbOut);
		}
		$ch2 = curl_init ();
		curl_setopt($ch2, CURLOPT_URL, $cjUrl); 
		curl_setopt($ch2, CURLOPT_TIMEOUT, 200); 
		curl_setopt($ch2,CURLOPT_NOBODY,0);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER,1); 
		curl_setopt($ch2, CURLOPT_COOKIEFILE, $cookie_jar_index);  
		$cjOut=curl_exec($ch2);
		$cjHttpCode = curl_getinfo($ch2,CURLINFO_HTTP_CODE);
		curl_close($ch2);
		if($cjHttpCode==200){
			array_push($return,$cjOut);
		}
		return $return;
	}else{
		// 登录失败
		return false;
	}
}
//post访问 返回json
if($_SERVER["REQUEST_METHOD"]=="GET"){
	$username=$_GET["username"];
	$password=$_GET["password"];
	$res=getHtml($username,$password);
	$html1=str_get_html($res[0]);
	$html2=str_get_html($res[1]);
	if($html1===false){
		 $json=json_encode(["returnErr"=>"账号密码有误或服务器拒绝访问（登录失败）"]);
		 echo $json;
	}else if($html1===0){
		 $json=json_encode(["returnErr"=>"课表访问失败（登录成功但未获取到数据）"]);
		 echo $json;
	}else{
		// 返回json原型的大数组
		$return=[];
		// 课程头
		$xkalH=[];
		// 课表头
		$xkkbH=[];
		// 课程
		$xkal=[];
		// 课表
		$xkkb=[];
		// 筛课程头
		foreach($html1->find('table[id=GVxkall] b') as $key => $value){
		preg_match("/^<b>.*<\/b>/",$value,$match);
		$temp=substr($match[0],3,-4);
		array_push($xkalH,$temp);
		}
		// 筛课程详情
		foreach($html1->find('table[id=GVxkall] font') as $key => $value){
			if(preg_match("/^<font color=\"Black\" size=\"2\">.*<\/font>/",$value,$match)){
				$temp=substr($match[0],29,-7);
				array_push($xkal,$temp);
			}else{
				continue;
			}
		}
		// 筛课表头
		foreach($html1->find('table[id=GVxkkb] b') as $key => $value){
				preg_match("/^<b>.*<\/b>/",$value,$match);
				$temp=substr($match[0],3,-4);
				array_push($xkkbH,$temp);
		}
		// 筛课表详情
		foreach($html1->find('table[id=GVxkkb] font') as $key => $value){
			if(preg_match("/^<font color=\"Black\" size=\"2\">.*<\/font>/",$value,$match)){
				$temp=substr($match[0],29,-7);
				array_push($xkkb,$temp);
			}else{
				continue;
			}
		}
		$temp=[];
		for($i=0;$i<count($xkalH);$i++){
			for($j=0;$j<count($xkal);$j++){
				if($i+1==($j+1)%count($xkalH)||($i+1==count($xkalH)&&0==($j+1)%count($xkalH))){
					array_push($temp,$xkal[$j]);
				}
			}
			$return[$xkalH[$i]]=$temp;
			$temp=[];
		}
		$temp=[];
		for($i=0;$i<count($xkkbH);$i++){
			for($j=0;$j<count($xkkb);$j++){
				if($i+1==($j+1)%count($xkkbH)||($i+1==count($xkkbH)&&0==($j+1)%count($xkkbH))){
					array_push($temp,$xkkb[$j]);
				}
			}
			$return[$xkkbH[$i]]=$temp;
			$temp=[];
		}
		// 成绩和绩点
		$cj=[];
		$jd="";
		
		// 获取平均绩点
		foreach($html2->find('input[name=Txtjd]') as $key=>$value){
			$jd=substr($value,39,-61);
		}
		$return["jd"]=$jd;
		// 获取各项成绩 本数组前14项为表头
		foreach($html2->find('table[id=gvcj1] font') as $key=>$value){
			if(preg_match("/^<font size=\"2\">.*<\/font>/",$value,$match)){
				$temp=substr($match[0],15,-7);
				array_push($cj,$temp);
			}else if(preg_match("/^<font color=\"Blue\" size=\"2\">.*<\/font>/",$value,$match)){
				$temp=substr($match[0],28,-7);
				array_push($cj,$temp);
			}else{
				continue;
			}
		}
		$return["cj"]=$cj;
		$json=json_encode($return);
		echo $json;
	}
}
?>