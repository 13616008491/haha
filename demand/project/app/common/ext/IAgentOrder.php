<?php
namespace app\common\ext;

use app\common\enum\AgentAccountEvent;
use app\common\enum\AgentOrderStatus;
use app\common\enum\AgentStockEvent;
use app\common\enum\OpenStatus;
use app\common\enum\PayType;
use app\common\enum\RefundStatus;
use app\common\enum\ReleaseStatus;
use app\common\enum\VerifyStatus;

class IAgentOrder{
    const REFUND_EVENT_AUDIT_FAIL = 1; //审核失败
    const REFUND_EVENT_ORDER_UP = 2; //订单升级
    const REFUND_EVENT_ORDER_REFUND = 3; //退单

    const PAY_EVENT_ORDER_PAY = 1; //下单支付
    const PAY_EVENT_ORDER_UP = 2; //订单升级

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
     * @功能 取得错误信息
     * @param $agent_order_id int 订单编号
     * @param $order_status int 订单状态
     * @param $verify_note string 审核说明
     * @开发者：cxl
     * @return string
     */
    public static function audit($agent_order_id, $order_status, $verify_note = ''){
        //判断审核状态是否正确
        if(!in_array($order_status,array(AgentOrderStatus::Reject,AgentOrderStatus::Pass))){
            self::$error = '审核状态错误！';
            return false;
        }

        //取得订单数据
        $agent_order_where['agent_order_id'] = $agent_order_id;
        $order_info = IDb::getInstance("agent_order")->setDbWhere($agent_order_where)->row();
        if(empty($order_info)){
            self::$error = '订单不存在！';
            return false;
        }

        //判断订单是否已经支付
        if($order_info['order_status'] != AgentOrderStatus::Pay){
            self::$error = '订单已审核！';
            return false;
        }

        //开启事物处理
        IDb::dbStartTrans();

        //判断是否审核失败
        $open_status = OpenStatus::Wait;
        if($order_status == AgentOrderStatus::Reject){ //审核失败
            //退款
            $result = self::refund(self::REFUND_EVENT_AUDIT_FAIL,$order_info['agent_id'],$order_info);
            if(!$result){
                IDb::dbRollback();
                return false;
            }
        }else{
            //设置提单状态
            $open_status = OpenStatus::OpenWait;
        }

        //修改订单状态
        $order_data['order_status'] = $order_status;
        $order_data['verify_admin_id'] = get_login_admin_id();
        $order_data['verify_time'] = get_date_time();
        $order_data['verify_note'] = $verify_note;
        $order_data['open_status'] = $open_status;
        $agent_order_upd = IDb::getInstance('agent_order')->setDbWhere(['agent_order_id'=>$agent_order_id])->setDbData($order_data)->upd();
        if(empty($agent_order_upd)){
            self::$error = '修改订单状态失败';
            IDb::dbRollback();
            return false;
        }

        //提交事物处理
        IDb::dbCommit();

        //返回值
        return true;
    }

