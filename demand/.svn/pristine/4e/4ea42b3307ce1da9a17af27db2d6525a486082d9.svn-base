<?php
    //判断是否为debug模式
    $app_debug = \think\Config::get('app_debug');

    //不是debug模式
    if($app_debug === true){
        //取得错误文件
        echo '['.$code.']'.sprintf('%s in %s', $file, $line);
        echo nl2br(htmlentities($message));
    }else{
        //设置返回值
        $return = null;
        $return['code'] = SYSTEM_ERROR;
        $return['msg'] = "未知错误！";
        echo json_encode($return);
     }
?>