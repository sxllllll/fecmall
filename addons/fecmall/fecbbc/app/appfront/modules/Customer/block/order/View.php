<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Customer\block\order;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class View
{
    public function getLastData()
    {
        $order_id = Yii::$app->request->get('order_id');
        $order_info = $this->getCustomerOrderInfo($order_id);
        $bInfo = $this->getBInfo($order_info);
        $shippingMethodId = $order_info['shipping_method'];
        $shippingLabel = Yii::$service->bdminUser->shipping->getLabelByPrimaryKey($shippingMethodId);
        return [
            'bInfo' => $bInfo,
            'orderInfo' => $order_info,
            
            'shippingLabel' => $shippingLabel,
        ];
    }
    
    public function getBInfo($order)
    {
        $arr['can_payment'] = Yii::$service->order->info->isCustomerCanPayment($order);
        $arr['can_cancel'] = Yii::$service->order->info->isCustomerCanCancel($order);
        $arr['can_received'] = Yii::$service->order->info->isCustomerCanReceive($order);
        $arr['can_after_sale'] = Yii::$service->order->info->isCustomerCanAfterSale($order);
        $arr['can_cancel_back'] = Yii::$service->order->info->isCustomerCanCancelBack($order);
        $arr['can_delay_receive'] = Yii::$service->order->info->isCustomerCanDelayReceiveOrder($order);
        $arr['can_query_shipping'] = Yii::$service->order->info->isCustomerCanQueryTracking($order);
        $arr['can_reorder'] = Yii::$service->order->info->isCustomerCanReOrder($order);
        $arr['can_review'] = Yii::$service->order->info->isCustomerCanReview($order);   
        
        return $arr;
    }

    public function getCustomerOrderInfo($order_id)
    {
        if ($order_id) {
            $order_info = Yii::$service->order->getOrderInfoById($order_id);
            if (isset($order_info['customer_id']) && !empty($order_info['customer_id'])) {
                $identity = Yii::$app->user->identity;
                $customer_id = $identity->id;
                if ($order_info['customer_id'] == $customer_id) {
                    return $order_info;
                }
            }
        }

        return [];
    }
}
