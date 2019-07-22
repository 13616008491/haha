<?php
/**
 * @功能：枚举类
 * @文件：UserLevelTypeStatus.class.php
 * @作者：陈旭林
 * @时间：15-4-2 下午4:16
 */
namespace app\common\enum;

class OrderStatus{
    //订单状态【1、未支付；2、已支付；3、审核通过；4、审核失败】
    const NotPay = '1'; //未支付
    const Pay = '2'; //已支付
    const VerifyOK = '3'; //审核通过
    const VerifyNG = '4'; //审核失败
}