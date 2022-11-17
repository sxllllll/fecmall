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
class Aftersale
{
    public function getLastData($order_info)
    {
        $order_info = $this->getCustomerOrderInfo($order_info);
        $shippingMethodId = $order_info['shipping_method'];
        $shippingLabel = Yii::$service->bdminUser->shipping->getLabelByPrimaryKey($shippingMethodId);
        return [
            'orderInfo' => $order_info,
            
            'shippingLabel' => $shippingLabel,
        ];
    }
    

    public function getCustomerOrderInfo($order_info)
    {
        if (isset($order_info['customer_id']) && !empty($order_info['customer_id'])) {
            $identity = Yii::$app->user->identity;
            $customer_id = $identity->id;
            if ($order_info['customer_id'] == $customer_id) {
                return $order_info;
            }
        }
        
        return [];
    }
}
