<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：ApiBaseAction.class.php
 * @类功能: 接口基类
 * @开发者: 陈旭林
 * @开发时间： 14-10-28
 * @版本：version 1.0
 */
namespace app\common\controller;

use app\api\cache\ITokenCache;
use app\common\cache\user\IUserCache;
use app\common\enum\IsUserEnable;
use app\common\ext\IDb;
use think\Loader;
use think\Request;
use app\common\enum\IsDelete;

class DemandBaseController extends CommonController{
    //操作成功
    const SUCCESS  = '0';

    //检测接口错误编码
    const ERR_CHECK_0000 = 'C0000';
    const ERR_CHECK_0001 = 'C0001';
    const ERR_CHECK_0002 = 'C0002';
    const ERR_CHECK_0003 = 'C0003';
    const ERR_CHECK_0004 = 'C0004';
    const ERR_CHECK_0005 = 'C0005';
    const ERR_CHECK_0006 = 'C0006';
    const ERR_CHECK_0007 = 'C0007';
    const ERR_CHECK_0008 = 'C0008';
    const ERR_CHECK_0009 = 'C0009';
    const ERR_CHECK_0010 = 'C0010';

    //错误编码
    const ERR_0001 = 'E0001';
    const ERR_0002 = 'E0002';
    const ERR_0003 = 'E0003';
    const ERR_0004 = 'E0004';
    const ERR_0005 = 'E0005';
    const ERR_0006 = 'E0006';
    const ERR_0007 = 'E0007';
    const ERR_0008 = 'E0008';
    const ERR_0009 = 'E0009';
    const ERR_0010 = 'E0010';
    const ERR_0011 = 'E0011';
    const ERR_0012 = 'E0012';
    const ERR_0013 = 'E0013';
    const ERR_0014 = 'E0014';
    const ERR_0015 = 'E0015';
    const ERR_0016 = 'E0016';
    const ERR_0017 = 'E0017';
    const ERR_0018 = 'E0018';
    const ERR_0019 = 'E0019';
    const ERR_0020 = 'E0020';
    const ERR_0021 = 'E0021';

    //警告编码
    const WARNING_0001 = 'W0001';
    const WARNING_0002 = 'W0002';
    const WARNING_0003 = 'W0003';
    const WARNING_0004 = 'W0004';
    const WARNING_0005 = 'W0005';
    const WARNING_0006 = 'W0006';
    const WARNING_0007 = 'W0007';
    const WARNING_0008 = 'W0008';
    const WARNING_0009 = 'W0009';
    const WARNING_0010 = 'W0010';
    const WARNING_0011 = 'W0011';
    const WARNING_0012 = 'W0012';
    const WARNING_0013 = 'W0013';
    const WARNING_0014 = 'W0014';
    const WARNING_0015 = 'W0015';
    const WARNING_0016 = 'W0016';
    const WARNING_0017 = 'W0017';
    const WARNING_0018 = 'W0018';
    const WARNING_0019 = 'W0019';
    const WARNING_0020 = 'W0020';
    const WARNING_0021 = 'W0021';

    //定义校验类型值
    const EMAIL = 'email'; //验证不能为空
    const IP = 'ip'; //验证不能为空
    const NOT_EMPTY = 'require';//验证不能为空
    const PHONE = 'phone';//验证手机号
    const QQ = 'qq';
    const TEL = 'tel';//验证度座机号
    const ACCOUNT = 'account';//验证账号最短长度及最大长度
    const NUMBER = 'number';//检测是否为number
    const URL = 'url';//检测网站路径地址
    const ZIP = 'zip';//检测邮编
    const INT = 'integer';//检测是否为integer
    const DOUBLE = 'double';//检测double
    const ENGLISH = 'english';//检测邮编

    //定义校验类型值 扩展
    const INT_TINY = 'tinyint';
    const INT_SMALL = 'smallint';

    //定义关键值下标
    const SUBSCRIPT_KEY    = "_k"; //参数加密验证key
    const SUBSCRIPT_TOKEN  = "_t"; //token值
    const SUBSCRIPT_CHECK  = "_c"; //用户控制相同token能够使用的次数

    //Token值标签
    const USER              = '_u'; //用户ID
    const COMPANY           = '_c'; //公司ID
    const DEVICE            = '_d'; //设备ID
    const OS                = '_o'; //设备ID
    const VERSION          = '_v'; //版本号ID
    const EXPIRATION       = '_e'; //过期时间
    const RANDOM            = '_r'; //随机数

    //token加密
    const CHECK_TOKEN      = '@%34YGYj23w&(*2e89r888';

    //接口信息
    private static $receive = null; //接收参数
    private static $send = null; //返回结果

