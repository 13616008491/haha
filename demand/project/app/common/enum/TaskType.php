<?php
/**
 * @功能：枚举类
 * @文件：UserLevelTypeStatus.class.php
 * @作者：陈旭林
 * @时间：15-4-2 下午4:16
 */
namespace app\common\enum;

class TaskType{
    const Development = '1'; //设计
    const Design = '2'; //开发
    const Test = '3'; //测试
    const Operation = '4'; //运维
    const Demand = '5'; //需求调研
    const Technology = '6'; //技术调研
    const Meeting = '7'; //会议
    const Manage = '8'; //管理
    const Other = '9'; //其他
}