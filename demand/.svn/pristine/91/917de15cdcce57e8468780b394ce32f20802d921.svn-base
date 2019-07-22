<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：ITreeShow.class.php
 * @类功能: 树展示
 * @开发者: zc
 * @开发时间： 14-10-28
 * @版本：version 1.0
 */
namespace app\common\widget;

use think\Controller;

class ListTree extends Controller{

    //静态变量
    private static $config = null;

    /**
     * @功能：参数设置
     * @param $key string 配置信息key
     * @param $sub_key string 配置信息子key
     * @param $val string 配置信息值
     * @开发者：zc
     * @return string html
     */
    public static function set($key,$sub_key,$val) {
        //判断配置文件是否初期化
        if(empty(self::$config)){
            //初期化
            self::$config = array(
                "node"=>"",                                  //节点字段名
                "node_parent"=>"parent_id",               //上层节点字段名
                "node_checkbox"=>false,                    //是否需要checkbox
                "node_root"=>array("名称","href"),        //根节点的操作连接HTML内容
                "node_filed"=>array(                       //字段列表
                    //"filed1"=>array("字段1","40%"),
                    //"filed1"=>array("字段2","40%"),
                    //"_href_"=>array("操作","20%","href","还原href"),
                ),
                "node_default"=>null                       //选中的ID
            );
        }

        //判断子key是否存在
        if(empty($sub_key)){
            self::$config[$key] = $val;
        }else{
            self::$config[$key][$sub_key] = $val;
        }
    }

    /**
     * @功能：生成Html
     * @param $data string 数据
     * @param $parent_id int 起始节点ID
     * @开发者：zc
     * @return string html
     */
    public static function show($data,$parent_id=0) {
        //变量定义
        $node = self::$config['node'];
        $node_parent = self::$config['node_parent'];
        $node_checkbox = self::$config['node_checkbox'];
        $node_root = self::$config['node_root'];
        $node_filed = self::$config['node_filed'];
        $node_default = self::$config['node_default'];

        //设置开始符
        $html = array();
        array_push($html,"<script type='text/javascript' src='__STATIC__/common/plugins/AccessShow/access_show.js'></script>");
        array_push($html,"<table class='pointer' cellpadding='0' cellspacing='0' border='0' width='100%' id='access_tree_table'>");
        array_push($html,"<thead>");
        array_push($html,"<tr>");

        //循环写字段标题
        if(is_array($node_filed)){
            foreach($node_filed as $key=>$val){
                array_push($html,"<th style='width: {$val[1]};' class='tabSort-header'><a class='tabSort-header-inner'><p>{$val[0]}</span></p></a></th>");
            }
        }
        //树头部闭合
        array_push($html,"</tr>");
        array_push($html,"</thead>");
        array_push($html,"<tbody >");

        //增加根节点
        array_push($html, "<tr data-parent='' data-node='0' data-status='close'>");
        array_push($html, "<td style='padding:0 0 0 10px;height: 31px;line-height: 31px;'>");
        array_push($html, "<img style='margin-top:-3px;margin-right:10px;' src='__STATIC__/admin/img/reduce.png'>");
        array_push($html, $node_root[0]);
        array_push($html, "</td>");

        //设置其他展示字段的值
        $node_filed_flg = false;
        if(is_array($node_filed)) {
            foreach ($node_filed as $key => $val) {
                //判断是否为第一列
                if ($node_filed_flg) {
                    //判断是否为超级连接
                    if (strtolower($key) == "_href_") {
                        array_push($html, "<td style='padding:0 0 0 10px;height: 31px;line-height: 31px;'>$node_root[1]</td>");
                    } else {
                        array_push($html, "<td style='padding:0 0 0 10px;height: 31px;line-height: 31px;'>--</td>");
                    }
                }

                //设置状态
                $node_filed_flg = true;
            }
        }
        //判断数据是否存在
        if(is_array($data)) {
            //整理树形数据
            $data_tree = array();
            foreach($data as $item){
                //判断上层节点是否存在
                if(!isset($item[$node_parent])){
                    return false;
                }

                //整理数据
                $data_tree[$item[$node_parent]][] = $item;
            }

            //取得树内容部分
            $tmp_html = self::show_item($data_tree,$parent_id,$node,$node_parent,$node_filed,$node_checkbox,$node_default);
            array_push($html,implode("",$tmp_html));

            //判断是否有数据
            if(empty($html)){
                array_push($html,"<tr>");
                array_push($html,"<td colspan='2' style='color: red;'>没有相应的数据！</td>");
                array_push($html,"</tr>");
            }
        }

        //写结束符号
        array_push($html,"</tbody>");
        array_push($html,'</table>');

        //返回结果
        return implode("",$html);
    }

