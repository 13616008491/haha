$(function(){
	//拆分链接给对应类型加上样式
	/*var _url = window.location.href;
	var bank_index="n",
		purpose_index="n",
		currency_index="n",
		free_index="n",
		level_index="n";
	_url=_url.split("/");
	for(var i=0;i<_url.length;i++){
		(_url[i].indexOf(".") != -1)?_url[i]=_url[i].split(".")[0]:[];
	}
	for(var i=0;i<_url.length;i++){
		(_url[i]=="bank")?bank_index=_url[i+1]:[];
		(_url[i]=="purpose")?purpose_index=_url[i+1]:[];
		(_url[i]=="currency")?currency_index=_url[i+1]:[];
		(_url[i]=="free")?free_index=_url[i+1]:[];
		(_url[i]=="level")?level_index=_url[i+1]:[];
	}
	//console.log("bank_index"+bank_index+"   purpose_index"+purpose_index+"   currency_index"+currency_index+"   free_index"+free_index+"   level_index"+level_index);
	
	$("#_bank ul li[nav_id="+((bank_index)*89+91)+"]").addClass("aft");
	$("#_purpose ul li[nav_id="+((purpose_index)*89+91)+"]").addClass("aft");
	$("#_level ul li[nav_id="+((level_index)*89+91)+"]").addClass("aft");
	$("#_free ul li[nav_id="+((free_index)*89+91)+"]").addClass("aft");
	$("#_currency ul li[nav_id="+((currency_index)*89+91)+"]").addClass("aft");
	
	(bank_index=="n")?$("#_bank div a").addClass("no-limit"):[];
	(purpose_index=="n")?$("#_purpose div a").addClass("no-limit"):[];
	(currency_index=="n")?$("#_currency div a").addClass("no-limit"):[];
	(free_index=="n")?$("#_free div a").addClass("no-limit"):[];
	(level_index=="n")?$("#_level div a").addClass("no-limit"):[];*/

	
	//对应类型加上样式
	var type_objs = $("#chonice_list .list-item-row");
	for(var i=0;i<type_objs.length;i++){
		var start = type_objs.eq(i).find("li").find("a").hasClass("aft");
		if(start==false){
			type_objs.eq(i).find("div").find("a").addClass("no-limit");
		}
	}

	
	//判断来源
	var ly=document.referrer;
	if(ly.split("/")[ly.split("/").length-1] != "credit.html"){
		console.log("f")
		$('html, body').animate({
	        scrollTop:500
		}, 0);
		
		//页面刷新时是否需要展示下拉菜单
		if(sessionStorage.getItem("is_list_more") == "true"){
			$("#chonice_list").css("height","auto");
			$(".list-item-more").html("收起更多筛选<i></i>");
			$(".list-item-more").find("i").css({"border-bottom":"6px solid #999","border-top":"0px solid #999"});
		}
	}
	
	//储存看看更多银行的状态
	if(ly.split("/")[ly.split("/").length-1] != "credit.html" && sessionStorage.getItem("is_drop_open") == "true"){
		$("#bank_more_box").css({
			"height":"auto"
		});
		$("#bank_more").find("i").addClass("onup-dowm");
	}
	
	//查看更多银行
	$("#bank_more").click(function(){
		if($(this).find("i").hasClass("onup-dowm")){
			$(this).find("i").removeClass("onup-dowm");
			$("#bank_more_box").css({
				"height":"29px"
			});
			sessionStorage.setItem("is_drop_open","false");
		}else{
			$(this).find("i").addClass("onup-dowm");
			$("#bank_more_box").css({
				"height":"auto"
			});
			sessionStorage.setItem("is_drop_open","true");
		}
	});

})
