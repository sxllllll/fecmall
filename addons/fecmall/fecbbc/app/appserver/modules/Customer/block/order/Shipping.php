<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appserver\modules\Customer\block\order;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Shipping
{
    public function getLastData()
    {
        $order_increment_id = Yii::$app->request->get('order_increment_id');
        $order = Yii::$service->order->getByIncrementId($order_increment_id);
        //echo $order['increment_id'];exit;
        $customerId = Yii::$app->user->identity->id;
        if (!$order || $order['customer_id'] != $customerId) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $tracking_company_code = $order['tracking_company'];
        $tracking_number = $order['tracking_number'];
        
        if (!$tracking_company_code || !$tracking_number) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $shippingInfo = $this->getShippingInfo($tracking_company_code, $tracking_number);
        $shippingInfo['tracking_number'] = $tracking_number;
        $code = Yii::$service->helper->appserver->status_success;
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $shippingInfo);
        
        return $responseData;
    }
    
    public function getShippingInfo($type, $traceNo)
    {
        $shippingInfo = Yii::$service->delivery->kdiniao->getOrderTracesByJson($type, $traceNo );
        $Traces = $shippingInfo['Traces'];
        $Reason = $shippingInfo['Reason'];
        $Label = $shippingInfo['Label'];
        $ShipperName =  $shippingInfo['ShipperName'];
        $arr = [];
        $arr['shipping_company'] = $ShipperName;
        $arr['shipping_status'] = $Label;
        $arr['shipping_reason'] = $Reason;
        $arr['traces'] = [];
        
        if (is_array($Traces) && !empty($Traces)) {
            $Traces = \fec\helpers\CFunc::array_sort($Traces, 'AcceptTime', $dir='desc', false);
            foreach ($Traces as $one) {
                $AcceptStation = $one['AcceptStation'];
                $AcceptTime = $one['AcceptTime'];
                if ($AcceptStation && $AcceptTime ) {
                    $AcceptTimeYmd = date('Y-m-d H:i:s', strtotime($AcceptTime));
                    
                    $arr['traces'][] = [
                        'time' => $AcceptTimeYmd,
                        'info' => $AcceptStation,
                    ];
                }
            }
        }
        
        return $arr;
    }
}