    //ToKen信息
    protected static $token = null;  //ToKen信息

    //请求信息
    private static $action = null; //控制器方法名信息

    //用户信息
    protected static $uid  = null; //用户ID
    protected static $cid  = null; //公司ID
    protected static $vid  = null; //版本ID
    protected static $info = null; //用户详情

    protected static $call_controller = null; //调用控制器名称
    protected static $call_action = null; //调用方法名称



    public function __construct($param = null)
    {
        //判断是否为跨模块调用
        defined('CALL_SOURCE') or define('CALL_SOURCE','');

        //判断是否为跨模块调用
        if(!empty(CALL_SOURCE)){
            if(isset($param['controller']) && isset($param['action'])){
                self::$call_controller = $param['controller'];
                self::$call_action = $param['action'];
                self::$uid = $param['uid'];

            }
        }

        parent::__construct(request());
    }

    /**
     * @功能：构造函数
     * @开发者： 陈旭林
     */
    public function _initialize(){
        //初期化返回值
        self::init();

        //小程序版本
        self::$vid = self::get_data('version');

        //登录token
        $token = self::get_data('token');
        if(!empty($token)){
            self::$token = $token;
            $uid = ITokenCache::getUserId(self::$token);
            if(!empty($uid)){
                self::$uid = $uid;
            }
        }

        if(empty(self::$uid)){
            $uid = session('uid');
            if(!empty($uid)){
                self::$uid = $uid;
            }
        }

        //判断用户是否登录
        if(self::$action != "login:login" && empty(self::$uid)){
//            $data['cookie'] = $_COOKIE;
//            $data['session'] = $_SESSION;
//            self::set_values($data);
//            $data['module'] = request()->module();
//            $data['controller'] = self::$call_controller;
//            $data['action'] = self::$call_action;
//            $data['error'] = '未登录'.'-'.self::$token;
//            IDb::getInstance('com_database')->setDbData($data)->add();
            self::set_code(self::ERR_0001);
            self::set_msg('未登录');
            self::send();
        }else{
//            $data['module'] = request()->module();
//            $data['controller'] = self::$call_controller;
//            $data['action'] = self::$call_action;
//            $data['error'] = self::$uid.'-'.self::$token;
//            IDb::getInstance('com_database')->setDbData($data)->add();
        }
        if (!empty(self::$uid)){
            $where['user_id'] = self::$uid;
            $user_info = IDb::getInstance('user')->setDbWhere($where)->row();
            if (empty($user_info)){
                self::set_code(self::ERR_0002);
                self::set_msg('用户不存在');
                self::send();
            }else if (self::$action != "login:login" &&$user_info['is_enable']==IsUserEnable::No){
                self::set_code(self::ERR_0003);
                self::set_msg('没有访问权限');
                self::send();
            }
        }


    }


    /**
     * @功能：构造函数
     * @开发者： 陈旭林
     */
    private static function init(){
        //取得控制器及方法名
        if(empty(self::$call_controller)){
            self::$call_controller = strtolower(Loader::parseName(Request::instance()->controller()));
            self::$call_action = strtolower(Loader::parseName(Request::instance()->action()));
        }

        //取得控制器及方法名
        self::$action = strtolower(self::$call_controller.":".self::$call_action);

        //初期化返回值
        self::$send['_code'] = self::SUCCESS;
        self::$send['_msg'] = "操作成功！";
    }

    protected static function get_info($uid = null,$field=null){
        if(empty($uid)){
            $uid = self::$uid;
        }
        $user_info = IUserCache::getInfo($uid,$field);
        if(empty($user_info)){
            return false;
        }

        return $user_info;
    }

