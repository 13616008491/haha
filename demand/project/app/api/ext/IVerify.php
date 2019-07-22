<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：IVerify.class.php
 * @类功能: 接口数据验证
 * @开发者: 陈旭林
 * @开发时间： 14-10-28
 * @版本：version 1.0
 */
namespace app\api\ext;

use think\Request;

class IVerify{
    //定义常量
    const CHECK_KEY         = 'mh#@md~!2ert';
    const CHECK_AES         = 'AZCwerusdfoIPLMK'; //不能重复字符
    const CHECK_TOKEN      = '@%34YGYj23w&(*2e89r888'; //token加密
    const CHECK_RANDOM     = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz"; //定义随意值范围

    //定义关键值下标
    const SUBSCRIPT_KEY    = "_k"; //参数加密验证key
    const SUBSCRIPT_TOKEN  = "_t"; //token值
    const SUBSCRIPT_CHECK  = "_c"; //用户控制相同token能够使用的次数

    //用户类型ID
    const USER_GROUP       = '_g'; //用户类型ID

    /**
     * 加密
     * @param array $data 需要加密的数据
     * @return string 加密后的结果
     */
    public static function encrypt($data){
        //判断参数是否为空及数组
        if (!is_array($data) || empty($data)) {
            return false;
        }

        //取得控制器方法名
        $action = strtolower(Request::instance()->controller() . ":" . Request::instance()->action());

        //判断是否为初始化接口
        if ($action != 'system:start') {
            //取得未加密的token值
            if(isset($data[self::SUBSCRIPT_TOKEN])) {
                $token = $data[self::SUBSCRIPT_TOKEN];
            }

            //判断是否有token值
            if (!empty($token)) {
                $data[self::SUBSCRIPT_TOKEN] = ICryptAes::encrypt(json_encode($token), self::CHECK_TOKEN);
            }
        }

        //取得数据效应key
        $data[self::SUBSCRIPT_KEY] = self::key($data);

        //数据加密
        $data = self::encrypt_aes($data);

        //返回结果
        return $data;
    }

    /**
     * 解密
     * @param string $data 需要解密的数据
     * @return array 解密后的结果数据
     */
    public static function decrypt($data){
        //判断参数是否为空及数组
        if(empty($data)){
            return false;
        }

        //请求参数解密
        $data = self::decrypt_aes($data);

        //判断解密是否正常
        if(empty($data[self::SUBSCRIPT_KEY])){
            return false;
        }

        //数据效应
        $key = self::key($data);
        if($data[self::SUBSCRIPT_KEY] != $key){
            return false;
        }

        //判断是否存在token
        if(strtolower(Request::instance()->controller().":".Request::instance()->action()) != "system:start") {
            //解析Token
            $data[self::SUBSCRIPT_CHECK] = md5($data[self::SUBSCRIPT_TOKEN]);
            $data[self::SUBSCRIPT_TOKEN] = json_decode(ICryptAes::decrypt($data[self::SUBSCRIPT_TOKEN], self::CHECK_TOKEN), true);
        }

        //返回结果
        return $data;
    }

    /**
     * 请求参数结果加密
     * @param $data array 需要加密的数据
     * @return string 加密后的结果
     */
    private static function encrypt_aes($data){
        //取得加密因子
        $hv = mt_rand(5, (strlen(self::CHECK_AES) - 1));
        $random = self::random($hv);

        //数据加密
        $data = ICryptAes::encrypt(json_encode($data),$random);

        //取得加密偏移量
        $excursion = floor(strlen($data)/strlen($random));

        //数据分配加密信息初期化
        $data = str_split($data,1);
        $random = str_split($random,1);

        //循环分配加密信息
        foreach($random as $key=>$val){
            //计算加入位置
            $subscript = (($key + 1) * $excursion);

            //数据拼接
            array_splice($data,$subscript,0,str_split($val));
        }

        //数组转字符串
        $data = implode("",$data);

        //设置加密钩子长度
        $aes = self::CHECK_AES;
        $aes = $aes[$hv];
        $data = $aes . $data;

        //返回结果
        return $data;
    }

    /**
     * 请求参数解密
     * @param $data array 需要解密的数据
     * @return array 解密后的结果数据
     */
    private static function decrypt_aes($data){
        //取得加密因子长度
        $hv = substr($data,0,1);
        $data = substr($data,1);

        //判断加密因子是否异常
        $hv = stripos(self::CHECK_AES,$hv);
        if($hv <= 0){
            return false;
        }

        //计算偏移量
        if($hv > 0) {
            $excursion = floor(strlen($data) / $hv);
        }else{
            return false;
        }

        //数据分配加密信息初期化
        $data = str_split($data,1);

        //取得加密因子
        $random = null;
        for($i = $hv ; $i > 0 ; $i--){
            //取得加密因子组成数据
            $random = $data[$i * $excursion - $i] . $random;

            //异常数据元素
            unset($data[$i * $excursion - $i]);
        }

        //判断加密因子是否成功
        if(empty($random)){
            return false;
        }

        //数组转字符串
        $data = implode("",$data);

        //解密
        $data = ICryptAes::decrypt($data,$random);
        $data = str_replace(array("\n","\t","\r"),"\\n",$data);
        $data = json_decode($data,true);

        //返回结果
        return $data;
    }

    /**
     * 生产效应Key值
     * @param $data array 需要效应的值
     * @return string 返回效应Key值
     */
    private static function key($data){
        //初期化
        $encrypt = null;

        //对效应数据排序
        ksort($data);

        //循环拼接数据
        foreach ($data as $key => $val) {
            //排除token值
            if ($key != self::SUBSCRIPT_KEY) {
                $encrypt .= $key . $val;
            }
        }

        //数据加密
        $key = md5(md5($encrypt).self::CHECK_KEY);

        //返回数据
        return $key;
    }

    /**
     * 生产随机数
     * @param $len int 随机数长度
     * @return array 随机数
     */
    private static function random($len){
        //初期化
        $random_range = self::CHECK_RANDOM;

        //设置随机因子
        mt_srand((double)microtime() * 1000000);

        //初期化返回就
        $random = array_fill(0,$len,null);

        //循环生产随机数
        foreach($random as $key=>$val){
            //设置随机值
            if($val === null){
                $random[$key] = $random_range[mt_rand(0, strlen($random_range) - 1)];
            }
        }

        //随机数组转字符串
        $random = implode("",$random);

        //返回结果
        return $random;
    }
}