$(function(){
    //字段值模板
    var select_tpl = {
        getTemplate : function(info){
            return '<option value="' + info.key + '">' + info.val + '</option>';
        }
    }

    //单选框联动下拉
    $("input[type='radio']").live("click",function(){
        //取得本下拉框名称
        var parent_name = $(this).attr("node");
        var parent_value = $(this).val();

        //遍历所有select框
        $("select[parent]").each(function(){
            //判断是否和本下拉框联动
            if($(this).attr("parent") == parent_name){
                //取得缓存变量
                var select_cache =$(this).attr("cache");
                var data = eval(select_cache);

                //判断选择项是否有值
                var select_data = data[parent_value];
                if(typeof select_data != "undefined" && select_data != undefined){
                    //初始化数据
                    $(this).empty();
                    $(this).append("<option value=''>请选择...</option>");

                    //循环生产选项
                    for(var key in select_data){
                        $(this).append(select_tpl.getTemplate({
                            key : key,
                            val : select_data[key]
                        }));
                    }
                }else{
                    //初始化数据
                    $(this).empty();
                    $(this).append("<option value=''>请选择...</option>");
                }
            }
        });
    });

    //联动下拉框
    $("select").live("change",function(){
        //取得本下拉框名称
        var parent_name = $(this).attr("node");
        var parent_value = $(this).val();

        //遍历所有select框
        $("select[parent]").each(function(){
            //判断是否和本下拉框联动
            if($(this).attr("parent") == parent_name){
                //取得缓存变量
                var select_cache =$(this).attr("cache");
                //console.log(select_cache);
                var data = eval(select_cache);
                //console.log(data);

                //判断选择项是否有值
                var select_data = data[parent_value];
                if(typeof select_data != "undefined" && select_data != undefined){
                    //初始化数据
                    $(this).empty();
                    $(this).append("<option value=''>请选择...</option>");

                    //循环生产选项
                    for(var key in select_data){
                        $(this).append(select_tpl.getTemplate({
                            key : key,
                            val : select_data[key]
                        }));
                    }
                }else{
                    //初始化数据
                    $(this).empty();
                    $(this).append("<option value=''>请选择...</option>");
                }
            }
        });
    });

    //遍历所有select框
    $("select[parent]").each(function(){
        //取得本下拉框名称
        var parent_name = $(this).attr("parent");
        var parent_value = $("[name='" + parent_name + "']").val();

        //取得缓存变量
        var select_cache =$(this).attr("cache");
        var data = eval(select_cache);

        //判断选择项是否有值
        var select_data = data[parent_value];
        if(typeof select_data != "undefined" && select_data != undefined){
            //初始化数据
            $(this).empty();
            $(this).append("<option value=''>请选择...</option>");

            //循环生产选项
            for(var key in select_data){
                $(this).append(select_tpl.getTemplate({
                    key : key,
                    val : select_data[key]
                }));
            }
        }

        //设置初期值
        var data_default = eval(select_cache + "_default");
        $(this).val(data_default);
    });
});