<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Customer\block\order;

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
            return [];
        }
        $tracking_company_code = $order['tracking_company'];
        $tracking_number = $order['tracking_number'];
        
        if (!$tracking_company_code || !$tracking_number) {
            //var_dump($tracking_company_code, $tracking_number);exit;
            return [
                'order' => $order ,
            ];
        }
        //$tracking_company_code = 'YZPY';
        //$tracking_number = '9896365268436';
        
        $shippingInfo = $this->getShippingInfo($tracking_company_code, $tracking_number);
        //var_dump($order); exit;
        return [
            'order' => $order,
            'traceNo' => $tracking_number,
            'shippingInfo' => $shippingInfo,
        ];
    }
    
    public function getShippingInfo($type, $traceNo)
    {
        $shippingInfo = Yii::$service->delivery->kdiniao->getOrderTracesByJson($type, $traceNo );
        $Traces = $shippingInfo['Traces'];
        $Reason = $shippingInfo['Reason'];
        $Label = $shippingInfo['Label'];
        $ShipperName =  $shippingInfo['ShipperName'];
        $arr = [];
        $arr['info']['shipping_company'] = $ShipperName;
        $arr['info']['shipping_status'] = $Label;
        $arr['info']['shipping_reason'] = $Reason;
        $arr['info']['trace'] = [];
        
        if (is_array($Traces) && !empty($Traces)) {
            $Traces = \fec\helpers\CFunc::array_sort($Traces, 'AcceptTime', $dir='desc', false);
            foreach ($Traces as $one) {
                $AcceptStation = $one['AcceptStation'];
                $AcceptTime = $one['AcceptTime'];
                if ($AcceptStation && $AcceptTime ) {
                    $AcceptTimeYmd = date('m-d', strtotime($AcceptTime));
                    
                    $AcceptTimeHi = date('H:i', strtotime($AcceptTime));
                    $arr['info']['trace'][$AcceptTimeYmd][] = [
                        'time' => $AcceptTimeHi,
                        'info' => $AcceptStation,
                    ];
                }
            }
        }
        
        return $arr;
    }
}