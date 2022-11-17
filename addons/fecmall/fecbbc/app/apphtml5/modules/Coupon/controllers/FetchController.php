<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Coupon\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class FetchController extends AppfrontController
{
    public function init()
    {
        parent::init();
        
    }

    // 分类页面。
    public function actionLists()
    {
        $data = $this->getBlock()->getLastData();
        return $this->render($this->action->id, $data);
    }

    
    public function actionCustomer()
    {
        if (Yii::$app->user->isGuest) {
            $redirectUrl = Yii::$service->url->getUrl('coupon/fetch/lists');
            $type = Yii::$app->request->post('type');
            if ($type == 'register_gift_coupon') {
                $redirectUrl = Yii::$service->url->getUrl('coupon/customer/registergift');
            }
            Yii::$service->customer->setLoginSuccessRedirectUrl($redirectUrl);
            echo json_encode([
                'status' => 'no_login'
            ]);exit;
        }
        $coupon_id = Yii::$app->request->post('coupon_id');
        $customerModel = Yii::$app->user->identity;
        if (!Yii::$service->coupon->customer->fetchCoupon($customerModel, ['coupon_id' => $coupon_id])) {
            $errors = Yii::$service->helper->errors->get(',');
            echo json_encode([
                'status' => 'fail',
                'content' => $errors ,
            ]);exit;
        }
        echo json_encode([
            'status' => 'success',
            'content' => 'customer add coupon success'
        ]);exit;
    }
    
    
    public function actionCustomerexchange()
    {
        if (Yii::$app->user->isGuest) {
            $redirectUrl = Yii::$service->url->getUrl('coupon/fetch/lists');
            
            Yii::$service->customer->setLoginSuccessRedirectUrl($redirectUrl);
            echo json_encode([
                'status' => 'no_login'
            ]);exit;
        }
        
        $coupon_code = Yii::$app->request->post('coupon_code');
        $customerModel = Yii::$app->user->identity;
        if (!Yii::$service->coupon->customer->fetchCoupon($customerModel, ['coupon_code' => $coupon_code])) {
            echo json_encode([
                'status' => 'fail',
                'content' => 'customer add coupon fail'
            ]);exit;
        }
        echo json_encode([
            'status' => 'success',
            'content' => 'customer add coupon success'
        ]);exit;
        
        
    }
    
    
    
    
    
    
    
}
