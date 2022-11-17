<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Checkout\block\onepage;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Address 
{
    public function getLastData()
    {
        $identity = Yii::$app->user->identity;
        $customer_id = $identity['id'];
        $filter = [
            'numPerPage'    => 100,
            'pageNum'        => 1,
            'orderBy'    => ['updated_at' => SORT_DESC],
            'where'            => [
                ['customer_id' => $customer_id],
            ],
            'asArray' => true,
        ];
        $coll = Yii::$service->customer->address->coll($filter);
        if (isset($coll['coll']) && !empty($coll['coll'])) {
            
            return [
                'addresses' => $coll['coll'],
            ];
        }
    
        
        return [];
    }
}
