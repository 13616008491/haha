<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:89:"/phpstudy/www/demand/public/../project/app/admin/view/allot/demand_distribution_list.html";i:1549957977;s:74:"/phpstudy/www/demand/public/../project/app/admin/view/layout_menu_all.html";i:1549957979;}*/ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta http-equiv="Cache-Control" content="max-age=2592000" />
    <title>需求小助手 · 总后台</title>
    <link href="__STATIC__/favicon.ico" rel="shortcut icon">
    <link href="__STATIC__/admin/css/base.css" rel="stylesheet">
    <link href="__STATIC__/admin/css/index.css" rel="stylesheet" />
    <link href="__STATIC__/admin/css/system.css" rel="stylesheet" />
    <script type="text/javascript" src="__STATIC__/common/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="__STATIC__/common/jquery.validate.js"></script>
    <script type="text/javascript" src="__STATIC__/common/jquery.metadata.js"></script>
    <script type='text/javascript' src="__STATIC__/common/jquery.resizableColumns.js"></script>
    <script type="text/javascript" src="__STATIC__/common/plugins/layer/layer.min.js"></script>
    <script type="text/javascript" src="__STATIC__/admin/js/common.js?v=1.0"></script>
    <script type="text/javascript" src="__STATIC__/common/json2.js"></script>
    <script type="text/javascript">
        var json = JSON.parse('<?php echo \think\Config::get('json'); ?>');
    </script>
</head>

