/*首页意向贷款弹窗*/
function loan_intention(state){
	if(state=="show"){
		$(".loan-intention-mask").show();
		$(".loan-intention").show();
	}
	if(state=="hide"){
		$(".loan-intention-mask").hide();
		$(".loan-intention").hide();
	}
}


/*返回顶部*/
function return_top(){
	$('html, body').animate({
        scrollTop:0
    }, 500);
}

//贷款申请分类切换
function navtjs(thisObj,Num){
   if(thisObj.className == "active")return;
   var tabObj = thisObj.parentNode.parentNode.id;
   var tabList = document.getElementById(tabObj).getElementsByTagName("li");
   for(i=0; i <tabList.length; i++){
		if (i == Num){
		      thisObj.className = "navli-active"; 
		      document.getElementById(tabObj+"-c"+i).style.display = "block";
	  	}else{
		   tabList[i].className = "navli"; 
		   document.getElementById(tabObj+"-c"+i).style.display = "none";
	  	}
	} 
}
//首页tab
function homeTab(thisObj){
	$(thisObj).addClass("aft").siblings().removeClass("aft");
	$(".funBox .funBoxList").eq($(thisObj).index()).show().siblings(".funBoxList").hide()
}
/*车贷详情-搜索问题(聚焦离焦事件)
 * 
 */
function focuSwitch(type){
	var textBox=document.getElementById('YfocuText');
	if(textBox.value==""){
		if(type!='0'){
			document.getElementById("Yfocusing").style.display="block";
			document.getElementById("Nfocusing").style.display="none";
			textBox.focus();
		}else{
			document.getElementById("Yfocusing").style.display="none";
			document.getElementById("Nfocusing").style.display="block";
			textBox.value='';
		}
	}
}


//信用卡首页自动轮播
function autoBanner(boxId,countId){
	var banCont=0;
	for (var i=0;i<$("#"+boxId+" li").length;i++) {
		if($("#"+boxId+" li").eq(i).css("right")=="0px")banCont=i;
	}
	banCont++;
	if(banCont==$("#"+boxId+" li").length)banCont=0;
	$("#"+boxId+" li").eq(banCont).css("right","0").siblings("li").css("right","100%");
	$("#"+countId+" a").eq(banCont).addClass("aft").siblings("a").removeClass("aft");
}
//信用卡首页轮播按钮事件
function clickBanner(boxId,countId,thisObj){
	var bID=boxId;
	var cID=countId;
	var banThis=$(thisObj).index();
	$("#"+boxId+" li").eq(banThis).css("right","0").siblings("li").css("right","100%");
	$("#"+countId+" a").eq(banThis).addClass("aft").siblings("a").removeClass("aft");
	clearInterval(banSetTime);
	banSetTime=setInterval("autoBanner('"+bID+"','"+cID+"')",4000);
}

 /**
 * @功能：登入弹窗
 * @param state 状态
 * @开发者：zj
 * 2018-3-22
 */
function loginBox(state){

	$("input[name=user_phone]").val("");
	$("input[name=user_pwd]").val("");
	if(state=="close"){
		$("#_loginForm").hide();
		$(".loginFormCover").hide();
		//移除回车事件
		$(document).unbind("keydown"); 
	}
	if(state=="open"){
		$("#_loginForm").show();
		$(".loginFormCover").show();
		//绑定回车事件
		$(document).keydown(function(event){ 
			if(event.keyCode==13){ 
				$("#login_btn").click(); 
			}
		}) 
	}
}
/*
 登录弹框tab
 * */
function loginTab(thisObj,thisType, url){
	console.log(url);
	$(thisObj).addClass("aft").siblings().removeClass("aft");
	if(thisType=="mm"){
		$("#_loginMM_tc").show();
		$("#_loginSJ_tc").hide();
        $('#login_form').attr("action",url);
        $('#login_form input[type="text"]').val("");
	}
	if(thisType=="sj"){
		$("#_loginMM_tc").hide();
		$("#_loginSJ_tc").show();
        $('#login_form').attr("action",url);
        $('#login_form input[type="text"]').val("");
	}
}
/*
 登录（主）类型切换
 * */
function loginType(thisObj,type, url){
	$(thisObj).addClass("aft").siblings("span").removeClass("aft");
	if(type==0){
		$("#_loginMM").show();
		$("#_loginTX").show();
		$("#_loginSJ").hide();
        $('#form').attr("action",url);
		$(thisObj).siblings("i").css("left","118px");
	}
	if(type==1){
		$("#_loginSJ").show();
		$("#_loginMM").hide();
		$('#form').attr("action",url);
		$(thisObj).siblings("i").css("left","239px");
	}
}

/**
 * 登录操作 自动退出
 */
var last_time,
	loginOut;
