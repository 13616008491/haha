<?php
namespace app\admin\controller;

use app\api\ext\IApiConfig;
use app\common\controller\AdminBaseController;
use app\common\ext\IRequest;
use think\Config;
use think\Loader;

class Api extends AdminBaseController {
    //token加密
    const CHECK_TOKEN      = '@%34YGYj23w&(*2e89r888';

    /**
     * @功能：接口测试
     * @开发者：cxl
     * @return string
     */
    public function index(){
        //整理API列表信息
        $api_list = self::api_list();

        //渲染画面
        $this->assign('data', 'aaa');
        $this->assign('api_list', $api_list['api']);
        $this->assign('api_param', json_encode($api_list['param']));

        //模板输出
        return view('index');
    }

    /**
     * @功能：取得API列表
     * @return bool
     */
    public function api_call(){
        //接收参数
        $api_url = IRequest::get("api_url"); //地址
        $controller = IRequest::get("api_controller"); //取得API的控制器名称
        $action = IRequest::get("api_action"); //取得API的方法名称
        $uid = IRequest::get("_u"); //用户

        //初期化返回值
        $api_json=null;
        $api_json['status'] = "OK";
        $api_json['info'] = "操作成功！";
        //判断是否为取得token
        $data = $_POST;
        unset($data['api_url'],$data['api_controller'],$data['api_action']);

        //请求参数
        $api_json['parameter'] = http_build_query($data);
        $api_json['request'] = $data;

        $token = IRequest::get('token');

        //设置全局变量
        define('CALL_SOURCE','web');
//        Session::set('call_param')

//        if(empty($token)){
//            IRequest::set('device_no',md5('mini'.StringUtil::randString(32)));
//            IRequest::set('os',Source::Mini);
//            IRequest::set('version','1.0.0');
//            Session::set("api_not_auth",true);
//            $even = controller("credit/system");
//            $token_action = 'get_init_token';
//
//            //判断方法是否存在
//            if(!method_exists($even,$token_action)){
//                $this->error("亲,你迷路了");
//                return false;
//            }
//
//            $token = $even->$token_action();
//
//            Session::set("api_not_auth",false);
//        }

        //实例化控制器
        $controller_name = Loader::parseClass('api','controller',$controller);
//        $controller_name = "\\app\\card\\controller\\{$controller}";
        if(!class_exists($controller_name)){
            $this->set_error("亲,你迷路了.$controller_name");
            return false;
        }


        $even = new $controller_name([
            'uid'=>$uid,
            'controller'=>$controller,
            'action'=>$action
        ]);

        //判断方法是否存在
        if(!method_exists($even,$action)){
            $this->set_error("亲,你迷路了!");
            return false;
        }

        //调用方法名
        return $even->$action();
    }

    private function set_error($info = '请求失败'){
        $api_json['status'] = 'NG';
        $api_json['info'] = $info;
        echo json_encode($api_json);
        exit;
    }

    /**
     * @功能：取得API列表
     * @return bool
     */
    private static function api_list(){
        //取得配置信息
        $api_list = array();

        //判断APP类型，取得接口列表
        $api_temp = IApiConfig::$api;

        //循环整理API列表信息
        foreach($api_temp as $item=>$value){
            //设置控制器及方法名
            list($api_controller,$api_action) = explode("/",$item);

            //取得IP地址
            $ip = Config::get("domain");

            //保存数据
            $api_list['api'][$value['api_group']][$item] = $value;
            $api_list['api'][$value['api_group']][$item]['api_url'] = url($item,'',true,true);
//            $api_list['api'][$value['api_group']][$item]['api_url'] = "http://{$ip}/api/{$item}.html";
            $api_list['api'][$value['api_group']][$item]['api_controller'] = $api_controller;
            $api_list['api'][$value['api_group']][$item]['api_action'] = $api_action;

            //参数数据
            $api_list['param'][$item] = $value['api_param'];
        }

        //放回接口列表信息
        return $api_list;
    }

}
