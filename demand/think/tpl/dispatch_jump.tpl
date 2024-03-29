{__NOLAYOUT__}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>跳转提示</title>
    <style type="text/css">
        *{ padding: 0; margin: 0; }
        body{ background: #f5f5f5; font-family: '微软雅黑'; color: #333; font-size: 16px; }
        .system-message{ margin: 0 auto; width: 1200px; height: 650px; background: url("__STATIC__/admin/img/tip.png") no-repeat;}
        .system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
        .system-message .system-cont{ width: 1000px; height: 226px; text-align: center; padding: 120px 80px 0 155px;; }
        .system-message .jump{ padding-top: 10px}
        .system-message .jump a{ color: #333;}
        .system-message .success,.system-message .error{ line-height: 1.8em; font-size: 30px;width: 700px;padding-left: 155px; }
        .system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
    </style>
</head>
<body>
    <div class="system-message">
        <div class="system-cont">
            <?php switch ($code) { ?>
                <?php case 1:?>
                    <h1><img src="__STATIC__/admin/img/success_icon.png" width="64" height="64" /></h1>
                    <p class="success"><?php echo(strip_tags($msg));?></p>
                    <?php break;?>
                <?php case 0:?>
                    <h1><img src="__STATIC__/admin/img/error_icon.png" width="64" height="64" /></h1>
                    <p class="error"><?php echo(strip_tags($msg));?></p>
                    <?php break;?>
            <?php } ?>
            <p class="detail"></p>
            <p class="jump">
                页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait">10</b>
            </p>
        </div>
    </div>
    <script type="text/javascript">
        (function(){
            var wait = document.getElementById('wait'),
                href = document.getElementById('href').href;
            var interval = setInterval(function(){
                var time = --wait.innerHTML;
                if(time <= 0) {
                    location.href = href;
                    clearInterval(interval);
                };
            }, 1000);
        })();
    </script>
</body>
</html>
