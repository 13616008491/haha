<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：FileWidget.class.php
 * @类功能: 文件上传控件封装
 * @开发者: zc
 * @开发时间： 15-10-28
 * @版本：version 1.0
 */
namespace app\common\widget;

use app\common\cache\bank\IBankCache;
use think\Controller;

class Bank extends Controller{
    /**
     * @功能：下拉框设置
     * {:W('Common/Select/select',array('input_name',"check_show",$property_check_type,null,"width:120px;"))}
     * {:W('Common/Select/select',array('input_name',array($data,"value","name"),$value,null,"width:120px;"))}
     * @param $input_name string 下拉框名称
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return mixed
     */
    public static function select($input_name,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //取得银行数据
        $bank_list = IBankCache::getList();

        //整理数据
        if(!is_array($bank_list) && count($bank_list) <= 0){
            $bank_list = array();
        }

        //返回值
        return Select::select($input_name,array($bank_list,"bank_id","bank_name"),$default,$required,$disabled,$style,$class);
    }
}