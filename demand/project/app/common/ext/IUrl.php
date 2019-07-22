<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：IUrl.class.php
 * @类功能: 任务管理
 * @开发者: zc
 * @开发时间： 15-10-22
 * @版本：version 1.0
 */
namespace app\common\ext;

class IUrl{
    /**
     * @功能：取得地址
     * @param $url string 地址
     * @开发者：zc
     * @return string
     */
    public static function get($url){
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        return $http_type."{$_SERVER["HTTP_HOST"]}/{$url}";
        //return "http://101.132.36.105/{$url}";
    }
}