$(function(){
    //图片上传控件
    $("div.file_upload input[name='_upload_']").fileupload({
        apc:true,
        done: function (e, data) {
            //判断返回值是否为空
            if(data.result == "" || data.result.info != undefined){
                //提示信息
                $("div.file_upload div.file_head div.msg").text("上传图片请求失败！");
                return false;
            }

            //判断上传是否完成
            var result = $.parseJSON(data.result);
            if (result.state == 'success') {
                $(this).next("input.hidden").val(result.id);
            } else {
                //上传错误提示
                $("div.file_upload div.file_head label").text(result['note']);
            }
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $("div.file_upload div.file_head label").text('已经完成'+progress+'%');
        }
    });
});