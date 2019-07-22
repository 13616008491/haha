<?php
//应用设置

return [
    // 二维码校验值
    'user_secret'        => 'jqyp_demand',
    // 默认模块名
    'default_module'        => 'admin',
    'wx_access_token'       =>true,
    //系统配置
    'app_debug'              => true,         //应用调试模式
    'url_domain_deploy'     => false,        //动态注册和配置定义
    'url_ip'                  => '127.0.0.1',        //ip地址

    //短信接口配置
    'redis'                    => array(
        'host'                 => '127.0.0.1',         //Redis服务器地址
        'port'                 => '6379',               //Redis服务器端口
        'pwd'                  => '',                    //密码
        'prefix'               => 'DEMAND_',                //key前缀
    ),

    //阿里云oss配置
    'oss'                      => array(
        'access_key_id'      => 'LTAIuAB7ynxSZ4Xk',             //您从OSS获得的AccessKeyId
        'access_key_secret'  => '2yXojQHXdstXOqPAjSkvuwI1moCKas',       //您从OSS获得的AccessKeySecret
        'endpoint'            => 'oss-cn-shanghai.aliyuncs.com',         //您选定的OSS数据中心访问域名
        'bucket'              => 'juqi',         //存储空间
        'path'               => 'demand/',         //存储目录
        'url'                  => 'http://juqi.oss-cn-shanghai.aliyuncs.com', //您选定的OSS数据中心访问域名
    ),
];