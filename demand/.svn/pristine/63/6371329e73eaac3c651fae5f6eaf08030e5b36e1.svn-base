$(function(){
    //初期化拖拽
    $("div.img_upload div.img_body ul").sortable();

    //图片模板
    var img_tpl = {
        getTemplate : function(info){
            return '<li class="has">\
                        <input name="' + info.photo_field + '[]" value="' + info.photo_url + '" type="hidden">\
                        <div class="pic">\
                            <div class="preview"><img ondrag="return false" src="' + info.photo_url + '"></div>\
                            <div class="default"><img src=""/><div class="mask"></div></div>\
                            <div class="operate"><i class="del">删除</i></div>\
                        </div>\
                    </li>';
        }
    };

    //捆绑数据源
    var file_key;
    var input_file = $("div.img_upload input[name='file']");
    input_file.bind('fileuploadsubmit', function (e, data) {
        var filename=data.files[0].name;
        //获取文件名的后缀名（文件格式）
        var suffix = filename.substring( filename.lastIndexOf('.')+1 ).toLowerCase();
        if(suffix != 'jpg' && suffix != 'jpeg' && suffix != 'png' && suffix != 'gif'){
            //提示信息
            $(this).parents("div.img_upload").find("div.img_head div.msg").text("只支持图片格式：jpg、jpeg、png、gif");
            return false;
        }
        var size = data.files[0].size;
        if(size > ImgInfo.MaxSize*1024){
            //提示信息
            $(this).parents("div.img_upload").find("div.img_head div.msg").text("可上传文件最大"+ImgInfo.MaxSize/1024+"M！");
            return false;
        }
        var timestamp = new Date().getTime();
        var num = Math.ceil(Math.random()*1000);
        var key = ImgInfo.path + '/' + timestamp + num + '.' + suffix;
        file_key = key;
        data.formData = { OSSAccessKeyId:ImgInfo.OSSAccessKeyId,policy:ImgInfo.policy,Signature:ImgInfo.Signature,key:key,success_action_status:201 };
    });

    //图片上传控件
    input_file.fileupload({
        apc:true,
        done: function (e, data) {
            //判断返回值是否为空
            if(data.textStatus != "success"){
                //提示信息
                $(this).parents("div.img_upload").find("div.img_head div.msg").text("上传图片请求失败！");
                return false;
            }

            //判断地址是否正常
            var url = "";
            if(data.formData.key == undefined){
                url = ImgInfo.url + '/' + file_key;
            }else{
                url = ImgInfo.url + '/' + data.formData.key;
            }

            //取得字段名称
            var fields = $.trim($(this).attr("data-field"));
            if(fields == ""){
                fields = "upload";
            }

            //判断是否在默认的li上
            if($(this).parents("div.img_upload").find("div.img_body ul li.has").size() == 0 || $(this).is("[multiple]") == false){
                //增加上传信息
                $(this).parents("div.img_upload").find("div.img_body ul li input").val(url);
                $(this).parents("div.img_upload").find("div.img_body ul li").addClass("has");
                $(this).parents("div.img_upload").find("div.img_body div.preview img").attr("src",url);
                $(this).parents("div.img_upload").find("div.img_body ul li div.operate").show();
            }else{
                //增加图片
                $(this).parents("div.img_upload").find("div.img_body ul").append(img_tpl.getTemplate({photo_field:fields,photo_url:url}));
                $(this).parents("div.img_upload").find("div.img_body ul").sortable();
            }
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $(this).parents("div.img_upload").find("div.img_head div.msg").text('完成'+progress+'%');
        }
    });

    //移除图片
    $("div.img_body li div.operate i.del").live('click',function(){
        //判断图片张数
        if($(this).parents("div.img_upload").find("div.img_body ul li").size() <= 1){
            $(this).parents("div.img_upload").find("div.img_body ul li input").val("");
            $(this).parents("div.img_upload").find("div.img_body ul li").removeClass("has");
            $(this).parents("div.img_upload").find("div.img_body div.preview img").attr("src","");
            $(this).parents("div.img_upload").find("div.img_body ul li div.operate").hide();
        }else{
            $(this).parents("li").remove();
        }
    });
});