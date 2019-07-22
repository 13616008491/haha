<?php
namespace app\common\ext;

use app\common\cache\agent\IAgentAdminCache;
use app\common\enum\AgentAccountEvent;
use app\common\enum\IsLeader;
use think\Db;
use think\Exception;

class IAgentAccount{
    //静态变量定义
    private static $error = null;

    /**
     * @功能 取得错误信息
     * @开发者：cxl
     * @return string
     */
    public static function getError(){
        return self::$error;
    }

    /**
     * @功能 设置错误信息
     * @param string $agent_admin_id 业务员/信贷经理编号
     * @param string $note 用户ID
     * @param array $param 参数
     * @开发者：cxl
     * @return string
     */
    private static function setError($agent_admin_id,$note,$param){
        //设置错误信息；
        self::$error = $note;
        //写错误日志
        self::AgentAccountError($agent_admin_id,$note,$param);

        //返回值
        return false;
    }

    /**
     * @功能 用户资金日志
     * @param int $agent_account_event 操作类型
     * @param int $agent_id 代理商编号
     * @param int $amount 操作金额
     * @param int $item_id 操作资金对应的数据编号
     * @param int $create_admin_id 管理员编号
     * @开发者：cxl
     * @return bool
     */
    public static function AccountLog($agent_account_event,$agent_id,$amount,$item_id=null,$create_admin_id=null){
        //设置参数信息
        $param = array("agent_id"=>$agent_id,"agent_account_event"=>$agent_account_event,"amount"=>$amount,"item_id"=>$item_id,"create_admin_id"=>$create_admin_id);

        //判断资金类型
        if(!in_array($agent_account_event,array(AgentAccountEvent::BalanceRecharge,AgentAccountEvent::BalanceLading,
            AgentAccountEvent::BalanceReturnLading,AgentAccountEvent::BalanceDeduction,AgentAccountEvent::BalanceReturn,
            AgentAccountEvent::BondRecharge,AgentAccountEvent::BondDeduction,AgentAccountEvent::BalanceReturnUse,
            AgentAccountEvent::BalanceReturnBack,AgentAccountEvent::BalanceReturnRefund,AgentAccountEvent::BalanceRefund,
            AgentAccountEvent::BalancePromotion,AgentAccountEvent::BalancePromotionRefund,AgentAccountEvent::BalanceAdjustAdd,
            AgentAccountEvent::BalanceAdjustUp, AgentAccountEvent::BalanceAdjustBidAdd, AgentAccountEvent::BalanceAdjustBidUp))){
            return self::setError($agent_id,"操作类型错误！",$param);
        }

        //取得操作账户信息
        $agent_where['agent_id'] = $agent_id;
        $agent_info = IDb::getInstance("agent")->setDbWhere($agent_where)->row(true);
        if (empty($agent_info['agent_id'])) {
            return self::setError($agent_id, "取得操作账号信息失败！", $param);
        }

        //取得用户账户信息
        $balance = floatval($agent_info['balance']);
        $return = floatval($agent_info['return']);
        $bond = intval($agent_info['bond']);

        //判断用户资金信息
        $amount = floatval($amount);
        if (bccomp($amount,0,2) < 0) {
            return self::setError($agent_id,"操作金额不能小于0元",$param);
        }

        //资金操作
        $agent_account_log_data = array();
        switch($agent_account_event){
            case AgentAccountEvent::BalanceRecharge: //余额充值
                $agent_account_log_data['note'] = "余额充值，余额增加{$amount}元！";
                $agent_account_log_data['balance'] = floatval(bcadd($balance,$amount,2));
                $agent_account_log_data['return'] = $return;
                $agent_account_log_data['bond'] = $bond;
                break;
            case AgentAccountEvent::BalanceLading: //余额提单消耗
                //计算金额
                $agent_account_log_data['note'] = "余额提单，余额减少{$amount}元！";
                $agent_account_log_data['balance'] = floatval(bcsub($balance,$amount,2));
                $agent_account_log_data['return'] = $return;
                $agent_account_log_data['bond'] = $bond;

                //判断金额是否正常
                if(bccomp($agent_account_log_data['balance'],0,2) < 0){
                    return self::setError($agent_id,"余额计算错误！",$param);
                }
                break;
            case AgentAccountEvent::BalanceReturnLading: //余额提单失败返还
                $agent_account_log_data['note'] = "余额提单审核失败，余额增加{$amount}元！";
                $agent_account_log_data['balance'] = floatval(bcadd($balance,$amount,2));
                $agent_account_log_data['return'] = $return;
                $agent_account_log_data['bond'] = $bond;
                break;
            case AgentAccountEvent::BalanceRefund: //余额退单返还
                $agent_account_log_data['note'] = "退单，余额增加{$amount}元！";
                $agent_account_log_data['balance'] = floatval(bcadd($balance,$amount,2));
                $agent_account_log_data['return'] = $return;
                $agent_account_log_data['bond'] = $bond;
                break;
            case AgentAccountEvent::BalancePromotion: //余额提单消耗
                //计算金额
                $agent_account_log_data['note'] = "促销包支付，余额减少{$amount}元！";
                $agent_account_log_data['balance'] = floatval(bcsub($balance,$amount,2));
                $agent_account_log_data['return'] = $return;
                $agent_account_log_data['bond'] = $bond;

                //判断金额是否正常
                if(bccomp($agent_account_log_data['balance'],0,2) < 0){
                    return self::setError($agent_id,"余额计算错误！",$param);
                }
                break;
            case AgentAccountEvent::BalancePromotionRefund: //余额提单失败返还
                $agent_account_log_data['note'] = "促销包审核失败，余额增加{$amount}元！";
                $agent_account_log_data['balance'] = floatval(bcadd($balance,$amount,2));
                $agent_account_log_data['return'] = $return;
                $agent_account_log_data['bond'] = $bond;
                break;
            case AgentAccountEvent::BalanceDeduction: //余额扣除
                //计算金额
                $agent_account_log_data['note'] = "扣除余额，余额减少{$amount}元！";
                $agent_account_log_data['balance'] = floatval(bcsub($balance,$amount,2));
                $agent_account_log_data['return'] = $return;
                $agent_account_log_data['bond'] = $bond;

                //判断金额是否正常
                if(bccomp($agent_account_log_data['balance'],0,2) < 0){
                    return self::setError($agent_id,"余额计算错误！",$param);
                }
                break;
            case AgentAccountEvent::BalanceReturn: //余额返点
                $agent_account_log_data['note'] = "返点，返点增加{$amount}元！";
                $agent_account_log_data['balance'] = $balance;
                $agent_account_log_data['return'] = floatval(bcadd($return,$amount,2));
                $agent_account_log_data['bond'] = $bond;
                break;
            case AgentAccountEvent::BalanceReturnUse: //返点提单消耗
                //计算金额
                $agent_account_log_data['note'] = "返点提单，返点减少{$amount}元！";
                $agent_account_log_data['balance'] = $balance;
                $agent_account_log_data['return'] = floatval(bcsub($return,$amount,2));
                $agent_account_log_data['bond'] = $bond;

                //判断金额是否正常
                if(bccomp($agent_account_log_data['return'],0,2) < 0){
                    return self::setError($agent_id,"返点金额计算错误！",$param);
                }
                break;
            case AgentAccountEvent::BalanceReturnBack: //返点提单失败返还
                $agent_account_log_data['note'] = "返点提单审核失败，返点增加{$amount}元！";
                $agent_account_log_data['balance'] = $balance;
                $agent_account_log_data['return'] = floatval(bcadd($return,$amount,2));
                $agent_account_log_data['bond'] = $bond;
                break;
            case AgentAccountEvent::BalanceReturnRefund: //返点提单失败返还
                $agent_account_log_data['note'] = "退单，返点增加{$amount}元！";
                $agent_account_log_data['balance'] = $balance;
                $agent_account_log_data['return'] = floatval(bcadd($return,$amount,2));
                $agent_account_log_data['bond'] = $bond;
                break;
            case AgentAccountEvent::BondRecharge: //保证金充值
                $agent_account_log_data['note'] = "保证金充值，保证金增加{$amount}元！";
                $agent_account_log_data['bond'] = floatval(bcadd($bond,$amount,2));
                $agent_account_log_data['return'] = $return;
                $agent_account_log_data['balance'] = $balance;
                break;
            case AgentAccountEvent::BondDeduction: //保证金扣除
                //计算金额
                $agent_account_log_data['note'] = "扣除保证金，保证金减少{$amount}元！";
                $agent_account_log_data['bond'] = floatval(bcsub($bond,$amount,2));
                $agent_account_log_data['return'] = $return;
                $agent_account_log_data['balance'] = $balance;

                //判断金额是否正常
                if(bccomp($agent_account_log_data['balance'],0,2) < 0){
                    return self::setError($agent_id,"保证金计算错误！",$param);
                }
                break;
            case AgentAccountEvent::BalanceAdjustAdd: //资金调整余额增加
                //计算金额
                $agent_account_log_data['note'] = "资金调整，余额增加{$amount}元！";
                $agent_account_log_data['bond'] = $bond;
                $agent_account_log_data['return'] = $return;
                $agent_account_log_data['balance'] = floatval(bcadd($balance,$amount,2));
                break;
                case AgentAccountEvent::BalanceAdjustUp: //资金调整余额减少
                //计算金额
                $agent_account_log_data['note'] = "资金调整，余额减少{$amount}元！";
                $agent_account_log_data['bond'] = $bond;
                $agent_account_log_data['return'] = $return;
                $agent_account_log_data['balance'] = floatval(bcsub($balance,$amount,2));

                //判断金额是否正常
                if(bccomp($agent_account_log_data['balance'],0,2) < 0){
                    return self::setError($agent_id,"保证金计算错误！",$param);
                }
                break;
            case AgentAccountEvent::BalanceAdjustBidUp: //资金调整保证金减少
                //计算金额
                $agent_account_log_data['note'] = "资金调整，保证金减少{$amount}元！";
                $agent_account_log_data['bond'] = floatval(bcsub($bond,$amount,2));
                $agent_account_log_data['return'] = $return;
                $agent_account_log_data['balance'] = $balance;

                //判断金额是否正常
                if(bccomp($agent_account_log_data['balance'],0,2) < 0){
                    return self::setError($agent_id,"保证金计算错误！",$param);
                }
                break;
            case AgentAccountEvent::BalanceAdjustBidAdd: //资金调整保证金增加
                //计算金额
                $agent_account_log_data['note'] = "资金调整，保证金增加{$amount}元！";
                $agent_account_log_data['bond'] = floatval(bcadd($bond,$amount,2));
                $agent_account_log_data['return'] = $return;
                $agent_account_log_data['balance'] = $balance;

                //判断金额是否正常
                if(bccomp($agent_account_log_data['balance'],0,2) < 0){
                    return self::setError($agent_id,"保证金计算错误！",$param);
                }
                break;
        }

        //判断数据是否为空
        if(empty($agent_account_log_data)){
            return self::setError($agent_id,"代理商资金操作错误！",$param);
        }

        //修改用户资金信息
        $agent_where['agent_id'] = $agent_id;
        $agent_data['balance'] = $agent_account_log_data['balance'];
        $agent_data['return'] = $agent_account_log_data['return'];
        $agent_data['bond'] = $agent_account_log_data['bond'];
        $agent_upd = IDb::getInstance("agent")->setDbData($agent_data)->setDbWhere($agent_where)->upd();

        if ($agent_upd === false) {
            return self::setError($agent_id, "代理商资金操作错误！", $param);
        }

        //添加用户自己日志数据
        $agent_account_log_data["agent_id"] = $agent_id;
        $agent_account_log_data["agent_account_event"] = $agent_account_event;
        $agent_account_log_data["amount"] = $amount;
        $agent_account_log_data["item_id"] = $item_id;
        $agent_account_log_data["create_admin_id"] = $create_admin_id;
        $agent_account_log_data["create_time"] = get_date_time();
        $agent_stock_log_add = IDb::getInstance("agent_account_log")->setDbData($agent_account_log_data)->add();
        if($agent_stock_log_add === false){
            return self::setError($agent_id,"代理商资金操作错误！",$param);
        }

        //返回值
        return true;
    }

    /**
     * @功能 资金操作错误日志
     * @param string $agent_id 业务员/信贷经理编号
     * @param string $error 错误说明
     * @param array|null $param 错误参数
     * @开发者：cxl
     * @return bool
     */
    public static function AgentAccountError($agent_id,$error,$param){
        //整理数据
        $data["agent_id"] = $agent_id;
        $data["error"] = $error;
        $data["param"] = json_encode($param);
        $data["create_time"] = get_date_time();

        //写数据库操作错误日志
        $agent_stock_error_add = IDb::getInstance("agent_account_error")->setDbData($data)->add();
        if($agent_stock_error_add === false){
            return false;
        }

        //返回值
        return true;
    }
}
