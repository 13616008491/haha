<?php
/*
* @开发工具: JetBrains PhpStorm.
* @文件名: IRequest.class.php
* @类功能: 表单数据接收、验证、过滤类
* @开发者: zc
* @开发时间: 2014-10-29
* @版本：version 1.0
*/
namespace app\common\ext;

class IRequest{
    //定义校验类型值
    const EMAIL = 'email'; //验证不能为空
    const IP    = 'ip'; //验证不能为空
    const NOT_EMPTY = 'require';//验证不能为空
    const MOBILE = 'mobile';//验证手机号
    const QQ = 'qq';
    const TEL = 'tel';//验证度座机号
    const ACCOUNT = 'account';//验证账号最短长度及最大长度
    const NUMBER   = 'number';//检测是否为number
    const URL   = 'url';//检测网站路径地址
    const ZIP   = 'zip';//检测邮编
    const INT   = 'integer';//检测是否为integer
    const DOUBLE   = 'double';//检测double
    const ENGLISH   = 'english';//英文单词
    const FILED   = 'filed';//英文单词
    const DATETIME = 'datetime';//日期时间
    const DATE = 'date';//日期
    const TIME = 'time';//时间

    /**
     * @方法功能:获取键为$key的 $_REQUEST
     * @param string $key 取得参数
     * @param string $check   数据验证类型
     * @param string $message  错误提示信息
     * @param string $default   设置默认值
     * @param boolean $force_test   是否强制检测（不管有没有值）
     * @param mixed $filter 参数过滤方法
     * @开发者: zc
     * @return string
     */
    public static function get($key,$check="",$message="",$default=null,$force_test=true,$filter=null){
        //如果验证值为空，提示必须传入参数
        if(empty($key)){
            error("取得参数名不能为空！");
        }

        //取得数据
        if(empty($filter)){
            //$data = input("request.{$key}",$default,"addslashes,htmlspecialchars");
            $data = input("request.{$key}",$default,"addslashes");
        }else{
            $data = input("request.{$key}",$default,$filter);
        }

		//判断值是否为空
        if(null === $data || '' === $data){
            //判断是否需要检测
            if(!empty($check)){
                //是否开启强制检测
                if($force_test){
                    //取得错误信息
                    $msg = empty($message) ? '有数据验证，数据不能为空!':$message;

                    //输出错误信息
                    error($msg);
                }else{
                    //返回值
                    return $data;
                }
            }else{
                return null;
            }
        }

        //返回结果
        $data = empty($check) ? $data : self::check_data($data,$check,$message);
        return $data;
	}

    /**
     * @方法功能:设置键为$key 的$_GET 或者 $_POST 的变量值
     * @param string $key  $_GET 或者 $_POST 键
     * @param string $value 设置的值
     * @开发者: zc
     */
    public static function set($key, $value){
        //设置参数
        $_REQUEST[$key] = $value;
        $_GET[$key] = $value;
    }

    /**
     * @方法功能: 数据校验方法
     * @param string $data 要校验的数据值
     * @param string $check 要验证的类型
     * @param string $message 错误提示信息
     * @开发者: zc
     * @return string
     */
    private static function check_data($data,$check,$message){
        //检测类型判断
        $result = self::check($data,$check);
        if($result !== true){
            error(empty($message)?$result:$message);
        }

        //检查值是正确
		return $data;
	}

    /**
     * @方法功能: 数据校验方法
     * @param string $data 要校验的数据值
     * @param string $check 要验证的类型
     * @开发者: zc
     * @return string
     */
    public static function check($data,$check){
        //去掉多余空格
        $data = trim($data);

        //检测类型判断
        switch($check){
            case self::NOT_EMPTY:
                //检测数据是否为空
                if(!(bool)preg_match('/.+/',$data)){
                    return "数据不能为空！";
                }
                break;
            case self::EMAIL:
                //检测邮箱地址是否正确
                if(!(bool)preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',$data)){
                    return "数据不是邮箱格式！";
                }
                break;
            case self::IP:
                //检测IP地址是否正确
                if(!(bool)preg_match('/^((2[0-4]\d|25[0-5]|[01]?\d\d?)\.){3}(2[0-4]\d|25[0-5]|[01]?\d\d?)$/',$data)){
                    return "数据不是IP格式！";
                }
                break;
            case self::MOBILE:
                //检测手机号是否正确
                if(!(bool)preg_match('/^((13)|(14)|(15)|(18)|(17))[0-9]{9}$/',$data)){
                    return "数据不是手机号格式！";
                }
                break;
            case self::TEL:
                //检测座机号是否正确
                if(!(bool)preg_match('/^((\+86)|(86))?(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/',$data)){
                    return "数据不是座机格式！";
                }
                break;
            case self::QQ:
                //检测QQ号是否正确
                if(!(bool)preg_match('/^[1-9][0-9]{4,}$/',$data)){
                    return "数据不是QQ号格式！";
                }
                break;
            case self::NUMBER:
                //检测数量是否正确（正整数）
                if(!(bool)preg_match('/^\d+$/',$data)){
                    return "数据不是正整数格式！";
                }
                break;
            case self::URL:
                //检测网站地址是否正确
                if(!(bool)preg_match('/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',$data)){
                    return "数据不是网址格式！";
                }
                break;
            case self::ZIP:
                //检测邮编是否正确
                if(!(bool)preg_match('/^\d{6}$/',$data)){
                    return "数据不是邮编格式！";
                }
                break;
            case self::INT:
                //检测整数（包含负数）
                if(!(bool)preg_match('/^[-\+]?\d+$/',$data)){
                    return "数据不是整数格式！";
                }
                break;
            case self::DOUBLE:
                //检测浮点数
                if(!(bool)preg_match('/^[-\+]?\d+(\.\d+)?$/',$data)){
                    return "数据不是浮点型格式！";
                }
                break;
            case self::ENGLISH:
                //检测是否为英文
                if(!(bool)preg_match('/^[A-Za-z]+$/',$data)){
                    return "数据不是英文字符串格式！";
                }
                break;
            case self::FILED:
                //检测是否字母加下划线
                if(!(bool)preg_match('/^[0-9A-Za-z_]+$/',$data)){
                    return "数据不是字母加下划线格式！";
                }
                break;
            case self::DATETIME:
                //检测日期时间
                if(!(bool)strtotime($data)){
                    return "数据不是日期时间格式！";
                }
                break;
            case self::DATE:
                //检测日期
                if(!(bool)strtotime($data." 00:00:00")){
                    return "数据不是日期格式！";
                }
                break;
            case self::TIME:
                //检测时间
                if(!(bool)strtotime("1970-01-01 ".$data)){
                    return "数据不是时间格式！";
                }
                break;
        }

        //非检测范围的数据
        return true;
    }
}
