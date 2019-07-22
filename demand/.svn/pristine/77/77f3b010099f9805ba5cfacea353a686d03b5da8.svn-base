<?php
namespace app\common\cache\user;

use app\common\cache\BaseCache;
use app\common\ext\IDb;
use app\common\ext\IRedis;

class IUserCache extends BaseCache{
    /**
     * @功能：取得缓存数据
     * @param int $user_id 数据编号
     * @param array $field 取得字段
     * @开发者：陈旭林
     * @return array|string
     */
    public static function getInfo($user_id, $field=null){
        //取得缓存信息
        $info = IRedis::getCallBackInfo(self::CD_USER_INFO,$user_id,$field,function($user_id){
            //设置条件
            $where['user_id'] = $user_id;

            //实例化对象
            $info = IDb::getInstance('user')->setDbWhere($where)->row();

            //返回值
            return $info;
        },3600);

        //返回值
        return $info;
    }

    /**
     * @功能：删除缓存数据
     * @param int $user_id 数据编号
     * @开发者：陈旭林
     * @return string
     */
    public static function deleteInfo($user_id){
        return IRedis::delItem(self::CD_USER_INFO,$user_id);
    }
}