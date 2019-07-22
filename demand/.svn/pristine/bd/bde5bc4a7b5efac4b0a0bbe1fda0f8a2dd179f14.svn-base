<?php
namespace app\common\ext;

class IYm{
    //常量定义
    private static $Url = "http://bc.clouddream.net/aliyunservice/XiRui";//请求地址
    private static $Key = "rSKnWozcEqJlsuzIxXK2d2qucatvEiXw5bcM3iugHS+rTrxSoyuIpw==";//Token加密key
    private static $Template = "empty";//默认空模板

    /*
    产品编号：yunmeng252000004销售渠道：杭州熙睿信息技术有限公司产品名称：云·速成美站2018（官网型（PC+手机+微信+小程序））
    产品编号：yunmeng252000005销售渠道：杭州熙睿信息技术有限公司产品名称：云·速成美站2018（免备案官网型（PC+手机+小程序））
    产品编号：yunmeng00000032销售渠道：杭州熙睿信息技术有限公司产品名称：云·速成美站2018（推广型免备案（含小程序））
    产品编号：yunmeng00000012销售渠道：杭州熙睿信息技术有限公司产品名称：云·速成美站2018（推广型（全功能+独立IP+小程序））
    */

    /**
     *请求接口返回内容
     *@param string $skuId 产品规格标识
     *@param string $orderId 订单编号
     *@return string
     */
    public static function createInstance($skuId,$orderId){
        //判断参数是否正确
        if(!in_array($skuId,array("yunmeng252000004","yunmeng252000005","yunmeng00000032","yunmeng00000012"))){
            return false;
        }

        //接收参数
        $params['action'] = "createInstance";
        $params['skuId'] = $skuId;
        $params['orderBizId'] = $orderId;
        $params['expiredOn'] = date('Y-m-d',strtotime("+1 year",time()));
        $params['template'] = self::$Template;
        $params['orderId'] = $orderId;

        //计算token值
        ksort($params);
        $param_str = null;
        foreach($params as $key=>$val){
            $param_str .= "{$key}={$val}&";
        }

        //添加token值
        $param_str.= "token=".md5($param_str . "key=".self::$Key);

        //调用接口
        $data = file_get_contents(self::$Url . '?'.$param_str);

        //判断是否正常
        if(empty($data)){
            return false;
        }else{
            try {
                //数据解析
                $data = json_decode($data, true);

                //判断数据是否异常
                if(empty($data) || !is_array($data) || !isset($data['instanceId'])){
                    return false;
                }
            }catch (\Exception $e){
                return false;
            }
        }

        //返回值
        return $data;
    }
}