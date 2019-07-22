$(function(){
    //初期值
    var img_open = "/static/admin/img/open.gif";
    var img_reduce = "/static/admin/img/reduce.png";
    var data_status;

    //数组展开、收缩
    $("#access_tree_table tbody tr img").live("click",function(){
        //取得行信息
        var tr = $(this).parents("tr[data-node]");
        var tr_node = tr.attr('data-node');
        var tr_status = tr.attr('data-status');

        //循环查找子节点
        $("#access_tree_table tbody tr").each(function(){
            //取得当前节点id
            var tr_parent = $(this).attr('data-parent');

            //查找子节点
            if(tr_parent.indexOf(tr_node) == 0){
                //判断当前状态
                if(tr_status == "close"){
                    $(this).hide()
                    tr.attr('data-status','open');
                    tr.find("img").attr("src",img_open);
                }else{
                    $(this).show()
                    tr.attr('data-status','close');
                    tr.find("img").attr("src",img_reduce);
                }
            }
        });
    });

    //递归调用检查上层节点信息
    var parent_status_fun = function(data_parent,data_status){
        //判断是否存在上层节点
        console.log(data_parent);
        if(data_parent == undefined) return false;

        //循环查找父节点
        $("#access_tree_table tbody tr[data-node='"+data_parent+"']").each(function(){
            //判断当前状态
            if(data_status == "checked"){
                $(this).find("input[type='checkbox']").attr("checked","checked");
            }else{
                //取得下级节点ID
                var data_parent = $(this).attr('data-node');

                //循环查找子节点
                var count = $("#access_tree_table tbody tr[data-parent='"+data_parent+"'] input:checked").size();
                if(count == 0){
                    $(this).find("input[type='checkbox']").removeAttr("checked");
                }
            }

            //递归调用上层
            data_status = $(this).find("input[type='checkbox']").attr('checked');
            parent_status_fun($(this).attr('data-parent'),data_status);
        });
    };

    //菜单联动选中
    $("#access_tree_table tbody tr input[type='checkbox']").live("click",function(){
        //判断点击的是否为默认节点
        if($(this).attr("data-default") != undefined){
            //判断是否为选择
            var tr_status = $(this).attr('checked');
            if(tr_status == undefined){
                alert("菜单页面默认权限节点不能单独设置，需设置父级菜单！");
                return false;
            }
        }else{
            var parent_default = $(this).parents("tr").attr("data-node");
            $("#access_tree_table tbody tr[data-parent='"+parent_default+"']").find("input[data-default]").attr("checked","checked");
        }

        //取得行信息
        var tr = $(this).parents("tr[data-node]");
        var data_node = tr.attr('data-node');
        data_status = tr.find("input[type='checkbox']").attr('checked');

        //循环查找子节点
        $("#access_tree_table tbody tr").each(function(){
            //取得当前节点id
            var data_parent = $(this).attr('data-parent');
            var data_node_check = data_node + "-";
            //查找子节点
            if(data_parent.indexOf(data_node_check) == 0 || data_parent == data_node){
                //判断当前状态
                if(data_status == "checked"){
                    $(this).find("input[type='checkbox']").attr("checked","checked");
                }else{
                    $(this).find("input[type='checkbox']").removeAttr("checked");
                }
            }
        });

        //递归判断上层节点
        var data_parent = tr.attr('data-parent');
        if(data_parent != undefined){
            parent_status_fun(data_parent,data_status);
        }
    });

    //选择菜单列表
    $("div.crumbs select[name='menu_id']").change(function(){
        //取得当前节点id
        var tr_selected = $(this).val();

        //展示所有菜单
        $("#access_tree_table tbody tr").show();

        //判断是否搜索菜单
        if(tr_selected != ""){
            //展示所有菜单
            $("#access_tree_table tbody tr").show();

            //展示选中菜单
            $("#access_tree_table tbody tr").each(function(){
                //取得当前节点id
                var tr_node = $(this).attr('data-node');
                var tr_parent = $(this).attr('data-parent');

                //隐藏节点
                if(tr_parent == "0"){
                    if(tr_node.indexOf("0-" + tr_selected) != 0){
                        $(this).hide();
                    }
                }else{
                    if(tr_parent.indexOf("0-" + tr_selected) != 0){
                        $(this).hide();
                    }
                }
            });
        }
    });
});