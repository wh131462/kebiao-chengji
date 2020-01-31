<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=yes" />
		<title>课表和成绩查询</title>
		<link rel="shortcut icon" href="image/logo.ico">
		<link rel="stylesheet" type="text/css" href="css/main.css" />
		<script src="js/vue.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/axios.min.js"></script>
	</head>
	<body>

		<div id="vue">
			<!-- 更改绑定信息 重置信息-->
			<div id="top">
				<a href="javascript:void(0);" @click="reregister()">更改绑定</a>
				<a href="https://gxust-yiban.com:10086/index.html">直接查询</a>
			</div>
			<div id="tips">
				<p>1.第一次登陆需要输入学号密码，一般密码默认为身份证号后八位.<a href="javascript:void(0);" style="text-decoration: none;color: #FF0000;float: right;margin-right: 10px;"
					 onclick="{document.getElementById('tips').style.display='none';console.log('dis');}">×</a></p>
			</div>
			<!-- 加载动画 -->
			<div id="loadingBG" v-if="isloading">
				<div id="loading"><img src="image/loading.png">
				</div>
			</div>

			<div id="login" :hidden="lghidden">
				<span>&nbsp;</span>
				<div id="login_detail">
					<p id="login_title">课表和成绩查询</p>
					<input placeholder="Student ID" type="number" name="username" id="username" v-model="username" />
					<input type="password" placeholder="Password" name="password" id="password" v-model="password" />
					<button type="button" @click="login()">开始查询</button>
				</div>
				<div class="foot">
					<small>
						<p>课表和成绩查询v1.0 <a href="mailto:wanghao@gxust-yiban.com">联系我们</a></p>
					</small>
				</div>
			</div>
			<div class="kbBox" :hidden="kbhidden">
				<!-- 时间显示 -->
				<p class="extraInfo">{{sj}}</p>
				<!-- 课表显示 -->
				<div id="kb">
					<table id="kbTab">
						<tr id="kbHead"></tr>
						<!-- 此处会填充 tr class=kbDetailTr -->
					</table>
				</div>
				<div id="kcxx">
					<!-- 课程信息显示 -->
					<table id="kcTab">
						<tr id="kcHead"></tr>
						<!-- 此处会填充 tr class=kcDetailTr  -->
					</table>
				</div>

			</div>
			<div class="cjBox" :hidden="cjhidden">
				<p class="extraInfo">您的必修课平均学分绩点:{{jd}}</p>
				<table id="cjTab">
					<tr id="cjHead"></tr>
					<!-- 此处会填充 tr clas=cjDetailTr -->
				</table>
				<div class="kbfoot">
					<small>
						<p>课表和成绩查询v1.0 <a href="mailto:wanghao@gxust-yiban.com">联系我们</a></p>
					</small>
				</div>
			</div>
		</div>
