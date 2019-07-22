<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：ImagesWidget.class.php
 * @类功能: 图片上传控件封装
 * @开发者: zc
 * @开发时间： 15-10-28
 * @版本：version 1.0
 */
namespace app\common\widget;

use think\Config;
use think\Controller;

class Images extends Controller{

    //静态变量
    private static $photo_static = false;


    /**
     * @功能：单图上传功能
     * {:W('Common/Images/image',array('input_name','house',array(1,2,3),'150x150'))}
     * @param $input_name string 图片字段名称
     * @param $dir string 目录
     * @param $default array|string 初期数据 数据格式array(photo_id,photo_id)
     * @param $photo_plural bool 是否为多图，默认单图 true:多图，false:单图
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @开发者：zc
     * @return mixed
     */
    public static function image($input_name,$dir,$default=null,$photo_plural=false,$required=false,$disabled=false){
        //初期化
        $html = array();

        //初期化变量
        $property = null;
        $validate = null;
        if($required) $validate=" validate='{required:true}'";
        if($disabled) $disabled=" onclick='return false'";

        //判断是否加载JS
        if(!self::$photo_static){
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/ImageUpload/jquery.ui.widget.js"></script>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/ImageUpload/jquery.iframe-transport.js"></script>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/ImageUpload/jquery.fileupload.js"></script>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/jquery-ui.js"></script>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/ImageUpload/upload.js"></script>');
            array_push($html,'<link rel="stylesheet" href="__STATIC__/common/plugins/ImageUpload/upload.css"/>');
            self::$photo_static = true;
        }

        //拼接上传图片HTML
        array_push($html,'<div class="img_upload">');
        array_push($html,'<div class="img_head">');
        array_push($html,'<ul>');
        array_push($html,'<li>');
        array_push($html,'<div class="btn">');

        //判断单图还是多图
        $data_url = Url('common/upload',array('type'=>$dir));
        if($photo_plural){
            array_push($html,'<input name="_upload_" data-url="'.$data_url.'" data-field="'.$input_name.'" type="file" multiple="true" '.$disabled.'>');
        }else{
            array_push($html,'<input name="_upload_" data-url="'.$data_url.'" data-field="'.$input_name.'" type="file" '.$disabled.'>');
        }

        //拼接上传图片HTML
        array_push($html,'</div>');
        array_push($html,'<div class="msg"></div>');

        //判断是否为多图上传
        if($photo_plural){
            array_push($html,'<div class="tip"><p>说明：多图上传时点击图片框，可以拖拽排序！</p></div>');
        }else{
            array_push($html,'<div class="tip"><p>说明：单图上传！</p></div>');
        }

        //拼接上传图片HTML
        array_push($html,'</li>');
        array_push($html,'</ul>');
        array_push($html,'</div>');
        array_push($html,'<div class="img_body">');
        array_push($html,'<ul>');

        //判断是否存在初期值
        if(empty($default)){
            //拼接上传图片HTML
            array_push($html,'<li>');
            //array_push($html, '<input name="' . $input_name . '[]" value="" type="hidden" ' . $validate . '>');

            //判断是否多图
            if($photo_plural) {
                array_push($html, '<input name="' . $input_name . '[]" value="" type="hidden" ' . $validate . '>');
            }else{
                array_push($html, '<input name="' . $input_name . '" value="" type="hidden" ' . $validate . '>');
            }

            //拼接上传图片HTML
            array_push($html,'<div class="pic">');
            array_push($html,'<div class="preview"><img ondrag="return false" src=""><div class="dis"></div></div>');
            array_push($html,'<div class="default"><img ondrag="return false" src="__STATIC__/common/plugins/ImageUpload/images/default.jpg"><div class="mask"></div></div>');
            array_push($html,'<div class="operate hide"><i class="del">删除</i></div>');
            array_push($html,'</div>');
            array_push($html,'</li>');
        }else{
            //判断值是否为空；
            if(is_array($default) && count($default) > 0){
                //循环赋值
                foreach($default as $photo_url){
                    //取得图片地址
                    if(empty($photo_url)){
                        $photo_class = "";
                        $photo_url = "__STATIC__/common/plugins/ImageUpload/images/default.jpg";
                    }else{
                        $photo_class = "has";
                    }

                    //拼接上传图片HTML
                    array_push($html,'<li class="'.$photo_class.'">');
                    //array_push($html,'<input name="'.$input_name.'[]" value="'.$photo_url.'" type="hidden" '.$validate.'>');

                    //判断是否多图
                    if($photo_plural) {
                        array_push($html, '<input name="' . $input_name . '[]" value="'.$photo_url.'" type="hidden" ' . $validate . '>');
                    }else{
                        array_push($html, '<input name="' . $input_name . '" value="'.$photo_url.'" type="hidden" ' . $validate . '>');
                    }

                    //拼接上传图片HTML
                    array_push($html,'<div class="pic">');
                    array_push($html,'<div class="preview"><img ondrag="return false" src="'.$photo_url.'"><div class="dis"></div></div>');
                    array_push($html,'<div class="default"><img ondrag="return false" src="__STATIC__/common/plugins/ImageUpload/images/default.jpg"><div class="mask"></div></div>');
                    array_push($html,'<div class="operate"><i class="del">删除</i></div>');
                    array_push($html,'</div>');
                    array_push($html,'</li>');
                }
            }else{
                //取得图片地址
                if(empty($default)){
                    $photo_class = "";
                    $photo_url = "__STATIC__/common/plugins/ImageUpload/images/default.jpg";
                }else{
                    $photo_class = "has";
                    $photo_url = $default;
                }

                //拼接上传图片HTML
                array_push($html,'<li class="'.$photo_class.'">');
                //array_push($html,'<input name="'.$input_name.'[]" value="'.$photo_url.'" type="hidden" '.$validate.'/>');

                //判断是否多图
                if($photo_plural) {
                    array_push($html, '<input name="' . $input_name . '[]" value="'.$photo_url.'" type="hidden" ' . $validate . '>');
                }else{
                    array_push($html, '<input name="' . $input_name . '" value="'.$photo_url.'" type="hidden" ' . $validate . '>');
                }

                //拼接上传图片HTML
                array_push($html,'<div class="pic">');
                array_push($html,'<div class="preview"><img ondrag="return false" src="'.$photo_url.'"><div class="dis"></div></div>');
                array_push($html,'<div class="default"><img ondrag="return false" src="__STATIC__/common/plugins/ImageUpload/images/default.jpg"><div class="mask"></div></div>');
                array_push($html,'<div class="operate"><i class="del">删除</i></div>');
                array_push($html,'</div>');
                array_push($html,'</li>');
            }
        }

        //拼接上传图片HTML
        array_push($html,'</ul>');
        array_push($html,'</div>');

        //错误提示信息
        if($required){
            //array_push($html,"<label for='".$input_name."[]' generated='true' class='error'>*</label>");

            //判断是否多图
            if($photo_plural) {
                array_push($html,"<label for='".$input_name."[]' generated='true' class='error'>*</label>");
            }else{
                array_push($html,"<label for='".$input_name."' generated='true' class='error'>*</label>");
            }
        }else{
            //array_push($html,"<label for='".$input_name."[]' generated='true' class='error'></label>");

            //判断是否多图
            if($photo_plural) {
                array_push($html,"<label for='".$input_name."[]' generated='true' class='error'></label>");
            }else{
                array_push($html,"<label for='".$input_name."' generated='true' class='error'></label>");
            }
        }

        //结束符
        array_push($html,'</div>');

        //判断数据是否存在
        return implode("",$html);
    }

