<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\services\cart;

use fecshop\services\Service;
use Yii;

/**
 * Cart services. 对购物车产品操作的具体实现部分。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class QuoteItem extends \fecshop\services\cart\QuoteItem
{
    
    /**
     * @param $activeProduct | boolean , 是否只要active的产品
     * @return array ， foramt：
     *               [
     *               'products' 		=> $products, 				# 产品详细信息，详情参看代码中的$products。
     *               'product_total' => $product_total, 			# 产品的当前货币总额
     *               'base_product_total' => $base_product_total,# 产品的基础货币总额
     *               'product_weight'=> $product_weight,			# 蟾皮的总重量、
     *               ]
     *               得到当前购物车的产品信息，具体参看上面的example array。
     */
    public function getCartProductInfo($activeProduct = true)
    {
        $cart_id        = Yii::$service->cart->quote->getCartId();
        $products       = [];
        $product_total  = 0;
        $product_weight = 0;
        $product_volume_weight = 0;
        $base_product_total = 0;
        $product_volume = 0;
        $product_qty_total = 0;
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        if ($cart_id) {
            if (!isset($this->_cart_product_info[$cart_id])) {
                $data = $this->_itemModel->find()->where([
                    'cart_id' => $cart_id,
                ])->orderBy( ['active' => SORT_ASC, 'updated_at' => SORT_DESC])  // 加入按照active  updated_at 进行排序
                ->all();
                if (is_array($data) && !empty($data)) {
                    foreach ($data as $one) {
                        $active             = $one['active'];
                        if ($activeProduct && ($active != $this->activeStatus)) {
                            continue;
                        }
                        $product_id     = $one['product_id'];
                        $product_one    = Yii::$service->product->getByPrimaryKey($product_id);
                        $bdmin_user_id = $product_one['bdmin_user_id'];
                        if ($product_one[$productPrimaryKey]) {
                            $qty                = $one['qty'];
                            
                            $custom_option_sku  = $one['custom_option_sku'];
                            $product_price_arr  = Yii::$service->product->price->getCartPriceByProductId($product_id, $qty, $custom_option_sku, 2);
                            $curr_product_price = isset($product_price_arr['curr_price']) ? $product_price_arr['curr_price'] : 0;
                            $base_product_price = isset($product_price_arr['base_price']) ? $product_price_arr['base_price'] : 0;
                            $product_price      = isset($curr_product_price['value']) ? $curr_product_price['value'] : 0;

                            $product_row_price  = $product_price * $qty;
                            $base_product_row_price = $base_product_price * $qty;
                            
                            $volume = Yii::$service->shipping->getVolume($product_one['long'], $product_one['width'], $product_one['high']);
                            $p_pv               = $volume * $qty;
                            $p_wt               = $product_one['weight'] * $qty;
                            $p_vwt              = $product_one['volume_weight'] * $qty;
                            
                            if ($active == $this->activeStatus) {
                                $product_total          += $product_row_price;
                                $base_product_total     += $base_product_row_price;
                                $product_weight         += $p_wt;
                                $product_volume_weight  += $p_vwt;
                                $product_volume         += $p_pv;
                                $product_qty_total      += $qty;
                            }
                            $productSpuOptions  = $this->getProductSpuOptions($product_one);
                            $products[$bdmin_user_id][] = [
                                'item_id'                   => $one['item_id'],
                                'active'                     => $active,
                                'product_id'              => $product_id,
                                'bdmin_user_id'        => $product_one['bdmin_user_id'],
                                'sku'                         => $product_one['sku'],
                                'name'                      => Yii::$service->store->getStoreAttrVal($product_one['name'], 'name'),
                                'qty'                          => $qty,
                                'custom_option_sku'  => $custom_option_sku,
                                'product_price'           => $product_price,
                                'product_row_price'    => $product_row_price,

                                'base_product_price'    => $base_product_price,
                                'base_product_row_price'=> $base_product_row_price,

                                'product_name'      => $product_one['name'],
                                'product_weight'    => $product_one['weight'],
                                'product_row_weight'=> $p_wt,
                                'product_volume_weight'     => $product_one['volume_weight'],
                                'product_row_volume_weight' => $p_vwt,
                                'product_volume'        => $volume,
                                'product_row_volume'    => $p_pv,
                                'product_url'       => $product_one['url_key'],
                                'product_image'     => $product_one['image'],
                                'custom_option'     => $product_one['custom_option'],
                                'spu_options'       => $productSpuOptions,
                            ];
                        }
                    }
                    $this->_cart_product_info[$cart_id] = [
                        'products'              => $products,
                        'product_qty_total'     => $product_qty_total,
                        'product_total'         => $product_total,
                        'base_product_total'    => $base_product_total,
                        'product_weight'        => $product_weight,
                        'product_volume_weight' => $product_volume_weight,
                        'product_volume'        => $product_volume,
                        
                    ];
                }
            }

            return $this->_cart_product_info[$cart_id];
        }
    }
    
    protected $_cart_order_product_info;
    /**
     * @param $activeProduct | boolean , 是否只要active的产品
     * @return array ， foramt：
     *               [
     *               'products' 		=> $products, 				# 产品详细信息，详情参看代码中的$products。
     *               'product_total' => $product_total, 			# 产品的当前货币总额
     *               'base_product_total' => $base_product_total,# 产品的基础货币总额
     *               'product_weight'=> $product_weight,			# 蟾皮的总重量、
     *               ]
     *               得到当前购物车的产品信息，具体参看上面的example array。
     */
    public function getCartOrderProductInfo()
    {
        $activeProduct = true;
        $cart_id        = Yii::$service->cart->quote->getCartId();
        $products       = [];
        if ($cart_id) {
            $productPrimaryKey = Yii::$service->product->getPrimaryKey();
            if (!isset($this->_cart_order_product_info[$cart_id])) {
                $data = $this->_itemModel->find()->asArray()->where([
                    'cart_id' => $cart_id,
                ])->all();
                if (is_array($data) && !empty($data)) {
                    foreach ($data as $one) {
                        $active             = $one['active'];
                        if ($activeProduct && ($active != $this->activeStatus)) {
                            continue;
                        }
                        $product_id     = $one['product_id'];
                        $product_one    = Yii::$service->product->getByPrimaryKey($product_id);
                        $bdmin_user_id = $product_one['bdmin_user_id'];
                        if (!$bdmin_user_id) {
                            continue;
                        }
                        // 初始化
                        if (!isset($products[$bdmin_user_id]['product_total'])) {
                            $products[$bdmin_user_id]['product_total'] = 0;
                        }
                        if (!isset($products[$bdmin_user_id]['base_product_total'])) {
                            $products[$bdmin_user_id]['base_product_total'] = 0;
                        }
                        if (!isset($products[$bdmin_user_id]['product_weight'])) {
                            $products[$bdmin_user_id]['product_weight'] = 0;
                        }
                        if (!isset($products[$bdmin_user_id]['product_volume_weight'])) {
                            $products[$bdmin_user_id]['product_volume_weight'] = 0;
                        }
                        if (!isset($products[$bdmin_user_id]['product_volume'])) {
                            $products[$bdmin_user_id]['product_volume'] = 0;
                        }
                        if (!isset($products[$bdmin_user_id]['product_qty_total'])) {
                            $products[$bdmin_user_id]['product_qty_total'] = 0;
                        }
                        
                        if ($product_one[$productPrimaryKey]) {
                            $qty                = $one['qty'];
                            
                            $custom_option_sku  = $one['custom_option_sku'];
                            $product_price_arr  = Yii::$service->product->price->getCartPriceByProductId($product_id, $qty, $custom_option_sku, 2);
                            $curr_product_price = isset($product_price_arr['curr_price']) ? $product_price_arr['curr_price'] : 0;
                            $base_product_price = isset($product_price_arr['base_price']) ? $product_price_arr['base_price'] : 0;
                            $product_price      = isset($curr_product_price['value']) ? $curr_product_price['value'] : 0;

                            $product_row_price  = $product_price * $qty;
                            $base_product_row_price = $base_product_price * $qty;
                            
                            $volume = Yii::$service->shipping->getVolume($product_one['long'], $product_one['width'], $product_one['high']);
                            $p_pv               = $volume * $qty;
                            $p_wt               = $product_one['weight'] * $qty;
                            $p_vwt              = $product_one['volume_weight'] * $qty;
                            
                            if ($active == $this->activeStatus) {
                                $products[$bdmin_user_id]['product_total']              += $product_row_price;
                                $products[$bdmin_user_id]['base_product_total']     += $base_product_row_price;
                                $products[$bdmin_user_id]['product_weight']               += $p_wt;
                                $products[$bdmin_user_id]['product_volume_weight']  += $p_vwt;
                                $products[$bdmin_user_id]['product_volume']              += $p_pv;
                                $products[$bdmin_user_id]['product_qty_total']            += $qty;
                            }
                            $productSpuOptions  = $this->getProductSpuOptions($product_one);
                            $products[$bdmin_user_id]['products'][] = [
                                'item_id'                   => $one['item_id'],
                                'active'                     => $active,
                                'product_id'              => $product_id,
                                //'shipping_theme'      => $product_one['shipping_theme'],
                                'bdmin_user_id'        => $product_one['bdmin_user_id'],
                                'sku'                         => $product_one['sku'],
                                'name'                      => Yii::$service->store->getStoreAttrVal($product_one['name'], 'name'),
                                'qty'                          => $qty, 
                                'custom_option_sku'  => $custom_option_sku,
                                'product_price'           => $product_price,
                                'product_row_price'    => $product_row_price,

                                'base_product_price'    => $base_product_price,
                                'base_product_row_price'=> $base_product_row_price,

                                'product_name'      => $product_one['name'],
                                'product_weight'    => $product_one['weight'],
                                'product_row_weight'=> $p_wt,
                                'product_volume_weight'     => $product_one['volume_weight'],
                                'product_row_volume_weight' => $p_vwt,
                                'product_volume'        => $volume,
                                'product_row_volume'    => $p_pv,
                                'product_url'       => $product_one['url_key'],
                                'product_image'     => $product_one['image'],
                                'custom_option'     => $product_one['custom_option'],
                                'spu_options'       => $productSpuOptions,
                            ];
                        }
                    }
                    $this->_cart_order_product_info[$cart_id] = $products;
                }
            }

            return $this->_cart_order_product_info[$cart_id];
        }
    }
    
    
     /**
     * @param $item_id | Int ， quoteItem表的id
     * @return bool
     *              将这个item_id对应的产品个数+1.
     */
    public function selectChoseItem($checked, $items)
    {
        if (!is_array($items)) {
            Yii::$service->helper->errors->add('items must be array');
            
            return false;
        }
        $cart_id = Yii::$service->cart->quote->getCartId();
        if ($cart_id) {
            $active = $this->noActiveStatus;
            if ($checked == true) {
                $active = $this->activeStatus;
            }
            $updateCount = $this->_itemModel->updateAll(
                    ['active'  => $active],
                    ['and', ['cart_id' =>$cart_id], 
                    ['in', 'item_id', $items]]
                );
            if ($updateCount > 0) {
                Yii::$service->cart->quote->computeCartInfo();
            }
            
            return true;
        }

        return false;
    }
    /**
     * 将某个产品加入到购物车中。
     * 在添加到 cart_item 表后，更新购物车中产品的总数。
     * @param array $item
     * @param Object $product ， Product Model
     * @return mixed
     * example:
     * $item = [
     *		'product_id' 		=> 22222,
     *		'custom_option_sku' => red-xxl,
     *		'qty' 				=> 22,
     *      'sku' 				=> 'xxxx',
     * ];
     */
    public function addBuyNowItem($item, $product)
    {
        $cart_id = Yii::$service->cart->quote->getCartId();
        if (!$cart_id) {
            Yii::$service->cart->quote->createCart();
            $cart_id = Yii::$service->cart->quote->getCartId();
        }
        // 
        if (!isset($item['product_id']) || empty($item['product_id'])) {
            Yii::$service->helper->errors->add('add to cart error, product id is empty');

            return false;
        }
        
        
        $where = [
            'cart_id'    => $cart_id,
            'product_id' => $item['product_id'],
        ];
        if (isset($item['custom_option_sku']) && !empty($item['custom_option_sku'])) {
            $where['custom_option_sku'] = $item['custom_option_sku'];
        }
        // Buy Now类型：将所有的购物车产品no active
        $updateColumns = $this->_itemModel->updateAll(
            ['active' => $this->noActiveStatus],
            ['cart_id'    => $cart_id,]
        );
        
        /** @var \fecshop\models\mysqldb\cart\Item $item_one */
        $item_one = $this->_itemModel->find()->where($where)->one();
        
        if ($item_one['cart_id']) {
            // 检查产品满足加入购物车的条件
            $checkItem = $item;
            $checkItem['qty'] = $item['qty'] + $item_one['qty'];
            $productValidate = Yii::$service->cart->info->checkProductBeforeAdd($checkItem, $product);
            
            if (!$productValidate) {
                return false;
            }
            $item_one->active = $this->itemDefaultActiveStatus;
            $item_one->qty = $item['qty'] + $item_one['qty'];
            $item_one->save();
            // 重新计算购物车的数量
            Yii::$service->cart->quote->computeCartInfo();
        } else {
            // 检查产品满足加入购物车的条件
            $checkItem = $item;
            $productValidate = Yii::$service->cart->info->checkProductBeforeAdd($checkItem, $product);
            if (!$productValidate) {
                return false;
            }
            $item_one = new $this->_itemModelName;
            $item_one->store = Yii::$service->store->currentStore;
            $item_one->cart_id = $cart_id;
            $item_one->created_at = time();
            $item_one->updated_at = time();
            $item_one->product_id = $item['product_id'];
            $item_one->qty = $item['qty'];
            $item_one->active = $this->itemDefaultActiveStatus;
            $item_one->custom_option_sku = ($item['custom_option_sku'] ? $item['custom_option_sku'] : '');
            $item_one->save();
            // 重新计算购物车的数量,并写入 sales_flat_cart 表存储
            Yii::$service->cart->quote->computeCartInfo();
        }
        
        $item['afterAddQty'] = $item_one->qty;
        $this->sendTraceAddToCartInfoByApi($item);

        return true;
    }

}
