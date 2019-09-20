<?php
header("Content-type:text/html;charset=utf-8");
//引入html解析库
include_once("php/simple_html_dom.php");
//本地测试用  省略调用getKB函数
if($_SERVER["REQUEST_METHOD"]=="GET"){

	$user=$_GET["username"];
	$pwd=$_GET["password"];
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,"http://127.0.0.1/KeBiao/kb.html");
curl_setopt($ch,CURLOPT_NOBODY,0);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$res=curl_exec($ch);
//本地测试结束
	$html=str_get_html($res);
	
	if($html==false){
		echo "解析函数调用失败";
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
		foreach($html->find('table[id=GVxkall] b') as $key => $value){
		preg_match("/^<b>.*<\/b>/",$value,$match);
		$temp=substr($match[0],3,-4);
		array_push($xkalH,$temp);
		}
		// 课程详情
		foreach($html->find('table[id=GVxkall] font') as $key => $value){
			if(preg_match("/^<font color=\"Black\" size=\"2\">.*<\/font>/",$value,$match)){
				$temp=substr($match[0],29,-7);
				array_push($xkal,$temp);
			}else{
				continue;
			}
		}
		// 课表头
		foreach($html->find('table[id=GVxkkb] b') as $key => $value){
				preg_match("/^<b>.*<\/b>/",$value,$match);
				$temp=substr($match[0],3,-4);
				array_push($xkkbH,$temp);
		}
		// 课表详情
		foreach($html->find('table[id=GVxkkb] font') as $key => $value){
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
		$json=json_encode($return);
		echo $json;
	}
}
?>