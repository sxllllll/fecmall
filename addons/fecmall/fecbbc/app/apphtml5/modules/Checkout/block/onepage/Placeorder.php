<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Checkout\block\onepage;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Placeorder extends \fecshop\app\apphtml5\modules\Checkout\block\onepage\Placeorder
{
    
     /**
     * 用户的账单地址信息，通过用户传递的信息计算而来。
     */
    public $_billing;
    public $_address_id;
    /**
     * 用户的货运方式.
     */
    public $_shipping_method;
    public $_cart_coupon;
    /**
     * 订单备注信息.
     */
    public $_order_remark;
    
    
    public function getLastData()
    {
        
        if (Yii::$app->user->isGuest) {
            return false;
        } 
        //$customerId = Yii::$app->user->identity->id;
        $post = Yii::$app->request->post();
        $checkInfo = $this->checkOrderInfoAndInit($post);
        if ($checkInfo !== true) {
            
            return false;
        }
       
        if (is_array($post) && !empty($post)) {
            /**
             * 对传递的数据，去除掉非法xss攻击部分内容（通过\Yii::$service->helper->htmlEncode()）.
             */
            $post = \Yii::$service->helper->htmlEncode($post);
            
            $order_remark = isset($post['editForm']['remark']) ? $post['editForm']['remark'] : '';
            
            $shipping_method = isset($post['editForm']['shipping_method']) ? $post['editForm']['shipping_method'] : '';
            if (!$shipping_method) {
                return false;
            }
           
            // 设置checkout type
            $serviceOrder = Yii::$service->order;
            $checkout_type = $serviceOrder::CHECKOUT_TYPE_STANDARD;
            $serviceOrder->setCheckoutType($checkout_type);
            // 将购物车数据，生成订单。
            $innerTransaction = Yii::$app->db->beginTransaction();
            try {
                # 生成订单，扣除库存，但是，不清空购物车。
                //$genarateStatus = Yii::$service->order->generateOrderByCart2($shipping_method, $order_remark);
                $genarateStatus = Yii::$service->order->generateOrderByCart2($this->_billing, $this->_shipping_method, $this->_cart_coupon,'', true);
                
                if ($genarateStatus) {
                    $innerTransaction->commit();
                    Yii::$service->url->redirectByUrlKey('checkout/onepage/payment');

                    return true;
                } else {
                    $innerTransaction->rollBack();
                }
            } catch (\Exception $e) {
                $innerTransaction->rollBack();
                Yii::$service->helper->errors->add($e->getMessage());
            }
        }
        
        return false;
    }

    
    public function checkOrderInfoAndInit($post)
    {
        $customerDefaultAddress = Yii::$service->customer->address->getDefaultAddress();
        if (empty($customerDefaultAddress)) {
            
            return false;
        }
        foreach ($customerDefaultAddress as $k=>$v) {
            $arr[$k] = $v;
        }
        $this->_billing = $arr;
        $identity = Yii::$app->user->identity;
        if (!isset($this->_billing['email']) || !$this->_billing['email']){
            $this->_billing['email'] = $identity['email'];
        }
        
        $shipping_method = isset($post['editForm']['shipping_method']) ? $post['editForm']['shipping_method'] : '';
        $cart_coupon = isset($post['editForm']['cart_coupon']) ? $post['editForm']['cart_coupon'] : '';
        // 验证货运方式
        if (!$shipping_method) {
            Yii::$service->helper->errors->add('shipping method can not empty');
            
            return false;
        } 
        // 增加event
        $beforeEventName = 'event_place_order_check_order';
        Yii::$service->event->trigger($beforeEventName, $post);
        $eventCheckErr = Yii::$service->event->getErrStr();  // 事件检查结果，是否存在报错，得到报错信息
        if ($eventCheckErr) {
            Yii::$service->helper->errors->add($eventCheckErr);

            return false;
        }
        // 订单备注信息不能超过1500字符
        /*
        $orderRemarkStrMaxLen = Yii::$service->order->orderRemarkStrMaxLen;
        $order_remark = isset($post['order_remark']) ? $post['order_remark'] : '';
        if ($order_remark && $orderRemarkStrMaxLen) {
            $order_remark_strlen = strlen($order_remark);
            if ($order_remark_strlen > $orderRemarkStrMaxLen) {
                Yii::$service->helper->errors->add('order remark string length can not gt {orderRemarkStrMaxLen}', ['orderRemarkStrMaxLen' => $orderRemarkStrMaxLen]);
                
                return false;
            } else {
                // 去掉xss攻击字符，关于防止xss攻击的yii文档参看：http://www.yiichina.com/doc/guide/2.0/security-best-practices#fang-zhi-xss-gong-ji
                $this->_order_remark = $order_remark;
            }
        }
        */
        $this->_cart_coupon = $cart_coupon;
        $this->_shipping_method = $shipping_method;
        return true;
    }
    
}
