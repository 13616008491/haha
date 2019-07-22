<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：AccessWidget.class.php
 * @类功能: 权限分配
 * @开发者: zc
 * @开发时间： 14-10-28
 * @版本：version 1.0
 */
namespace app\common\widget;

use think\Controller;

class Access extends Controller{

    //静态变量
    private static $html_cache = null;

    /**
     * @功能：生成Html
     * @param $data string 数据
     * @param $item_id string 节点ID
     * @param $default array 默认值
     * @param $item_filed string 节点字段
     * @param $parent_filed string 上层节点字段
     * @param $name_filed string 名称字段
     * @开发者：zc
     * @return string html
     */
    public static function show($data,$item_id,$default=array(),$item_filed="menu_id",$parent_filed="parent_id",$name_filed="name") {
        //判断数据是否存在
        if(is_array($data)){
            //整理数据
            $html = array();
            $data_tree = array();
            foreach($data as $item){
                //判断上层节点是否存在
                if(!isset($item[$parent_filed]) || $item[$parent_filed] === null){
                    return false;
                }

                //整理数据
                $data_tree[$item[$parent_filed]][] = $item;
            }

            //加载JS
            if(!self::$html_cache){
                array_push($html,"<script type='text/javascript' src='__STATIC__/common/plugins/AccessShow/access_show.js'></script>");
                self::$html_cache = true;
            }

            //设置开始符
            array_push($html,"<table class='pointer' cellpadding='0' cellspacing='0' border='0' id='access_tree_table'>");
            array_push($html,"<thead>");
            array_push($html,"<tr>");
            array_push($html,"<th style='width: 60%;' class='tabSort-header'><a class='tabSort-header-inner'><p>功能节点</span></p></a></th>");
            array_push($html,"<th style='width: 20%;' class='tabSort-header'><a class='tabSort-header-inner'><p>类型</span></p></a></th>");
            array_push($html,"<th style='width: 20%;' class='tabSort-header'><a class='tabSort-header-inner'><p>备注</span></p></a></th>");
            array_push($html,"</tr>");
            array_push($html,"</thead>");
            array_push($html,"<tbody >");

            //取得选项数据
            $option = self::show_item($data_tree,$item_id,$default,$item_filed,$parent_filed,$name_filed);
            if(empty($option)){
                array_push($html,"<tr>");
                array_push($html,"<td colspan='3' style='color: red;'>数据不存在！</td>");
                array_push($html,"</tr>");
            }else{
                array_push($html,$option);
            }

            //写结束符号
            array_push($html,"</tbody>");
            array_push($html,'</table>');
            return implode("",$html);
        }else{
            return false;
        }

    }

    /**
     * @功能：取得select中的选项内容
     * @param array $data_tree 数据列表
     * @param int $item_id 上层节点ID
     * @param array $default 默认值
     * @param string $item_filed 节点字段
     * @param string $parent_filed 上层节点字段
     * @param string $name_filed 名称字段
     * @param string $default_node 默认节点
     * @param int $level 层次
     * @param string $level_str 层次字符串
     * @开发者： zc
     * @return string
     */
    private static function show_item($data_tree,$item_id,$default,$item_filed,$parent_filed,$name_filed,$default_node=null,$level=0,$level_str=null){
        //初期化
        $html = null;
        $img_reduce = "__STATIC__/admin/img/reduce.png";

        //判断数据对象是否为空
        if(!is_array($data_tree)){
            return false;
        }

        //角色层级字符串
        if(empty($level_str)){
            $level_str = $item_id;
        }

        //循环赋值
        foreach($data_tree[$item_id] as $value){
            //计算层级
            $item_id = $value['menu_id'];
            $padding_left = ($level * 30) + 10;
            $level_node = $level_str."-".$item_id;

            //取得选中状态
            if(in_array($value['menu_id'],$default)){
                $checked = "checked='checked'";
            }else{
                $checked = "";
            }

            //判断图片类型
            if(!empty($data_tree[$value[$item_filed]]) && is_array($data_tree[$value[$item_filed]])){
                //拼接html
                $name = "<img style='margin-top:-3px;' src='{$img_reduce}'>&nbsp;";
                $name.= "<input style='vertical-align: middle;' type='checkbox' name='data[]' value='{$value['menu_id']}' {$checked}>&nbsp;";
                $name.= $value[$name_filed];

                //拼接HTML
                $html .= "<tr data-parent='{$level_str}' data-node='{$level_node}' data-status='close'>";
                $html .= "<td style='padding-left:{$padding_left}px;'>{$name}</td>";
                $html .= "<td>&nbsp;</td>";
                $html .= "<td>&nbsp;</td>";
                $html .= "</tr>";
            }else{
                //判断是否为默认节点
                if($item_id == $default_node){
                    //名称
                    $name = "<input style='vertical-align: middle;' type='checkbox' name='data[]' value='{$value['menu_id']}' data-default='true' {$checked}>&nbsp;";
                    $name.= $value[$name_filed];

                    //拼接HTML
                    $html .= "<tr data-parent='{$level_str}' data-node='{$level_node}'>";
                    $html .= "<td style='padding-left:{$padding_left}px;'>{$name}</td>";
                    $html .= "<td>功能点</td>";
                    $html .= "<td>菜单默认功能点</td>";
                    $html .= "</tr>";
                }else{
                    //名称
                    $name = "<input style='vertical-align: middle;' type='checkbox' name='data[]' value='{$value['menu_id']}' {$checked}>&nbsp;";
                    $name.= $value[$name_filed];

                    //拼接HTML
                    $html .= "<tr data-parent='{$level_str}' data-node='{$level_node}'>";
                    $html .= "<td style='padding-left:{$padding_left}px;'>{$name}</td>";
                    $html .= "<td>功能点</td>";
                    $html .= "<td>&nbsp;</td>";
                    $html .= "</tr>";
                }
            }

            //子节点
            if(!empty($data_tree[$value[$item_filed]]) && is_array($data_tree[$value[$item_filed]])){
                //取得子节点
                $default_node = $value['default_node'];
                $html .=  self::show_item($data_tree,$value[$item_filed],$default,$item_filed,$parent_filed,$name_filed,$default_node,$level+1,$level_node);
            }
        }

        //还原变量
        $level_str=null;
        $level_node=null;

        //返回结果
        return $html;
    }
}