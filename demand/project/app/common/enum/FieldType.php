<?php
/**
 * @功能：接口类型
 * @文件：IsDelete.class.php
 * @作者：zc
 * @时间：15-4-2 下午4:16
 */
namespace app\common\enum;

class FieldType{
    const Text = 1; //文本框
    const Select = 2; //下拉框
    const Radio = 3; //单选框
    const TextArea = 4; //文本域
    const Img = 5; //单图
    const ImgMulti = 6; //多图
    const File = 7; //文件
    const FileMulti = 8; //多文件
}