    /**
     * @功能:获取接口参数
     * @作者: 陈旭林、陈英杰
     * @param string $key 取得参数
     * @param string $check 数据验证类型
     * @param string $message 错误提示信息
     * @return string
     */
    protected static function get_data($key,$check = null,$message = null){
        //判断数据是否存在
        $data = null;
        if(isset($_REQUEST[$key])) {
            $data = $_REQUEST[$key];
        }

        //判断参数是否正确
        if(!empty($check) && empty($message)){
            self::set_code(self::ERR_CHECK_0008);
            self::set_msg("取得参数方法参数错误！");
            self::send();
        }

        //判断是否需要检查
        if (!empty($check)) {
            //初期化
            $flag = true;

            //检测类型判断
            switch ($check) {
                case self::NOT_EMPTY:
                    $flag = (bool)preg_match('/.+/', $data);
                    break;
                case self::EMAIL:
                    $flag = (bool)preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $data);
                    break;
                case self::PHONE:
                    $flag = (bool)preg_match('/^1[0-9]{10}$/', $data);
                    break;
                case self::TEL:
                    $flag = (bool)preg_match('/^((\+86)|(86))?(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/', $data);
                    break;
                case self::QQ:
                    $flag = (bool)preg_match('/^[1-9][0-9]{4,}$/', $data);
                    break;
                case self::NUMBER:
                    $flag = (bool)preg_match('/^\d+$/', $data);
                    break;
                case self::URL:
                    $flag = (bool)preg_match('/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/', $data);
                    break;
                case self::ZIP:
                    $flag = (bool)preg_match('/^\d{6}$/', $data);
                    break;
                case self::INT:
                    $flag = (bool)preg_match('/^[-\+]?\d+$/', $data);
                    break;
                case self::DOUBLE:
                    $flag = (bool)preg_match('/^[-\+]?\d+(\.\d+)?$/', $data);
                    break;
                case self::ENGLISH:
                    $flag = (bool)preg_match('/^[A-Za-z]+$/', $data);
                    break;
            }

            //判断是否有异常
            if ($flag == false) {
                if(!empty($message)) {
                    self::set_code(self::ERR_CHECK_0009);
                    self::set_msg("{$message}");
                    self::send();
                }else{
                    self::set_code(self::ERR_CHECK_0009);
                    self::set_msg("参数[{$key}]检测错误！");
                    self::send();
                }
            }
        }

        //返回结果
        return $data;
    }

    /**
     * @功能:获取接口int参数
     * @作者: gys
     * @param string $data 取得参数
     * @param string $check 数据验证类型
     * @param string $field_name 字段名
     * @return null|bool
     */
    protected static function data_filter($data,$check = null,$field_name = null){
        if(empty($data)){
            return null;
        }

        $message_add = '不正确';

        //检测类型判断
        $flag = true;
        switch ($check) {
            case self::INT:
                if($data > 2147483647){
                    $flag = false;
                    $message_add = '数值过大';
                }else if($data < -2147483648){
                    $flag = false;
                    $message_add = '数值过小';
                }else{
                    $data = intval($data);
                }
                break;
            case self::INT_TINY:
                if($data > 127){
                    $flag = false;
                    $message_add = '数值过大';
                }else if($data < -128){
                    $flag = false;
                    $message_add = '数值过小';
                }else{
                    $data = intval($data);
                }
                break;
            case self::INT_SMALL:
                if($data > 32767){
                    $flag = false;
                    $message_add = '数值过大';
                }else if($data < -32768){
                    $flag = false;
                    $message_add = '数值过小';
                }else{
                    $data = intval($data);
                }
                break;
            case self::DOUBLE:
                if($data > 1000000000){
                    $flag = false;
                    $message_add = '数值过大';
                }else if($data < -1000000000){
                    $flag = false;
                    $message_add = '数值过小';
                }else{
                    $data = floatval($data);
                }
                break;
        }

        //判断是否有异常
        if ($flag == false) {
            self::set_code(self::WARNING_0001);
            self::set_msg($field_name.$message_add);
            self::send();
        }

        return $data;
    }

    /**
     * @功能：设置返回CODE
     * @param string $val 值
     * @开发者： 陈旭林
     */
    protected static function set_code($val){
        self::$send['_code'] = $val;
    }

    /**
     * @功能：设置返回提示信息
     * @param string $val 值
     * @开发者： 陈旭林
     */
    protected static function set_msg($val){
        self::$send['_msg'] = $val;

        //判断是否生产key
        if(!empty(self::$receive['_k'])) {
            self::$send['_k'] = json_encode(self::$receive['_k']);
        }
    }

    /**
     * @功能：设置返回列表记录信息
     * @param string $key key
     * @param array $val 值
     * @开发者： 陈旭林
     */
    protected static function set_value($key, $val){
        self::$send[$key] = $val;
    }

    /**
     * @功能：答应sql文信息
     * @开发者： 陈旭林
     */
    protected static function sql(){
        self::$send['sql'][] = IDb::getDbLastSql();
        self::$send['sql'][] = IDb::getDbError();
    }

    /**
     * @功能：设置返回信息
     * @param string $values 值
     * @开发者： 陈旭林
     */
    protected static function set_values($values){
        //判断值是否为空
        if(is_array($values)){
            self::$send = array_merge(self::$send,$values);
        }
    }

    /**
     * @功能：发送请求
     * @开发者： 陈旭林
     */
    protected static function send(){
        //数据过滤
        array_walk_recursive(self::$send,function(&$value){
            //判断是否为空
            if(null === $value){
                $value = "";
            }
        });

        if(!empty(CALL_SOURCE)){
            exit(print_r(self::$send, true));
        }else{
            //输出结果
            exit(json_encode(self::$send));
        }

    }
}