    /**
     * @功能 订单升级
     * @param $agent_order_up_id int 升级编号
     * @param $verify_status int 订单状态
     * @param $verify_note string 审核说明
     * @开发者：cxl
     * @return string
     */
    public static function up($agent_order_up_id, $verify_status, $verify_note = ''){
        //判断审核状态是否正确
        if(!in_array($verify_status,array(VerifyStatus::VerifyOK,VerifyStatus::VerifyNG))){
            self::$error = '审核状态错误！';
            return false;
        }

        $admin_id = get_login_admin_id();

        $up_info = IDb::getInstance('agent_order_up as aou')
            ->setDbWhere(['agent_order_up_id'=>$agent_order_up_id])
            ->row();

        if(empty($up_info['from_order_id'])){
            if(empty($order_info)){
                self::$error = '要升级的订单不存在！';
                return false;
            }
        }

        //取得订单数据
        $old_order_id = $up_info['from_order_id'];
        $agent_order_where['agent_order_id'] = $old_order_id;
        $order_info = IDb::getInstance("agent_order")->setDbWhere($agent_order_where)->row();
        if(empty($order_info)){
            self::$error = '订单不存在！';
            return false;
        }

        //判断订单是否已经支付
        if($order_info['order_status'] != AgentOrderStatus::Up){
            self::$error = '订单状态错误！';
            return false;
        }

        //开启事物处理
        IDb::dbStartTrans();

        if($verify_status == VerifyStatus::VerifyOK){

            $agent_id = $order_info['agent_id'];

            //旧订单退款
            $result = self::refund(self::REFUND_EVENT_ORDER_UP,$agent_id,$order_info);
            if(!$result){
                IDb::dbRollback();
                return false;
            }

            //检查商品状态
            $result = self::checkGoods($up_info['supplier_goods_id'],$up_info['supplier_product_id']);
            if(!$result){
                IDb::dbRollback();
                return false;
            }

            //判断是否使用库存
            $discount = floatval($up_info['discount']);
            $supplier_product_price = floatval($up_info['product_price']);
            $product_order_num = intval($order_info['product_num']);
            if($up_info['pay_type'] == PayType::Stock){
                //设置产品价格
                $order_amount = 0;
                $pay_amount = 0;
            }else{
                //计算订单金额
                $order_amount = ($supplier_product_price * $product_order_num);
                $pay_amount = round((($order_amount * $discount)/10),2);

                //判断支付金额是否正确
                if(bccomp($pay_amount,0,2) <= 0){
                    self::$error = '订单金额错误';
                    IDb::dbRollback();
                    return false;
                }
            }

            $op_time = get_date_time();
            $new_order = [];
            $new_order['from_order_id'] = $old_order_id;
            $new_order['agent_id'] = $order_info['agent_id'];
            $new_order['agent_customer_id'] = $order_info['agent_customer_id'];
            $new_order['product_num'] = $order_info['product_num'];

            $new_order['supplier_goods_id'] = $up_info['supplier_goods_id'];
            $new_order['supplier_product_id'] = $up_info['supplier_product_id'];
            $new_order['pay_type'] = $up_info['pay_type'];
            $new_order['product_price'] = $up_info['product_price'];
            $new_order['discount'] = $up_info['discount'];
            $new_order['order_amount'] = $order_amount;
            $new_order['pay_amount'] = $pay_amount;
            $new_order['order_status'] = AgentOrderStatus::Pass;
            $new_order['open_status'] = OpenStatus::OpenWait;
            $new_order['remark'] = '订单升级，自动生成';
            $new_order['place_time'] = $op_time;

            $new_order['service_url'] = $order_info['service_url'];
            $new_order['know_url'] = $order_info['know_url'];
            $new_order['proxy_url'] = $order_info['proxy_url'];
            $new_order['business_url'] = $order_info['business_url'];
            $new_order['identity_front_url'] = $order_info['identity_front_url'];
            $new_order['identity_back_url'] = $order_info['identity_back_url'];
            $new_order['voice_url'] = $order_info['voice_url'];
            $new_order['video_url'] = $order_info['video_url'];

            $new_order['verify_admin_id'] = get_login_admin_id();
            $new_order['verify_time'] = $op_time;
            $new_order['pay_time'] = $op_time;
            $new_order['verify_note'] = '订单升级，自动通过';

            $new_order['agent_order_id'] = IDb::getInstance("agent_order")->setDbData($new_order)->add();
            if(!$new_order['agent_order_id']){
                self::$error = "添加订单失败！";
                IDb::dbRollback();
                return false;
            }

            $result = self::pay(self::PAY_EVENT_ORDER_UP,$agent_id,$new_order);
            if(!$result){
                IDb::dbRollback();
                return false;
            }

            $up_data['to_order_id'] = $new_order['agent_order_id'];
            $order_data['to_order_id'] = $new_order['agent_order_id'];

            //旧订单状态
            $order_data['order_status'] = AgentOrderStatus::UpClose;
        }else{

            //旧订单状态
            $order_data['order_status'] = AgentOrderStatus::Pass;
        }

        //修改旧订单状态
        $agent_order_upd = IDb::getInstance('agent_order')->setDbWhere(['agent_order_id'=>$old_order_id])->setDbData($order_data)->upd();
        if(empty($agent_order_upd)){
            self::$error = '修改旧订单状态失败';
            IDb::dbRollback();
            return false;
        }

        //修改升级申请
        $up_data['verify_status'] = $verify_status;
        $up_data['verify_note'] = $verify_note;
        $up_data['verify_admin_id'] = $admin_id;
        $up_data['verify_time'] = get_date_time();
        $result = IDb::getInstance('agent_order_up')->setDbWhere(['agent_order_up_id'=>$agent_order_up_id])->setDbData($up_data)->upd();
        if(!$result){
            self::$error = '修改升级申请失败';
            IDb::dbRollback();
            return false;
        }

        //提交事物处理
        IDb::dbCommit();

        //返回值
        return true;
    }

