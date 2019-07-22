<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：common.php
 * @类功能: 公共函数文件
 * @开发者: 陈旭林
 * @开发时间： 14-10-28
 * @版本：version 1.0
 */


/**
 * @功能：取得系统日期时间
 * @开发者： cxl
 */
function get_date_time(){
    return date('Y-m-d H:i:s', time());
}

/**
 * @功能：取得系统日期
 * @开发者： cxl
 */
function get_date(){
    return date('Y-m-d', time());
}

/**
 * @功能：取得系统时间
 * @开发者： cxl
 */
function get_time(){
    return date('H:i:s', time());
}

/**
 * @功能：取得系统时间
 * @开发者： cxl
 */
function get_week(){
    return date("w");
}

/**
 * @功能：取得ip地址
 * @开发者： cxl
 */
function get_client_ip(){
    return \think\Request::instance()->ip();
}

/**
 * @功能：获取唯一编码
 * @param string $header 开头字母
 * @param string $no 编号
 * @param int $len 数据长度
 * @开发者： cxl
 * @return string
 */
function get_code($header,$no,$len){
    return strtoupper($header).str_pad($no, $len, '0', STR_PAD_LEFT);
}


/**
 * @功能：字符串转化为数组（支持汉字）只支持utf-8
 * @param string $data 字符串
 * @开发者： zc
 * @return string
 */
function str_to_array($data){
    //初期化变量
    $result = array();
    $len = strlen($data);
    $i = 0;

    //循环取得字符串数值
    while($i < $len){
        //取得编码值
        $chr = ord($data[$i]);

        //判断编码占用字符数量
        if($chr == 9 || $chr == 10 || (32 <= $chr && $chr <= 126)) {
            $result[] = substr($data,$i,1);
            $i +=1;
        }elseif(192 <= $chr && $chr <= 223){
            $result[] = substr($data,$i,2);
            $i +=2;
        }elseif(224 <= $chr && $chr <= 239){
            $result[] = substr($data,$i,3);
            $i +=3;
        }elseif(240 <= $chr && $chr <= 247){
            $result[] = substr($data,$i,4);
            $i +=4;
        }elseif(248 <= $chr && $chr <= 251){
            $result[] = substr($data,$i,5);
            $i +=5;
        }elseif(252 <= $chr && $chr <= 253){
            $result[] = substr($data,$i,6);
            $i +=6;
        }
    }

    //返回结果
    return $result;
}

/**
 * @功能：获取表全名
 * @param string $table 表名字
 * @开发者：zc
 * @return string 表全名
 */
function get_table($table){
    //取得表前缀
    $prefix = \think\Config::get("database.prefix");

    //返回表明
    return $prefix . $table;
}

/**
 * @功能：密文字符串
 * @param string $data 字符串
 * @param int $start 加密开始位置（位置从0开始计算）
 * @param int $count 加密位数（0表示全部）
 * @开发者： zc
 * @return string
 */
function get_string_rsa($data,$start,$count=10){
    //拆分字符串
    $data = str_to_array($data);

    //填充加密值
    if(is_array($data)) {
        foreach ($data as $key => $item) {
            //判断字符位置
            if ($key >= $start) {
                //判断是否超出
                if ($count <= 0) {
                    $data[$key] = "*";
                }

                //判断是否在填充范围内
                if ($key < ($start + $count)) {
                    $data[$key] = "*";
                }
            }
        }
    }

    //返回结果
    return implode("",$data);
}

/**
 * @功能：获取中文字符拼音首字母
 * @param string $str 字符串
 * @开发者： cxl
 * @return string
 */
//php获取中文字符拼音首字母
function getFirstCharter($str){
    //判断字符串是否存在
    if(empty($str)){
        return "#";
    }

    //返回ASCII值的首字母
    $first_char=ord($str{0});

    //判断首字符是否为字母
    if($first_char >= ord('A') && $first_char <= ord('z')) {
        return strtoupper($str{0});
    }

    //计算首字母的ASCII值
    $asc =ord($str{0}) * 256 + ord($str{1}) - 65536;

    //判断值范围获取首字母
    if($asc >= -20319 && $asc <= -20284) return 'A';
    if($asc >= -20283 && $asc <= -19776) return 'B';
    if($asc >= -19775 && $asc <= -19219) return 'C';
    if($asc >= -19218 && $asc <= -18711) return 'D';
    if($asc >= -18710 && $asc <= -18527) return 'E';
    if($asc >= -18526 && $asc <= -18240) return 'F';
    if($asc >= -18239 && $asc <= -17923) return 'G';
    if($asc >= -17922 && $asc <= -17418) return 'H';
    if($asc >= -17417 && $asc <= -16475) return 'J';
    if($asc >= -16474 && $asc <= -16213) return 'K';
    if($asc >= -16212 && $asc <= -15641) return 'L';
    if($asc >= -15640 && $asc <= -15166) return 'M';
    if($asc >= -15165 && $asc <= -14923) return 'N';
    if($asc >= -14922 && $asc <= -14915) return 'O';
    if($asc >= -14914 && $asc <= -14631) return 'P';
    if($asc >= -14630 && $asc <= -14150) return 'Q';
    if($asc >= -14149 && $asc <= -14091) return 'R';
    if($asc >= -14090 && $asc <= -13319) return 'S';
    if($asc >= -13318 && $asc <= -12839) return 'T';
    if($asc >= -12838 && $asc <= -12557) return 'W';
    if($asc >= -12556 && $asc <= -11848) return 'X';
    if($asc >= -11847 && $asc <= -11056) return 'Y';
    if($asc >= -11055 && $asc <= -10247) return 'Z';

    //无法获取的值
    return "#";
}

/**
 * 功能：根据模块类型输出错误信息
 * @开发者： cxl
 * @param $content string 错误信息
 */
function error($content){
    //取得请求对象
    $request = \think\Request::instance();

    //判断是否为ajax
    if($request->isAjax()){
        //设置返回值
        $response["code"] = SYSTEM_ERROR;
        $response["msg"] = $content;

        //输出结果
        exit(json_encode($response));
    }else{
        //判断模块类型
        switch(strtoupper($request->module())){
            case "ADMIN":
            case "AGENT":
                //需要错误画面
                $action = new app\common\controller\ErrorBaseController();
                $action->err($content);
                exit;
            default:
                //未知错误
                exit;
        }
    }
}

/**
 * @功能：取得时间列表
 * @param string $end 时间
 * @param string $interval 计算公式
 * @开发者：cxl
 * @return array;
 */
function get_date_list($end,$interval){
    //计算开始时间
    $tmp_end = strtotime($end);
    $start = strtotime($interval,$tmp_end);
    $start = $start + 86400;

    //循环取得时间
    $ret = array();
    for($i = $start;$i<= strtotime($end);$i+= 86400){
        $ret[] = date("m/d",$i);
    }

    //返回结果
    return $ret;
}

/**
 * @功能：取得枚举变量名称
 * @param string $name 参数
 * @param string $code 参数
 * @开发者：cxl
 * @return array;
 */
function get_enum_name($name,$code){
    //取得参数字符串
    if(!empty(\app\common\enum\HtmlEnumValue::$enum_value[$name][$code])){
        return \app\common\enum\HtmlEnumValue::$enum_value[$name][$code];
    }else{
        return null;
    }
}



