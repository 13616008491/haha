<?php
namespace app\common\cache\project;

use app\common\cache\BaseCache;
use app\common\enum\IsPropose;
use app\common\ext\IDb;
use app\common\enum\IsDelete;
use app\common\ext\IRedis;

class IProjectCache extends BaseCache{
    /**
     * @功能：取得缓存数据
     * @param int $user_id 数据编号
     * @param int $bottom_id 数据编号
     * @开发者：曾文杰
     * @return string
     */
    public static function getList($user_id,$bottom_id){
        self::deleteList($user_id);
        //取得缓存信息
        $list = IRedis::getCallBackList(self::DM_PROJECT_LIST,$user_id,$bottom_id,function($user_id,$bottom_id){
            //实例化对象
            $dm_project_where['is_delete'] = IsDelete::No;
            $dm_project_where['propose_status'] = IsPropose::Yes;
            if(!empty($bottom_id)) $dm_project_where['project_id'] = array("gt",$bottom_id);


            //取得数据
            $list = IDb::getInstance('project as pj')
                ->setDbFiled("pj.project_id,pj.project_name,pj.project_photo_url,pj.project_describe,pj.start_time,pj.sort_level")
                ->setDbWhere($dm_project_where)
                ->setDbOrder("sort_level desc,project_name asc")

                ->sel();
            //file_put_contents('wz',var_export($list, true),FILE_APPEND);

            //返回值
            return $list;
        },3600);

        //返回值
        return $list;
    }

    /**
     * @功能：删除缓存数据
     * @param int $user_id 数据编号
     * @开发者：陈旭林
     * @return string
     */
    public static function deleteList($user_id){
        return IRedis::delList(self::DM_PROJECT_LIST,$user_id);
    }

    /**
     * @功能：删除缓存数据
     * @开发者：陈旭林
     * @return string
     */
    public static function deleteListAll(){
        return IRedis::delKey(self::DM_PROJECT_LIST);
    }
}