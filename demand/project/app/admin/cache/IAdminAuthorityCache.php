<?php
 /**
  * @开发工具：JetBrains PhpStorm.
  * @文件名：IAuthorityCache.class.php
  * @功能：后台权限缓存类
  * @开发者：陈旭林
  * @开发时间： 15-5-22 下午2:02
  * @版本：version 1.0
  */
namespace app\admin\cache;

use app\common\enum\AdminSuper;
use app\common\ext\IDb;
use app\common\ext\ITree;
use think\Cache;

class IAdminAuthorityCache{

    /**
     * @功能：取得公司角色方法
     * @param int $role_id 角色ID
     * @开发者： 陈旭林
     * @return array
     */
    public static function getAuthorityModel($role_id){
        //取得权限角色节点  
        $role_node = self::role_node($role_id);

        //取得权限角色菜单缓存
        $node_model = self::node_model();

        //整理数据
        $role_model = array();
        foreach ($role_node as $node) {
            $role_model[] = $node_model[$node];
        }

        //返回值
        return $role_model;
    }

    /**
     * @功能：取得公司角色权限菜单缓存
     * @param string $mode 模型名称
     * @param int $role_id 角色ID
     * @开发者： 陈旭林
     * @return array
     */
    public static function getAuthorityMenu($mode,$role_id=null){
        //取得权限角色菜单
        $role_menu = self::role_menu($role_id);

        //取得公司角色节点菜单
        $node_menu = self::node_menu($role_id);

        //取得公司角色节点方法
        $node_model = self::node_model();
        if(!is_array($node_model)){
            return false;
        }

        //取得权限菜单树
        $role_menu_tree = new ITree();
        $role_menu_tree->set($role_menu,"menu_id");
        $role_menu_data = $role_menu_tree->get_tree(0);

        //取得上层节点
        if(empty($node_menu[$mode])){
            return false;
        }

        //取得节点值
        $parent_node = $role_menu_tree->get_parents_id($node_menu[$mode]);
        if(empty($parent_node[1]) || empty($parent_node[2])){
            return false;
        }

        //判断是否为数组
        $menu = array();
        $_menu2 = array();
        if(is_array($role_menu_data)) {
            //一级菜单
            foreach ($role_menu_data as $_menu) {
                $menu[1][$_menu['menu_id']] = self::get_action($_menu, 1, $node_model);
            }

            //二级菜单
            foreach ($role_menu_data as $_menu) {
                //判断是否为下一级菜单
                if ($_menu['menu_id'] == $parent_node[1]) {
                    //取得二级菜单信息
                    $_menu2 = (empty($_menu["child"]) ? array() : $_menu["child"]);
                    foreach ($_menu2 as $_menu_temp) {
                        $menu[2][$_menu_temp['menu_id']] = self::get_action($_menu_temp, 2, $node_model);
                    }
                }
            }

            //三级菜单
            foreach ($_menu2 as $_menu) {
                //判断是否为下一级菜单
                if ($_menu['menu_id'] == $parent_node[2]) {
                    //取得二级菜单信息
                    $_menu3 = (empty($_menu["child"]) ? array() : $_menu["child"]);
                    foreach ($_menu3 as $_menu_temp) {
                        $menu[3][$_menu_temp['menu_id']] = self::get_action($_menu_temp, 3, $node_model);
                    }
                }
            }

            //保存数据
            $authority_menu = array("menu"=>$menu,"current"=>$parent_node);
        }else{
            //保存数据
            $authority_menu = array("menu"=>array(),"current"=>array());
        }

        //返回值
        return $authority_menu;
    }

