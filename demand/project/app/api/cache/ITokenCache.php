<?php
namespace app\api\cache;

use app\common\cache\BaseCache;
use app\common\ext\IRedis;

class ITokenCache extends BaseCache{
    /**
     * @功能：通过token取得当前登录用户
     * @param string $token
     * @开发者：gys
     * @return string
     */
    public static function getUserId($token){

        $redis = IRedis::getInstance(1);

        $key = BaseCache::CD_MINI_TOKEN;

        //计算键名称
        $key = self::getKey($key);

        //判断取得字段是否为空
        $data = $redis->hget($key,$token);

        $redis->select(0);

        if(empty($data)){
            return false;
        }

        //返回值
        return $data;
    }

    /**
     * @功能：通过token设置当前登录用户
     * @param int $token
     * @param string $user_id
     * @开发者：gys
     * @return string
     */
    public static function setUseId($token, $user_id){
        $redis = IRedis::getInstance(1);

        $key = BaseCache::CD_MINI_TOKEN;

        //计算键名称
        $key = self::getKey($key);

        //判断键\值是否为空
        if(empty($token) || empty($user_id)){
            return false;
        }

        //设置数据
        $result = $redis->hsetnx($key,$token,$user_id);

        $redis->select(0);

        return $result;
    }

    public static function delUserId($token){
        $redis = IRedis::getInstance(1);

        $key = BaseCache::CD_MINI_TOKEN;

        //计算键名称
        $key = self::getKey($key);

        //判断键\值是否为空
        if(empty($token)){
            return false;
        }

        //设置数据
        $result = $redis->hdel($key,$token);

        $redis->select(0);

        return $result;
    }

    public static function clear(){
        $redis = IRedis::getInstance(1);

        //设置数据
        $result = $redis->del(self::getKey(BaseCache::CD_MINI_TOKEN));

        $redis->select(0);

        return $result;
    }
}