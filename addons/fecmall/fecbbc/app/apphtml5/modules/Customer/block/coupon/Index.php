<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Customer\block\coupon;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index 
{
    protected $numPerPage = 20;
    protected $pageNum;
    protected $orderBy;
    protected $_page = 'p';
    
    public function getLastData()
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
        $bdminUserIds = [];
        foreach ($coll['coll'] as $one) {
            $bdminUserIds[] = $one['bdmin_user_id'];
        }
        $coupons = [];
        $pageToolBar = '';
        $bdminUser = Yii::$service->bdminUser->bdminUser->getIdAndPersonArrByIds($bdminUserIds);
        if (isset($coll['coll']) && !empty($coll['coll'])) {
            foreach ($coll['coll'] as $k=>$one) {
                $coll['coll'][$k]['bdminUser'] = isset($bdminUser[$one['bdmin_user_id']]) ? $bdminUser[$one['bdmin_user_id']] : '';
            }
            $count = $coll['count'];
            $pageToolBar = $this->getPage($count);
            //$return_arr['pageToolBar'] = $pageToolBar;
            $coupons = $coll['coll'];
        }
        
        list($coupon_available_count, $coupon_used_count, $coupon_unavailable_count) = $this->getCouponCount($customer_id);
        
        return [
            'coupons' => $coupons,
            'pageToolBar' => $pageToolBar,
            'coupon_available_count' => $coupon_available_count,
            'coupon_used_count' => $coupon_used_count,
            'coupon_unavailable_count' => $coupon_unavailable_count,
            'activeStatus' => $activeStatus,
        ];
    }
    
    
    public function getCouponCount($customer_id)
    {
        // coupon_available_count
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
        
        // coupon_used_count
        $filter = [
            'where'            => [
                ['customer_id' => $customer_id],
                ['is_used' => 1],
            ],
            'asArray' => true,
        ];
        $coll = Yii::$service->coupon->customer->coll($filter);
        $coupon_used_count = $coll['count'];
        
        // coupon_unavailable_count
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
    
    protected function getPage($countTotal)
    {
        if ($countTotal <= $this->numPerPage) {
            return '';
        }
        $config = [
            'class'        => 'fecshop\app\apphtml5\widgets\Page',
            'view'        => 'widgets/page.php',
            'pageNum'        => $this->pageNum,
            'numPerPage'    => $this->numPerPage,
            'countTotal'    => $countTotal,
            'page'            => $this->_page,
        ];

        return Yii::$service->page->widget->renderContent('category_product_page', $config);
    }
}