    /**
     * @功能：取得公司角色菜单缓存
     * @param int $role_id 角色ID
     * @开发者： 陈旭林
     * @return array
     */
    private static function role_menu($role_id){
        //查询条件
        $node_where = null;
        $menu_where = "am.is_delete='1' ";

        //判断是否需要取得权限节点
        $admin_super = get_login_admin_super();
        if($admin_super == AdminSuper::Ordinary){
            //取得角色权限
            $admin_role_menu_info = IDb::getInstance("admin_role_menu")->setDbWhere("admin_role_id='{$role_id}'")->row();

            //设置地点及相关节点
            if(!empty($admin_role_menu_info)){
                //取得数据
                $menu = $admin_role_menu_info['menu'];
                $node = $admin_role_menu_info['node'];

                //设置条件
                $node_where = " and find_in_set(amn.node_id,'{$node}')";
                $menu_where .= " and find_in_set(am.menu_id,'{$menu}')";
            }else{
                error("您的角色尚未分配权限！");
            }
        }

        //取得公司角色菜单节点列表
        $admin_menu_list = IDb::getInstance("admin_menu as am")
            ->setDbFiled("am.menu_id,am.name,am.parent_id,am.default_node,am.level,am.order_by,group_concat(amn.node_id) as node_id")
            ->setDbJoin("admin_menu_node as amn","am.menu_id=amn.menu_id {$node_where}","LEFT")
            ->setDbWhere($menu_where)
            ->setDbGroup("am.menu_id")
            ->setDbOrder("am.order_by")
            ->sel();

        //整理数据
        $role_menu = array();
        foreach($admin_menu_list as $menu){
            $role_menu[] = $menu;
        }

        //返回数据
        return $role_menu;
    }

    /**
     * @功能：取得公司角色节点缓存
     * @param int $role_id 角色ID
     * @开发者： 陈旭林
     * @return array
     */
    private static function role_node($role_id){

        //取得权限角色菜单缓存
        $role_menu = self::role_menu($role_id);
        //整理数据
        $role_node = array();
        foreach($role_menu as $menu){
            if(!empty($menu['node_id'])) {
                $role_node = array_unique(array_merge($role_node, explode(",", $menu['node_id'])));
            }
        }

        //数组排序
        sort($role_node);

        //返回数据
        return $role_node;
    }

    /**
     * @功能：取得公司节点菜单缓存
     * @param int $role_id 角色ID
     * @开发者： 陈旭林
     * @return array
     */
    private static function node_menu($role_id){

        //取得权限角色菜单缓存
        $role_menu = self::role_menu($role_id);

        //取得权限角色菜单缓存
        $node_model = self::node_model();

        //整理数据
        $node_menu = array();
        foreach($role_menu as $menu){

            //判断是否存在下级节点
            if(!empty($menu['node_id'])){
                //取得节点数量
                $node_list = explode(",",$menu['node_id']);

                //循环取得节点model
                foreach($node_list as $node_id){
                    //取得节点名字
                    if(!empty($node_model[$node_id])){
                        $node_name = $node_model[$node_id];
                        $node_menu[$node_name] = $menu['menu_id'];
                    }
                }
            }
        }

        //返回数据
        return $node_menu;
    }

    /**
     * @功能：取得公司角色节点方法缓存
     * @开发者： 陈旭林
     * @return array
     */
    private static function node_model(){
        //取得公司角色菜单节点列表
        $admin_node_list = IDb::getInstance("admin_node")->setDbWhere("is_delete=1")->sel();

        //整理数据
        $admin_node = array();
        if(!empty($admin_node_list) && is_array($admin_node_list)) {
            foreach ($admin_node_list as $node) {
                $admin_node[$node['node_id']] = strtoupper("{$node['model_name']}.{$node['action_name']}");
            }
        }

        //返回数据
        return $admin_node;
    }

    /**
     * @功能：菜单信息
     * @param array $menu array 菜单数组
     * @param string $level  菜单层次
     * @param array $node_model  节点数据
     * @开发者： 陈旭林
     * @return string
     */
    private static function get_action($menu,$level,$node_model){

        //根据菜单层级取得默认节点
        $action=null;
        switch($level){
            case 1:
                if(!empty($menu['child']['0']['child']['0']['default_node'])) {
                    $action = $menu['child']['0']['child']['0']['default_node'];
                }
                break;
            case 2:
                if(!empty($menu['child']['0']['default_node'])) {
                    $action = $menu['child']['0']['default_node'];
                }
                break;
            case 3:
                if(!empty($menu['default_node'])) {
                    $action = $menu['default_node'];
                }
                break;
        }

        //判断是否有默认节点
        if(empty($action)){
            $menu['action']='';
        }else{
            //取得菜单请求地址
            if(empty($node_model[$action])){
                $menu['action']='';
            }else{
                $action=strtolower(str_ireplace(".","/",$node_model[$action]));
                $menu['action']=$action;
            }
        }

        //删除多余数据
        unset($menu['child']);
        return $menu;
    }
}