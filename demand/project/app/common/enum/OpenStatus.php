<?php
/**
 * @功能：枚举类
 * @文件：UserLevelTypeStatus.class.php
 * @作者：陈旭林
 * @时间：15-4-2 下午4:16
 */
namespace app\common\enum;

class OpenStatus{
    //开通状态【1、等待开通；2、开通中；3、开通成功；4、开通失败】
    const Wait = '1'; //等待开通
    const OpenWait = '2'; //开通中
    const OpenOK = '3'; //开通成功
    const OpenNG = '4'; //开通失败
}