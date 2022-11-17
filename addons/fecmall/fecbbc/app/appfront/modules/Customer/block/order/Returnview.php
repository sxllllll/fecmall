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
class Returnview
{
    public function getLastData($afterSaleOne, $order_info, $order_id, $item_id)
    {
        // 退货表中查询， item_id 查找是否存在
        /*
        if ($afterSaleOne['item_id']) {
            $data = [
                'after_sale'=> [
                    'id' => $afterSaleOne['id'],
                    'order_id' => $afterSaleOne['order_id'],
                    'item_id' => $afterSaleOne['item_id'],
                    'status' => $afterSaleOne['status'],
                    'sku' => $afterSaleOne['sku'],
                    'price' => $afterSaleOne['base_price'],
                    'qty' => $afterSaleOne['qty'],
                    'order_id' => $afterSaleOne['order_id'],
                    'tracking_number' => $afterSaleOne['tracking_number'],
                ],
                'order' => '',
            ];
            
            return $data;
        }
        */
        $product_arr = [];
        if (is_array($order_info['products'])) {
            foreach ($order_info['products'] as $product) {
                if ($product['item_id'] == $item_id) {
                    $product_arr[] = $product;
                }
            }
        }
        $order_info['products'] = $product_arr;
        return        [
            'order'=> $order_info,
            // 'after_sale' => '',
        ];
    }
    
}
