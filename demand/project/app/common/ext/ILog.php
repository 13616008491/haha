<?php
/*
* @开发工具: JetBrains PhpStorm.
* @文件名: ILog.class.php
* @类功能: 日志
* @开发者: zc
* @开发时间: 2014-10-29
* @版本：version 1.0
*/
namespace app\common\ext;

use app\common\enum\IsDelete;
use app\common\enum\IsTrack;
use Common\Enum\AccountEvent;
use think\Db;
use think\Exception;
use think\Request;

class ILog{
    /**
     * @功能 数据库错误
     * @param string $msg 错误说明
     * @param string|null $sql sql文
     * @开发者：cxl
     * @return bool
     */
    public static function DbLog($msg,$sql=null){
        //判断是否存在sql文
        if(!is_null($sql)){
            $msg .= "[{$sql}]";
        }

        //整理数据
        $data["module"] = Request::instance()->module();
        $data["controller"] = Request::instance()->controller();
        $data["action"] = Request::instance()->action();
        $data["error"] = $msg;
        $data["create_time"] = get_date_time();

        //写数据库操作错误日志
        $com_database_add = IDb::getInstance("database")->setDbData($data)->add();
        if($com_database_add === false){
            return false;
        }

        //返回值
        return true;
    }
}
