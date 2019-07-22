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
    }

    //图片上传控件
    $("div.img_upload input[name='_upload_']").fileupload({
        apc:true,
        done: function (e, data) {
            //判断返回值是否为空
            if(data.result == "" || data.result.info != undefined){
                //提示信息
                $(this).parents("div.img_upload").find("div.img_head div.msg").text("上传图片请求失败！");
                return false;
            }

            //判断上传是否完成
            var result = $.parseJSON(data.result);
            if (result['code'] == '0') {
                //取得图片地址
                var url = result['url'];

                //取得字段名称
                var field = $.trim($(this).attr("data-field"));
                if(field == ""){
                    var field = "upload";
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
                    $(this).parents("div.img_upload").find("div.img_body ul").append(img_tpl.getTemplate({photo_field:field,photo_url:url}));
                    $(this).parents("div.img_upload").find("div.img_body ul").sortable();
                }
            } else {
                //上传错误提示
                $(this).parents("div.img_upload").find("div.img_head div.msg").text(result['msg']);
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