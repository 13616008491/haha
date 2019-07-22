<?php
//检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

//共通配置
header("Content-type: text/html;charset=utf-8");                                    //定义页面编码格式
ini_set("memory_limit","64M");                                                          //定义内存使用量
//error_reporting(E_ERROR | E_WARNING | E_PARSE);                                        //错误信息
error_reporting(E_ALL);                                                                   //错误信息
date_default_timezone_set('Asia/Shanghai');                                              //设置时区
//define('BIND_MODULE','agent');                                                           //设置默认模板

//工程配置
define('DOCUMENT', __DIR__ . '/');                                                        //工程目录
define('APP_PATH', __DIR__ . '/../project/app/');                                      //工程目录
define('ROOT_PATH', dirname(realpath(APP_PATH)) . DIRECTORY_SEPARATOR);                //工程绝对路径
define('EXTEND_PATH', ROOT_PATH . 'ext' . DIRECTORY_SEPARATOR);                       //扩展目录
define('VENDOR_PATH', ROOT_PATH . 'ven' . DIRECTORY_SEPARATOR);                       //插件目录
define('CONF_PATH', ROOT_PATH .'conf' . DIRECTORY_SEPARATOR);                         //配置文件目录
define('RUNTIME_PATH', ROOT_PATH .'run' . DIRECTORY_SEPARATOR);                       //Runtime目录
define('UPLOADS_PATH', './uploads' . DIRECTORY_SEPARATOR);                             //Uploads目录

//未知错误
define('SYSTEM_ERROR', 99);                                                                 //系统错误编码

//加载框架引导文件
require __DIR__ . '/../think/start.php';