    /**
     * @功能：单图上传功能
     * {:W('Common/Images/oss',array('input_name','house',array(1,2,3),'150x150'))}
     * @param $input_name string 图片字段名称
     * @param $dir string 目录
     * @param $default array|string 初期数据 数据格式array(photo_id,photo_id)
     * @param $photo_plural bool 是否为多图，默认单图 true:多图，false:单图
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @开发者：zc
     * @return mixed
     */
    public static function oss($input_name,$dir,$default=null,$photo_plural=false,$required=false,$disabled=false){
        //初期化
        $html = array();

        //初期化变量
        $property = null;
        $validate = null;
        if($required) $validate=" validate='{required:true}'";
        if($disabled) $disabled=" onclick='return false'";

        //判断是否加载JS
        if(!self::$photo_static){
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/ImageUpload/jquery.ui.widget.js"></script>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/ImageUpload/jquery.iframe-transport.js"></script>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/ImageUpload/jquery.fileupload.js"></script>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/jquery-ui.js"></script>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/ImageUpload/upload.js"></script>');
            array_push($html,'<link rel="stylesheet" href="__STATIC__/common/plugins/ImageUpload/upload.css"/>');
            self::$photo_static = true;
        }

        //拼接上传图片HTML
        array_push($html,'<div class="img_upload">');
        array_push($html,'<div class="img_head">');
        array_push($html,'<ul>');
        array_push($html,'<li>');
        array_push($html,'<div class="btn">');

        //判断单图还是多图
        $data_url = Url('common/oss',array('type'=>$dir));
        if($photo_plural){
            array_push($html,'<input name="_upload_" data-url="'.$data_url.'" data-field="'.$input_name.'" type="file" multiple="true" '.$disabled.'>');
        }else{
            array_push($html,'<input name="_upload_" data-url="'.$data_url.'" data-field="'.$input_name.'" type="file" '.$disabled.'>');
        }

        //拼接上传图片HTML
        array_push($html,'</div>');
        array_push($html,'<div class="msg"></div>');

        //判断是否为多图上传
        if($photo_plural){
            array_push($html,'<div class="tip"><p>说明：多图上传时点击图片框，可以拖拽排序！</p></div>');
        }else{
            array_push($html,'<div class="tip"><p>说明：单图上传！</p></div>');
        }

        //拼接上传图片HTML
        array_push($html,'</li>');
        array_push($html,'</ul>');
        array_push($html,'</div>');
        array_push($html,'<div class="img_body">');
        array_push($html,'<ul>');

        //判断是否存在初期值
        if(empty($default)){
            //拼接上传图片HTML
            array_push($html,'<li>');
            //array_push($html, '<input name="' . $input_name . '[]" value="" type="hidden" ' . $validate . '>');

            //判断是否多图
            if($photo_plural) {
                array_push($html, '<input name="' . $input_name . '[]" value="" type="hidden" ' . $validate . '>');
            }else{
                array_push($html, '<input name="' . $input_name . '" value="" type="hidden" ' . $validate . '>');
            }

            //拼接上传图片HTML
            array_push($html,'<div class="pic">');
            array_push($html,'<div class="preview"><img ondrag="return false" src=""><div class="dis"></div></div>');
            array_push($html,'<div class="default"><img ondrag="return false" src="__STATIC__/common/plugins/ImageUpload/images/default.jpg"><div class="mask"></div></div>');
            array_push($html,'<div class="operate hide"><i class="del">删除</i></div>');
            array_push($html,'</div>');
            array_push($html,'</li>');
        }else{
            //判断值是否为空；
            if(is_array($default) && count($default) > 0){
                //循环赋值
                foreach($default as $photo_url){
                    //取得图片地址
                    if(empty($photo_url)){
                        $photo_class = "";
                        $photo_url = "__STATIC__/common/plugins/ImageUpload/images/default.jpg";
                    }else{
                        $photo_class = "has";
                    }

                    //拼接上传图片HTML
                    array_push($html,'<li class="'.$photo_class.'">');
                    //array_push($html,'<input name="'.$input_name.'[]" value="'.$photo_url.'" type="hidden" '.$validate.'>');

                    //判断是否多图
                    if($photo_plural) {
                        array_push($html, '<input name="' . $input_name . '[]" value="'.$photo_url.'" type="hidden" ' . $validate . '>');
                    }else{
                        array_push($html, '<input name="' . $input_name . '" value="'.$photo_url.'" type="hidden" ' . $validate . '>');
                    }

                    //拼接上传图片HTML
                    array_push($html,'<div class="pic">');
                    array_push($html,'<div class="preview"><img ondrag="return false" src="'.$photo_url.'"><div class="dis"></div></div>');
                    array_push($html,'<div class="default"><img ondrag="return false" src="__STATIC__/common/plugins/ImageUpload/images/default.jpg"><div class="mask"></div></div>');
                    array_push($html,'<div class="operate"><i class="del">删除</i></div>');
                    array_push($html,'</div>');
                    array_push($html,'</li>');
                }
            }else{
                //取得图片地址
                if(empty($default)){
                    $photo_class = "";
                    $photo_url = "__STATIC__/common/plugins/ImageUpload/images/default.jpg";
                }else{
                    $photo_class = "has";
                    $photo_url = $default;
                }

                //拼接上传图片HTML
                array_push($html,'<li class="'.$photo_class.'">');
                //array_push($html,'<input name="'.$input_name.'[]" value="'.$photo_url.'" type="hidden" '.$validate.'/>');

                //判断是否多图
                if($photo_plural) {
                    array_push($html, '<input name="' . $input_name . '[]" value="'.$photo_url.'" type="hidden" ' . $validate . '>');
                }else{
                    array_push($html, '<input name="' . $input_name . '" value="'.$photo_url.'" type="hidden" ' . $validate . '>');
                }

                //拼接上传图片HTML
                array_push($html,'<div class="pic">');
                array_push($html,'<div class="preview"><img ondrag="return false" src="'.$photo_url.'"><div class="dis"></div></div>');
                array_push($html,'<div class="default"><img ondrag="return false" src="__STATIC__/common/plugins/ImageUpload/images/default.jpg"><div class="mask"></div></div>');
                array_push($html,'<div class="operate"><i class="del">删除</i></div>');
                array_push($html,'</div>');
                array_push($html,'</li>');
            }
        }

        //拼接上传图片HTML
        array_push($html,'</ul>');
        array_push($html,'</div>');

        //错误提示信息
        if($required){
            //array_push($html,"<label for='".$input_name."[]' generated='true' class='error'>*</label>");

            //判断是否多图
            if($photo_plural) {
                array_push($html,"<label for='".$input_name."[]' generated='true' class='error'>*</label>");
            }else{
                array_push($html,"<label for='".$input_name."' generated='true' class='error'>*</label>");
            }
        }else{
            //array_push($html,"<label for='".$input_name."[]' generated='true' class='error'></label>");

            //判断是否多图
            if($photo_plural) {
                array_push($html,"<label for='".$input_name."[]' generated='true' class='error'></label>");
            }else{
                array_push($html,"<label for='".$input_name."' generated='true' class='error'></label>");
            }
        }

        //结束符
        array_push($html,'</div>');

        //判断数据是否存在
        return implode("",$html);
    }

