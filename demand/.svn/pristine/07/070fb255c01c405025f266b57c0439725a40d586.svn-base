<?php
namespace app\api\cache;

use app\common\cache\BaseCache;
use app\common\ext\IRedis;

class IClientCache extends BaseCache{
    /**
     * @功能：通过token取得当前登录用户
     * @param string $client_id
     * @开发者：gys
     * @return string
     */
    public static function getUserId($client_id){
        $redis = IRedis::getInstance(1);

        $key = BaseCache::CD_CHAT_USER_ID;

        //计算键名称
        $key = self::getKey($key);

        //判断取得字段是否为空
        $data = $redis->hget($key,$client_id);

        $redis->select(0);

        if(empty($data)){
            return false;
        }

        //返回值
        return $data;
    }

    /**
     * @功能：通过token设置当前登录用户
     * @param int $client_id
     * @param string $user_id
     * @开发者：gys
     * @return string
     */
    public static function setUseId($client_id, $user_id){
        $redis = IRedis::getInstance(1);

        $key = BaseCache::CD_CHAT_USER_ID;

        //计算键名称
        $key = self::getKey($key);

        //判断键\值是否为空
        if(empty($client_id) || empty($user_id)){
            return false;
        }

        //设置数据
        $result = $redis->hset($key,$client_id,$user_id);

        $redis->select(0);

        return $result;
    }

    public static function delUserId($client_id){
        $redis = IRedis::getInstance(1);

        $key = BaseCache::CD_CHAT_USER_ID;

        //计算键名称
        $key = self::getKey($key);

        //判断键\值是否为空
        if(empty($client_id)){
            return false;
        }

        //计算键名称
//        $key = IRedis::getKey($key,$client_id);

        //设置数据
        $result = $redis->hdel($key,$client_id);

        $redis->select(0);

        return $result;
    }

    /**
     * @功能：通过token取得当前登录用户
     * @param string $user_id
     * @开发者：gys
     * @return string
     */
    public static function getClientId($user_id){
        $redis = IRedis::getInstance(1);

        $key = BaseCache::CD_CHAT_CLIENT_ID;

        //计算键名称
        $key = self::getKey($key);

        //判断取得字段是否为空
        $data = $redis->hget($key,$user_id);

        $redis->select(0);

        if(empty($data)){
            return false;
        }

        //返回值
        return $data;
    }

    /**
     * @功能：通过token设置当前登录用户
     * @param int $user_id
     * @param string $client_id
     * @开发者：gys
     * @return string
     */
    public static function setClientId($user_id, $client_id){
        $redis = IRedis::getInstance(1);

        $key = BaseCache::CD_CHAT_CLIENT_ID;

        //计算键名称
        $key = self::getKey($key);

        //判断键\值是否为空
        if(empty($user_id) || empty($client_id)){
            return false;
        }

        //设置数据
        $result = $redis->hset($key,$user_id,$client_id);

        $redis->select(0);

        return $result;
    }

    public static function delClientId($user_id){
        $redis = IRedis::getInstance(1);

        $key = BaseCache::CD_CHAT_CLIENT_ID;

        //计算键名称
        $key = self::getKey($key);

        //判断键\值是否为空
        if(empty($user_id)){
            return false;
        }

        //设置数据
        $result = $redis->hdel($key,$user_id);

        $redis->select(0);

        return $result;
    }

    public static function clear(){
        $redis = IRedis::getInstance(1);

        //设置数据
        $result = $redis->del(self::getKey(BaseCache::CD_CHAT_USER_ID),self::getKey(BaseCache::CD_CHAT_CLIENT_ID));

        $redis->select(0);

        return $result;
    }
}