<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：ErrorBaseController.class.php
 * @类功能: Action基类
 * @开发者: 陈旭林
 * @开发时间： 14-10-28
 * @版本：version 1.0
 */
namespace app\common\controller;

use think\Controller;

class ErrorBaseController extends Controller {

    /**
     * @功能：错误页面函数
     * @param string $message
     * @开发者： 陈旭林
     */
    public function err($message){
        $this->error($message);
    }
}