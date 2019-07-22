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

use app\common\enum\HtmlEnumValue;
use think\Controller;

class Select extends Controller{

    //静态变量
    private static $html_cache = null;
    private static $select_group = false;
    private static $select_parent = false;
    private static $select_search = false;

    /**
     * @功能：下拉框设置
     * {:W('Common/Select/select',array('input_name',"check_show",$property_check_type,null,"width:120px;"))}
     * {:W('Common/Select/select',array('input_name',array($data,"value","name"),$value,null,"width:120px;"))}
     * @param $input_name string 下拉框名称
     * @param $data string 下拉框数据有2种方式：
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
    public static function select($input_name,$data,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //默认样式
        if(empty($style)) $style="width:255px;";

        //判断参数是否正确
        if(empty($input_name) || empty($data)){
            return false;
        }

        //增量控件ID
        $input_id = str_ireplace("[","_",str_ireplace("]","",$input_name));

        //初期化变量
        $property = null;
        if($required)$property.=" validate='{required:true}'";
        if($input_name) $property.= " name='{$input_name}' node='{$input_id}'";
        if($class) $property.=" class='{$class}'";
        if($style) $property.=" style='{$style}'";
        if($disabled) $property.=" disabled='disabled'";

        //设置开始符
        $html = array("<select {$property}>");

        //写默认值
        array_push($html,'<option value="">请选择...</option>');

        //判断第二次参数类型
        if(is_string($data)){
            //取得值列表
            if(empty(self::$html_cache['enum_value'][$data])){
                //取得中文名字及颜色
                self::$html_cache['enum_value'][$data] = HtmlEnumValue::$enum_value[$data];
            }

            //取得单项的值
            $data = self::$html_cache['enum_value'][$data];
            if(is_array($data)){
                //循环赋值
                foreach($data as $value=>$name){
                    //判断默认值
                    if(empty($default) || $value != $default){
                        array_push($html,"<option value='{$value}'>{$name}</option>");
                    }else{
                        array_push($html,"<option value='{$value}' selected='selected'>{$name}</option>");
                    }
                }
            }
        }else{
            //判断值是否为空；
            if(is_array($data)){

                //拆分数组
                list($data_list,$value,$name) = $data;

                //循环赋值
                foreach($data_list as $item){
                    //判断字段是否正确
                    if(!isset($item[$value]) || !isset($item[$name])){
                        break;
                    }

                    //判断默认值
                    if(empty($default) || $item[$value] != $default){
                        array_push($html,"<option value='{$item[$value]}'>{$item[$name]}</option>");
                    }else{
                        array_push($html,"<option value='{$item[$value]}' selected='selected'>{$item[$name]}</option>");
                    }
                }
            }
        }

        //写结束符号
        array_push($html,'</select>');

        //增加必填图标
        if($required){
            array_push($html,"<label for='{$input_name}' generated='true' class='error'>*</label>");
        }

        //判断数据是否存在
        return implode("",$html);
    }

    /**
     * @功能：搜索下拉框设置
     * {:W('Common/Select/select_search',array('input_name',array($data,"value","name"),$value))}
     * @param $input_name string 下拉框名称
     * @param $data string 传输数据集array(
     *                                   $data_list=>array():数据集数组，
     *                                   $data_value_filed="id"；值字段名称，
     *                                   $data_name_filed=>"名称字段名称"
     *                                )
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return mixed
     */
    public static function select_search($input_name,$data,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //默认样式
        if(empty($style)) $style="width:257px;";

        //判断参数是否正确
        if(empty($input_name) || empty($data)){
            return false;
        }

        //增量缓存数据名字
        $cache_name = str_ireplace("[","_",str_ireplace("]","",$input_name));
        $input_id = str_ireplace("[","_",str_ireplace("]","",$input_name));
        if($disabled){$input_disabled =" disabled='disabled'";}else{$input_disabled="";}

        //初期化变量
        $property = "size='5'";
        if($required)$property.=" validate='{required:true}'";
        if($input_name) $property.= " name='{$input_name}' node='{$input_id}'";
        if($style) $property.=" style='{$style}'";
        if($disabled) $property.=" disabled='disabled'";

        //设置开始符
        $html=array();
        if(!self::$select_search){
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/SelectSearch/select_search.js"></script>');
            self::$select_search = true;
        }

        //写默认值
        array_push($html,"<input id='{$input_name}' type='text' cache='{$cache_name}' class='__select_search_ tab_text borddd {$class}' $input_disabled value='' placeholder='请搜索...'></br>");
        array_push($html,"<select {$property}>");

        //判断第二次参数类型
        if(is_string($data)){
            //取得值列表
            if(empty(self::$html_cache['enum_value'][$data])){
                //取得中文名字
                self::$html_cache['enum_value'][$data] = HtmlEnumValue::$enum_value[$data];
            }

            //取得单项的值
            $data = self::$html_cache['enum_value'][$data];
            if(is_array($data)){
                //数据转换为json数据
                $data_json = json_encode($data);
                array_push($html,"<script type='text/javascript'>var {$cache_name}=jQuery.parseJSON('{$data_json}');</script>");

                //循环赋值
                foreach($data as $value=>$name){
                    //判断默认值
                    if(empty($default) || $value != $default){
                        array_push($html,"<option value='{$value}'>{$name}</option>");
                    }else{
                        array_push($html,"<option value='{$value}' selected='selected'>{$name}</option>");
                    }
                }
            }
        }else{
            //判断值是否为空；
            if(is_array($data)){

                //拆分数组
                list($data_list,$value,$name) = $data;
                if(!(is_array($data_list) || empty($value) || empty($name))){
                    return false;
                }

                //循环赋值
                $data = array();
                foreach($data_list as $item){
                    //判断字段是否正确
                    if(!isset($item[$value]) || !isset($item[$name])){
                        break;
                    }

                    //判断默认值
                    if(empty($default) || $item[$value] != $default){
                        array_push($html,"<option value='{$item[$value]}'>{$item[$name]}</option>");
                    }else{
                        array_push($html,"<option value='{$item[$value]}' selected='selected'>{$item[$name]}</option>");
                    }

                    //整理JSON数据
                    $data[$item[$value]] = $item[$name];
                }

                //数据转换为json数据
                $data_json = json_encode($data);
                array_push($html,"<script type='text/javascript'>var {$cache_name}=jQuery.parseJSON('{$data_json}');</script>");
            }
        }

        //写结束符号
        array_push($html,'</select>');

        //增加必填图标
        if($required){
            array_push($html,"<label for='{$input_name}' generated='true' class='error'>*</label>");
        }else{
            array_push($html,"<label for='{$input_name}' generated='true' class='error'></label>");
        }

        //判断数据是否存在
        return implode("",$html);
    }

