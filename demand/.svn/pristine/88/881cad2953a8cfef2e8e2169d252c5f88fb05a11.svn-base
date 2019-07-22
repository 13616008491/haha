<?php

namespace app\common\ext;

use app\common\enum\AgentAccountEvent;
use app\common\enum\PromotionStatus;
use app\common\enum\ReleaseStatus;

class IAgentPromotion{
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
     * @功能 支付促销包
     * @开发者：gys
     * @param $agent_id
     * @param $promotion_id
     * @return bool
     */
    public static function pay($agent_id,$promotion_id){

        $where['ap.agent_id'] = $agent_id;
        $where['ap.agent_promotion_id'] = $promotion_id;
        $promotion_info = IDb::getInstance("agent_promotion as ap")
            ->setDbFiled("ap.*,sg.release_status as goods_status,sp.release_status as product_status")
            ->setDbJoin("supplier_goods as sg","ap.supplier_goods_id = sg.supplier_goods_id")
            ->setDbJoin("supplier_product as sp","ap.supplier_product_id = sp.supplier_product_id")
            ->setDbJoin("agent as a","a.agent_id = ap.agent_id")
            ->setDbWhere($where)
            ->row();

        if(empty($promotion_info)){
            self::$error = '促销包不存在';
            return false;
        }

        if($promotion_info['promotion_status'] != PromotionStatus::Apply){
            self::$error = "促销包状态不可支付";
            return false;
        }

        if($promotion_info['goods_status'] != ReleaseStatus::Release || $promotion_info['product_status'] != ReleaseStatus::Release){
            self::$error = '商品已下架';
            return false;
        }

        if($promotion_info['promotion_price'] <= 0 || $promotion_info['product_num'] <= 0){
            self::$error = '促销包数据不正确';
            return false;
        }

        $pay_amount = bcmul($promotion_info['promotion_price'], $promotion_info['product_num'], 2);

        //判断账号余额是否充足
        $agent_where['agent_id'] = $agent_id;
        $agent_info = IDb::getInstance("agent")->setDbWhere($agent_where)->row();
        if (empty($agent_info)) {
            self::$error = "支付失败：取得代理商信息失败！";
            return false;
        }

        //判断余额是否充足
        $balance = floatval($agent_info['balance']);
        if(bccomp($balance,$pay_amount,2) < 0){
            self::$error = "支付失败：可用余额不足！";
            return false;
        }

        //判断支付金额是否正确
        if(bccomp($pay_amount,0,2) <= 0){
            self::$error = "支付失败：订单金额错误！";
            return false;
        }

        IDb::dbStartTrans();

        //修改资金记录
        $account_log = IAgentAccount::AccountLog(AgentAccountEvent::BalancePromotion, $agent_id, $pay_amount, $promotion_id);
        if ($account_log === false) {
            self::$error = "支付失败：".IAgentAccount::getError();
            IDb::dbRollback();
            return false;
        }

        //修改促销包状态
        $result = IDb::getInstance('agent_promotion')
            ->setDbData(['promotion_status'=>PromotionStatus::Pay])
            ->setDbWhere(['agent_promotion_id'=>$promotion_id])
            ->upd();
        if(!$result){
            self::$error = "支付失败";
            IDb::dbRollback();
            return false;
        }

        IDb::dbCommit();
        return true;
    }

    /**
     * @功能 促销包退款
     * @开发者：gys
     * @param $promotion_id
     * @return bool
     */
    public static function refund($promotion_id){

        $where['ap.agent_promotion_id'] = $promotion_id;
        $promotion_info = IDb::getInstance("agent_promotion as ap")
            ->setDbWhere($where)
            ->row();
        if($promotion_info['promotion_status'] != PromotionStatus::Pay){
            self::$error = "促销包状态不可退款";
            return false;
        }

        //修改促销包状态
        $promotion_where['agent_promotion_id'] = $promotion_id;
        $promotion_data['promotion_status'] = PromotionStatus::VerifyNG;
        $promotion_data['update_admin_id'] = get_login_admin_id();
        $promotion_data['update_time'] = get_date_time();
        if(!empty($verify_note)) $promotion_data['verify_note'] = $verify_note;
        $agent_promotion_update = IDb::getInstance('agent_promotion')->setDbData($promotion_data)->setDbWhere($promotion_where)->upd();
        if(!$agent_promotion_update){
            self::$error = "促销包状态更新失败";
            return false;
        }

        $agent_id = $promotion_info['agent_id'];
        $pay_amount = $promotion_info['pay_amount'];

        //修改资金记录
        $account_log = IAgentAccount::AccountLog(AgentAccountEvent::BalancePromotionRefund, $agent_id, $pay_amount, $promotion_id);
        if ($account_log === false) {
            self::$error = "退款失败：".IAgentAccount::getError();
            return false;
        }

        return true;
    }
}