    public static function checkGoods($supplier_goods_id,$supplier_product_id){

        //取得商品信息
        $supplier_goods_where['supplier_goods_id'] = $supplier_goods_id;
        $supplier_goods_info = IDb::getInstance('supplier_goods')->setDbWhere($supplier_goods_where)->row();
        if(empty($supplier_goods_info)){
            self::$error = '取得商品信息失败！';
            return false;
        }

        //判断是否上架
        if($supplier_goods_info['release_status'] != ReleaseStatus::Release){
            self::$error = '新商品规格已下架！';
            return false;
        }

        //取得商品信息
        $supplier_product_where['supplier_product_id'] = $supplier_product_id;
        $supplier_product_info = IDb::getInstance('supplier_product')->setDbWhere($supplier_product_where)->row();
        if(empty($supplier_product_info)){
            self::$error = '取得商品规格信息失败！';
            return false;
        }

        //判断商品和规格是否一致
        if($supplier_product_info['supplier_goods_id'] != $supplier_goods_id){
            self::$error = '取得商品和规格不一致！';
            return false;
        }

        //判断是否上架
        if($supplier_product_info['release_status'] != ReleaseStatus::Release){
            self::$error = '新商品规格已下架！';
            return false;
        }

        return true;
    }

    /**
     * 账户支付操作
     * @param $pay_even
     * @param $agent_id
     * @param $agent_order_info
     * @return bool
     */
    private static function pay($pay_even,$agent_id,$agent_order_info){
        $agent_order_id = $agent_order_info['agent_order_id'];

        $balance_log_type = AgentAccountEvent::BalanceLading;
        $return_log_type = AgentAccountEvent::BalanceReturnUse;
        $stock_log_type = AgentStockEvent::Lading;

        $admin_id = null;
        switch($pay_even){
            case self::PAY_EVENT_ORDER_PAY:

                break;
            case self::PAY_EVENT_ORDER_UP:
                //注意，总后台模块才有此方法
                $admin_id = get_login_admin_id();
                break;
            default:
                self::$error = '支付失败：支付事件错误';
                return false;
        }

        //判断是否修改订单库存
        if($agent_order_info['pay_type'] == PayType::Stock){
            //取得库存信息
            $agent_stock_where['agent_id'] = $agent_id;
            $agent_stock_info = IDb::getInstance("agent_stock")->setDbWhere($agent_stock_where)->row();
            if (empty($agent_stock_info)) {
                self::$error = "订单支付失败：取得商品库存信息失败！";
                return false;
            }

            $pay_stock = intval($agent_order_info['product_num']);
            if($pay_stock <= 0){
                self::$error = "订单支付失败：支付库存错误！";
                return false;
            }

            //判断库存是否充足
            $stock_num = intval($agent_stock_info['stock_num']);
            if($pay_stock > $stock_num){
                self::$error = "订单支付失败：可用库存不足！";
                return false;
            }

            //增加商品库存
            $stock_log = IAgentStock::StockLog($stock_log_type,$agent_id,$agent_order_info['supplier_goods_id'],$agent_order_info['supplier_product_id'],$pay_stock,null,$agent_order_id,$admin_id);
            if ($stock_log === false) {
                self::$error = "订单支付失败：".IAgentStock::getError();
                return false;
            }
        }else if($agent_order_info['pay_type'] == PayType::BalanceReturn){
            //判断账号余额是否充足
            $agent_where['agent_id'] = $agent_id;
            $agent_info = IDb::getInstance("agent")->setDbWhere($agent_where)->row();
            if (empty($agent_info)) {
                self::$error = "订单支付失败：取得代理商信息失败！";
                return false;
            }

            //判断余额是否充足
            $pay_amount = floatval($agent_order_info['pay_amount']);
            $return = floatval($agent_info['return']);
            if(bccomp($return,$pay_amount,2) < 0){
                self::$error = "订单支付失败：可用返点余额不足！";
                return false;
            }

            //判断支付金额是否正确
            if(bccomp($pay_amount,0,2) <= 0){
                self::$error = "订单支付失败：订单金额错误！";
                return false;
            }

            //修改资金记录
            $account_log = IAgentAccount::AccountLog($return_log_type, $agent_id, $agent_order_info['pay_amount'], $agent_order_id,$admin_id);
            if ($account_log === false) {
                self::$error = "订单支付失败：" . IAgentAccount::getError();
                return false;
            }
        }else{
            //判断账号余额是否充足
            $agent_where['agent_id'] = $agent_id;
            $agent_info = IDb::getInstance("agent")->setDbWhere($agent_where)->row();
            if (empty($agent_info)) {
                self::$error = "订单支付失败：取得代理商信息失败！";
                return false;
            }

            //判断余额是否充足
            $pay_amount = floatval($agent_order_info['pay_amount']);
            $balance = floatval($agent_info['balance']);
            if(bccomp($balance,$pay_amount,2) < 0){
                self::$error = "订单支付失败：可用余额不足！";
                return false;
            }

            //判断支付金额是否正确
            if(bccomp($pay_amount,0,2) <= 0){
                self::$error = "订单支付失败：订单金额错误！";
                return false;
            }

            //修改资金记录
            $account_log = IAgentAccount::AccountLog($balance_log_type, $agent_id, $agent_order_info['pay_amount'], $agent_order_id,$admin_id);
            if ($account_log === false) {
                self::$error = "订单支付失败：".IAgentAccount::getError();
                return false;
            }
        }

        return true;
    }

