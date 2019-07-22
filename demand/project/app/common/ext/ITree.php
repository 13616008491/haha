<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：ITree.class.php
 * @类功能: 根据规则生成唯一CODE
 * @开发者: 陈旭林
 * @开发时间： 14-11-10
 * @版本：version 1.0
 */
namespace app\common\ext;

class ITree {
    //定义静态变量
    private  $_tree_data=array(); //树的初始数据
    private  $_tree_parent=array(); //根据上层节点整理的数据
    private  $_node_name=null; //树的节点ID字段名字
    private  $_parent_name=null; //树的上层节点ID字段名字
    private  $_node_level=null; //树的上层节点ID字段名字

    /**
     * @功能：设置树初始数据
     * @param array $tree_data 初始数据
     * @param string $node_name 节点ID字段名称
     * @param string $parent_name 上层节点ID字段名称
     * @param string $node_level 节点层次ID字段名称
     * @开发者： 陈旭林
     */
    public function set($tree_data,$node_name="id",$parent_name="parent_id",$node_level="level"){
        //设置字段名
        $this->_node_name = $node_name;
        $this->_parent_name = $parent_name;
        $this->_node_level = $node_level;

        //整理数据
        foreach($tree_data as $val){
            //判断字段ID是否正确
            if(!isset($val[$node_name]) || null === $val[$node_name]){
                error("节点字段名设置不正确！");
            }

            //判断字段ID是否正确
            if(!isset($val[$parent_name]) || null === $val[$parent_name]){
                error("上层节点字段名设置不正确！");
            }

            //整理数据
            $this->_tree_parent[$val[$parent_name]][]=$val;
            $this->_tree_data[$val[$node_name]]=$val;
        }
    }

    /**
     * @功能：取得上层节点ID
     * @param int $id 节点ID
     * @开发者： 陈旭林
     * @return string
     */
    public function get_parent_id($id){
        //判断上层节点ID是否存在
        if(!isset($this->_tree_data[$id][$this->_parent_name]) && null === $this->_tree_data[$id][$this->_parent_name]){
            return false;
        }

        //返回数据
        return $this->_tree_data[$id][$this->_parent_name];
    }

    /**
     * @功能：取得上层节点字段
     * @param int $id 节点ID
     * @param string $field 字段名
     * @开发者： 陈旭林
     * @return string
     */
    public function get_parent_field($id,$field){
        //判断上层节点ID是否存在
        if(!isset($this->_tree_data[$id][$field]) && null === $this->_tree_data[$id][$field]){
            return false;
        }

        //返回数据
        return $this->_tree_data[$id][$field];
    }

    /**
     * @功能：取得上层节点ID集合
     * @param int $id 节点ID
     * @param array $node_list 递归回调数组
     * @开发者： 陈旭林
     * @return string
     */
    public function get_parents_id($id,$node_list=array()){
        //添加到返回值中
        $node_list[$this->_tree_data[$id][$this->_node_level]]=(int)$id;

        //判断上层节点ID是否存在
        if(isset($this->_tree_data[$id][$this->_parent_name]) && !empty($this->_tree_data[$id][$this->_parent_name])){
            //节点上层ID
            $parent_id = $this->_tree_data[$id][$this->_parent_name];
            $parent_id = (int)$parent_id;
            if(0 == $parent_id){
                return $node_list;
            }
        }else{
            return $node_list;
        }


        //判断是否到了顶级节点
        $node_list = $this->get_parents_id($parent_id,$node_list);

        //返回数据
        return $node_list;
    }

    /**
     * @功能：取得上层节点字段集合
     * @param int $id 节点ID
     * @param string $field 字段名
     * @param array $node_list 递归回调数组
     * @开发者： 陈旭林
     * @return string
     */
    public function get_parents_field($id,$field,$node_list=array()){
        //添加到返回值中
        $node_list[]=$this->_tree_parent[$id][$field];

        //判断上层节点ID是否存在
        if(isset($this->_tree_data[$id][$this->_parent_name]) && !empty($this->_tree_data[$id][$this->_parent_name])){
            $parent_id = $this->_tree_data[$id][$this->_parent_name];
            $parent_id = (int)$parent_id;
            if(0 == $parent_id){
                return $node_list;
            }
        }else{
            return $node_list;
        }


        //判断是否到了顶级节点
        $node_list = $this->get_parents_id($parent_id,$node_list);

        //返回数据
        return $node_list;
    }

    /**
     * @功能：取得下层节点ID集合
     * @param int $id 节点ID
     * @param array $node_list 递归回调数组
     * @开发者： 陈旭林
     * @return string
     */
    public function get_nodes_id($id,$node_list=array()){
        //添加到返回值中
        $node_list[]=(int)$id;

        //判断下层节点ID是否存在
        if(!empty($this->_tree_parent[$id])) {
            $nodes = $this->_tree_parent[$id];
            if (empty($nodes)) {
                return $node_list;
            } else {
                //循环取得各个节点
                foreach ($nodes as $node) {
                    //判断是否到了下级节点
                    $node_id = (int)$node[$this->_node_name];
                    $node_list = $this->get_nodes_id($node_id, $node_list);
                }
            }
        }

        //返回数据
        return $node_list;
    }

    /**
     * @功能：取得下层节点字段集合
     * @param int $id 节点ID
     * @param string $field 字段名
     * @param array $node_list 递归回调数组
     * @开发者： 陈旭林
     * @return string
     */
    public function get_nodes_field($id,$field,$node_list=array()){

        //添加到返回值中
        $node_list[]=$this->_tree_parent[$id][$field];

        //判断下层节点ID是否存在
        $nodes = $this->_tree_parent[$id];
        if(empty($nodes)){
            return $node_list;
        }else{
            //循环取得各个节点
            foreach($nodes as $node){

                //判断是否到了下级节点
                $node_id = (int)$node[$this->_node_name];
                $node_list = $this->get_nodes_id($node_id,$node_list);
            }
        }

        //返回数据
        return $node_list;
    }

    /**
     * @功能：取得树数组
     * @param int $id 节点ID
     * @开发者： 陈旭林
     * @return string
     */
    public function get_tree($id){
        //定义变量
        $node_list = array();
        $nodes = $this->_tree_data;
        foreach ($nodes AS $node) {
            if ($node[$this->_parent_name] == $id) {
                //设置字迹
                $node['child'] = $this->get_tree($node[$this->_node_name]);
                if(empty($node['child'])){
                    $node['child'] = array();
                }

                //提交数据
                $node_list[] = $node;
            }
        }

        //判断返回值
        if(count($node_list)==0){
            return null;
        }else{
            return $node_list;
        }
    }
}