<?php
/**
 * @功能：接口类型
 * @文件：IsDelete.class.php
 * @作者：zc
 * @时间：15-4-2 下午4:16
 */
namespace app\common\enum;

class PayType{
    const Stock = '2'; //现有库存支付
    const Balance = '1'; //余额支付
    const BalanceReturn = '3'; //返点金额支付
}