    /**
     * @功能：联动下拉框设置
     * {:W('Common/Select/select_group',array('input_name',array($data,"value","name"),null,$value))}
     * @param $input_name string 下拉框名称
     * @param $data string 传输数据集array(
     *                                   $data_list=>array():数据集数组，
     *                                   $data_value_filed=>"id"；值字段名称，
     *                                   $data_name_filed=>"名称字段名称",
     *                                   $data_parent_filed=>"上层节点字段名称"
     *                                )
     * @param $parent_id int 上层节点ID
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return mixed
     */
    public static function select_group($input_name,$data,$parent_id=0,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //默认样式
        if(empty($style)) $style="width:255px;";

        //判断参数是否正确
        if(empty($input_name) || empty($data)){
            return false;
        }

        //取得数据
        if(is_array($data)){
            //拆分数组
            list($data_list,$value,$name,$parent) = $data;
            if(!(is_array($data_list) || empty($value) || empty($name) || empty($parent))){
                return false;
            }

            //整理数据
            $data = null;
            foreach($data_list as $item){
                $data[$item[$parent]][$item[$value]] = $item[$name];
            }
        }else{
            return false;
        }

        //增量缓存数据名字
        $cache_name = str_ireplace("[","_",str_ireplace("]","",$input_name));

        //初期化变量
        $property = null;
        if($required)$property.=" validate='{required:true}'";
        if($input_name) $property.= " name='{$input_name}[]' cache='{$cache_name}'";
        if($style) $property.=" style='{$style}'";
        if($disabled) $property.=" disabled='disabled'";

        //设置样式
        if($class) {
            $property.=" class='__select_group_'";
        }else{
            $property.=" class='__select_group_ {$class}'";
        }

        //判断是否存在默认值
        if(empty($default)){
            $default = array();
        }

        //判断是否加载JS
        $html = array();
        $data_json = json_encode($data);
        $default_json = json_encode($default);
        array_push($html,"<script type='text/javascript'>var {$cache_name}=jQuery.parseJSON('{$data_json}');var {$cache_name}_default=jQuery.parseJSON('{$default_json}');</script>");
        if(!self::$select_group){
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/SelectGroup/select_group.js"></script>');
            self::$select_group = true;
        }

        //设置开始符
        array_push($html,"<select {$property}>");
        array_push($html,'<option value="">请选择...</option>');

        //判断第二次参数类型
        if(is_array($data)){
            //循环赋值
            foreach($data[$parent_id] as $key=>$val){
                array_push($html,"<option value='{$key}'>{$val}</option>");
            }
        }

        //写结束符号
        array_push($html,'</select>');

        //增加必填图标
        if($required){
            array_push($html,"<label for='{$input_name}[]' generated='true' class='error'>*</label>");
        }else{
            array_push($html,"<label for='{$input_name}[]' generated='true' class='error'></label>");
        }

        //判断数据是否存在
        return implode("",$html);
    }

