//更新验证码
$("#verify,#verify img").click(function(){
    //取得地址
    var url = $("#verify img").attr("data-src");
    var time = new Date().getTime();

    //重新设置地址
    $("#verify img").attr("src",url.replace("time_data",time));

    //返回结果
    return false;
});

//登录请求
$("#submit").click(function(){
    //登录请求
    $.post($("#form").attr("action"),$("form").serializeArray(),function(data){
        //判断信息是否取得成功
        if(data.status == "success"){
            location.href = $("form").attr("data-src");
        }else{
            //提示信息
            layer.msg(data.info);

            //刷新验证码
            var url = $("#verify img").attr("data-src");
            var time = new Date().getTime();
            $("#verify img").attr("src",url.replace("time_data",time));

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























































