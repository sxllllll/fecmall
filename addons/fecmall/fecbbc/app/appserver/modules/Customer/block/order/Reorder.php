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
class Reorder
{
    
    public function getLastData()
    {
        if (Yii::$app->user->isGuest) {
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $order_increment_id = Yii::$app->request->get('order_increment_id');
        if (!$order_increment_id) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $order = Yii::$service->order->getByIncrementId($order_increment_id);
        if (!$order['increment_id']) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $customer_id = Yii::$app->user->identity->id;
        if (!$order['customer_id'] || ($order['customer_id'] != $customer_id)) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $this->addOrderProductToCart($order['order_id']);
        $code = Yii::$service->helper->appserver->status_success;
        $responseData = Yii::$service->helper->appserver->getResponseData($code, []);
        
        return $responseData;
    }

    public function addOrderProductToCart($order_id)
    {
        $items = Yii::$service->order->item->getByOrderId($order_id);
        if (is_array($items) && !empty($items)) {
            foreach ($items as $one) {
                $item = [
                    'product_id'        => $one['product_id'],
                    'custom_option_sku' => $one['custom_option_sku'],
                    'qty'                => (int) $one['qty'],
                ];
                Yii::$service->cart->addProductToCart($item);
            }
        }
    }

}