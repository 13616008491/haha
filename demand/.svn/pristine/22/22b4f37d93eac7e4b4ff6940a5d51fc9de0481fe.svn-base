<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：ICryptAes.class.php
 * @类功能: 加密 参照：http://www.cnblogs.com/yipu/articles/3871576.html
 * @开发者: 陈旭林
 * @开发时间： 14-10-28
 * @版本：version 1.0
 */
namespace app\api\ext;

class ICryptAes{
    //常理
    private static $hex_iv = 'b2d799159b86715cf7efeff0f734c4f9';//加密向量
    private static $key = '4047f1ec64fa6cd42a3273f366053648';//加密key

    /**
     * @功能 加密
     * @param string $str 字符串
     * @param string $key 加密钩子
     * @开发者：陈旭林
     * @return bool
     */
    public static function encrypt($str,$key = null) {
        //取得加密key
        if(empty($key)){
            $key = hash('sha256',self::$key, true);
        }else{
            $key = hash('sha256',$key, true);
        }

        //hex转化为字符串
        $iv = null;
        $hex_iv = self::$hex_iv;
        for ($i=0; $i < strlen($hex_iv)-1; $i+=2){
            $iv .= chr(hexdec($hex_iv[$i].$hex_iv[$i+1]));
        }

        //加密
        $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        mcrypt_generic_init($module,$key, $iv);
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $pad = $block - (strlen($str) % $block);
        $str .= str_repeat(chr($pad), $pad);
        $encrypted = mcrypt_generic($module, $str);
        mcrypt_generic_deinit($module);
        mcrypt_module_close($module);

        //返回结果
        return base64_encode($encrypted);
    }

    /**
     * @功能 解密
     * @param string $str 字符串
     * @param string $key 解密钩子
     * @开发者：陈旭林
     * @return bool
     */
    public static function decrypt($str,$key = null) {
        //取得加密key
        if(empty($key)){
            $key = hash('sha256',self::$key, true);
        }else{
            $key = hash('sha256',$key, true);
        }

        //hex转化为字符串
        $iv = null;
        $hex_iv = self::$hex_iv;
        for ($i=0; $i < strlen($hex_iv)-1; $i+=2){
            $iv .= chr(hexdec($hex_iv[$i].$hex_iv[$i+1]));
        }

        //解密
        $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        mcrypt_generic_init($module, $key, $iv);
        $str = mdecrypt_generic($module, base64_decode($str));
        mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        mcrypt_generic_deinit($module);
        mcrypt_module_close($module);

        //取得数据
        $str_last = ord(substr($str, -1));
        $str_last_chr = chr($str_last);
        if (preg_match("/$str_last_chr{" . $str_last . "}/", $str)) {
            $string = substr($str, 0, strlen($str) - $str_last);
            return $string;
        } else {
            return false;
        }
    }
}