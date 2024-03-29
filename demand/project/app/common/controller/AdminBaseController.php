<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：AdminBaseController.class.php
 * @类功能: Action基类
 * @开发者: 陈旭林
 * @开发时间： 14-10-28
 * @版本：version 1.0
 */
namespace app\common\controller;

use app\admin\cache\IAdminAuthorityCache;
use app\common\enum\AdminSuper;
use app\common\enum\IsRead;
use app\common\ext\IDb;
use think\Cache;
use think\Config;
use think\Loader;
use think\Request;

class AdminBaseController extends CommonController {
    /**
     * @功能：构造函数
     * @开发者： 陈旭林
     */
    public function _initialize(){
        //取得登录用户信息
        $admin_id = get_login_admin_id();
        $admin_role_id = get_login_admin_role_id();
        $admin_super = get_login_admin_super();

        //取得不需要权限的controller名称
        $current_controller = strtolower(Loader::parseName(Request::instance()->controller()));
        $current_action = strtolower(Loader::parseName(Request::instance()->action()));
        $auth_controller = explode(',',strtolower(Config::get("NOT_AUTH_MODULE")));

        //判断是否为超级管理员
        if(!in_array($current_controller,$auth_controller)){
            //判断是否登录
            if(empty($admin_id)){
                $this->redirect("login/login");
            }

            //判断是否为超级管理员
            if($admin_super == AdminSuper::Ordinary){
                //缓存角色的功能数据权限数据
                $role_model = IAdminAuthorityCache::getAuthorityModel($admin_role_id);
                $auth_model = strtoupper($current_controller.".".$current_action);

                //设置不需要权限控制器
                $role_model[] = "INDEX.INDEX";
                $role_model[] = "ADMIN.ADMIN_INFO";
                $role_model[] = "ADMIN.ADMIN_INFO_SUBMIT";
                $role_model[] = "ADMIN.ADMIN_PWD";
                $role_model[] = "ADMIN.ADMIN_PWD_SUBMIT";
                $role_model[] = "SYSTEM.CLEAR_CACHE_ALL";

                //判断是否有权限
                if(!in_array($auth_model,$role_model)){
                    error("您没有操作这个功能的权限！");
                }
            }
        }

        //取得客户未读消息数量
        $notice_where['user_id'] =get_login_user_id();
        $notice_where['notice_read'] = IsRead::No;
        $notice_count = IDb::getInstance('notice')->setDbWhere($notice_where)->con();
        if($notice_count === false){
            $notice_count = 0;
        }

        //设置layout
        $this->layout("layout_menu_all");

        //取得菜单
        $menu_model = strtoupper($current_controller.".".$current_action);
        $role_menu = IAdminAuthorityCache::getAuthorityMenu($menu_model, $admin_role_id);

        //设置配置信息
        Config::set("json",json_encode([]));//设置JSON数据
        Config::set("menu",$role_menu);//设置菜单数据

        //页面赋值
        $this->assign('_notice_count_', $notice_count);
    }

    /**
     * @功能：设置页面layout
     * @param $layout string 模板名称
     * @开发者： 陈旭林
     * @return bool
     */
    protected function layout($layout=null){
        //判断是否设置layout
        if(null === $layout){
            //判断是否为登录
            if(strtolower(Request::instance()->controller()) == "login"){
                //设置模板名称
                $this->view->engine->layout("layout_login");
            }else{
                //设置模板名称
                $this->view->engine->layout("layout_menu_all");
            }
        }else{
            //判断是否需要layout
            if(false === $layout){
                //设置模板是否关闭
                $this->view->engine->layout(false);
            }else{
                //转化大小写
                $layout = strtolower($layout);

                //判断参数值是否正确
                if(in_array($layout,array("layout_login","layout_empty","layout_menu_one","layout_menu_all"))){
                    //设置模板名称
                    $this->view->engine->layout($layout);
                }else{
                    error("页面Layout设置错误！");
                }
            }
        }

        //返回
        return true;
    }

    /**
     * @功能：设置页面json数据
     * @param $key string 键
     * @param $value string 值
     * @开发者： 陈旭林
     */
    protected function json($key,$value){
        //取得原来配置信息
        $json = Config::get("json");

        //判断数据是否存在
        if(empty($json)){
            //添加新数据
            $json[$key] = $value;
        }else {
            //获取旧数据
            $json = json_decode($json, true);

            //添加新数据
            $json[$key] = $value;
        }

        //设置json数据
        Config::set("json",json_encode($json));
    }

    /**
     * @功能：取得缓存名称
     * @param string $where 搜索条件
     * @param string $sort 排序条件
     * @param bool $value 缓存值
     * @开发者： 陈旭林
     * @return string
     */
    protected function cache($where,$sort,$value=false){
        //生产缓存key
        $key = Request::instance()->controller().".".Request::instance()->action()."_".md5(json_encode($where).json_encode($sort));

        //判断是否为清除
        if(is_null($value)){
            return  Cache::rm($key); //清除缓存
        }

        //判断是否取得缓存
        if($value === false){
            return Cache::get($key); //取得缓存
        }else{
            //设置缓存
            return Cache::set($key,$value,3600);
        }
    }

    /**
     * @功能：页面排序
     * @param string $sort 排序条件
     * @param string $callback 回调函数
     * @开发者： 陈旭林
     * @return bool
     */
    protected function sort($sort,$callback){
        //动态整理数据
        if(is_callable($callback)){
            $data = $callback();
        }elseif(is_array($callback)){
            $data = $callback;
        }else{
            return false;
        }

        //取得数组值
        $assign = array_flip($data);
        $assign = array_fill_keys($assign,null);

        //判断默认值是否存在
        if(empty($data['default'])){
            return false;
        }else{
            $default = $data['default'];
        }

        //解析排序参数
        if(empty($sort)) {
            //设置排序条件
            $assign['default'] = $default;

            //设置状态
            $this->assign("sort_class",$assign);

            //放回排序字符串
            return $default;
        }else{
            //拆分排序信息
            list($field, $order) = explode("-", $sort);

            //判断自动是否为空
            if(empty($data[$field])){
                //设置排序条件
                $assign[$field] = $sort;

                //设置状态
                $this->assign("sort_class",$assign);

                //放回排序字符串
                return $default;
            }
        }

        //取得排序方式
        if(empty($order)){
            //设置排序条件
            $assign[$field] = "desc";

            //设置状态
            $this->assign("sort_class",$assign);

            //放回排序字符串
            return "{$data[$field]} desc";
        }else{
            //判断排序方式
            if(empty($order) || $order == "up"){
                //设置排序条件
                $assign[$field] = "down";

                //设置状态
                $this->assign("sort_class",$assign);

                //放回排序字符串
                return "{$data[$field]} desc";
            }else{
                //设置排序条件
                $assign[$field] = "up";

                //设置状态
                $this->assign("sort_class",$assign);

                //放回排序字符串
                return $data[$field];
            }
        }
    }
}