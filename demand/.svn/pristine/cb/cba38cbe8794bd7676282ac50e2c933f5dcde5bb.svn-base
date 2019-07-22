//初期化变量
var info_win = null;

//修改样式
window.alert = layer.alert;

//设置列表行颜色
//$("div.lists-tab-cont table tbody").find("tr:odd").addClass("tra");

//更多条件
/*$(".c-head-button .down").live("click",function(){
    //判断是收起还是展开
    if($.trim($(this).val()) == "展开"){
        $(".c-head-search ul").css("max-height","100%");
        $(this).val("收缩");
    }else{
        $(".c-head-search ul").css("max-height","95px");
        $(this).val("展开");
    }
});*/

//打开信息页面
$("[data-type='info-win']").live("click",function(){
    //取得地址
    var href = $(this).attr('data-src');
    var title = $(this).attr('data-title');
    var width = $(this).attr('data-width');
    var height = $(this).attr('data-height');

    //判断是否设置宽度
    if(width == undefined){
        width = "80%";
    }

    //判断是否设置高度
    if(height == undefined){
        height = "90%";
    }

    //打开页面
    info_win = layer.open({type: 2,title:title,shade: 0.8,area: [width,height],content: href,cancel: function(index){
        //关闭画面
        layer.close(info_win);
        //刷新画面
        //location.reload();
    }});

    //返回值
    return false;
});

//列表排序
$("tr a.tabSort-header-inner").live("click",function(){
    //判断是否存在搜索
    if($(this).find("span").attr("sort") == undefined){
        return false;
    }

    //初期化
    var href = window.location.href;
    var protocol = window.location.protocol;
    var host = window.location.host;
    var re =new RegExp("^" + protocol + "\/\/" + host,"gim");
    var exp = href.match(re);

    //取得相对地址
    href = href.replace(exp[0],'');
    href = href.replace(/\/sort\/.*\.html/,'.html');

    //计算地址
    var span_sort = $(this).find("span").attr("sort");
    var span_class = $(this).find("span").attr("class");

    //设置提交地址
    if($.trim(span_class) == ""){
        //设置地址
        href = href.replace(/\.html/,"/sort/" + span_sort + "-up.html");

        //设置地址
        $(this).attr("href",href);
    }else{
        //设置地址
        href = href.replace(/\.html/,"/sort/" + span_sort + "-" + span_class + ".html");

        //设置地址
        $(this).attr("href",href);
    }

    //提交
    return true;
});



