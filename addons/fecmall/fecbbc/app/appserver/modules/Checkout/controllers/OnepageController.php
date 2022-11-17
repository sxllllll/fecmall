<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appserver\modules\Checkout\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class OnepageController extends \fecshop\app\appserver\modules\Checkout\controllers\OnepageController
{
    public $enableCsrfValidation = false;
    public $blockNamespace = 'fecbbc\app\appserver\modules\Checkout\block';
    //public function init(){
    //	Yii::$service->page->theme->layoutFile = 'one_step_checkout.php';

    //}

    public function actionIndex()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        if (Yii::$app->user->isGuest) {
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        } 

        return $this->getBlock()->getLastData();
    }

    // 更改货运方式
    public function actionChangeshippingmethod()
    {
        $shipping_method = Yii::$app->request->get('shipping_method');
        if (!$shipping_method) {
           $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        if (!Yii::$service->shipping->changeCurrentShippingMehtod($shipping_method)) {
            $code = Yii::$service->helper->appserver->order_shipping_switch_fail;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [ ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    public function actionChangecoupon()
    {
        $coupon_code = Yii::$app->request->get('coupon_code');
        if (!$coupon_code) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        if (Yii::$app->user->isGuest) {
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $customerId = Yii::$app->user->identity->id;
        if (!Yii::$service->cart->changeCouponCode($customerId, $coupon_code)) {
           
        }
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [ ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
    }
    
    
    public function actionWxsubmitorder(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            
            return [];
        }
        if (Yii::$app->user->isGuest) {
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        } 
        
        return $this->getBlock('wxplaceorder')->getLastData();
        
    }
    
    
    /**
     * 用户货运地址列表
     */
    public function actionPayment()
    {
        $payment_method = Yii::$app->request->get('payment_method');
        $order_increment_id = Yii::$app->request->get('order_increment_id');
        if ($payment_method && $order_increment_id) {
            if ($this->orderPayment($payment_method, $order_increment_id)) {
                //$startUrl = Yii::$service->payment->getStandardStartUrl();
                //return Yii::$service->url->redirect($startUrl);
                $code = Yii::$service->helper->appserver->status_success;
                $data = [ ];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
            }
            
        }
        
        $code = Yii::$service->helper->appserver->status_invalid_param;
        $data = [ 
            'errors' => Yii::$service->helper->errors->get(),
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        //Yii::$service->page->message->addByHelperErrors();
        //$data = $this->getBlock()->getLastData();
        //return $this->render($this->action->id, $data);
    }
    
    
    public function orderPayment($payment_method, $order_increment_id)
    {
        if (!Yii::$service->payment->ifIsCorrectStandard($payment_method)) {
            Yii::$service->helper->errors->add('payment method is not correct');
            
            return false;
        }
        Yii::$service->payment->setPaymentMethod($payment_method);
        // update order payment method
        if (!Yii::$service->order->updatePaymentMethod($order_increment_id, $payment_method, true)) {
            
            return false;
        }
        
        return true;
    }
}
