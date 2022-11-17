<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Customer\block\order;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Receive
{
    public function orderReceive()
    {
        $increment_id = Yii::$app->request->get('order_increment_id');
        $customer_id = Yii::$app->user->identity->id;
        
        if (!Yii::$service->order->process->customerReceiveOrderByIncrementId($increment_id, $customer_id ) ) {
            
            return false;
        }
        
        return true;
    }
    
}