<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appserver\modules\Coupon\controllers;

use fecshop\app\appserver\modules\AppserverTokenController;
use Yii;
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0 
 */
class FetchController extends AppserverTokenController
{
   public $enableCsrfValidation = false ;
   // 分类页面。
    public function actionLists()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $data = Yii::$service->coupon->getAllAssignCoupons();
        $coupon_ids = [];
        foreach ($data['coll'] as $one) {
            $coupon_ids[] = $one['id'];
        }
        $customer_coupon_ids = [];
        if (!Yii::$app->user->isGuest) {
            $customerId = Yii::$app->user->identity->id;
            
            // 得到customer表的coupon
            $customer_coupons = Yii::$service->coupon->customer->getByCustomerIdAndCouponIds($customerId, $coupon_ids);
            if (is_array($customer_coupons)) {
                foreach ($customer_coupons as $customer_coupon){
                    $customer_coupon_ids[$customer_coupon['coupon_id']] = $customer_coupon['coupon_id'];
                }
            }
        }
        $baseSymbol = Yii::$service->page->currency->getBaseSymbol();
        foreach ($data['coll'] as $k=>$one) {
            $coupon_id = $data['coll'][$k]['id'];
            if (isset($customer_coupon_ids[$coupon_id])) {
                $data['coll'][$k]['fetched'] = true;
            } else {
                $data['coll'][$k]['fetched'] = false;
            }
            $data['coll'][$k]['baseSymbol'] = $baseSymbol;
            $data['coll'][$k]['assign_begin_at'] = date('Y.m.d', $one['assign_begin_at']);
            $data['coll'][$k]['assign_end_at'] = date('Y.m.d', $one['assign_end_at']);
        }
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'coupons' => $data['coll'],
            'count' => $data['count'],
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    public function actionCustomer()
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
        $coupon_id = Yii::$app->request->post('coupon_id');
        $customerModel = Yii::$app->user->identity;
        if (!Yii::$service->coupon->customer->fetchCoupon($customerModel, ['coupon_id' => $coupon_id])) {
            $errors = Yii::$service->helper->errors->get(',');
            $code = Yii::$service->helper->appserver->customer_fetch_coupon_fail;
            $data = ['errors' => $errors, 'coupon_id' => $coupon_id];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    public function actionCustomerexchange()
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
        
        $coupon_code = Yii::$app->request->post('coupon_code');
        $customerModel = Yii::$app->user->identity;
        if (!Yii::$service->coupon->customer->fetchCoupon($customerModel, ['coupon_code' => $coupon_code])) {
            $code = Yii::$service->helper->appserver->customer_fetch_coupon_by_code_fail;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
    }
    
}