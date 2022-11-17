<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Checkout\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class OnepageController extends \fecshop\app\appfront\modules\Checkout\controllers\OnepageController
{
    public $enableCsrfValidation = true;
    
    public $blockNamespace = 'fecbbc\app\appfront\modules\Checkout\block';
    
    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) {
            Yii::$service->url->redirectByUrlKey('customer/account');
            exit;
        }
        
        Yii::$service->page->theme->layoutFile = 'checkout.php';
    }
    
    // 用户购物车页面进入下单页面，如果用户没有货运地址，那么进入该页面进行货运地址的add
    public function actionAddressadd()
    {
        Yii::$service->page->theme->layoutFile = 'one_step_checkout.php';
        $data = $this->getBlock()->getLastData();
        return $this->render($this->action->id, $data);
    }
    
    /**
     * 根据address_id 得到address info
     */
    public function actionAddressone()
    {
        $address_id =  Yii::$app->request->get('address_id');
        if ($address_id) {
            $customer_id = Yii::$app->user->identity->id;
            $address = Yii::$service->customer->address->getAddressByIdAndCustomerId($address_id, $customer_id);
            if ($address['address_id']) {
                echo json_encode([
                    'status' => 'success',
                    'address' => [
                        'first_name' => $address['first_name'],
                        'telephone' => $address['telephone'],
                        'street1' => $address['street1'],
                        'state' => $address['state'],
                        'city' => $address['city'],
                        'area' => $address['area'],
                        'zip' => $address['zip'],
                        'is_default' => $address['is_default'],
                        'address_id' => $address['address_id'],
                    ],
                ]);exit;
            }
        }
    }
    // 设置默认地址
    public function actionSetdefaultaddress()
    {
        
        $address_id =  Yii::$app->request->get('address_id');
        if ($address_id) {
            $customer_id = Yii::$app->user->identity->id;
            if (Yii::$service->customer->address->setDefault($customer_id, $address_id)) {
                echo json_encode([
                    'status' => 'success',
                ]);exit;
            }
        }
        echo json_encode([
            'status' => 'fail',
        ]);exit;
    }
    
    
    /**
     * 用户货运地址列表
     */
    public function actionAddress()
    {
        $defaultAddressId =  Yii::$app->request->get('defaultaddressid');
        if ($defaultAddressId) {
            // 更改default address id
            $customer_id = Yii::$app->user->identity->id;
            //var_dump($customer_id, $defaultAddressId);exit;
            if (Yii::$service->customer->address->setDefault($customer_id, $defaultAddressId)) {
                Yii::$service->url->redirectByUrlKey('checkout/onepage');
                
                return;
            }
        }
        $data = $this->getBlock()->getLastData();
        return $this->render($this->action->id, $data);
    }
    
    /**
     * 支付页面的地址信息编辑
     * 1.method == add ， 为用户第一次添加地址的编辑
     * 2.method == edit ，代表在支付页面，用户编辑和添加address
     */
    public function actionAddressedit()
    {
        $address = Yii::$app->request->post('editForm');
        if (is_array($address) && !empty($address)) {
            // 用户初次添加地址
            if (Yii::$app->request->get('method') == 'add') {
                if ($this->getBlock()->saveFirst($address)) {
                    
                    return Yii::$service->url->redirectByUrlKey('checkout/onepage');
                } else {
                    
                    return Yii::$service->url->redirectByUrlKey('checkout/onepage/addressadd');
                }
            } else if (Yii::$app->request->get('method') == 'edit') {
                if ($this->getBlock()->save($address)) {
                    
                    return Yii::$service->url->redirectByUrlKey('checkout/onepage/index');
                }
            }
        }
        //$data = $this->getBlock()->getLastData();
        //return $this->render($this->action->id, $data);
    }
    
    
    
    
    // 地址删除
    public function actionAddressremove() 
    {
        $address_id = Yii::$app->request->get('address_id');
        $customer_id = Yii::$app->user->identity->id;
        //var_dump($address_id, $customer_id);exit;
        Yii::$service->customer->address->remove($address_id, $customer_id);
        
        return Yii::$service->url->redirectByUrlKey('checkout/onepage/index');
        
    }
    
    // 更改货运方式
    public function actionChangeshippingmethod()
    {
        $shipping_method = Yii::$app->request->get('shipping_method');
        if (!$shipping_method) {
            echo json_encode([
                'status' => 'fail',
                'content' => 'shipping_method is empty',
            ]);exit;
        }
        if (Yii::$service->shipping->changeCurrentShippingMehtod($shipping_method)) {
            echo json_encode([
                'status' => 'success',
                'content' => 'shipping_method change success',
            ]);exit;
        }
        
        echo json_encode([
                'status' => 'fail',
                'content' => 'shipping_method change fail',
            ]);exit;
    }
    
    public function actionChangecoupon()
    {
        $coupon_code = Yii::$app->request->get('coupon_code');
        if (!$coupon_code) {
            echo json_encode([
                'status' => 'fail',
                'content' => 'coupon_code is empty',
            ]);exit;
        }
        if (Yii::$app->user->isGuest) {
            echo json_encode([
                'status' => 'no_login',
            ]);exit;
        }
        $customerId = Yii::$app->user->identity->id;
        if (Yii::$service->cart->changeCouponCode($customerId, $coupon_code)) {
            echo json_encode([
                'status' => 'success',
                'content' => 'coupon_code change success',
            ]);exit;
        }
        
        echo json_encode([
            'status' => 'fail',
            'content' => 'coupon_code change fail',
        ]);exit;
        
    }
    
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            $checkoutOrderUrl = Yii::$service->url->getUrl('checkout/onepage/index');
            Yii::$service->customer->setLoginSuccessRedirectUrl($checkoutOrderUrl);
            return Yii::$service->url->redirectByUrlKey('customer/account/login');
        } 
        // 查看用户是否存在default address
        if (!Yii::$service->customer->address->getDefaultAddress()) {
            return Yii::$service->url->redirectByUrlKey('checkout/onepage/addressadd');
        }
        $_csrf = Yii::$app->request->post('_csrf');
        $postType = Yii::$app->request->post('postType');
        if ($_csrf && $postType == 'placeOrder') {
            $status = $this->getBlock('placeorder')->getLastData();
            if (!$status) {
                //var_dump(Yii::$service->helper->errors->get());
                //exit;
                Yii::$service->page->message->addByHelperErrors();
            }
        }

        $data = $this->getBlock()->getLastData();
        if (is_array($data) && !empty($data)) {
            return $this->render($this->action->id, $data);
        } else {
            return $data;
        }
    }
    
    /**
     * 用户货运地址列表
     */
    public function actionPayment()
    {
        Yii::$service->page->theme->layoutFile = 'checkout_payment.php';
        $editForm = Yii::$app->request->post('editForm');
        if (is_array($editForm) && !empty($editForm)) {
            if ($this->getBlock()->orderPayment($editForm)) {
                $startUrl = Yii::$service->payment->getStandardStartUrl();
                //echo $startUrl;exit;
                return Yii::$service->url->redirect($startUrl);
            }
            Yii::$service->page->message->addByHelperErrors();
        }
        $data = $this->getBlock()->getLastData();
        return $this->render($this->action->id, $data);
    }
    
    
    /*
    public function actionChangecountry()
    {
        $this->getBlock('index')->ajaxChangecountry();
    }

    public function actionAjaxupdateorder()
    {
        $this->getBlock('index')->ajaxUpdateOrderAndShipping();
    }
    */
}
