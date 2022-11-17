<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Payment\block\success;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
{
    public function getLastData()
    {
        $trade_no = Yii::$service->order->getSessionTradeNo();
        if (!$trade_no) {
            Yii::$service->url->redirectHome();
        }
        $increment_id_arr = [];
        $order_model_arr = [];
        // 判断存储的订单交易号，是订单编号，还是支付号（多个订单一起支付而设立的支付号）
        if (Yii::$service->order->paymentCodeIsPaymentNo($trade_no)) {
            $order_model_arr = Yii::$service->order->getByPayNo($trade_no, false);
            foreach ($order_model_arr as $order_model) {
                $increment_id_arr[] = $order_model['increment_id'];
            }
        } else {
            $order_model = Yii::$service->order->getByIncrementId($trade_no);
            if ($order_model['increment_id']) {
                $order_model_arr[] = $order_model;
                $increment_id_arr[] = $order_model['increment_id'];
            }
        }
        // 清空购物车。这里针对的是未登录用户进行购物车清空。
        Yii::$service->cart->clearCartProductAndCoupon();
        // 清空session中存储的当前订单编号。
        Yii::$service->order->removeSessionTradeNo();
        
        return [ 
            'increment_ids'  => implode(',', $increment_id_arr),
            'orders'         => $order_model_arr,
        ];
        
    }
}
