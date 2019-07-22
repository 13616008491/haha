$(function(){
    //字段值模板
    var select_tpl = {
        getTemplate : function(info){
            return '<option value="' + info.key + '">' + info.val + '</option>';
        }
    }

    //搜索值
    $("input.__select_search_").live("keyup",function(){
        //设置新的信息
        var cache = $(this).attr("cache");

        //清除数据
        $(this).nextAll("select").empty();

        //取得缓存数据
        var select_cache = $(this).attr("cache");
        var select_data = eval(select_cache);

        //循环生产选项
        for(var key in select_data){
            //判断搜索是否满足
            var name = select_data[key];
            if(name.indexOf($(this).val()) >= 0){
                $(this).siblings("select").append(select_tpl.getTemplate({
                    key : key,
                    val : select_data[key]
                }));
            }
        }
    });
});