function login_out() {

	var data = new Date(), //实例化时间
		max_tim = 30;//seconds 30分钟操作自动退出登录


	var url = $('#login_out').attr('href');
	//赋值
    var current_time = data.getTime();

    //时间的判断
    if((parseInt(current_time)- parseInt(last_time)) > max_tim*60*1000){
         clearInterval(loginOut);
         window.location.href = url;
    }


}

/**
 * @功能：产品详情tab切换
 * @param papr 参数对象
 * 		//tab菜单id
 *		menu_id:'',
 *		
 *		//tab菜单筛选器
 *		menu_filter:'a',
 *		
 *		//锚点属性（不可使用标签自带属性*必须为自定义属性）
 *		menu_attr:'carLoanTab',
 *		
 *		//滚动距离
 *		top_dis:'',
 *		
 *		//实例化的名称
 *		this_fun:''
 * @开发者：zj
 * @return string
 * 2018-3-29
 */
function HoverMenu(papr){
	//参数说明
	var obj={
		
		//tab菜单id
		menu_id:'',
		
		//tab菜单筛选器
		menu_filter:'',
		
		//锚点属性（不可使用标签自带属性*必须为自定义属性）
		menu_attr:'',
		
		//滚动距离
		top_dis:'',
		
		//实例化的名称
		this_fun:''
	}
	
	this.obj=papr;
	
	var temp = $("#"+papr.menu_id);
	
	for(var i=0;i<temp.find(papr.menu_filter).length;i++){
  		temp.find(papr.menu_filter).eq(i).attr("onclick",papr.this_fun+".carLoanTab(this,"+i+",120)");
  	}
	
	//监听滚动事件
	$(window).scroll(function(){
	  if($(this).scrollTop()>papr.top_dis){
	  	temp.addClass("carLoanTab-hover");
	  	for(var i=0;i<temp.find(papr.menu_filter).length;i++){
	  		temp.find(papr.menu_filter).eq(i).attr("onclick",papr.this_fun+".carLoanTab(this,"+i+",64)");
	  	}
	  }else{
	  	temp.removeClass("carLoanTab-hover");
	  	for(var i=0;i<temp.find(papr.menu_filter).length;i++){
	  		temp.find(papr.menu_filter).eq(i).attr("onclick",papr.this_fun+".carLoanTab(this,"+i+",120)");
	  	}
	  }
	});
	
	//主体函数
	this.carLoanTab = function(thisObj,Num,top_dis){
		if(thisObj.className == "aft")return;
		var tabObj=thisObj.parentNode.id;
		var tabList = $("#"+tabObj).find("a");
		for(var i=0;i<tabList.length;i++){
			if (i == Num){
		      	tabList.eq(i).addClass("aft"); 
		        $('html, body').animate({
			        scrollTop:($("["+this.obj.menu_attr+"="+Num+"]").offset().top)-top_dis
			    }, 500);
		  	}else{
				tabList.eq(i).removeClass("aft");
		  	}
		}
	}
}