    /**
     * 订单退款
     * @param $refund_event
     * @param $agent_id
     * @param $order_info
     * @return bool
     */
    private static function refund($refund_event, $agent_id, $order_info){
        if(!is_array($order_info) && !isset($order_info['agent_id'])){
            self::$error = '退款失败：订单错误';
            return false;
        }

        $order_id = $order_info['agent_order_id'];

        //注意，总后台模块才有此方法
        $admin_id = get_login_admin_id();

        switch($refund_event){
            case self::REFUND_EVENT_AUDIT_FAIL:
                $balance_log_type = AgentAccountEvent::BalanceReturnLading;
                $return_log_type = AgentAccountEvent::BalanceReturnBack;
                $stock_log_type = AgentStockEvent::ReturnLading;
                break;
            case self::REFUND_EVENT_ORDER_UP:
            case self::REFUND_EVENT_ORDER_REFUND:
                $balance_log_type = AgentAccountEvent::BalanceRefund;
                $return_log_type = AgentAccountEvent::BalanceReturnRefund;
                $stock_log_type = AgentStockEvent::Refund;
                break;
                break;
            default:
                self::$error = '退款失败：退款类型错误';
                return false;
        }
        if($order_info['pay_type'] == PayType::Balance) {
            //修改账户
            $result = IAgentAccount::AccountLog($balance_log_type, $agent_id, $order_info['pay_amount'], $order_id, $admin_id);
            if ($result === false) {
                self::$error = '退款失败：'.IAgentAccount::getError();
                return false;
            }
        }else if($order_info['pay_type'] == PayType::BalanceReturn){
            //修改返点账户
            $result = IAgentAccount::AccountLog($return_log_type, $agent_id, $order_info['pay_amount'], $order_id, $admin_id);
            if ($result === false) {
                self::$error = '退款失败：'.IAgentAccount::getError();
                return false;
            }
        }else if($order_info['pay_type'] == PayType::Stock){
            $pay_stock = intval($order_info['product_num']);
            if($pay_stock <= 0){
                self::$error = "订单支付失败：支付库存错误！";
                return false;
            }

            //修改商品库存
            $result = IAgentStock::StockLog($stock_log_type, $agent_id, $order_info['supplier_goods_id'], $order_info['supplier_product_id'], $pay_stock, null, $order_id, $admin_id);
            if ($result === false) {
                self::$error = '退款失败：'.IAgentAccount::getError();
                return false;
            }
        }else{
            self::$error = "退款失败：支付方式错误！";
            return false;
        }

        return true;
    }