    /**
     * @功能：联动下拉框设置
     * {:W('Common/Select/select_parent',array('input_name',array($data,"value","name"),input_name,null,"width:120px;"))}
     * @param $input_name string 下拉框名称
     * @param $data string 传输数据集array(
     *                                   $data_list=>array():数据集数组，
     *                                   $data_value_filed=>"id"；值字段名称，
     *                                   $data_name_filed=>"名称字段名称",
     *                                   $data_parent_filed=>"上层节点字段名称"
     *                                )
     * @param $parent_filed string 上层值页面元素名称
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return mixed
     */
    public static function select_parent($input_name,$data,$parent_filed,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //默认样式
        if(empty($style)) $style="width:255px;";

        //增量缓存数据名字
        $cache_name = str_ireplace("[","_",str_ireplace("]","",$input_name));
        $cache_parent = str_ireplace("[","_",str_ireplace("]","",$parent_filed));

        //初期化变量
        $property = " parent='{$cache_parent}'";
        if($required)$property.=" validate='{required:true}'";
        if($input_name) $property.= " name='{$input_name}' cache='{$cache_name}' node='{$input_name}'";
        if($style) $property.=" style='{$style}'";
        if($disabled) $property.=" disabled='disabled'";

        //设置样式
        if($class) {
            $property.=" class='__select_parent_'";
        }else{
            $property.=" class='__select_parent_ {$class}'";
        }

        //取得数据
        if(is_array($data)){
            //拆分数组
            list($data_list,$value,$name,$parent) = $data;
            if(!(is_array($data_list) || empty($value) || empty($name) || empty($parent))){
                return false;
            }

            //整理数据
            $data = null;
            foreach($data_list as $item){
                $data[$item[$parent]][$item[$value]] = $item[$name];
            }
        }else{
            return false;
        }

        //判断是否加载JS
        $html = array();
        if(!empty($input_name) && !empty($data)){
            //加载数据
            $data_json = json_encode($data);
            array_push($html,"<script type='text/javascript'>var {$cache_name}=jQuery.parseJSON('{$data_json}');var {$cache_name}_default='{$default}';</script>");
            if(!self::$select_parent){
                array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/SelectParent/select_parent.js"></script>');
                self::$select_parent = true;
            }
        }

        //设置开始符
        array_push($html,"<select {$property}>");
        array_push($html,'<option value="">请选择...</option>');
        array_push($html,'</select>');

        //增加必填图标
        if($required){
            array_push($html,"<label for='{$input_name}' generated='true' class='error'>*</label>");
        }else{
            array_push($html,"<label for='{$input_name}' generated='true' class='error'></label>");
        }

        //判断数据是否存在
        return implode("",$html);
    }

