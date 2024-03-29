<?php
/**
 * @开发工具：PhpStorm.
 * @文件名：ModuleInit.class.php
 * @功能： 控制器开始标签位（生命周期）
 * @开发者：zc
 * @开发时间： 14-11-25 上午9:57
 * @版本：version 1.0
 */
namespace app\common\behavior;

use think\Request;

final class ModuleInit {

    /**
     * @功能：控制器开始标签位执行入口
     * @param mixed $params 引用参数
     * @开发者： cxl
     * @return bool
     */
    public function run(&$params){
        //取得变量
        $request = Request::instance()->request();
        $param = Request::instance()->param();

        //判断是否需要合并数据
        if(is_array($param)) {
            //合并数据
            $request = array_merge($request, $param);

            //设置环境变量
            Request::instance()->request($request);
        }

        //取得模块信息
        $module = Request::instance()->module();

        //判断模块是否存在
        if (!in_array(strtolower($module), array("admin", "agent", "api", "task","index"))) {
            exit("Module信息错误！");
        }

        //返回值
        return true;
    }
}