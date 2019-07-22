<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：FileWidget.class.php
 * @类功能: 文件上传控件封装
 * @开发者: zc
 * @开发时间： 15-10-28
 * @版本：version 1.0
 */
namespace app\common\widget;

use think\Controller;

class File extends Controller{

    //静态变量
    private static $photo_static = false;

    /**
     * @功能：单图上传功能
     * {:W("Common/Images/wos",array("input_name","house",array(1,2,3),"150x150"))}
     * @param string $file_name 图片字段名称
     * @param string $type 图片类型
     * @param bool $required 是否必填
     * @param bool $disabled 是否disabled状态
     * @开发者：zc
     * @return mixed
     */
    public static function file($file_name,$type=".txt",$required=false,$disabled=false){
        //初期化
        $html = array();

        //初期化变量
        $property = null;
        $validate = null;
        if(empty($type)) $type=".txt";
        if($required) $validate=" validate='{required:true}'";
        if($disabled) $disabled=" onclick='return false'";

        //判断是否加载JS
        if(!self::$photo_static){
            array_push($html,"<script type='text/javascript' src='__STATIC__/common/plugins/FileUpload/jquery.ui.widget.js'></script>");
            array_push($html,"<script type='text/javascript' src='__STATIC__/common/plugins/FileUpload/jquery.iframe-transport.js'></script>");
            array_push($html,"<script type='text/javascript' src='__STATIC__/common/plugins/FileUpload/jquery.fileupload.js'></script>");
            array_push($html,"<script type='text/javascript' src='__STATIC__/common/jquery-ui.js'></script>");
            array_push($html,"<script type='text/javascript' src='__STATIC__/common/plugins/FileUpload/upload.js'></script>");
            array_push($html,"<link rel='stylesheet' href='__STATIC__/common/plugins/FileUpload/upload.css'/>");
            self::$photo_static = true;
        }

        //拼接上传图片HTML
        array_push($html,"<div class='file_upload'>");
        array_push($html,"<div class='file_head'>");
        array_push($html,"<ul>");
        array_push($html,"<li>");
        array_push($html,"<div class='btn'>");

        //判断单图还是多图
        $data_url = Url("common/file");
        array_push($html,"<input type='file' name='_upload_' data-url='{$data_url}' accept='{$type}' data-field='{$file_name}' {$validate} {$disabled}>");
        array_push($html,"<input type='hidden' name='{$file_name}' class='hidden' value=''  {$validate}>");

        //拼接上传图片HTML
        array_push($html,"</div>");

        //错误提示信息
        if($required){
            array_push($html,"<label for='{$file_name}' generated='true' style='margin-left: 20px;' class='error'>*必须上传文本文件！</label>");
        }else{
            array_push($html,"<label for='{$file_name}' generated='true' class='error'></label>");
        }

        //拼接上传图片HTML
        array_push($html,"</li>");
        array_push($html,"</ul>");
        array_push($html,"</div>");
        array_push($html,"<div class='file_body'>");
        array_push($html,"<ul>");

        //拼接上传图片HTML
        array_push($html,"</ul>");
        array_push($html,"</div>");

        //结束符
        array_push($html,"</div>");

        //判断数据是否存在
        return implode("",$html);
    }

    /**
     * @功能：单图上传功能
     * {:W("Common/File/wos",array("input_name","house",array(1,2,3),"150x150"))}
     * @param string $file_name 图片字段名称
     * @param string $dir 目录
     * @param string $type 图片类型
     * @param bool $required 是否必填
     * @param bool $disabled 是否disabled状态
     * @开发者：zc
     * @return mixed
     */
    public static function wos($file_name,$dir='',$type=".txt",$required=false,$disabled=false){
        //初期化
        $html = array();

        //初期化变量
        $property = null;
        $validate = null;
        if(empty($type)) $type=".txt";
        if($required) $validate=" validate='{required:true}'";
        if($disabled) $disabled=" onclick='return false'";

        //判断是否加载JS
        if(!self::$photo_static){
            array_push($html,"<script type='text/javascript' src='__STATIC__/common/plugins/FileUpload/jquery.ui.widget.js'></script>");
            array_push($html,"<script type='text/javascript' src='__STATIC__/common/plugins/FileUpload/jquery.iframe-transport.js'></script>");
            array_push($html,"<script type='text/javascript' src='__STATIC__/common/plugins/FileUpload/jquery.fileupload.js'></script>");
            array_push($html,"<script type='text/javascript' src='__STATIC__/common/jquery-ui.js'></script>");
            array_push($html,"<script type='text/javascript' src='__STATIC__/common/plugins/FileUpload/upload_oss.js'></script>");
            array_push($html,"<link rel='stylesheet' href='__STATIC__/common/plugins/FileUpload/upload.css'/>");
            self::$photo_static = true;
        }

        //拼接上传图片HTML
        array_push($html,"<div class='file_upload'>");
        array_push($html,"<div class='file_head'>");
        array_push($html,"<ul>");
        array_push($html,"<li>");
        array_push($html,"<div class='btn'>");

        //取得上传oss信息
        $max_size = 1024*5; //单位kb
        $data_info = Images::getOssInfo($max_size,1800,$dir);
        $data_url = $data_info['url'];
        if(!empty($data_info)){
            $data_info['MaxSize'] = $max_size;
            $data_info = json_encode($data_info);
            array_push($html,'<script type="text/javascript"> var FileInfo='.$data_info.';</script>');
        }

        //判断单图还是多图
        array_push($html,"<input type='file' name='file' data-url='{$data_url}' accept='{$type}' data-field='{$file_name}' {$disabled}>");
        array_push($html,"<input type='hidden' name='{$file_name}' class='hidden' value=''  {$validate}>");

        //拼接上传图片HTML
        array_push($html,"</div>");

        //错误提示信息
        if($required){
            array_push($html,"<label for='{$file_name}' generated='true' style='margin-left: 20px;' class='error'>*</label>");
        }else{
            array_push($html,"<label for='{$file_name}' generated='true' class='error'></label>");
        }

        //拼接上传图片HTML
        array_push($html,"</li>");
        array_push($html,"</ul>");
        array_push($html,"</div>");
        array_push($html,"<div class='file_body'>");
        array_push($html,"<ul>");

        //拼接上传图片HTML
        array_push($html,"</ul>");
        array_push($html,"</div>");

        //结束符
        array_push($html,"</div>");

        //判断数据是否存在
        return implode("",$html);
    }

    /**
     * @功能：文件显示
     * {:W('Common/Images/img',array('1'))}
     * @param $file_url string 文件路径
     * @开发者：zc
     * @return mixed
     */
    public static function show($file_url){
        //判断是否取得图片
        if(empty($file_url)){
            //返回值
            return "未上传";
        }else{
            if(!is_array($file_url)){
                //返回值
                return "<a target='_blank' class='blue' href='{$file_url}'>点击查看</a>";
            }else{
                if(count($file_url) == 1){
                    $file_url = array_pop($file_url);
                    return "<a target='_blank' class='blue' href='{$file_url}'>点击查看</a>";
                }else{
                    $html = '';
                    $index = 1;
                    foreach ($file_url as $url){
                        $html .= "<a target='_blank' class='blue' href='{$url}'>文件{$index}</a>";
                        $index++;
                    }

                    return $html;
                }
            }
        }
    }
}