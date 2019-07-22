//用户中心短信发送
function user_sms_code(this_id){
    //ajax 发送验证码

    $.post($(this_id).attr("sms_src"),{},function(data){

        //判断信息是否取得成功
        if(data.status=="success"){
            count_down(this_id);
        }else{
            layer.msg(data.msg);
            //返回值
            return false;
        }

    },'json')
}

//绑定事件
$("#sms_code").on("click",function(){
    user_sms_code("#sms_code");
});
