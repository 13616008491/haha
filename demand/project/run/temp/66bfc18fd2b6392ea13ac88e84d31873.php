<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:70:"/phpstudy/www/demand/public/../project/app/admin/view/login/login.html";i:1549957979;}*/ ?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>需求小助手 · 总后台</title>
    <link href="__STATIC__/admin/css/base.css" type="text/css" rel="stylesheet" />
    <link href="__STATIC__/admin/login/css/layout.css" rel="stylesheet" type="text/css" />
    <link href="__STATIC__/admin/login/css/login.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__STATIC__/common/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="__STATIC__/common/plugins/layer/layer.min.js"></script>
</head>

<body>
    <div class="body_gys clearfix">
        <div class="form" style="padding-right:0;float: none;margin:0 auto;">
            <form action="<?php echo Url('login_submit'); ?>" data-src="<?php echo Url('admin/admin_info'); ?>" id="form" autocomplete="on">
                <h2>需求小助手 · 总后台</h2>
                <div class="item tel"><input type="text" name="phone" placeholder="请输入电话号码" /></div>
                <div class="item pwd"><input type="password" name="pwd" placeholder="请输入密码" /></div>
                <div class="item yzm clearfix"><input type="text" name="verify" placeholder="请输入验证码" />
                    <a id="verify">
                        <img width="93px;" height="42px;" src="<?php echo Url('verify'); ?>" data-src="<?php echo Url('verify',array('time'=>'time_data')); ?>" />
                    </a>
                </div>
                <input type="hidden" name="screen" value="1920" />
                <button id="submit">登录</button>
                <!--<div class="register" >注册-->
                    <!--<div class="register-img">-->
                        <!--<?php echo widget('common/Images/img',array($mini_code,200,200)); ?>-->
                        <!--<p>微信扫一扫注册账号</p>-->
                    <!--</div>-->
                <!--</div>-->
            </form>
        </div>
    </div>
    <div class="footer">厦门酷融信息科技有限公司版权所有 © Copyright 2014-2017 闽ICP备18006609号-1</div>
</body>

<!-- JS处理 -->
<script language="javascript">
    $(function() {
        $('input[name="screen"]').val(window.screen.width);
    });
</script>

<script src="__STATIC__/admin/login/js/interactive.js" type="text/javascript"></script>

</html>