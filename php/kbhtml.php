<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=yes" />
		<title>课表查询</title>
		<link rel="shortcut icon" href="image/logo.ico">
		<link rel="stylesheet" type="text/css" href="css/kb.css" />
		<script src="js/vue.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/axios.min.js"></script>
	</head>
	<body>

		<div id="vue">
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
					<p id="login_title">课表查询</p>
					<input placeholder="Student ID" type="number" name="username" id="username" v-model="username" />
					<input type="password" placeholder="Password" name="password" id="password" v-model="password" />
					<button type="button" @click="login()">开始查询</button>
				</div>
				<div class="foot">
					<small>
						<p>课表绩查询v1.1 <a href="mailto:wanghao@gxust-yiban.com">联系我们</a></p>
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
				<div class="kbfoot">
					<small>
						<p>课表查询v1.1 <a href="mailto:wanghao@gxust-yiban.com">联系我们</a></p>
					</small>
				</div>
			</div>
		</div>