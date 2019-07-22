<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：IRedis.class.php
 * @类功能: 缓存类
 * @开发者: 陈旭林
 * @开发时间： 14-10-28
 * @版本：version 1.0
 */
namespace app\common\ext;

use app\common\cache\BaseCache;
use Redis;
use think\Config;

class IRedis {
    //静态化缓存列表
    public static $redis = null;

    //常理设置
    const WITH = "WITHSCORES";

    /**
     * @功能 设置当前数据库ID
     * @param int $database 数据库ID
     * @return null
     */
    public static function getInstance($database=0){
        //判断对象是否存在
        if(!self::$redis){
            //初期化
            $init  = self::init();

            //判断创建redis对象是否成功
            if($init === false){
                return false;
            }
        }

        //判断是否需要切换库
        if(!empty($database)){
            self::$redis->select($database);
        }

        //返回值
        return self::$redis;
    }

    /**
     * @功能 取得redis值
     * @param string $key 键
     * @param string $item 键下标
     * @param string $callback 数据值
     * @param mixed $expire 有效期
     * @return bool
     */
    public static function getCallBackString($key,$item=null,$callback=null,$expire=null) {
        //初期化redis
        self::init();

        //判断键\值是否为空
        if(empty($key) || empty($callback)){
            return false;
        }

        //计算键名称
        $key = self::getKey($key,$item);

        //取得缓存数据
        $data = self::$redis->get($key);

        //判断缓存数据是否为空
        if($data === false){
            //取得数据
            $data = self::data_array($callback,$item);
            if($data === false){
                return false;
            }

            //数据转化
            $data = json_encode($data);

            //判断是否设置有效期
            if (empty($expire)) {
                //缓存数据
                self::$redis->set($key, $data);
            } else {
                //缓存数据
                self::$redis->setex($key, $expire, $data);
            }
        }

        //数据转义
        $data = json_decode($data,true);

        //放回数据
        return $data;
    }

    /**
     * @功能 取得redis值
     * @param string $key 键
     * @param string $item 键下标
     * @param string $field 字段
     * @param string $callback 数据值
     * @param mixed $expire 有效期
     * @return bool
     */
    public static function getCallBackInfo($key,$item=null,$field=null,$callback=null,$expire=null) {
        //初期化redis
        self::init();

        //判断键\值是否为空
        if(empty($key) || empty($callback)){
            return false;
        }

        //计算键名称
        $key = self::getKey($key,$item);

        //判断取得字段是否为空
        $exists = self::$redis->exists($key);

        //判断缓存数据是否为空
        if($exists === false){
            //取得数据
            $data = self::data_array($callback, $item);

            //判断是否设置有效期
            if (is_array($data)) {
                //设置值
                self::$redis->hMSet($key, $data);

                //设置过期时间
                if(is_int($expire)) {
                    self::$redis->expire($key, $expire);
                }
            } else {
                return false;
            }
        }
        //取得数据
        if(empty($field)){
            //取得当前字段
            $data = self::$redis->hGetAll($key);
        }elseif(is_string($field)){
            //取得当前字段
            $data = self::$redis->hGet($key,$field);
        }elseif(is_array($field)){
            //取得多个字段
            $data = self::$redis->hMGet($key,$field);
        }

        //放回数据
        return $data;
    }

    /**
     * @功能 取得redis值
     * @param string $key 键
     * @param string $item 键下标
     * @param string $bottom 最下面一条数据编号
     * @param string $callback 数据值
     * @param mixed $expire 有效期
     * @return bool
     */
    public static function getCallBackList($key,$item=null,$bottom=null,$callback=null,$expire=null) {
        //初期化redis
        self::init();

        //判断键\值是否为空
        if(empty($key) || empty($callback)){
            return false;
        }

        //计算键名称
        $key = self::getKeys($key,$item,$bottom);

        //取得缓存数据
        $data = self::$redis->get($key);
        //$data = false;
        //判断缓存数据是否为空
        if($data === false){
            //取得数据
            $data = self::data_list($callback,$item,$bottom);
            if($data === false){
                return false;
            }

            //数据转化
            $data = json_encode($data);

            //判断是否设置有效期
            if (empty($expire)) {
                //缓存数据
                self::$redis->set($key, $data);
            } else {
                //缓存数据
                self::$redis->setex($key, $expire, $data);
            }
        }

        //数据转义
        $data = json_decode($data,true);

        //放回数据
        return $data;
    }

    /**
     * @功能 清除redis键
     * @param string $key 键
     * @return bool
     */
    public static function delKey($key) {
        //初期化redis
        self::init();

        $key = BaseCache::getKey($key); //拼上前缀

        //取得相同的Key
        $key_array = self::$redis->keys("{$key}.*");

        //判断是否取得相关缓存
        if(!empty($key_array)){
            return self::$redis->del($key_array);
        }

        //放回结果
        return true;
    }

