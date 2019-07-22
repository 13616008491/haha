<?php
namespace app\common\ext;

use app\common\cache\agent\IAgentAdminCache;
use app\common\enum\AgentAccountEvent;
use app\common\enum\AgentStockEvent;
use app\common\enum\IsLeader;
use think\Db;
use think\Exception;

class IAgentStock{
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
     * @param string $agent_id 代理商编号
     * @param string $note 用户ID
     * @param array $param 参数
     * @开发者：cxl
     * @return string
     */
    private static function setError($agent_id,$note,$param){
        //设置错误信息；
        self::$error = $note;

        //写错误日志
        self::AgentAccountError($agent_id,$note,$param);

        //返回值
        return false;
    }

    /**
     * @功能 用户资金日志
     * @param int $agent_stock_event 操作类型
     * @param int $agent_id 代理商编号
     * @param int $supplier_goods_id 产品编号
     * @param int $supplier_product_id 商品规格编号
     * @param int $discount 折扣
     * @param int $operation_num 操作库存
     * @param int $item_id 操作资金对应的数据编号
     * @param int $create_admin_id 管理员编号
     * @开发者：cxl
     * @return bool
     */
    public static function StockLog($agent_stock_event,$agent_id,$supplier_goods_id,$supplier_product_id,$operation_num,$discount=null,$item_id=null,$create_admin_id=null){
        //设置参数信息
        $param = array("agent_stock_event"=>$agent_stock_event,"agent_id"=>$agent_id,"supplier_product_id"=>$supplier_product_id,"operation_num"=>$operation_num,"item_id"=>$item_id,"create_admin_id"=>$create_admin_id);

        //判断资金类型
        if(!in_array($agent_stock_event,array(AgentStockEvent::Open,AgentStockEvent::Buy,AgentStockEvent::Lading,AgentStockEvent::ReturnLading, AgentStockEvent::PromotionOk,AgentStockEvent::Refund))){
            return self::setError($agent_id,"操作类型错误！",$param);
        }

        //取得操作账户信息
        if($agent_stock_event == AgentStockEvent::Open) {
            //初期化库存
            $stock_num = 0;

            //初期化折扣
            $agent_stock_data['discount'] = $discount;

               if ((bccomp($discount, 0, 2) <= 0) || (bccomp($discount, 10, 2) > 0)) {
                   return self::setError($agent_id, "折扣必须在0.00~10.00之间！", $param);
               }

        }else{
            $agent_stock_where['agent_id'] = $agent_id;
            $agent_stock_where['supplier_goods_id'] = $supplier_goods_id;
            $agent_stock_where['supplier_product_id'] = $supplier_product_id;
            $agent_stock_info = IDb::getInstance("agent_stock")->setDbWhere($agent_stock_where)->row(true);
            if (empty($agent_stock_info['agent_id'])) {
                return self::setError($agent_id, "取得操作账号信息失败！", $param);
            }

            //取得用户账户信息
            $stock_num = intval($agent_stock_info['stock_num']);
        }

        //判断代理商库存信息
        $operation_num = intval($operation_num);
        if ($operation_num < 0) {
            return self::setError($agent_id,"操作库存不能小于0",$param);
        }

        //资金操作
        switch($agent_stock_event){
            case AgentStockEvent::Open: //首次开通设置
                $agent_stock_data['stock_num'] = $operation_num;
                $agent_stock_log_data['note'] = "设置初始库存为{$operation_num}！";
                $agent_stock_log_data['stock_num'] = $agent_stock_data['stock_num'];
                break;
            case AgentStockEvent::Buy: //购买库存
                $agent_stock_data['stock_num'] = intval($stock_num + $operation_num);
                $agent_stock_log_data['note'] = "购买商品库存，库存增加{$operation_num}！";
                $agent_stock_log_data['stock_num'] = $agent_stock_data['stock_num'];
                break;
            case AgentStockEvent::Lading: //提单消耗
                //整理数据
                $agent_stock_data['stock_num'] = intval($stock_num - $operation_num);
                $agent_stock_log_data['note'] = "提单，商品库存减少{$operation_num}！";
                $agent_stock_log_data['stock_num'] = $agent_stock_data['stock_num'];

                //判断库存是否正确
                if($agent_stock_data['stock_num'] < 0){
                    return self::setError($agent_id,"库存不足！",$param);
                }
                break;
            case AgentStockEvent::ReturnLading: //提单失败
                //整理数据
                $agent_stock_data['stock_num'] = intval($stock_num + $operation_num);
                $agent_stock_log_data['note'] = "提单失败，商品库存返还{$operation_num}！";
                $agent_stock_log_data['stock_num'] = $agent_stock_data['stock_num'];
                break;
            case AgentStockEvent::Refund: //退单
                //整理数据
                $agent_stock_data['stock_num'] = intval($stock_num + $operation_num);
                $agent_stock_log_data['note'] = "退单，商品库存返还{$operation_num}！";
                $agent_stock_log_data['stock_num'] = $agent_stock_data['stock_num'];
                break;
            case AgentStockEvent::PromotionOk: //促销包审核
                //整理数据
                $agent_stock_data['stock_num'] = intval($stock_num + $operation_num);
                $agent_stock_log_data['note'] = "促销包审核，库存增加{$operation_num}！";
                $agent_stock_log_data['stock_num'] = $agent_stock_data['stock_num'];
                break;
        }

        //判断数据是否为空
        if(empty($agent_stock_data)){
            return self::setError($agent_id,"库存操作数据错误！",$param);
        }

        if($agent_stock_event === AgentStockEvent::Open) {
            //修改用户资金信息
            $agent_stock_data['agent_id'] = $agent_id;
            $agent_stock_data['supplier_goods_id'] = $supplier_goods_id;
            $agent_stock_data['supplier_product_id'] = $supplier_product_id;
            $agent_stock_data['create_time'] = get_date_time();
            $agent_stock_upd = IDb::getInstance("agent_stock")->setDbData($agent_stock_data)->add();
            if ($agent_stock_upd === false) {
                return self::setError($agent_id, "修改库存数据错误！", $param);
            }
        }else {
            //修改用户资金信息
            $agent_stock_where['agent_id'] = $agent_id;
            $agent_stock_upd = IDb::getInstance("agent_stock")->setDbData($agent_stock_data)->setDbWhere($agent_stock_where)->upd();
            if ($agent_stock_upd === false) {
                return self::setError($agent_id, "修改库存数据错误！", $param);
            }
        }

        //添加用户自己日志数据
        $agent_stock_log_data["agent_id"] = $agent_id;
        $agent_stock_log_data["supplier_goods_id"] = $supplier_goods_id;
        $agent_stock_log_data["supplier_product_id"] = $supplier_product_id;
        $agent_stock_log_data["agent_stock_event"] = $agent_stock_event;
        $agent_stock_log_data["operation_num"] = $operation_num;
        $agent_stock_log_data["item_id"] = $item_id;
        $agent_stock_log_data["create_admin_id"] = $create_admin_id;
        $agent_stock_log_data["create_time"] = get_date_time();
        $agent_stock_log_add = IDb::getInstance("agent_stock_log")->setDbData($agent_stock_log_data)->add();
        if($agent_stock_log_add === false){
            return self::setError($agent_id,"添加库存操作日志错误！",$param);
        }

        //返回值
        return true;
    }

    /**
     * @功能 资金操作错误日志
     * @param string $agent_id 代理商编号
     * @param string $error 错误说明
     * @param array|null $param 错误参数
     * @开发者：cxl
     * @return bool
     */
    private static function AgentAccountError($agent_id,$error,$param){
        //整理数据
        $data["agent_id"] = $agent_id;
        $data["error"] = $error;
        $data["param"] = json_encode($param);
        $data["create_time"] = get_date_time();

        //写数据库操作错误日志
        $agent_stock_error_add = IDb::getInstance("agent_stock_error")->setDbData($data)->add();
        if($agent_stock_error_add === false){
            return false;
        }

        //返回值
        return true;
    }
}
