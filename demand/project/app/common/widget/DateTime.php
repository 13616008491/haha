<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：Widgets.class.php
 * @类功能: 小部件
 * @开发者: 陈旭林
 * @开发时间： 2017-09-01
 * @版本：version 1.0
 */
namespace app\common\widget;

use think\Controller;

class DateTime extends Controller{
    //静态变量
    private static $date_time = false;

    /**
     * @功能：生成日期输入框Html
     * {:W('Common/Date/date',array('input_name','2015-10-10'))}
     * @param $input_name string 输入框名称
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return string html
     */
    public function date($input_name,$default=null,$required=false,$disabled=false,$style=null,$class=null){
        //设置默认值
        $html=array();
        if(empty($style)) $style="width:250px;";

        //加载JS
        if(!self::$date_time){
            array_push($html,'<link rel="stylesheet" href="__STATIC__/common/plugins/My97DatePicker/skin/WdatePicker.css"/>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/My97DatePicker/WdatePicker.js"></script>');
            self::$date_time = true;
        }

        //初期化变量
        $property = null;
        if($input_name) $property.= " name='{$input_name}'";
        if($required) $property.=" validate='{required:true}'";
        if($style) $property.=" style='{$style}'";
        if($disabled) $property.=" disabled='disabled'";

        //取得字符串长度
        array_push($html,"<input {$property} type='text' class='sertext Wdate {$class}' value='{$default}' onfocus='WdatePicker({ dateFmt:\"yyyy-MM-dd\",isShowToday: false, isShowClear: false });' placeholder='请输入日期...'/>");

        //判断是否必填
        if($required){
            array_push($html,"<label for='{$input_name}' generated='true' class='error'>*</label>");
        }else{
            array_push($html,"<label for='{$input_name}' generated='true' class='error'></label>");
        }

        //判断数据是否存在
        return implode("",$html);
    }

    /**
     * @功能：生成时间输入框Html
     * {:W('Common/Date/time',array('input_name','12:12:12'))}
     * @param $input_name string 输入框名称
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return string html
     */
    public static function time($input_name,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //设置默认值
        $html=array();
        if(empty($style)) $style="width:250px;";

        //加载JS
        if(!self::$date_time){
            array_push($html,'<link rel="stylesheet" href="__STATIC__/common/plugins/My97DatePicker/skin/WdatePicker.css"/>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/My97DatePicker/WdatePicker.js"></script>');
            self::$date_time = true;
        }

        //初期化变量
        $property = null;
        if($input_name) $property.= " name='{$input_name}'";
        if($required) $property.=" validate='{required:true}'";
        if($style) $property.=" style='{$style}'";
        if($disabled) $property.=" disabled='disabled'";

        //取得字符串长度
        array_push($html,"<input {$property} type='text' class='sertext Wdate {$class}' value='{$default}' onfocus='WdatePicker({ dateFmt:\"HH:mm:ss\",isShowToday: false, isShowClear: false });' placeholder='请输入时间...'/>");

        //判断是否必填
        if($required){
            array_push($html,"<label for='{$input_name}' generated='true' class='error'>*</label>");
        }else{
            array_push($html,"<label for='{$input_name}' generated='true' class='error'></label>");
        }

        //判断数据是否存在
        return implode("",$html);
    }

    /**
     * @功能：生成日期时间输入框Html
     * {:W('Common/Date/datetime',array('input_name','2015-10-25 12:12:12'))}
     * @param $input_name string 输入框名称
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return string html
     */
    public static function datetime($input_name,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //设置默认值
        $html=array();
        if(empty($style)) $style="width:250px;";

        //加载JS
        if(!self::$date_time){
            array_push($html,'<link rel="stylesheet" href="__STATIC__/common/plugins/My97DatePicker/skin/WdatePicker.css"/>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/My97DatePicker/WdatePicker.js"></script>');
            self::$date_time = true;
        }

        //初期化变量
        $property = null;
        if($input_name) $property.= " name='{$input_name}'";
        if($required) $property.=" validate='{required:true}'";
        if($style) $property.=" style='{$style}'";
        if($disabled) $property.=" disabled='disabled'";

        //取得字符串长度
        array_push($html,"<input {$property} type='text' class='sertext Wdate {$class}' value='{$default}' onfocus='WdatePicker({ dateFmt:\"yyyy-MM-dd HH:mm:ss\",isShowToday: false, isShowClear: false });' placeholder='请输入日期时间...'/>");

        //判断是否必填
        if($required){
            array_push($html,"<label for='{$input_name}' generated='true' class='error'>*</label>");
        }else{
            array_push($html,"<label for='{$input_name}' generated='true' class='error'></label>");
        }

        //判断数据是否存在
        return implode("",$html);
    }
}
