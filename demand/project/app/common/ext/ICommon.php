<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：ICompany.php
 * @类功能: 公司操作类
 * @开发者: gys
 * @开发时间： 18-03-15
 * @版本：version 1.0
 */
namespace app\common\ext;

class ICommon {
    //静态变量定义
    protected static $error = null;

    /**
     * @功能 取得错误信息
     * @开发者：cxl
     * @return string
     */
    public static function getError(){
        return self::$error;
    }

    /**
     * @功能 设置错误信息
     * @param string $note 错误信息
     * @开发者：wdd
     * @return string
     */
    protected static function setError($note){
        //设置错误信息；
        self::$error = $note;

        //返回值
        return false;
    }
}