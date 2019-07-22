<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：InputWidget.class.php
 * @类功能: Input控件封装
 * @开发者: zc
 * @开发时间： 15-10-28
 * @版本：version 1.0
 */
namespace app\common\widget;

use think\Controller;

class Input extends Controller{
    //静态变量
    private static $input_update = false;

    /**
     * @功能：生成输入框Html
     * {:W('Common/Input/input',array('input_name','2015-10-25 12:12:12'))}
     * @param $input_name string 输入框名称
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return string html
     */
    public static function input($input_name,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //设置默认值
        $html = array();
        if(empty($class)) $class="tab_text border_input";
        if(empty($style)) $style="width:250px;";

        //初期化变量
        $property = null;
        if($input_name) $property.= " name='{$input_name}'";
        if($class) $property.=" class='{$class}'";
        if($style) $property.=" style='{$style}'";
        if($disabled) $property.=" disabled='disabled'";
        if($required) $property.=" validate='{required:true}'";

        //取得字符串长度
        array_push($html,"<input {$property} type='text' value='{$default}' placeholder='请输入...'/>");

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
     * @功能：生成密码输入框Html
     * {:W('Common/Input/password',array('input_name','2015-10-25 12:12:12'))}
     * @param $input_name string 输入框名称
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return string html
     */
    public static function password($input_name,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //设置默认值
        $html = array();
        if(empty($class)) $class="tab_text border_input";
        if(empty($style)) $style="width:250px;";

        //初期化变量
        $property = null;
        if($input_name) $property.= " name='{$input_name}'";
        if($class) $property.=" class='{$class}'";
        if($style) $property.=" style='{$style}'";
        if($disabled) $property.=" disabled='disabled'";
        if($required) $property.=" validate='{required:true}'";

        //取得字符串长度
        array_push($html,"<input {$property} type='password' value='{$default}' placeholder='请输入密码...'/>");

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
     * @功能：生成整型输入框Html
     * {:W('Common/Input/int',array('input_name','2015-10-25 12:12:12'))}
     * @param $input_name string 输入框名称
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return string html
     */
    public static function int($input_name,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //设置默认值
        $html = array();
        if(empty($class)) $class="tab_text border_input";
        if(empty($style)) $style="width:250px;";

        //初期化变量
        $property = null;
        if($input_name) $property.= " name='{$input_name}'";
        if($class) $property.=" class='{$class}'";
        if($style) $property.=" style='{$style}'";
        if($disabled) $property.=" disabled='disabled'";
        if($required) {$property.=" validate='{digits:true,required:true}'";}else{$property.=" validate='{digits:true}'";}

        //取得字符串长度
        array_push($html,"<input {$property} type='number' value='{$default}' placeholder='请输入整数...'/>");

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
     * @功能：生成数量输入框Html
     * {:W('Common/Input/number',array('input_name','2015-10-25 12:12:12'))}
     * @param $input_name string 输入框名称
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return string html
     */
    public static function number($input_name,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //设置默认值
        $html = array();
        if(empty($class)) $class="tab_text border_input";
        if(empty($style)) $style="width:250px;";

        //初期化变量
        $property = null;
        if($input_name) $property.= " name='{$input_name}'";
        if($class) $property.=" class='{$class}'";
        if($style) $property.=" style='{$style}'";
        if($disabled) $property.=" disabled='disabled'";
        if($required) {$property.=" validate='{digits:true,required:true}'";}else{$property.=" validate='{digits:true}'";}

        //取得字符串长度
        array_push($html,"<input {$property} type='number' min='0' value='{$default}' placeholder='请输入数量...'/>");

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
     * @功能：生成浮点型输入框Html
     * {:W('Common/Input/float',array('input_name','1'))}
     * @param $input_name string 输入框名称
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return string html
     */
    public static function float($input_name,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //设置默认值
        $html = array();
        if(empty($class)) $class="tab_text border_input";
        if(empty($style)) $style="width:250px;";

        //初期化变量
        $property = null;
        if($input_name) $property.= " name='{$input_name}'";
        if($class) $property.=" class='{$class}'";
        if($style) $property.=" style='{$style}'";
        if($disabled) $property.=" disabled='disabled'";
        if($required) {$property.=" validate='{number:true,required:true}'";}else{$property.=" validate='{number:true}'";}

        //取得字符串长度
        array_push($html,"<input {$property} type='text' value='{$default}' placeholder='请输入浮点数...'/>");

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
     * @功能：自动提交数据款
     * @param $url string 提交地址
     * @param $item string 参数ID
     * @param $default string 提交地址
     * @param $style string CSS内容
     * @开发者：陈旭林
     * @return string
     */
    public static function update($url,$item,$default=null,$style=null) {
        //设置默认值
        $html = array();
        if(empty($class)) $class="_input_update tab_text";
        if(empty($style)) $style="width:95%;height:95%;background-color:#ffffff;border: 1px solid #ddd;";

        //判断地址是否存在
        if(empty($url) || empty($item)){
            return false;
        }else{
            $url = Url($url);
        }

        //加载JS
        if(!self::$input_update){
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/Input/input_update.js"></script>');
            self::$input_update = true;
        }

        //初期化变量
        $property = null;
        $property.= " url='{$url}'";
        $property.= " item='{$item}'";
        $property.= " class='{$class}'";
        $property.= " style='{$style}'";

        //取得字符串长度
        if($default) {
            array_push($html, "<input {$property} type='text' value='{$default}' placeholder='输入后，回车提交......'/>");
        }else{
            array_push($html, "<input {$property} type='text' value='' placeholder='输入后，回车提交......'/>");
        }

        //判断数据是否存在
        return implode("",$html);
    }
}