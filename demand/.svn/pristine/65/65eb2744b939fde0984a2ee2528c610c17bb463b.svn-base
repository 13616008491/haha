$(function(){
    //字段值模板
    var select_tpl = {
        getTemplate : function(info){
            return '<option value="' + info.key + '">' + info.val + '</option>';
        }
    }

    //多级联动下拉框
    $("select.__select_group_").live("change",function(){
        //判断选中下拉框的值
        if($.trim($(this).val()) == ""){
            $(this).next("select").remove();
            return false;
        }else{
            var val = $(this).val();
        }

        //取得控件属性
        var select_name =$(this).attr("name");
        var select_class =$(this).attr("class");
        var select_style =$(this).attr("style");
        var select_cache =$(this).attr("cache");
        var select_validate =$(this).attr("validate");
        var select_disabled =$(this).attr("disabled");

        //取得缓存变量名字
        var data = eval(select_cache);

        //查找当前下拉选项的后续选项
        $(this).nextAll("select.__select_group_").remove();

        //根据当前选择项的值生产后续下拉,判断是否有下拉框
        var select_data = data[val];
        if(typeof select_data != "undefined" && select_data != undefined){

            //添加下拉框
            $(this).after("<select></select>");
            $(this).next("select").attr("name",select_name);
            $(this).next("select").attr("class",select_class);
            $(this).next("select").attr("style",select_style);
            $(this).next("select").attr("cache",select_cache);
            $(this).next("select").attr("validate",select_validate);
            $(this).next("select").attr("disabled",select_disabled);

            //增加默认空选项
            $(this).next("select.__select_group_").append("<option value=''>请选择...</option>");

            //循环生产选项
            for(var key in select_data){
                $(this).next("select.__select_group_").append(select_tpl.getTemplate({
                    key : key,
                    val : select_data[key]
                }));
            }
        }

        //判断是否需要初期化
        select_row = $("select.__select_group_").size();
        if(select_default != undefined && select_default[select_row-1] != undefined){
            $(this).next("select.__select_group_").val(select_default[select_row-1]);
            $(this).next("select.__select_group_").change();
        }
    });

    //赋初期值
    var cache_name = $("select.__select_group_").attr("cache");
    if(cache_name != undefined){
        var select_row = $("select.__select_group_").size();
        var select_default = eval(cache_name+"_default");
        if(select_default[select_row-1] != undefined){
            $("select.__select_group_").val(select_default[select_row-1]);
            $("select.__select_group_").change();
        }
    }
});