    /**
     * @功能：单图上传功能oss，网页上传
     * {:W('Common/Images/wos',array('input_name','house',array(1,2,3),'150x150'))}
     * @param $input_name string 图片字段名称
     * @param $dir string 目录
     * @param $default array|string 初期数据 数据格式array(photo_id,photo_id)
     * @param $photo_plural bool 是否为多图，默认单图 true:多图，false:单图
     * @param $required bool 是否必填
     * @param $disabled bool 是否disabled状态
     * @开发者：zc
     * @return mixed
     */
    public static function wos($input_name,$dir,$default=null,$photo_plural=false,$required=false,$disabled=false){
        //初期化
        $html = array();

        //初期化变量
        $property = null;
        $validate = null;
        if($required) $validate=" validate='{required:true}'";
        if($disabled) $disabled=" onclick='return false'";

        //判断是否加载JS
        if(!self::$photo_static){
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/ImageUpload/jquery.ui.widget.js"></script>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/ImageUpload/jquery.iframe-transport.js"></script>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/ImageUpload/jquery.fileupload.js"></script>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/jquery-ui.js"></script>');
            array_push($html,'<script type="text/javascript" src="__STATIC__/common/plugins/ImageUpload/upload_oss.js"></script>');
            array_push($html,'<link rel="stylesheet" href="__STATIC__/common/plugins/ImageUpload/upload.css"/>');
            self::$photo_static = true;
        }

        //拼接上传图片HTML
        array_push($html,'<div class="img_upload">');
        array_push($html,'<div class="img_head">');
        array_push($html,'<ul>');
        array_push($html,'<li>');
        array_push($html,'<div class="btn">');

        //取得上传oss信息
        $max_size = 1024; //单位kb
        $data_info = self::getOssInfo($max_size,1800,$dir);
        $data_url = $data_info['url'];
        if(!empty($data_info)){
            $data_info['MaxSize'] = $max_size;
            $data_info = json_encode($data_info);
            array_push($html,'<script type="text/javascript"> var ImgInfo='.$data_info.';</script>');
        }

        //判断单图还是多图
        if($photo_plural){
            array_push($html,'<input name="file" data-url="'.$data_url.'" data-field="'.$input_name.'" type="file" multiple="true" '.$disabled.'>');
        }else{
            array_push($html,'<input name="file" data-url="'.$data_url.'" data-field="'.$input_name.'" type="file" '.$disabled.'>');
        }

        //拼接上传图片HTML
        array_push($html,'</div>');
        array_push($html,'<div class="msg"></div>');

        //判断是否为多图上传
        if($photo_plural){
            array_push($html,'<div class="tip"><p>说明：多图上传时点击图片框，可以拖拽排序！</p></div>');
        }else{
            array_push($html,'<div class="tip"><p>说明：单图上传！</p></div>');
        }

        //拼接上传图片HTML
        array_push($html,'</li>');
        array_push($html,'</ul>');
        array_push($html,'</div>');
        array_push($html,'<div class="img_body">');
        array_push($html,'<ul>');

        //判断是否存在初期值
        if(empty($default)){
            //拼接上传图片HTML
            array_push($html,'<li>');
            //array_push($html, '<input name="' . $input_name . '[]" value="" type="hidden" ' . $validate . '>');

            //判断是否多图
            if($photo_plural) {
                array_push($html, '<input name="' . $input_name . '[]" value="" type="hidden" ' . $validate . '>');
            }else{
                array_push($html, '<input name="' . $input_name . '" value="" type="hidden" ' . $validate . '>');
            }

            //拼接上传图片HTML
            array_push($html,'<div class="pic">');
            array_push($html,'<div class="preview"><img ondrag="return false" src=""><div class="dis"></div></div>');
            array_push($html,'<div class="default"><img ondrag="return false" src="__STATIC__/common/plugins/ImageUpload/images/default.jpg"><div class="mask"></div></div>');
            array_push($html,'<div class="operate hide"><i class="del">删除</i></div>');
            array_push($html,'</div>');
            array_push($html,'</li>');
        }else{
            //判断值是否为空；
            if(is_array($default) && count($default) > 0){
                //循环赋值
                foreach($default as $photo_url){
                    //取得图片地址
                    if(empty($photo_url)){
                        $photo_class = "";
                        $photo_url = "__STATIC__/common/plugins/ImageUpload/images/default.jpg";
                    }else{
                        $photo_class = "has";
                    }

                    //拼接上传图片HTML
                    array_push($html,'<li class="'.$photo_class.'">');
                    //array_push($html,'<input name="'.$input_name.'[]" value="'.$photo_url.'" type="hidden" '.$validate.'>');

                    //判断是否多图
                    if($photo_plural) {
                        array_push($html, '<input name="' . $input_name . '[]" value="'.$photo_url.'" type="hidden" ' . $validate . '>');
                    }else{
                        array_push($html, '<input name="' . $input_name . '" value="'.$photo_url.'" type="hidden" ' . $validate . '>');
                    }

                    //拼接上传图片HTML
                    array_push($html,'<div class="pic">');
                    array_push($html,'<div class="preview"><img ondrag="return false" src="'.$photo_url.'"><div class="dis"></div></div>');
                    array_push($html,'<div class="default"><img ondrag="return false" src="__STATIC__/common/plugins/ImageUpload/images/default.jpg"><div class="mask"></div></div>');
                    array_push($html,'<div class="operate"><i class="del">删除</i></div>');
                    array_push($html,'</div>');
                    array_push($html,'</li>');
                }
            }else{
                //取得图片地址
                if(empty($default)){
                    $photo_class = "";
                    $photo_url = "__STATIC__/common/plugins/ImageUpload/images/default.jpg";
                }else{
                    $photo_class = "has";
                    $photo_url = $default;
                }

                //拼接上传图片HTML
                array_push($html,'<li class="'.$photo_class.'">');
                //array_push($html,'<input name="'.$input_name.'[]" value="'.$photo_url.'" type="hidden" '.$validate.'/>');

                //判断是否多图
                if($photo_plural) {
                    array_push($html, '<input name="' . $input_name . '[]" value="'.$photo_url.'" type="hidden" ' . $validate . '>');
                }else{
                    array_push($html, '<input name="' . $input_name . '" value="'.$photo_url.'" type="hidden" ' . $validate . '>');
                }

                //拼接上传图片HTML
                array_push($html,'<div class="pic">');
                array_push($html,'<div class="preview"><img ondrag="return false" src="'.$photo_url.'"><div class="dis"></div></div>');
                array_push($html,'<div class="default"><img ondrag="return false" src="__STATIC__/common/plugins/ImageUpload/images/default.jpg"><div class="mask"></div></div>');
                array_push($html,'<div class="operate"><i class="del">删除</i></div>');
                array_push($html,'</div>');
                array_push($html,'</li>');
            }
        }

        //拼接上传图片HTML
        array_push($html,'</ul>');
        array_push($html,'</div>');

        //错误提示信息
        if($required){
            //array_push($html,"<label for='".$input_name."[]' generated='true' class='error'>*</label>");

            //判断是否多图
            if($photo_plural) {
                array_push($html,"<label for='".$input_name."[]' generated='true' class='error'>*</label>");
            }else{
                array_push($html,"<label for='".$input_name."' generated='true' class='error'>*</label>");
            }
        }else{
            //array_push($html,"<label for='".$input_name."[]' generated='true' class='error'></label>");

            //判断是否多图
            if($photo_plural) {
                array_push($html,"<label for='".$input_name."[]' generated='true' class='error'></label>");
            }else{
                array_push($html,"<label for='".$input_name."' generated='true' class='error'></label>");
            }
        }

        //结束符
        array_push($html,'</div>');

        //判断数据是否存在
        return implode("",$html);
    }

