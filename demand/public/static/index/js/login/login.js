//更新验证码
$("#verify, .imgCode img, #verify_login").click(function(){


    //取得地址
    var url = $("#verify, .imgCode img, #verify_login").attr("data-src");
    var time = new Date().getTime();

    //重新设置地址
    $("#verify, .imgCode im, #verify_login").attr("src",url.replace("time_data",time));

    //返回结果
    return false;
});

//更新验证码
function get_verify(_this) {
    //取得地址
    var url = $(_this).attr("data-src");
    var time = new Date().getTime();

    //重新设置地址
    $(_this).attr("src",url.replace("time_data",time));

    //返回结果
    return false;
}

//登录
function login(this_id) {
    //登录请求
    $.post($("#"+this_id).attr("action"),$("#"+this_id).serializeArray(),function(data){
        //判断信息是否取得成功
        if(data.status == "success"){
            layer.msg(data.msg);
            setTimeout(function(){  location.href = $("#"+this_id).attr("data-src"); }, 2000);
        }else{
            //提示信息
            layer.msg(data.msg);

            //刷新验证码
            var url = $(".verify").attr("data-src");
            var time = new Date().getTime();
            $(".verify").attr("src",url.replace("time_data",time));

            //返回值
            return false;

        }
    },"json");
}

//登录请求
$("#submit_reg").click(function(){
    //登录请求
    $.post($("#form").attr("action"),$("form").serializeArray(),function(data){
        //判断信息是否取得成功
        if(data.status == "success"){
            layer.msg(data.msg);
            setTimeout(function(){  location.href = $("form").attr("data-src"); }, 2000);

        }else{
            //提示信息
            layer.msg(data.msg);

            //刷新验证码
            // var url = $("#verify").attr("data-src");
            // var time = new Date().getTime();
            // $("#verify").attr("src",url.replace("time_data",time));

            //返回值
            return false;

        }
    },"json");

    //返回值
    return false;
});


//登录请求
$("#form").submit(function(){
    return false;
});

//短信发送
function sms_code(this_id){
	 //ajax 发送验证码
    var  user_phone = $('input[name=user_phone]').val();
    var  user_verification = $('input[name=user_verification]').val();

    //电话号码验证
    var phone=/^1[34578]\d{9}$/;
    if(user_phone == '' || phone.test(user_phone) == false){
        layer.msg('请正确输入电话号码!');
        return;
    }

    if(user_verification == ''){
        layer.msg('请正输入正确的图形验证码!');
        return;
    }
	
    $.post($(this_id).attr("sms_src"),{'user_phone':user_phone, 'user_verification':user_verification},function(data){
    	
        //判断信息是否取得成功
        if(data.status=="success"){
        	 count_down(this_id);

       	}else{
            layer.msg(data.msg);
            //刷新验证码
            var url = $("#verify").attr("data-src");
            var time = new Date().getTime();
            $("#verify").attr("src",url.replace("time_data",time));

            //返回值
            return false;
        }

    },'json');
}

//绑定事件
$("#sms_code").on("click",function(){
	sms_code("#sms_code");
});

//倒计时方法
/*
 @this_id 带#的id
 * */
function count_down(this_id){
	$(this_id).unbind();
	var i=60;
	var t;
	$(this_id).css({
		'border-color' : '#ccc',
	    'color' : '#ccc'
	});
	$(this_id).text(i+"秒后重试");
	t=setInterval(function(){
        i--;
		$(this_id).text(i+"秒后重试");
		if(i==0){
			clearTimeout(t);
			$(this_id).text("发送验证码");
			$(this_id).css({
				'border-color' : '#479ef8',
			    'color' : '#479ef8'
			});
			$(this_id).on("click",function(){
				sms_code(this_id);
			});
		}
    },1000);
}

//注册
$('#reg_user_phone').blur(function () {
    var _url = "'{:Url(index/login/reg_phone_ajax)}'" ;
    //ajax 发送验证码
    var  user_phone = $('input[name=user_phone]').val();

    //电话号码验证
    var phone=/^1[34578]\d{9}$/;
    if(user_phone == '' || phone.test(user_phone) == false){
        return;
    }

    $.post(_url,{'user_phone':user_phone}, function(data){

        //判断信息是否取得成功
        if(data.status=="error"){
            layer.msg(data.msg);
            //返回值
            return false;
        }

    },'json');

});

//意向贷款
function common_submit(this_id) {
    //登录请求
    $.post($(this_id).attr("action"),$(this_id).serializeArray(),function(data){
        //判断信息是否取得成功
        if(data.status == "success"){
            if(data.msg != ''){
                layer.msg(data.msg);
            }

            if(data.data.url){
                location.href = data.data.url;
            }else if(this_id != '#form_suggest') {
                location.href = $(this_id).attr("data-src");
            }else{
                $('#form_suggest textarea[name=content]').val("");
            }
        }else{
            if(data.msg != ''){
                //提示信息
                layer.msg(data.msg);
            }
            if(data.data.url){
                location.href = data.data.url;
            }
            //返回值
            return false;
        }
    },"json");
}

function bid(url) {
    $.post(url,function(data){

        //判断信息是否取得成功
        if(data.status == "success"){
            layer.msg(data.msg);

            tender_from('close')
        }else{
            //提示信息
            layer.msg(data.msg);

            //返回值
            return false;

        }
    },"json");

}

//贷款添加
function common_loan_add(this_id) {
    //登录请求
    $.post($(this_id).attr("action"),$(this_id).serializeArray(),function(data){
        //判断信息是否取得成功
        if(data.status == "success"){
            location.href = $(this_id).attr("data-src")+'?loan_type='+data.data.loan_type+'&apply_id='+data.data.apply_id;
        }else{
            //提示信息
            layer.msg(data.msg);

            //返回值
            return false;

        }
    },"json");
}

//绑定回车事件
$(document).keydown(function(event){ 
	if(event.keyCode==13){ 
		$("#submit").click();
		$("#submit_login").click();
	}
}) ;
$(document).keydown(function(event){ 
	if(event.keyCode==13){ 
		$("#submit").click();
		$("#submit_login").click();
	}
}) ;





















































