<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Coupon\block\fetch;

use Yii;
use yii\base\InvalidValueException;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Lists  extends \yii\base\BaseObject
{
    
    public function getLastData()
    {
        $data = Yii::$service->coupon->getAllAssignCoupons();
        $coupon_ids = [];
        $bdminUserIds = [];
        foreach ($data['coll'] as $one) {
            $coupon_ids[] = $one['id'];
            $bdminUserIds[] = $one['bdmin_user_id'];
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
        $bdminUser = Yii::$service->bdminUser->bdminUser->getIdAndPersonArrByIds($bdminUserIds);
        //var_dump($bdminUser);exit;
        $baseSymbol = Yii::$service->page->currency->getBaseSymbol();
        foreach ($data['coll'] as $k=>$one) {
            $coupon_id = $data['coll'][$k]['id'];
            $data['coll'][$k]['bdminUser'] = isset($bdminUser[$one['bdmin_user_id']]) ? $bdminUser[$one['bdmin_user_id']] : '';
            if (isset($customer_coupon_ids[$coupon_id])) {
                $data['coll'][$k]['fetched'] = true;
            } else {
                $data['coll'][$k]['fetched'] = false;
            }
            $data['coll'][$k]['baseSymbol'] = $baseSymbol;
        }
        
        return [
            'coupons' => $data['coll'],
            'count' => $data['count'],
        ];
    }
}
