<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Customer\block\order;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Returnview
{
    public function getLastData($afterSaleOne, $order_info, $order_id, $item_id)
    {
        // 退货表中查询， item_id 查找是否存在
        $product_arr = [];
        if (is_array($order_info['products'])) {
            foreach ($order_info['products'] as $product) {
                if ($product['item_id'] == $item_id) {
                    $product_arr[] = $product;
                }
            }
        }
        $order_info['products'] = $product_arr;
        
        return [
            'orderInfo'=> $order_info,
        ];
    }
    
}