    /**
     * @功能 清除redis键
     * @param string $key 键
     * @param string $item 键下标
     * @return bool
     */
    public static function delItem($key,$item) {
        //初期化redis
        self::init();

        //计算键名称
        $key = self::getKey($key,$item);

        //判断缓存是否存在
        return self::$redis->del($key);
    }

    /**
     * @功能 清除redis键
     * @param string $key 键
     * @param string $item 值
     * @return bool
     */
    public static function delList($key,$item) {
        //初期化redis
        self::init();

        //计算键名称
        $key = self::getKey($key,$item);

        //取得相同的Key
        $key_array = self::$redis->keys("{$key}.*");

        //判断是否取得相关缓存
        if(!empty($key_array)){
            return self::$redis->del($key_array);
        }

        //放回结果
        return true;
    }

    /**
     * @功能 清除redis
     * @return bool
     */
    public static function flushDB() {
        //初期化redis
        self::init();

        //判断缓存是否存在
        return self::$redis->flushDB();
    }

    /**
     * @功能 取得数据
     * @param string $callback 回调方法
     * @param array $condition 条件
     * @返回值 是否成功
     * @return string
     */
    private static function data_array($callback,$condition) {
        //动态整理数据
        if(is_callable($callback)){
            //调用回调函数
            $data = $callback($condition);
        }else{
            //数据赋值
            $data = $callback;
        }

        //返回值
        return $data;
    }

    /**
     * @功能 取得数据
     * @param string $callback 回调方法
     * @param array $condition 条件
     * @param int $bottom 最下面一条数据编号
     * @返回值 是否成功
     * @return string
     */
    private static function data_list($callback,$condition,$bottom) {
        //动态整理数据
        if(is_callable($callback)){
            //调用回调函数
            $data = $callback($condition,$bottom);
        }else{
            //数据赋值
            $data = $callback;
        }

        //返回值
        return $data;
    }

    /**
     * @功能：计算redis缓存key
     * @param string $key 缓存key
     * @param string $data 缓存key的下标
     * @开发者：陈旭林
     * @return string
     */
    public static function getKey($key,$data){
        //初期化
        $keys = null;

        //判断是否为空
        if(empty($data)) {
            $keys .= "NULL";
        }else{
            //判断是否为数组
            if(is_array($data)){
                //按照键值排序
                ksort($data);

                //取得索引值
                foreach($data as $item=>$value){
                    //判断值是否为空
                    if(empty($value)){
                        $keys .= "{".trim($item)."=NULL}";
                    }else {
                        if (is_array($value)) {
                            $keys .= "{" . trim($item) . "=" . trim(json_encode($value)) . "}";
                        }else{
                            $keys .= "{" . trim($item) . "=" . trim($value) . "}";
                        }
                    }
                }
            }else{
                $keys .= trim($data);
            }
        }

        //判断长度
        if(strlen($keys) > 50){
            $keys = md5($keys);
        }

        //设置KEY
        $key = $key.".".$keys;
        $key = BaseCache::getKey($key); //拼上前缀

        //返回值
        return $key;
    }

    /**
     * @功能：计算redis缓存key
     * @param string $key 缓存key
     * @param string $data 缓存key的下标
     * @param int $bottom 最下面一条数据编号
     * @开发者：陈旭林
     * @return string
     */
    public static function getKeys($key,$data,$bottom){
        //初期化
        $key = self::getKey($key,$data);

        //添加数据编号
        if(empty($bottom)) {
            $key = $key . ".NULL";
        }else{
            $key = $key . "." . $bottom;
        }

        //返回值
        return $key;
    }

    /**
     * @功能 初期化Redis
     * @返回值 是否成功
     */
    private static function init() {
        //判断是否生产Redis对象
        if(!empty(self::$redis)){
            return false;
        }

        //取得配置信息
        $host = Config::get('redis.host') ? Config::get('redis.host') : '127.0.0.1';
        $port = Config::get('redis.port') ? Config::get('redis.port') : 6379;
        $pwd = Config::get('redis.pwd') ? Config::get('redis.pwd') : '';

        //创建redis对象
        self::$redis = new Redis();
        if(empty(self::$redis)){
            return false;
        }

        //链接redis对象
        $connect = self::$redis->connect($host,$port);
        if(empty($connect)){
            return false;
        }

        //判断是否需要授权
        if(!empty($pwd)){
            $auth = self::$redis->auth($pwd);
            if(empty($auth)){
                return false;
            }
        }
		
		//选择redis库
        $select = self::$redis->select(0);
        if(empty($select)){
            return false;
        }

        //返回值
        return true;
    }
}