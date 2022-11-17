<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Coupon\block\customer;

use Yii;
use yii\base\InvalidValueException;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Registergift  extends \yii\base\BaseObject
{
    
    public function getLastData()
    {
        $customerId = '';
        if (!Yii::$app->user->isGuest) {
            $customerId = Yii::$app->user->identity->id;
        }
        $couponInfo = Yii::$service->coupon->getRegisterUserCoupon($customerId);
        
        return [
            'coupon' => $couponInfo['coupon'],
            'is_received' => $couponInfo['is_received'],
        ];
    }
}
