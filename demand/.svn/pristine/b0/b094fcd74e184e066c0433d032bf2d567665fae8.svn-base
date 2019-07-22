<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：ColorWidget.class.php
 * @类功能: Color控件封装
 * @开发者: zc
 * @开发时间： 15-10-28
 * @版本：version 1.0
 */
namespace app\common\widget;

use app\common\enum\HtmlEnumValue;
use think\Controller;

class Color extends Controller{

    //静态变量
    private static $html_cache = null;

    /**
     * @功能 数据缓存
     * @param $data string 在HtmlEnumValue中的值数组名称，如果没有定于则是对应类型的下标
     * @param $value string 状态值
     * @开发者：zc
     * @return string html
     */
    public static function color($data,$value) {
        //颜色列表
        if(empty(self::$html_cache['enum_color'])){
            //取得中文名字及颜色
            self::$html_cache['enum_color'] = HtmlEnumValue::$enum_color;
        }

        //判断是否有静态缓存
        if(empty(self::$html_cache['enum_value'][$data])){
            //取得中文名字及颜色
            self::$html_cache['enum_value'][$data] = HtmlEnumValue::$enum_value[$data];
        }

        //判断第二次参数类型
        if(empty(self::$html_cache['enum_value'][$data])){
            //判断值是否为空
            if(empty($value)){
                return '--';
            }

            //取得颜色值
            if(!in_array($value,self::$html_cache['color_tpl'][$data])){
                self::$html_cache['color_tpl'][$data][] = $value;
            }

            //取得状态值
            $color_tpl = array_flip(self::$html_cache['color_tpl'][$data]);
            $color_tpl = self::$html_cache['enum_color'][$color_tpl[$value] + 1];
            $html = "<span style='color:{$color_tpl}'>$value</span>";
        }else{
            //取得颜色数据
            $color_value = self::$html_cache['enum_value'][$data];
            if(!empty($color_value[$value])) {
                $color_value_tmp = $color_value[$value];
                if (empty($color_value) || empty($color_value_tmp)) {
                    return '--';
                }
            }else{
                return '--';
            }

            //取得状态值
            $color_tpl = self::$html_cache['enum_color'][$value];
            $html = "<span style='color:{$color_tpl}'>$color_value_tmp</span>";
        }

        //返回结果
        return $html;
    }

    /**
     * @功能 [获取没颜色的中文数据]
     * @param $data string 在HtmlEnumValue中的值数组名称，如果没有定于则是对应类型的下标
     * @param $value string 状态值
     * @开发者：zc
     * @return string html
     */
    public static function name($data,$value) {
        //判断是否有静态缓存
        if(empty(self::$html_cache['enum_value'][$data])){
            //取得中文名字
            self::$html_cache['enum_value'][$data] = HtmlEnumValue::$enum_value[$data];
        }

        //判断第二次参数类型
        if(empty(self::$html_cache['enum_value'][$data])){
            //判断值是否为空
            if(empty($value)){
                return '--';
            }

            //取得状态值
            $html = $value;
        }else{
            ////取得中文名字数据
            $name_value = self::$html_cache['enum_value'][$data];
            if(!empty($name_value[$value])) {
                $name_value_tmp = $name_value[$value];
                if (empty($name_value) || empty($name_value_tmp)) {
                    return '--';
                }
            }else{
                return '--';
            }

            //取得状态值
            $html = $name_value_tmp;
        }

        //返回结果
        return $html;
    }
}