    /**
     * @功能：树形下拉框设置
     * {:W('Common/Select/select_tree',array('input_name',array($data,"value","name"),input_name,null,"width:120px;"))}
     * @param $input_name string 下拉框名称
     * @param $data string 传输数据集array(
     *                                   $data_list=>array():数据集数组，
     *                                   $data_value_filed=>"id"；值字段名称，
     *                                   $data_name_filed=>"名称字段名称",
     *                                   $data_parent_filed=>"上层节点字段名称"
     *                                )
     * @param $parent_id int 上层节点ID
     * @param $default string 默认值
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @param $style string CSS内容
     * @param $class string CSS样式名称
     * @开发者：zc
     * @return mixed
     */
    public static function select_tree($input_name,$data,$parent_id=0,$default=null,$required=false,$disabled=false,$style=null,$class=null) {
        //默认样式
        if(empty($style)) $style="width:250px;";

        //判断参数是否正确
        if(empty($input_name) || empty($data)){
            return false;
        }

        //增量控件ID
        $input_id = str_ireplace("[","_",str_ireplace("]","",$input_name));

        //初期化变量
        $property = null;
        if($required)$property.=" validate='{required:true}'";
        if($input_name) $property.= " name='{$input_name}' node='{$input_id}'";
        if($style) $property.=" style='{$style}'";
        if($disabled) $property.=" disabled='disabled'";

        //设置样式
        if($class) {
            $property.=" class='__select_tree_'";
        }else{
            $property.=" class='__select_tree_ {$class}'";
        }

        //取得数据
        $html = array();
        if(is_array($data)){
            //拆分数组
            list($data_list,$value,$name,$parent) = $data;
            if(!(is_array($data_list) || empty($value) || empty($name) || empty($parent))){
                return false;
            }

            //整理数据
            $data = null;
            foreach($data_list as $item){
                $data[$item[$parent]][$item[$value]] = $item[$name];
            }
        }else{
            return false;
        }

        //设置开始符
        array_push($html,"<select {$property}>");
        array_push($html,'<option value="" data-text="请选择...">请选择...</option>');

        //取得选项数据
        $option = self::select_option($data,$parent_id,$default);
        if($option !== false){
            array_push($html,$option);
        }

        //写结束符号
        array_push($html,'</select>');

        //增加必填图标
        if($required){
            array_push($html,"<label for='{$input_name}' generated='true' class='error'>*</label>");
        }else{
            array_push($html,"<label for='{$input_name}' generated='true' class='error'></label>");
        }

        //判断数据是否存在
        return implode("",$html);
    }

    /**
     * @功能：取得select中的选项内容
     * @param array $data_list 数据列表
     * @param int $data_id 上层节点ID
     * @param string $default 当前选中值
     * @param int $level 层次
     * @开发者： zc
     * @return string
     */
    private static function select_option($data_list,$data_id=0,$default=null,$level=0){
        //判断数据对象是否为空
        $html = null;
        if(!is_array($data_list)){
            return false;
        }

        //判断数据是否正确
        if(!empty($data_list[$data_id]) && is_array($data_list[$data_id])) {
            //循环赋值
            foreach ($data_list[$data_id] as $key => $val) {
                //计算层级
                $text = str_repeat("   │ ", $level) . "   ├ " . $val;

                //判断当前是否被选中
                if ($key == $default) {
                    $html .= "<option value='{$key}' data-text='{$text}' selected>{$text}</option>";
                } else {
                    $html .= "<option value='{$key}' data-text='{$text}'>{$text}</option>";
                }

                //子节点
                if (!empty($data_list[$key]) && is_array($data_list[$key])) {
                    //取得子节点
                    $html .= self::select_option($data_list, $key, $default, $level + 1);
                }
            }
        }

        //返回结果
        return $html;
    }
}