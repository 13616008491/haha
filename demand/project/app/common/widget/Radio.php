<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：RadioWidget.class.php
 * @类功能: Radio控件封装
 * @开发者: zc
 * @开发时间： 15-10-28
 * @版本：version 1.0
 */
namespace app\common\widget;

use app\common\enum\HtmlEnumValue;
use think\Controller;

class Radio extends Controller{

    //静态变量
    private static $html_cache = null;

    /**
     * @功能：单选框设置
     * {:W('Common/Radio/radio',array("input_name","sex",$value))}
     * {:W('Common/Radio/radio',array("input_name",array($data,"value","name"),$value))}
     * @param $input_name string 单选框名称
     * @param $data string 单选框数据有2种方式：
     *               1、通过传输HtmlEnumValue的key找到相应的值，
     *               2、传输数据集array(
     *                                   $data_list=>array():数据集数组，
     *                                   $data_value_filed="id"；值字段名称，
     *                                   $data_name_filed=>"名称字段名称"
     *                                  )
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return mixed
     */
    public static function radio($input_name,$data,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //判断参数是否正确
        if(empty($input_name) || empty($data)){
            return false;
        }else{
            $input_name = trim($input_name);
        }

        //增量控件ID
        $input_id = str_ireplace("[","_",str_replace("]","",$input_name));

        //初期化变量
        $property = null;
        $validate = null;
//        $disabled = null;
        if($required)$validate=" validate='{required:true}'";
        if($class) $property.=" class='{$class}'";
        if($style){$property.=" style='margin-right:15px;{$style}'";}else{$property.=" style='margin-right:15px;'";}
        if($disabled) $disabled=" disabled='disabled'";

        //判断第二次参数类型
        $html = array();
        if(is_string($data)){
            //取得值列表
            if(empty(self::$html_cache['enum_value'][$data])){
                //取得中文名字及颜色
                self::$html_cache['enum_value'][$data] = HtmlEnumValue::$enum_value[$data];
            }

            //取得单项的值
            $data = self::$html_cache['enum_value'][$data];

            //循环赋值
            foreach($data as $key=>$val){
                //判断默认值
                if(empty($default) || $key != $default){
                    array_push($html,"<label {$property}><input name='{$input_name}' node='{$input_id}' type='radio' value='{$key}' {$disabled} {$validate}/>{$val}</label>");
                }else{
                    array_push($html,"<label {$property}><input name='{$input_name}' node='{$input_id}' type='radio' value='{$key}' checked='checked' {$disabled} {$validate}/>{$val}</label>");
                }
            }
        }else{
            //判断值是否为空；
            if(is_array($data)){
                //拆分数组
                list($data_list,$key,$val) = $data;

                //循环赋值
                foreach($data_list as $item){
                    //判断默认值
                    if(empty($default) || $item[$key] != $default){
                        array_push($html,"<label {$property}><input name='{$input_name}' node='{$input_id}' type='radio' value='{$item[$key]}' {$disabled} {$validate}/>{$item[$val]}</label>");
                    }else{
                        array_push($html,"<label {$property}><input name='{$input_name}' node='{$input_id}' type='radio' value='{$item[$key]}' checked='checked' {$disabled} {$validate}/>{$item[$val]}</label>");
                    }
                }
            }
        }

        //增加必填图标
        if($required){
            array_push($html,"<label for='{$input_name}' generated='true' class='error'>*</label>");
        }else{
            array_push($html,"<label for='{$input_name}' generated='true' class='error'></label>");
        }

        //判断数据是否存在
        return implode("",$html);
    }
}