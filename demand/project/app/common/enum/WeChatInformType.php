<?php
/**
 * @功能：接口类型
 * @文件：IsDelete.class.php
 * @作者：zc
 * @时间：15-4-2 下午4:16
 */
namespace app\common\enum;

class WeChatInformType{
    const Message = '1'; //聊天信息 
    const State = '2'; //需求状态   消息字段['需求内容'，'审核结果'，'回复结果']
    const Received = '3'; //收到任务  消息字段['项目名称'，'任务内容'，'项目负责人' ,'任务协同','任务计划时间']
    const Complete = '4'; //任务完成  消息字段['需求名称'，'时间'，'需求状态']
    const Submission = '5'; //任务完成  消息字段['需求信息'，'提交日期']
}