<body>
    <div class="warp tree">
        <div class="head-content">
            <!-- 页面head -->
            <div class="head">
                <div class="user-logo" style="color: #fff;font-size: 30px;width:500px;font-family:Arial,Helvetica,sans-serif;">需求小助手 · 总后台</div>
                <div class="user-info">
                    <p>
                        欢迎[
                        <font color="red"><?php echo get_login_admin_role_name(); ?></font>]<a href="<?php echo Url('admin/admin_info'); ?>"><?php echo get_login_admin_phone(); ?></a>的到来 |
                        <a href="<?php echo Url('system/clear_cache_all'); ?>">清除缓存</a> |
                        <a href="<?php echo Url('allot/demand_notice_list'); ?>">未读消息（<font color="red"><b><?php echo $_notice_count_; ?></b></font>）</a> |
                        <a href="<?php echo Url('login/logout'); ?>">退出</a>
                    </p>
                    <p><span>版本：1.0.0</span><span>最后登录IP：<?php echo get_client_ip(); ?></span></p>
                </div>
            </div>

            <!-- 一级菜单 -->
            <div class="nav-box">
                <ul class="nav" id="_menu_one">
                    <?php if(is_array(\think\Config::get('menu.menu')[1]) || \think\Config::get('menu.menu')[1] instanceof \think\Collection || \think\Config::get('menu.menu')[1] instanceof \think\Paginator): if( count(\think\Config::get('menu.menu')[1])==0 ) : echo "" ;else: foreach(\think\Config::get('menu.menu')[1] as $key=>$menu_one): if(($menu_one['menu_id'] == \think\Config::get('menu.current')[1])): ?>
                    <li class="cur"><a href="<?php echo Url($menu_one['action']); ?>"><span><?php echo $menu_one['name']; ?></span></a></li>
                    <?php else: ?>
                    <li><a href="<?php echo Url($menu_one['action']); ?>"><span><?php echo $menu_one['name']; ?></span></a></li>
                    <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>

            <!-- 内容 -->
            <div class="container">
                <!-- 二级菜单 -->
                <div class="side fl mt10">
                    <!-- 二级菜单 -->
                    <div class="scroll-bar-box">
                        <div class="view-port">
                            <div id="overviewTreeGroup" class="overview">
                                <ul id="_menu_two">
                                    <?php if(is_array(\think\Config::get('menu.menu')[2]) || \think\Config::get('menu.menu')[2] instanceof \think\Collection || \think\Config::get('menu.menu')[2] instanceof \think\Paginator): if( count(\think\Config::get('menu.menu')[2])==0 ) : echo "" ;else: foreach(\think\Config::get('menu.menu')[2] as $key=>$menu_two): if(($menu_two['menu_id'] == \think\Config::get('menu.current')[2])): ?>
                                    <li class="cur"><a href="<?php echo Url($menu_two['action']); ?>"><span><?php echo $menu_two['name']; ?></span></a></li>
                                    <?php else: ?>
                                    <li><a href="<?php echo Url($menu_two['action']); ?>"><span><?php echo $menu_two['name']; ?></span></a></li>
                                    <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 页面主体内容 -->
                <div class="cont mt10">
                    <!-- 收缩条 -->
                    <div id="content_hide" style="position: relative; left:-10px; top: 285px;" ret="1">
                        <div class="effect-btn">
                            <img src="__STATIC__/admin/css/img/btn_icon02.gif" class="left">
                        </div>
                    </div>

                    <!-- 搜索框及工具条 -->
                    <div class="crumbs-box">
                        <div class="crumbs">
                            <!-- 导航条 -->
                            <div class="fl" id="_mini_nav">
                                <a class='blue' href='<?php echo Url(\think\Config::get('menu')["menu"][1][\think\Config::get('menu')["current"][1]]["action"]); ?>'><?php echo \think\Config::get('menu')["menu"][1][\think\Config::get('menu')["current"][1]]["name"]; ?></a>&nbsp;>&nbsp;
                                <a class='blue' href='<?php echo Url(\think\Config::get('menu')["menu"][2][\think\Config::get('menu')["current"][2]]["action"]); ?>'><?php echo \think\Config::get('menu')["menu"][2][\think\Config::get('menu')["current"][2]]["name"]; ?></a>&nbsp;>&nbsp;
                                <a class='blue' href='<?php echo Url(\think\Config::get('menu')["menu"][3][\think\Config::get('menu')["current"][3]]["action"]); ?>'><?php echo \think\Config::get('menu')["menu"][3][\think\Config::get('menu')["current"][3]]["name"]; ?></a>
                            </div>

                            <!-- 返回 -->
                            <span><a class="return" href="javascript:history.go(-1);">返回上一页</a></span>
                        </div>
                    </div>

                    <!-- 编辑区域 -->
                    <div class="lists">
                        <!-- 三级菜单 -->
                        <div class="lists-tab">
                            <strong>
                                <!-- 设置第四级菜单 -->
                                <ul id="_menu_three">
                                    <!-- 设置第四级菜单 -->
                                    <?php if(is_array(\think\Config::get('menu.menu')[3]) || \think\Config::get('menu.menu')[3] instanceof \think\Collection || \think\Config::get('menu.menu')[3] instanceof \think\Paginator): if( count(\think\Config::get('menu.menu')[3])==0 ) : echo "" ;else: foreach(\think\Config::get('menu.menu')[3] as $key=>$menu_three): if(($menu_three['menu_id'] == \think\Config::get('menu.current')[3])): ?>
                                            <li class="current"><a href="<?php echo Url($menu_three['action']); ?>"><?php echo $menu_three['name']; ?></a></li>
                                        <?php else: ?>
                                            <li><a href="<?php echo Url($menu_three['action']); ?>"><?php echo $menu_three['name']; ?></a></li>
                                        <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                </ul>
                            </strong>
                        </div>

                        <!-- Layout加载内容-->
                        <!-- 搜索框及工具条 -->
<div class="c-head-box">
    <form method="get" action="<?php echo Url('demand_distribution_list'); ?>">
    <ul>
        <li class="c-head-search">
            <!-- 搜索条件 -->
            <ul>
                <li><span>需求编号：</span><input type="text" name="demand_id" value="<?php echo $demand_id; ?>"/></li>
                <li><span>需求概要：</span><input type="text" name="demand_describe" value="<?php echo $demand_describe; ?>"/></li>
                <li><span>选择项目：</span><?php echo widget('common/Select/select',array('project_id',array($project_list,"project_id","project_name"),$project_id)); ?></li>
                <li><span>优先级：</span><?php echo widget('common/Select/select',array('priority_level','priority_level',$priority_level)); ?></li>
            </ul>
        </li>
        <li class="c-head-button">
            <!-- 操作按钮 -->
            <div class="fr"><input type="submit" class="but_blue" value="搜索"></div>
        </li>
    </ul>
    </form>
