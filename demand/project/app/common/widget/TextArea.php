<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：SelectWidget.class.php
 * @类功能: Select控件封装
 * @开发者: zc
 * @开发时间： 15-10-28
 * @版本：version 1.0
 */
namespace app\common\widget;

use think\Controller;

class TextArea extends Controller{

    //静态变量
    private static $text_area = false;

    /**
     * @功能：文本输入域设置
     * @param $input_name string 文本输入域名称
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return mixed
     */
    public static function text_area($input_name,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //设置默认值
        $html = array();
        if(empty($style)) $style="width:650px;font-size:12px;";
        if(empty($class)) $class="tab_text border_input";

        //初期化变量
        $property = null;
        if($input_name) $property.= " name='{$input_name}'";
        if($class) $property.=" class='{$class}'";
        if($style) $property.=" style='{$style}'";
        if($required) $property.=" validate='{required:true}'";
        if($disabled) $property.=" disabled='disabled'";

        //取得字符串长度
        array_push($html,"<textarea {$property} type='text' placeholder='请输入...'>{$default}</textarea>");

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
     * @功能：富文本输入域设置
     * {:W('Common/Input/text_area',array('input_name','2015-10-25 12:12:12'))}
     * @param $input_name string 文本输入域名称
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return mixed
     */
    public static function text_area_html($input_name,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //设置默认值
        $html = array();
        if(empty($style)) $style="width:100%;font-size:12px;";
        if(empty($class)) $class="tab_text ";

        //增量缓存数据名字
        $cache_name = str_ireplace("[","_",str_ireplace("]","",$input_name));

        //初期化变量
        $property = null;
        if($input_name) $property.= " name='{$input_name}' id='{$cache_name}'";
        if($class) $property.=" class='{$class}'";
        if($style) $property.=" style='{$style}'";
        if($required) $property.=" validate='{required:true}'";
        if($disabled) $property.=" disabled='disabled'";

        //判断是否加载JS
        if(!self::$text_area && !$disabled){
            array_push($html,'<link href="__STATIC__/common/plugins/ueditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/ueditor/third-party/template.min.js"></script>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/ueditor/umeditor.config.js"></script>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/ueditor/umeditor.js"></script>');
            self::$text_area = true;
        }

        //取得字符串长度
        if($disabled){
            array_push($html,"<script type='text/plain' {$property}></script>");
        }else{
            $default = stripslashes($default);
            $default = htmlspecialchars_decode($default);
            array_push($html,"<script type='text/plain' {$property}>{$default}</script>");
        }

        //实例化编辑器JS方法
        if(!$disabled){
            array_push($html,"<script language='javascript'>");
            array_push($html,"var um = UM.getEditor('{$input_name}');");
            array_push($html,"</script>");
        }

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