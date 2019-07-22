<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：IPhoto.class.php
 * @类功能: 图片类
 * @开发者: zc
 * @开发时间： 14-10-28
 * @版本：version 1.0
 */
namespace app\common\ext;

use OSS\Core\OssException;
use OSS\OssClient;
use think\Config;
use Think\Image;

class IPhoto {
    /**
     * @功能 单图片上传(单张上传)
     * @param string $path 图片路径
     * @param string $name 表单字段名称
     * @开发者：zc
     * @return string
     */
    public static function upload($path,$name) {
        //判断目录是否存在
        if (!is_dir(UPLOADS_PATH.$path)) {
            @mkdir(UPLOADS_PATH.$path,0755,true);
        }

        //获取表单上传文件
        $file = request()->file($name);

        //判断是否为多图
        if($file){
            //移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->validate(array('size'=>1024 * 1024 * 5,'ext'=>'jpg,png,gif,jpeg'))->move(UPLOADS_PATH.$path);

            //判断文件移动是否成功
            if($info){
                //判断文件是否存在
                $file = str_ireplace("\\","/",$info->getPathname());
                if(file_exists($file)){
                    //正常返回
                    $result['code'] = "0";
                    $result['msg'] = "上传成功！";
                    $result['url'] = mb_substr($file,1,mb_strlen($file),'utf-8');
                }else{
                    //异常返回
                    $result['code'] = "99";
                    $result['msg'] = "文件不存在！";
                }
            }else{
                //异常返回
                $result['code'] = "99";
                $result['msg'] = $file->getError();
            }
        }else{
            //异常返回
            $result['code'] = "99";
            $result['msg'] = "对象未找到！";
        }

        //返回值
        return $result;
    }

    /**
     * @功能 单图片上传(单张上传)
     * @param string $path 图片路径
     * @param string $name 表单字段名称
     * @开发者：zc
     * @return string
     */
    public static function oss($path,$name) {
        //获取表单上传文件
        $file = request()->file($name);

        //判断是否为多图
        if($file){
            //加载阿里云OSS扩展
            Vendor('aliyun.autoload');

            //取得地址
            $name = $file->getFilename();
            $name = md5(get_date().$name);
            $path = config('oss.path')."{$path}/{$name}.png";

            //取得配置信息
            $access_id = Config::get("oss.access_key_id");
            $access_key = Config::get("oss.access_key_secret");
            $endpoint = Config::get("oss.endpoint");
            $bucket = Config::get("oss.bucket");

            //检查是否存在异常
            try {
                //创建对象
                $ossClient = new OssClient($access_id, $access_key, $endpoint);
                $info = $ossClient->uploadFile($bucket, $path,$file->getPathname());

                //判断图片地址是否正常
                if(strpos($info['oss-request-url'],"undefined") > 0){
                    //异常返回
                    $result['code'] = "99";
                    $result['msg'] = "上传图片失败！";

                    //返回值
                    return $result;
                }

                //返回信息
                $result['code'] = "0";
                $result['url'] = $info['oss-request-url'];
                $result['msg'] = "上传成功！";
            } catch (OssException $e) {
                //异常返回
                $result['code'] = "99";
                $result['msg'] = $e->getMessage();
            }
        }else{
            //异常返回
            $result['code'] = "99";
            $result['msg'] = "对象未找到！";
        }

        //返回值
        return $result;
    }
}