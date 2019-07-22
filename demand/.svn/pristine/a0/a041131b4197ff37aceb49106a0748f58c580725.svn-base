<?php
namespace app\task\controller;

use app\common\enum\IsDelete;
use app\common\enum\OpenStatus;
use app\common\enum\OrderStatus;
use app\common\ext\IDb;
use app\common\ext\IYm;

class Ym{

    /**
     * @功能：开通云梦
     * @开发者：cxl
     */
    public function open(){
        //设置php执行时间，直到执行结束为止
        set_time_limit(0);

        //取得需要调用开通的订单
        $agent_order_where['aor.is_delete'] = IsDelete::No;
        $agent_order_where['aor.order_status'] = OrderStatus::VerifyOK;
        $agent_order_where['aor.open_status'] = OpenStatus::OpenWait;
        $agent_order_list = IDb::getInstance("agent_order as aor")
            ->setDbFiled("aor.agent_order_id,sp.supplier_product_extend")   
            ->setDbJoin("supplier_product as sp","sp.supplier_product_id = aor.supplier_product_id")
            ->setDbWhere($agent_order_where)
            ->sel();

        //循环调用云梦接口
        foreach($agent_order_list as $item){
            //判断扩展信息是否存在
            if(!empty($item['supplier_product_extend'])){
                //判断数据是否存在
                list($extent_type,$extent_no) = explode(":",$item['supplier_product_extend']);
                if(empty($extent_type) || empty($extent_no)){
                    continue;
                }

                //判断是否为云梦
                if(strtolower($extent_type) == "ym"){
                    //调用建站
                    $data = IYm::createInstance($extent_no,$item['agent_order_id']);
                    if(empty($data) || !is_array($data) || !isset($data['instanceId'])){
                        continue;
                    }

                    //判断建站是否成功
                    if(intval($data['instanceId']) > 0){
                        $agent_order_where=null;
                        $agent_order_data=null;
                        $agent_order_where['agent_order_id'] = $item['agent_order_id'];
                        $agent_order_data['open_note'] = "网站地址：<a href='http://{$data['appInfo']['frontEndUrl']}'>http://{$data['appInfo']['frontEndUrl']}</a><br>后台地址：<a href='{$data['appInfo']['adminUrl']}'>{$data['appInfo']['adminUrl']}</a><br>说明：请打开管理地址绑定手机号并设置密码。";
                        $agent_order_data['open_status'] = OpenStatus::OpenOK;
                        $agent_order_upd = IDb::getInstance("agent_order")->setDbData($agent_order_data)->setDbWhere($agent_order_where)->upd();
                        if($agent_order_upd ===  false){
                            continue;
                        }
                    }
                }
            }
        }

        //成功
        exit("Success");
    }
}
