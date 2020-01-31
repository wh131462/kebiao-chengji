var vue = new Vue({
				el: "#vue",
				data: {
					username: "",
					password: "",
					lghidden: false,
					kbhidden: true,
					cjhidden: true,
					isloading: false,
					json: "",
					jd: "",
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
										_this.cjhidden = false;
										// 修改文档页面标题
										_this.xm = _this.json["xm"].substr(4);
										document.title = "课表和成绩查询详情-" + _this.xm;
										_this.jd = _this.json["jd"];
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
									_this.cjhidden = true;
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
						var kcxx = ["课程代码", "课程名称", "类别", "学分", "上课教师", "选课类别", "上课班级"];
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
						// 填充课程信息
						var kcHead = document.getElementById("kcHead");
						// 填充课程信息
						for (var i = 0; i < kcxx.length; i++) {
							// 循环创造课程头
							var kcele = document.createElement("th");
							var text = document.createTextNode(kcxx[i]);
							kcele.className = "kcxxHead";
							kcele.appendChild(text);
							kcHead.appendChild(kcele);
						}
						// 课程内容创造 
						var kcTab = document.getElementById("kcTab");
						for (var i = 0; i < json["课程代码"].length; i++) {
							// 循环创造课程内容
							var kceleDetailTr = document.createElement("tr");
							kceleDetailTr.className = "kcxxDetailTr";
							kcTab.appendChild(kceleDetailTr);
							var kceleDetailTrPar = document.getElementsByClassName("kcxxDetailTr")[i];
							for (var j = 0; j < kcxx.length; j++) {
								// 处理空格
								json[kcxx[j]][i] = json[kcxx[j]][i] == "&nbsp;" ? " " : json[kcxx[j]][i];
								var kceleDetail = document.createElement("td");
								var textDetail = document.createTextNode(json[kcxx[j]][i]);
								kceleDetail.className = "kcxxDetail";
								kceleDetail.appendChild(textDetail);
								kceleDetailTrPar.appendChild(kceleDetail);
							}
						}
						// 成绩查询
						var cjHead = document.getElementById("cjHead");
						if (json["cj"] == "") {
							cjHead.innerHTML = "暂无成绩!";
						} else {
							for (var i = 0; i < json["cj"].length / 14; i++) {

								if (i == 0) {
									// 表头
									for (var j = 0; j < 14; j++) {
										if (j == 0 || j == 1 || j == 4 || j == 7 || j == 12 || j == 13) {
											//不想输出什么就在上面判断 数组为["学号", "姓名", "学期", "课程名称", "类别", "学分", "平时", "期中", "期末", "总评成绩", "考试性质", "绩点", "课程代码"]
											continue;
										} else {
											json["cj"][j] = json["cj"][j] == "&nbsp;" ? " " : json["cj"][j];
											var cjH = document.createElement("th");
											var text = document.createTextNode(json["cj"][j]);
											cjH.className = "cjHead";
											cjH.appendChild(text);
											cjHead.appendChild(cjH);
										}
									}
								} else {
									// 循环创造成绩内容
									// 创造包含一行内容的th
									var cjEleDetailTr = document.createElement("tr");
									cjEleDetailTr.className = "cjDetailTr";
									cjTab.appendChild(cjEleDetailTr);
									var cjEleDetailTrPar = document.getElementsByClassName("cjDetailTr")[i - 1];
									for (var j = 0; j < 14; j++) {
										if (j == 0 || j == 1 || j == 4 || j == 7 || j == 12 || j == 13) {
											continue;
										} else {

											json["cj"][14 * i + j] = json["cj"][14 * i + j] == "&nbsp;" ? " " : json["cj"][14 * i + j];
											var cjEleDetail = document.createElement("td");
											var textDetail = document.createTextNode(json["cj"][14 * i + j]);
											// 判断期末成绩不及格
											if (j == 9 && (isNaN(json["cj"][14 * i + j]) == false && json["cj"][14 * i + j] < 60) || (isNaN(json["cj"]
													[14 * i + j]) && json["cj"][14 * i + j] == "零分卷")) {
												cjEleDetail.classList.add("NoneOfPeopleCanHelp", "cjDetail");
											} else {
												cjEleDetail.className += "cjDetail";
											}

											cjEleDetail.appendChild(textDetail);
											cjEleDetailTrPar.appendChild(cjEleDetail);
										}
									}
								}
							}
						}
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