    /**
     * 支付订单
     * @param $agent_id
     * @param $agent_order_id
     * @return bool
     */
    public static function payOrder($agent_id,$agent_order_id){
        //取得订单数据
        $agent_order_where['agent_id'] = $agent_id;
        $agent_order_where['agent_order_id'] = $agent_order_id;
        $agent_order_info = IDb::getInstance("agent_order")->setDbWhere($agent_order_where)->row();
        if(empty($agent_order_info)){
            self::$error = "要支付的订单不存在！";
            return false;
        }

        //判断订单是否支付
        if($agent_order_info['order_status'] != AgentOrderStatus::Create){
            self::$error = "该订单不可支付！";
            return false;
        }

        $result = self::pay(self::PAY_EVENT_ORDER_PAY,$agent_id,$agent_order_info);
        if(!$result){
            return false;
        }

        //修改订单支付状态
        $agent_order_where['agent_order_id'] = $agent_order_id;
        $agent_order_data['order_status'] = AgentOrderStatus::Pay;
        $agent_order_data['pay_time'] = get_date_time();
        $agent_order_upd = IDb::getInstance("agent_order")->setDbData($agent_order_data)->setDbWhere($agent_order_where)->upd();
        if($agent_order_upd === false){
            self::$error = "更新订单状态失败";
            return false;
        }

        return true;
    }

    /**
     * @功能 设置开通状态
     * @param $agent_order_id int 订单编号
     * @param $open_status int 订单状态
     * @param $open_note string 审核说明
     * @开发者：gys
     * @return string
     */
    public static function open($agent_order_id, $open_status, $open_note = ''){
        //判断审核状态是否正确
        if(!in_array($open_status,array(OpenStatus::OpenOK,OpenStatus::OpenNG))){
            self::$error = '开通状态错误！';
            return false;
        }

        //取得订单数据
        $agent_order_where['agent_order_id'] = $agent_order_id;
        $order_info = IDb::getInstance("agent_order")->setDbWhere($agent_order_where)->row();
        if(empty($order_info)){
            self::$error = '订单不存在！';
            return false;
        }

        //目前熙睿语音可以手动设置
        if(!in_array($order_info['supplier_goods_id'],[1,2])){
            self::$error = '该订单不可手动设置！';
            return false;
        }

        //判断订单是否已经支付
        if($order_info['order_status'] != AgentOrderStatus::Pass){
            self::$error = '请先审核订单！';
            return false;
        }

        //判断订单是否已经支付
        if($order_info['open_status'] == OpenStatus::OpenOK){
            self::$error = '订单已开通！';
            return false;
        }


        if(!empty($open_note)){
            $order_data['open_note'] = $open_note;
        }

        //修改订单状态
        $order_data['open_status'] = $open_status;
        $agent_order_upd = IDb::getInstance('agent_order')->setDbWhere(['agent_order_id'=>$agent_order_id])->setDbData($order_data)->upd();
        if(empty($agent_order_upd)){
            self::$error = '修改开通状态失败';
            return false;
        }

        //返回值
        return true;
    }

