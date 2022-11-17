<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appserver\modules\Customer\controllers;

use fecshop\app\appserver\modules\AppserverTokenController;
use Yii;
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CouponController extends AppserverTokenController
{
    protected $numPerPage = 20;
    protected $pageNum;
    protected $orderBy;
    protected $_page = 'p';
    
    public function actionIndex(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        if (Yii::$app->user->isGuest) {
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = $this->getCoupons();
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    public function getCoupons()
    {
        $identity = Yii::$app->user->identity;
        $customer_id = $identity['id'];
        $this->pageNum = (int) Yii::$app->request->get('p');
        $this->pageNum = ($this->pageNum >= 1) ? $this->pageNum : 1;
        $this->orderBy = ['created_at' => SORT_DESC];
        $where[] =  ['customer_id' => $customer_id];
        $type = Yii::$app->request->get('type');
        $activeStatus = 0;
        if ($type == 'used') {
            $where[] =  ['is_used' => 1];
            $activeStatus = 2;
        } else if ($type == 'overtime') {
            $where[] =  ['is_used' => 2];
            $where[] =  ['<', 'active_end_at', time()];
            $activeStatus = 3;
        } else {
            $where[] =  ['is_used' => 2];
            $where[] =  ['>=', 'active_end_at', time()];
            $activeStatus = 1;
        }    
        $filter = [
            'numPerPage'    => $this->numPerPage,
            'pageNum'        => $this->pageNum,
            'orderBy'    => $this->orderBy,
            'where'            => $where,
            'asArray' => true,
        ];
        $coll = Yii::$service->coupon->customer->coll($filter);
        $coupons = [];
        $pageToolBar = '';
        if (isset($coll['coll']) && !empty($coll['coll'])) {
            $count = $coll['count'];
            $coupons = $coll['coll'];
        }
        foreach ($coupons as $k=>$one) {
            $coupons[$k]['active_begin_at'] = date('Y.m.d', $one['active_begin_at']);
            $coupons[$k]['active_end_at'] = date('Y.m.d', $one['active_end_at']);
        }
        
        
        
        list($coupon_available_count, $coupon_used_count, $coupon_unavailable_count) = $this->getCouponCount($customer_id);
        
        return [
            'coupons' => $coupons,
            'coupon_available_count' => $coupon_available_count,
            'coupon_used_count' => $coupon_used_count,
            'coupon_unavailable_count' => $coupon_unavailable_count,
            'activeStatus' => $activeStatus,
        ];
    }
    public function getCouponCount($customer_id)
    {
        $filter = [
            'where'            => [
                ['customer_id' => $customer_id],
                ['>=', 'active_end_at', time()],
                ['is_used' => 2],
            ],
            'asArray' => true,
        ];
        $coll = Yii::$service->coupon->customer->coll($filter);
        $coupon_available_count = $coll['count'];
        
        $filter = [
            'where'            => [
                ['customer_id' => $customer_id],
                ['is_used' => 1],
            ],
            'asArray' => true,
        ];
        $coll = Yii::$service->coupon->customer->coll($filter);
        $coupon_used_count = $coll['count'];
        
        $filter = [
            'where'            => [
                ['customer_id' => $customer_id],
                ['<', 'active_end_at', time()],
                ['is_used' => 2],
            ],
            'asArray' => true,
        ];
        $coll = Yii::$service->coupon->customer->coll($filter);
        $coupon_unavailable_count = $coll['count'];
        
        return [$coupon_available_count, $coupon_used_count, $coupon_unavailable_count];
    }
    
}