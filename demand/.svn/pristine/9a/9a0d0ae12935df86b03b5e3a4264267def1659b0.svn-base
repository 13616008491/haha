<?php


/**
 * Created by PhpStorm.
 * User: zengwenjie
 * Date: 2018/12/5
 * Time: 15:48
 */
namespace app\api\controller;
use app\common\controller\DemandBaseController;
class Totol extends  DemandBaseController{
    /**
     * @功能：取得项目列表接口
     * @开发者：曾文杰
     */
    public function get_totol_url()
    {
        $user_id = self::$uid;
        //返回值
        self::set_code(self::SUCCESS);
        self::set_msg("成功！");
        self::set_value("totol_url", 'https://'.$_SERVER['HTTP_HOST'].'/index/total/index/user_id/'.$user_id.'.html');
        self::send();
    }
}