    /**
     * 申请退款
     * @param $agent_order_id
     * @param $apply_note
     * @return bool
     */
    public static function refundApply($agent_order_id,$apply_note){
        if(empty($apply_note)){
            self::$error = '退单说明不能为空';
            return false;
        }

        //取得订单数据
        $agent_order_where['agent_order_id'] = $agent_order_id;
        $order_info = IDb::getInstance("agent_order")->setDbWhere($agent_order_where)->row();
        if(empty($order_info)){
            self::$error = '订单不存在！';
            return false;
        }

        //目前语音产品可以退单
        if(!in_array($order_info['supplier_product_id'],[1,2,8,14])){
            self::$error = '该订单不可申请退单！';
            return false;
        }

        //目前语音产品可以退单
        if($order_info['order_status'] != AgentOrderStatus::Pass){
            self::$error = '该订单状态不可申请退单！';
            return false;
        }

        IDb::startTrans();

        //添加申请
        $data['agent_id'] = $order_info['agent_id'];
        $data['agent_order_id'] = $agent_order_id;
        $data['apply_note'] = $apply_note;
        $data['apply_admin_id'] = get_login_admin_id();
        $data['apply_time'] = get_date_time();
        $result = IDb::getInstance('agent_order_refund')->setDbData($data)->add();
        if(!$result){
            IDb::dbRollback();
            self::$error = '添加退单申请失败';
            return false;
        }

        //修改订单状态
        $order_data['order_status'] = AgentOrderStatus::Refund;
        $agent_order_upd = IDb::getInstance('agent_order')->setDbWhere(['agent_order_id'=>$agent_order_id])->setDbData($order_data)->upd();
        if(empty($agent_order_upd)){
            IDb::dbRollback();
            self::$error = '修改订单状态失败';
            return false;
        }

        IDb::dbCommit();

        return true;
    }

    /**
     * @功能 退单审核
     * @param $refund_id int 退单编号
     * @param $refund_status int 退款状态
     * @param $verify_note string 审核说明
     * @开发者：gys
     * @return string
     */
    public static function refundAudit($refund_id, $refund_status, $verify_note = ''){
        //判断审核状态是否正确
        if(!in_array($refund_status,array(RefundStatus::VerifyNG,RefundStatus::VerifyOK))){
            self::$error = '审核状态错误！';
            return false;
        }

        $refund_where['agent_order_refund_id'] = $refund_id;
        $refund_info = IDb::getInstance('agent_order_refund')->setDbWhere($refund_where)->row();
        if(!$refund_info){
            IDb::dbRollback();
            self::$error = '退单申请不存在';
            return false;
        }

        if($refund_info['refund_status'] != RefundStatus::Apply){
            IDb::dbRollback();
            self::$error = '退单申请状态错误';
            return false;
        }

        $agent_order_id = $refund_info['agent_order_id'];

        //取得订单数据
        $agent_order_where['agent_order_id'] = $agent_order_id;
        $order_info = IDb::getInstance("agent_order")->setDbWhere($agent_order_where)->row();
        if(empty($order_info)){
            self::$error = '订单不存在！';
            return false;
        }

        //判断订单是否已经支付
        if($order_info['order_status'] != AgentOrderStatus::Refund){
            self::$error = '订单状态错误！';
            return false;
        }

        //开启事物处理
        IDb::dbStartTrans();

        //判断是否审核失败
        if($refund_status == RefundStatus::VerifyOK){ //审核成功
            //退款
            $result = self::refund(self::REFUND_EVENT_ORDER_REFUND,$order_info['agent_id'],$order_info);
            if(!$result){
                IDb::dbRollback();
                return false;
            }

            //设置提单状态
            $order_status = AgentOrderStatus::RefundClose;
        }else{
            if(empty($verify_note)){
                self::$error = "审核说明不能为空！";
                IDb::dbRollback();
                return false;
            }

            //设置提单状态
            $order_status = AgentOrderStatus::Pass;
        }

        //修改申请状态
        $refund_data['refund_status'] = $refund_status;
        $refund_data['verify_admin_id'] = get_login_admin_id();
        $refund_data['verify_time'] = get_date_time();
        $refund_data['verify_note'] = $verify_note;
        $agent_order_upd = IDb::getInstance('agent_order_refund')->setDbWhere(['agent_order_refund_id'=>$refund_id])->setDbData($refund_data)->upd();
        if(empty($agent_order_upd)){
            self::$error = '修改申请状态失败';
            IDb::dbRollback();
            return false;
        }

        //修改订单状态
        $order_data['order_status'] = $order_status;
        $agent_order_upd = IDb::getInstance('agent_order')->setDbWhere(['agent_order_id'=>$agent_order_id])->setDbData($order_data)->upd();
        if(empty($agent_order_upd)){
            self::$error = '修改订单状态失败';
            IDb::dbRollback();
            return false;
        }

        //提交事物处理
        IDb::dbCommit();

        //返回值
        return true;
    }
}
