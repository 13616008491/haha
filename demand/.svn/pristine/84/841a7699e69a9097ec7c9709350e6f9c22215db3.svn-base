/**
 * Created by Administrator on 14-11-17.
 */

//树形类型显示问题
function jq_show(id,isshow) {
    var obj = $("div.lists-tab-cont table tr[data-parent='"+id+"']");
    if (obj.length>0){
        //循环检测是否显示
        obj.each(function(i) {
            jq_show($(this).attr('field-id'), isshow);
        });

        //是否隐藏
        if (isshow=='hide'){
            obj.hide();
        }else{
            obj.show();
        }
    }
}

//树形类型显示问题
$("div.lists-tab-cont table .minus_sign").each(function(i){
    $(this).toggle(function(){
        jq_show($(this).attr('field-id'), 'hide');
        $(this).attr("src", "/Public/admin/img/open.gif");
    },function(){
        jq_show($(this).attr('field-id'), 'show');
        $(this).attr("src", "/Public/admin/img/reduce.png");
    });
});
