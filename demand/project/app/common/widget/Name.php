<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：NameWidget.class.php
 * @类功能: 取得名称
 * @开发者: zc
 * @开发时间： 14-10-28
 * @版本：version 1.0
 */
namespace app\common\widget;

use app\common\ext\IDb;
use think\Controller;

class Name extends Controller{
    //静态变量
    private static $admin = null;
    private static $user = null;

    /**
     * @功能：取得管理员名称
     * @param $admin_id int 管理员ID
     * @开发者：cxl
     * @return string
     */
    public static function admin($admin_id) {
        //判断是否为空
        if(empty($admin_id)){
            return "--";
        }

        //判断缓存是否存在
        if(!empty(self::$admin[$admin_id])){
            return self::$admin[$admin_id];
        }

        //实例化对象
        $admin_where['admin_id'] = $admin_id;
        $admin_info = IDb::getInstance("admin")->setDbFiled("admin_id,real_name")->setDbWhere($admin_where)->row();

        //判断数据是否存在
        if(!empty($admin_info['admin_id'])){
            if(!empty($admin_info['real_name'])) {
                self::$admin[$admin_id] = $admin_info['real_name'];
            }else{
                self::$admin[$admin_id] = "--";
            }
        }else{
            self::$admin[$admin_id] = "--";
        }

        //返回值
        return self::$admin[$admin_id];
    }

    /**
     * @功能：取得管理员名称
     * @param $user_id int 用户名称
     * @开发者：cxl
     * @return string
     */
    public static function user($user_id) {
        //判断是否为空
        if(empty($user_id)){
            return "--";
        }

        //判断缓存是否存在
        if(!empty(self::$user[$user_id])){
            return self::$user[$user_id];
        }

        //实例化对象
        $user_where['user_id'] = $user_id;
        $admin_info = IDb::getInstance("user")->setDbFiled("user_id,nickname,real_name")->setDbWhere($user_where)->row();

        //判断数据是否存在
        if(!empty($admin_info['user_id'])){
            if(!empty($admin_info['real_name'])) {
                self::$user[$user_id] = $admin_info['real_name'];
            }else{
                if(!empty($admin_info['nickname'])) {
                    self::$user[$user_id] = $admin_info['nickname'];
                }else{
                    self::$user[$user_id] = "--";
                }
            }
        }else{
            self::$user[$user_id] = "--";
        }

        //返回值
        return self::$user[$user_id];
    }
}