$(function(){
	//$("input[type=password]").click();

	var user_id = $('#login_out').attr('token_id');
	//判断是否登录
	if(user_id>100) {
        $(document).on('keydown mousemove mousedown click scroll', function () {
            //更新时间
            last_time = new Date().getTime();
        })
        //登录无操作判断
      loginOut =   window.setInterval(login_out, 1000*60);

	}

	//输入框聚焦事件
	$(".inputTextBD input[type='text']").focus(function(){
		$(this).parents(".inputTextBD").css("border-color","#479ef8");
	});
	$(".inputTextBD input[type='text']").blur(function(){
		$(this).parents(".inputTextBD").css("border-color","#eee");
	});

	//下拉菜单op
	//下拉组件初始化及功能实现

	$(".inputTextBD ul").css({
		"width":$(".inputTextBD").css("width"),
		"top": $(".inputTextBD").css("height")
	});
	
	
	//只有单位没有下拉的时候样式控制
	var zhh_inputTextBD = $(".inputTextBD");
	for(var kk=0;kk<zhh_inputTextBD.length-6;kk++){
		if(zhh_inputTextBD.eq(kk).find("i").length==0 &&zhh_inputTextBD.eq(kk).find("b").length==1){
			zhh_inputTextBD.eq(kk).find("b").css("right","10px");
		}
	}

	//展示下拉
	$(".inputTextBD[is_select='y'] i,.inputTextBD[is_select='y'] input[readonly='readonly'],.inputTextBD[is_this_select='y']").click(function(){
		$(".inputTextBD ul").css({
			"width":$(this).parents(".inputTextBD").css("width"),
			"top": $(this).parents(".inputTextBD").css("height")
		});

		var start = $(this).parents(".inputTextBD").find("ul").css("display");
		$(".inputTextBD").find("ul").hide();//始终展示一个下拉
		(start=="block")?$(this).parents(".inputTextBD").find("ul").show():$(this).parents(".inputTextBD").find("ul").hide();
		($(this).parents(".inputTextBD").find("ul").css("display")=="block")?$(this).parents(".inputTextBD").find("ul").hide():$(this).parents(".inputTextBD").find("ul").show();
	});
	//选择下拉后赋值
	$(".inputTextBD ul li").click(function(){
		$(this).parents(".inputTextBD").find("input").val($(this).attr("val"));
		$(".inputTextBD ul").hide();
	});
	//特殊值要求下的赋值
	$(".inputTextBD[is_key='y'] ul li").click(function(){
		$(this).parents(".inputTextBD").find("input[is_key='y']").val($(this).attr("key"));
		$(this).parents(".inputTextBD").find("input").trigger("input");
	});
	
	//点击下拉菜单之外的元素隐藏下拉菜单
	$(document).bind("click",function(e){
		var target = $(e.target);
		if(target.closest(".inputTextBD").length == 0){//点击class为inputTextBD之外的地方触发
			$(".inputTextBD ul").hide();
		}
	})
	//下拉菜单ed
	
	
	//选择地址
	var cont=0;
	$("#_address").mouseout(function(){
		$(this).addClass("address-hide");
		$(this).find("i").css({
			"border-bottom":"0px solid #eee",
			"border-top":"6px solid #eee"
		});
	});
	
	//静态赋值
	$("#province_list a,#area_hot a").click(function(){
		$("#_address").addClass("address-hide");
		$("#_address b[aft='aft']").text($(this).text());
	});
	
	//省份导航
	$("#province_abb span").click(function(){
		$(this).addClass("aft").siblings().removeClass("aft");
		cont=$("li[province_abb="+$(this).text()+"]").offset().top-243
		$('#province_list').scrollTop(cont+$('#province_list').scrollTop());
	})
	
	//选择地址
	/*$("#_address span").click(function(){
		$(this).parents("dd").find("b").text($(this).text());
	});*/
	
	//监听滚动事件显示返回顶部按钮
	var user_info_btn = $(".personal-info-page .chhBtn").offset();
	$(window).scroll(function(){
	  ($(window).scrollTop()>1000)?$(".return-top").css({"visibility":"initial","opacity":"1"}):$(".return-top").css({"visibility":"hidden","opacity":"0"});
	  //关于我们导航固定

      if(($(window).scrollTop()>160)){
      	if(($(window).scrollTop())>$(document).height() - $(window).height() - $('.floor').height() + ($(window).height() - $('#about_page_l').height()-40)){
            $("#about_page_l").css({"position":"absolute","top":"auto","bottom":"0"});
		}else{
            $("#about_page_l").css({"position":"fixed","top":"0","bottom":"auto"});
		}
      }else{
	  	$("#about_page_l").css({"position":"relative","top":"0","bottom":"auto"});
      }
	//   ($(window).scrollTop()>15100)?$("#about_page_l").css({"position":"fixed","top":"0"}):$("#about_page_l").css({"position":"relative","top":"0"});
	  /*if($(window).scrollTop()>user_info_btn.top){
	  	$(".personal-info-page .chhBtn").css({
	  		"position":"fixed",
	  		"left":user_info_btn.left
	  	})
	  }else{
	  		$(".personal-info-page .chhBtn").css({
	  		"position":"absolute",
	  		"right":"30px",
	  		"left":"auto"
	  	})
	  }*/
	});
	
	//头部手机应用
	// $("#_app_down").mouseover(function(){
		
	// 	var off = $(this).offset();
		
	// 	$("body").append('<div class="nav-app-down">'+
	// 					'	<span>扫一扫，普融公众号</span>'+
	// 					'	<div class="imgCenterBox">'+
	// 					'		<img src="/static/index/images/app_down.png" />'+
	// 					'	</div>'+
	// 					'</div>');
		
	// 	$(".nav-app-down").css({
	// 		top : off.top+25+"px",
	// 		left : off.left-40+"px"
	// 	});
		
	// 	$(this).css({
	// 		color : '#2692E9'
	// 	});
	// });
	


	// //头部悬浮关闭
	// $("#_app_down").mouseout(function(){
	// 	$(".nav-app-down").remove();
	// 	$(this).css({
	// 		color : '#666'
	// 	});
	// });


	//title美化效果
    var myModal = new jBox('Mouse', {
						  attach: '.toolsign',
						  trigger: 'touchclick',
						  width: 300,
						  position: {x: 'right', y: 'bottom'}
						});
	$(".toolsign").mouseover(function(){
    	myModal.setContent($(this).text());
    	myModal.open();
    });
	$(".toolsign").mouseout(function(){
    	myModal.close();
    });
	
	
	
}); 



















































