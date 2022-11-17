<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\services\helper;

use fecshop\services\Service;
use Yii;

/**
 * 该类主要是给appserver端的api，返回的数据做格式输出，规范输出的各种状态。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Appserver extends \fecshop\services\helper\Appserver
{
    public $account_phone_send_sms_fail                      = 2000001;
    public $account_register_captcha_fail                      = 2000002;
    public $customer_fetch_coupon_fail                      = 2000003; 
    public $customer_fetch_coupon_by_code_fail                      = 2000004; 
    public $order_shipping_switch_fail                      = 2000005; 
    public $customer_order_receive_fail                      = 2000006; 
    public $customer_order_review_fail                      = 2000007; 
    public $customer_default_address_is_empty                      = 2000008; 
    public $customer_order_can_not_aftersale = 2000009; 
    public $customer_order_return_is_exist = 2000010; 
    public $customer_order_return_qty_limit = 2000011; 
    public $customer_order_return_submit_fail = 2000012; 
    public $customer_order_return_cancel = 2000013; 
    public $customer_order_return_dispatch_fail = 2000014; 
    
    public function getMessageArr()
    {
        $arr = parent::getMessageArr();
        $arr[$this->account_phone_send_sms_fail] = [  'message' => 'register account send phone sms fail' ];
        $arr[$this->account_register_captcha_fail] = [  'message' => 'register account captcha is not correct' ];
        $arr[$this->customer_fetch_coupon_fail] = [  'message' => 'customer fetch coupon fail' ];
        $arr[$this->customer_fetch_coupon_by_code_fail] = [  'message' => 'customer fetch coupon by code fail' ];
        $arr[$this->order_shipping_switch_fail] = [  'message' => 'order shipping switch fail' ];
        $arr[$this->customer_order_receive_fail] = [  'message' => 'customer order receive fail' ];
        $arr[$this->customer_order_review_fail] = [  'message' => 'customer order review fail' ];
        $arr[$this->customer_default_address_is_empty] = [  'message' => 'customer default address is empty' ];
        $arr[$this->customer_order_can_not_aftersale] = [  'message' => 'customer order can not aftersale' ];
        $arr[$this->customer_order_return_is_exist] = [  'message' => 'customer order return is exist' ];
        $arr[$this->customer_order_return_qty_limit] = [  'message' => 'customer order return qty is too big' ];
        $arr[$this->customer_order_return_submit_fail] = [  'message' => 'customer order return submit fail' ];
        $arr[$this->customer_order_return_cancel] = [  'message' => 'customer order return cancel' ];
        $arr[$this->customer_order_return_dispatch_fail] = [  'message' => 'customer order return dispatch fail' ];
        
        return $arr;
    }
    
}