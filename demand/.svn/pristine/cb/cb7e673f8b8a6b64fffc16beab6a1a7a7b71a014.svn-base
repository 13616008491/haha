//用户提交问题
$("#question_submit").click(function(){
    //提交请求
    $.post($("#form_question").attr("action"),$("form").serializeArray(),function(data){
        //判断信息是否取得成功
        if(data.status == "success"){
            window.history.go(-1);
        }else{
            //提示信息
            layer.msg(data.msg);

            //刷新验证码
            var url = $("#verify").attr("data-src");
            var time = new Date().getTime();
            $("#verify").attr("src",url.replace("time_data",time));

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

$("#qe_textarea").focus(function(){
    console.log("111");
    $(this).css("border-color","#479ef8");
});
$("#qe_textarea").blur(function(){
    $(this).css("border-color","#eee");
});