</div>

<!--用户列表-->
<div class="lists-tab-cont">
    <div class="lists-table">
        <table class="pointer" cellpadding="0" cellspacing="0" border="0">
            <thead>
            <tr>
                <th style="width: 2%" class="tabSort-header"><a class="tabSort-header-inner"><p>需求编号</p></a></th>
                <th style="width: 4%" class="tabSort-header"><a class="tabSort-header-inner"><p>项目名称</p></a></th>
                <th style="width: 4%" class="tabSort-header"><a class="tabSort-header-inner"><p>提出人</p></a></th>
                <th style="width: 4%" class="tabSort-header"><a class="tabSort-header-inner"><p>提出人电话</p></a></th>
                <th style="width: 10%" class="tabSort-header"><a class="tabSort-header-inner"><p>需求描述</p></a></th>
                <th style="width: 5%" class="tabSort-header"><a class="tabSort-header-inner"><p>提出时间</p></a></th>
                <th style="width: 2%" class="tabSort-header"><a class="tabSort-header-inner"><p>优先级</p></a></th>
                <th style="width: 10%" class="tabSort-header"><a class="tabSort-header-inner"><p>操作</p></a></th>
            </tr>
            </thead>
            <tbody>
                <?php if(is_array($demand_list) || $demand_list instanceof \think\Collection || $demand_list instanceof \think\Paginator): if( count($demand_list)==0 ) : echo "" ;else: foreach($demand_list as $key=>$info): ?>
                    <tr>
                        <td><?php echo $info['demand_id']; ?></td>
                        <td><?php echo $info['project_name']; ?></td>
                        <td><?php echo $info['real_name']; ?></td>
                        <td><?php echo $info['phone']; ?></td>
                        <td title="<?php echo $info['demand_describe']; ?>"><?php echo $info['demand_describe']; ?></td>
                        <td><?php echo $info['propose_time']; ?></td>
                        <td><?php echo widget('common/Color/color',array('priority_level',$info["priority_level"])); ?></td>
                        <td>
                            <a data-type="info-win" data-src="<?php echo Url('demand_info',array('demand_id'=>$info['demand_id'])); ?>" data-title="详情" data-width="80%" data-height="80%" class="blue">详情</a>
                            <a href="<?php echo Url('demand_auditing',array('demand_id'=>$info['demand_id'])); ?>" class="blue">需求审核</a>
                        </td>
                    </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>

    <!-- 翻页模板 -->
    <div class="page">
        <?php echo \think\Config::get('page_html'); ?>
    </div>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer_gp">
        <p>厦门酷融信息科技有限公司版权所有 © Copyright 2014-2017 闽ICP备18006609号-1</p>
    </div>
</body>
<!-- JS处理 -->
<script language="javascript">
    $(function() {
        //修改列表宽度
        $("div.lists-table table.pointer").width("<?php echo get_screen(); ?>");

        //二级菜单显示异常操作
        $('#content_hide').click(function() {
            //判断二级菜单是否显示
            if ($(this).attr("ret") == 1) {
                $(this).attr("ret", "2");
                $("div.container .side").hide();
                $("div.container .cont").css("width", "98.7%");
                $(this).find("img").attr("src", "__STATIC__/admin/css/img/btn_icon01.gif");
            } else {
                $(this).attr("ret", "1");
                $("div.container .side").show();
                $("div.container .cont").css("width", "83.5%");
                $(this).find("img").attr("src", "__STATIC__/admin/css/img/btn_icon02.gif");
            }
        });

        //修改列表宽度
        $("div.lists-table table").resizableColumns(function() {});

        //数据检查
        $.metadata.setType('attr', 'validate');
        $('form').validate();

        //重写alert
        window.alert = layer.alert;
    });
</script>

</html>