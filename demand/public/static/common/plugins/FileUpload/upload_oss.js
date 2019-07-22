$(function(){
    //捆绑数据源
    var file_key;
    var input_file = $("div.file_upload input[name='file']");
    input_file.bind('fileuploadsubmit', function (e, data) {
        var filename=data.files[0].name;
        //获取文件名的后缀名（文件格式）
        var suffix = filename.substring( filename.lastIndexOf('.')+1 ).toLowerCase();
        // if(suffix != 'jpg' && suffix != 'jpeg' && suffix != 'png' && suffix != 'gif'){
        //     //提示信息
        //     $(this).parents("div.img_upload").find("div.img_head div.msg").text("只支持图片格式：jpg、jpeg、png、gif");
        //     return false;
        // }
        var size = data.files[0].size;
        if(size > FileInfo.MaxSize*1024){
            //提示信息
            $(this).parents("div.file_upload").find("div.file_head label").text("可上传文件最大"+FileInfo.MaxSize/1024+"M！");
            return false;
        }
        var timestamp = new Date().getTime();
        var num = Math.ceil(Math.random()*1000);
        var key = FileInfo.path + '/' + timestamp + num + '.' + suffix;
        file_key = key;
        data.formData = { OSSAccessKeyId:FileInfo.OSSAccessKeyId,policy:FileInfo.policy,Signature:FileInfo.Signature,key:key,success_action_status:201 };
    });

    //图片上传控件
    input_file.fileupload({
        apc:true,
        done: function (e, data) {
            //判断返回值是否为空
            if(data.textStatus != "success"){
                //提示信息
                $(this).parents("div.file_upload").find("div.file_head div.msg").text("上传图片请求失败！");
                return false;
            }

            //判断地址是否正常
            var url = "";
            if(data.formData.key == undefined){
                url = FileInfo.url + '/' + file_key;
            }else{
                url = FileInfo.url + '/' + data.formData.key;
            }

            $(this).next("input.hidden").val(url);

            var view_btn = $(this).parent().next();
            if(view_btn.hasClass('error')){
                view_btn.before("<a style='margin:0 8px;' class='view-file blue' target='_blank' href='"+url+"' >查看文件</a>")
            }else{
                view_btn.prop('href',url);
            }

        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $(this).parents("div.file_upload").find("div.file_head label").text('完成'+progress+'%');
        }
    });
});