    /**
     * @功能：取得select中的选项内容
     * @param array $data_tree 数据列表
     * @param int $parent_id 上层节点ID
     * @param string $node 数据节点字段名
     * @param string $node_parent 数据上层节点字段名
     * @param string $node_filed 展示列表字段配置
     * @param bool $node_checkbox 是否需要checkbox
     * @param array $node_default 选中的默认值
     * @param int $level 层次
     * @param string $node_flg 层次字符串
     * @开发者： zc
     * @return string
     */
    private static function show_item($data_tree,$parent_id,$node,$node_parent,$node_filed,$node_checkbox=false,$node_default=array(),$level=1,$node_flg=null){
        //判断数据对象是否为空
        if(!is_array($data_tree)){
            return false;
        }

        //初期和
        $html = array();
        $node_keys = array_keys($node_filed);
        $node_tmp = $node_keys[0];

        //角色层级字符串
        if(empty($node_flg)){
            $node_flg = $parent_id;
        }

        //判断上次节点是否为空
        if(empty($parent_id)){
            $parent_id = 0;
        }

        //循环赋值
        if(!empty($data_tree[$parent_id]) && is_array($data_tree[$parent_id])) {
            foreach ($data_tree[$parent_id] as $item) {
                //计算缩进
                $node_left = ($level) * 20 + 10;
                $node_level = $node_flg . "-" . $item[$node];

                //取得选中状态
                $checked = "";
                if ($node_checkbox == true) {
                    //判断值是否被选中
                    if (in_array($item[$node], $node_default)) {
                        $checked = "<input style='vertical-align: middle;margin-right:10px;' type='checkbox' name='data[]' value='{$item[$node]}' checked='checked'>";
                    }
                }

                //拼接记录
                array_push($html, "<tr data-parent='{$node_flg}' data-node='{$node_level}' data-status='close'>");
                array_push($html, "<td style='padding:0 0 0 {$node_left}px;height: 31px;line-height: 31px;'>");
                array_push($html, "<img style='margin-top:-3px;margin-right:10px;' src='__STATIC__/admin/img/reduce.png'>");
                array_push($html, $checked);
                array_push($html, $item[$node_tmp]);
                array_push($html, "</td>");

                //设置其他展示字段的值
                foreach ($node_filed as $key => $val) {
                    //判断是否为第一列
                    if (strtolower($key) != strtolower($node_tmp)) {
                        //判断是否为超级连接
                        if (strtolower($key) == "_href_") {
                            //判断是否删除
                            if (empty($item['is_delete']) || $item['is_delete'] == 1) {
                                if (!empty($item[$node]) && !empty($val[2])) {
                                    $href = str_ireplace("%7B{$node}%7D", $item[$node], $val[2]);
                                    array_push($html, "<td style='padding:0 0 0 10px;height: 31px;line-height: 31px;'>{$href}</td>");
                                }
                            } else {
                                if (!empty($item[$node]) && !empty($val[3])) {
                                    $href = str_ireplace("%7B{$node}%7D", $item[$node], $val[3]);
                                    array_push($html, "<td style='padding:0 0 0 10px;height: 31px;line-height: 31px;'>{$href}</td>");
                                }
                            }
                        } else {
                            array_push($html, "<td style='padding:0 0 0 10px;height: 31px;line-height: 31px;'>{$item[$key]}</td>");
                        }
                    }
                }

                //结束行
                array_push($html, "</tr>");

                //判断是否存在子节点递归
                if (!empty($data_tree[$item[$node]]) && is_array($data_tree[$item[$node]])) {
                    $tmp_html = self::show_item($data_tree, $item[$node], $node, $node_parent, $node_filed, $node_checkbox, $node_default, ($level + 1), $node_level);
                    array_push($html, implode("", $tmp_html));
                }
            }
        }

        //还原变量
        $level_str=null;
        $level_node=null;

        //返回结果
        return $html;
    }
}