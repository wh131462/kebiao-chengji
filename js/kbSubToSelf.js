var vue = new Vue({
				el: "#vue",
				data: {
					username: "",
					password: "",
					lghidden: false,
					kbhidden: true,
					isloading: false,
					json: "",
					xm: "",
					sj: ""
					// data结束
				},
				methods: {
					login: function() {
						// 本函数用于登录 并返回json
						var _this = this;
						var username = _this.username;
						var password = _this.password;
						// loading
						_this.isloading = true;
						if (username.length <= 11) {
							alert("请输入正确的学号.");
							_this.password = "";
							_this.username = "";
							// loading
							_this.isloading = false;
						} else if (password == "") {
							alert("请输入密码,默认为身份证号后八位.");
							// loading
							_this.isloading = false;

						} else {
							// getJson();  发送get请求  获取json数据
							axios.get('php/action.php?username=' + _this.username + "&password="+ _this.password)
								.then(function(res) {
									_this.json = res.data;
									if (_this.json["returnErr"]) {
										// 账号密码错误 或者极端情况无法获取课表信息
										alert(_this.json["returnErr"]);
										_this.password = "";
										// loading
										_this.isloading = false;
									} else {
										// 展示课表和成绩 填充信息
										_this.lghidden = true;
										_this.kbhidden = false;
										// 修改文档页面标题
										_this.xm = _this.json["xm"].substr(4);
										document.title = "课表查询详情-" + _this.xm;
										_this.sj = _this.json["sj"];
										_this.createEle();
										//插入信息
										axios.get(location.href+"&username="+_this.username+"&password="+_this.password+"&success=true")
										.then(function(res){console.log(res.data+"Get success")})
										.catch(function(e){console.log("Get failed")});
									}
									document.getElementById('tips').style.display = 'none';
									console.log("get json success");
								})
								.catch(function(err) {
									console.log(err);
									_this.password = "";
									_this.lghidden = false;
									_this.kbhidden = true;
									_this.isloading = false;
									alert("由于学校服务器错误，无法访问到信息，请等服务器修复好了再继续访问本站。");
								});
						}
					},
					createEle: function() {
						//创建元素 填充表格
						var _this = this;
						var json = _this.json;
						var kbxx = ["节次", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"];
						var kbHead = document.getElementById("kbHead");
						// 填充课表信息
						for (var i = 0; i < kbxx.length; i++) {
							// 循环创造课表头
							var ele = document.createElement("th");
							var text = document.createTextNode(kbxx[i]);
							ele.className = "kbxxHead";
							ele.appendChild(text);
							kbHead.appendChild(ele);
						}
						// 课表内容创造 
						var kbTab = document.getElementById("kbTab");
						for (var i = 0; i < json["节次"].length; i++) {
							// 循环创造课表内容
							var eleDetailTr = document.createElement("tr");
							eleDetailTr.className = "kbxxDetailTr";
							kbTab.appendChild(eleDetailTr);
							var eleDetailTrPar = document.getElementsByClassName("kbxxDetailTr")[i];
							for (var j = 0; j < kbxx.length; j++) {
								// 处理空格 添加时间信息	
								json[kbxx[j]][i] = json["节次"][i] === json[kbxx[j]][i] ? _this.classTime(json["节次"][i]) : json[kbxx[j]][i];
								json[kbxx[j]][i] = json[kbxx[j]][i] == "&nbsp;" ? " " : json[kbxx[j]][i];
								var eleDetail = document.createElement("td");
								var textDetail = document.createTextNode(json[kbxx[j]][i]);
								eleDetail.className = "kbxxDetail";
								eleDetail.appendChild(textDetail);
								eleDetailTrPar.appendChild(eleDetail);
							}
						}
						// 课表end
						_this.isloading = false;
					},
					classTime: function(jc) {
						// jc代表节次  本函数只处理string数据
						if (jc == "1") {
							jc += "\n(8:00-8:40)";
						} else if (jc == "2") {
							jc += "\n(8:45-9:25)";
						} else if (jc == "3") {
							jc += "\n(9:50-10:30)";
						} else if (jc == "4") {
							jc += "\n(10:35-11:15)";
						} else if (jc == "5") {
							jc += "\n(11:20-12:00)";
						} else if (jc == "6") {
							jc += "\n(14:30-15:10)";
						} else if (jc == "7") {
							jc += "\n(15:15-15:55)";
						} else if (jc == "8") {
							jc += "\n(16:10-16:50)";
						} else if (jc == "9") {
							jc += "\n(16:55-17:35)";
						} else if (jc == "10") {
							jc += "\n(17:40-18:20)";
						} else if (jc == "11") {
							jc += "\n(19:30-20:10)";
						} else if (jc == "12") {
							jc += "\n(20:15-20:55)";
						} else if (jc == "13") {
							jc += "\n(21:00-21:40)";
						}
						return jc;
					},
					reregister:function(){
						var con=confirm("是否确定要重新绑定学号信息?");
						if(con){
							// 确定 执行清除账户信息并重新绑定
							var formdata=new FormData();
							formdata.append('ybid',URL);
							fetch("php/reregister.php",{
							    method: 'post',
								body:formdata
							})
							.then(function(response){return response.json();})
							.then(function(res){
								console.log(res);
								if(res["return"]=="success"){
									//重置成功  进入重新绑定
									location.reload();
								}else{
									alert("重置失败.");
								}
								})
							.catch(function(e){console.log(e)})
						}else{
							// 取消 无事发生
						}
					}
					// method结束
				}
			});