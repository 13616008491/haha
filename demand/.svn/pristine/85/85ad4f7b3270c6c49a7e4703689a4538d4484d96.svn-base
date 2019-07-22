$(function(){
    //数据常理
    var value = null;

    //鼠标移动到输入框
    $("input._input_update").live("focus",function(){
        //设置样式
        $(this).css("border","1px solid #ddd");

        //取得值
        value = $(this).val();
    });

    //鼠标移动出输入框
    $("input._input_update").live("blur",function(){
        //设置样式
        $(this).css("border","1px solid #ddd");

        //设置历史值
        if(value != null){
            $(this).val(value);
        }
    });

    //输入框失去焦点
    $("input._input_update").live("keyup",function(e){
        //判断按下的键
        if(e.keyCode == 13){
            //取得参数信息
            var obj = $(this);
            var url = obj.attr("url");
            var item = obj.attr("item");
            var val = obj.val();

            //设置历史值
            value = null;
        }else {
            return false;
        }

        //保存数据
        $.post(url,{item:item,val:val},function(data){
            //判断数据是否异常
            if(data.code == "OK"){
                obj.css("border","0");
                location.reload();
                layer.msg("编辑成功，因为存在缓存可能为即时更新！");
            }else{
                obj.css("border","1px solid red");
                location.reload();
                layer.msg("编辑失败，错误：" + data.msg);
            }
        },"json");
    });
});