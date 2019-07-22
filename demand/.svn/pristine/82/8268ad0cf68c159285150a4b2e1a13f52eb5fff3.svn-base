<?php

namespace app\common\ext;

use app\common\enum\FieldType;
use app\common\enum\IsDelete;
use app\common\enum\IsYes;

class ISupplierField{
    //静态变量定义
    private static $error = null;
    /**
     * @功能 取得错误信息
     * @开发者：cxl
     * @return string
     */
    public static function getError(){
        return self::$error;
    }

    /**
     * 获取所需要字段
     * @param $product_id
     * @return mixed
     */
    public static function getFieldList($product_id){
        $field_where['supplier_product_id'] = $product_id;
        $field_where['is_delete'] = IsDelete::No;
        $field_list = IDb::getInstance('supplier_field')
            ->setDbWhere($field_where)
            ->sel();

        return $field_list;
    }

    /**
     * 字段值检测
     * @param $product_id
     * @return bool
     */
    public static function checkFieldDataForWeb($product_id){
        $field_list = self::getFieldList($product_id);
        if($field_list === false){
            return false;
        }

        $check_result = true; //参数检测状态

        foreach($field_list as $field){
            $value = IRequest::get('field_'.$field['field_id']);
            if($field['is_require'] == IsYes::Yes && $value == '' && !in_array($field['type'],[FieldType::File,FieldType::FileMulti])){
                $check_result = false;
                self::$error = $field['title'].'不能为空';
                break;
            }
        }

        if(!$check_result){
            return false;
        }

        return true;
    }

    /**
     * 添加订单动态字段
     * @param $product_id
     * @param $order_id
     * @return array|bool
     */
    public static function addFieldData($product_id,$order_id){
        $field_list = self::getFieldList($product_id);
        if($field_list === false){
            return false;
        }

        $check_result = true; //参数检测状态

        foreach($field_list as $field){
            $value = IRequest::get('field_'.$field['field_id']);
            if(!is_null($value)){
                if(is_array($value)){
                    $value = implode('{|}',$value);
                }
                $temp = [];
                $temp['agent_order_id'] = $order_id;
                $temp['field_id'] = $field['field_id'];
                $temp['field_title'] = $field['title'];
                $temp['field_type'] = $field['type'];
                $temp['value'] = $value;

                $result = IDb::getInstance('agent_order_value')->setDbData($temp)->add();
                if(!$result){
                    $check_result = false;
                    self::$error = '添加订单数据失败！';
                    break;
                }
            }
        }

        if(!$check_result){
            return false;
        }

        return true;
    }

    /**
     * 获取字段值
     * @param $agent_order_id
     * @return mixed
     */
    public static function getFieldData($agent_order_id){
        $where['agent_order_id'] = $agent_order_id;
        $field_list = IDb::getInstance('agent_order_value')->setDbWhere($where)->sel();
        foreach($field_list as &$field){
            switch ($field['field_type']){
                case FieldType::ImgMulti:
                case FieldType::FileMulti:
                    $field['value'] = explode('{|}',$field['value']);
                    break;
            }
        }

        return $field_list;
    }
}