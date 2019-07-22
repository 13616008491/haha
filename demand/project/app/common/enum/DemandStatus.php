<?php
/**
 * @功能：接口类型
 * @文件：ProposeStatus.class.php
 * @作者：layaos
 * @时间：15-4-2 下午4:16
 */
namespace app\common\enum;

class DemandStatus{
    //需求状态【1：待确定，2：进行中，3：开发完成，4：已拒绝，5：测试完成，6：已上线】
    const IDENT = '1'; //待确定
    const PROCEEDING = '2'; //开发中
    const DONE = '3'; //开发完成
    const REFUSE = '4'; //已拒绝
    const TEST = '5'; //测试完成
    const UPING = '6'; //需求完成，已上线
}