    /**
     * @功能：oss网页上传
     * @param $size int 限制图片大小
     * @param $expire int 有效期限
     * @param $path string 保存地址
     * @开发者：zc
     * @return mixed
     */
    public static function getOssInfo($size=1024, $expire=1800, $path='pr/products/'){
        //取得配置信息
        $oss_config = Config::get('oss');

        //设置配置参数
        $options['expiration'] = date('Y-m-d\TH:i:s.0000\Z',intval(time())+$expire); //过期时间
        $options['conditions'][] = array('bucket' => $oss_config['bucket']); //存储空间

        //设置限制大小
        if(intval($size) > 0){
            //设置限制上传文件大小
            $options['conditions'][] = array('content-length-range', 0, intval($size)*1024);
        }

        //整理数据
        $response['policy'] = base64_encode(json_encode($options));
        $response['Signature'] = base64_encode(hash_hmac('sha1', base64_encode(json_encode($options)),$oss_config['access_key_secret'], true));
        $response['OSSAccessKeyId'] = $oss_config['access_key_id'];
        $response['path'] = $path;
        $response['url'] = $oss_config['url'];

        //返回结果
        return $response;
    }

    /**
     * @功能：图片显示
     * {:W('Common/Images/img',array('1',1,'150x150'))}
     * @param $photo_url string 图片字段名称
     * @param $photo_width string 宽度
     * @param $photo_height string 高度
     * @开发者：zc
     * @return mixed
     */
    public static function img($photo_url,$photo_width="30",$photo_height="30"){
        //判断是否取得图片
        if(empty($photo_url)){
            //返回值
            return "未上传";
//            return "<img width='100px;' height='100px;' src='__STATIC__/common/plugins/ImageUpload/images/default.jpg'>";
        }else{
            //返回值
            return "<a target='_blank' class='blue' href='{$photo_url}'><img width='{$photo_width}px;' height='{$photo_height}px;' src='{$photo_url}'></a>";
        }
    }

    /**
     * @功能：设置页面json数据
     * @param $key string 键
     * @param $value string 值
     * @开发者： 陈旭林
     */
    private static function json($key,$value){
        //取得原来配置信息
        $json = Config::get("json");

        //判断数据是否存在
        if(empty($json)){
            //添加新数据
            $json[$key] = $value;
        }else {
            //获取旧数据
            $json = json_decode($json, true);

            //添加新数据
            $json[$key] = $value;
        }

        //设置json数据
        Config::set("json",json_encode($json));
    }
}