<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Checkout\block\onepage;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends \fecshop\app\appfront\modules\Checkout\block\onepage\Index
{
    protected $_shipping_method;
    protected $_address_id;
    protected $_address_list;
    protected $_country;
    protected $_state;
    protected $_stateHtml;
    protected $_cartAddress;
    protected $_cart_address;
    protected $_cart_info;
    protected $_default_address;
    
    protected $bdmin_info;
    
    public function getLastData()
    {
        // 得到default address
        $this->_default_address = Yii::$service->customer->address->getDefaultAddress();
        
        if (!$this->_default_address) {
            
            Yii::$service->url->redirectByUrlKey('checkout/onepage/address');
        }
        // cart info
        $cartInfo = Yii::$service->cart->getCartOrderInfo();
        // 检查购物车是否存在产品
        if (empty($cartInfo) || !is_array($cartInfo)) {
            
            return Yii::$service->url->redirectByUrlKey('checkout/cart');
        }
        
        // currenty info
        $currency_info = Yii::$service->page->currency->getCurrencyInfo();
        // 货运地址列表
        $this->_address_list = Yii::$service->customer->address->currentAddressList();
        //var_dump($this->_address_list);exit;
        // shipping
        /*
        $default_shipping = [];
        $shippings = $this->getShippingsInfo($cartInfo['shipping_method']);
        //var_dump($shippings );exit;
        if (is_array($shippings)) {
            foreach ($shippings as $shipping) {
                if ($shipping['checked']) {
                    $default_shipping = $shipping;
                }
            }
        }
        */
        
        
        $last_cart_info = $this->getCartOrderInfo();
        $data = [
            'cart_infos'                 => $last_cart_info['cart_details'],  
            'all_count'   => $last_cart_info['all_count'],  
            'all_shipping_cost' => $last_cart_info['all_shipping_cost'],
            'all_shipping_base_cost' => $last_cart_info['all_shipping_base_cost'],
            
            'all_total'   => $last_cart_info['all_total'],
            'all_base_total'   => $last_cart_info['all_base_total'], 
            
            'all_discount'   => $last_cart_info['all_discount'],
            'all_base_discount'   => $last_cart_info['all_base_discount'], 
            
            'all_product_base_total'   => $last_cart_info['all_product_base_total'],
            'all_product_total'   => $last_cart_info['all_product_total'], 
            
            'bdmin_info'                 => $this->bdmin_info,  
            'currency_info'             => $currency_info,
            'default_address_list'              => $this->_default_address,
            'show_coupon' => Yii::$service->helper->canShowUseCouponCode(),
            'address_list'    => $this->_address_list,
        ];
        
        return $data;
    }
    
    /**
     * @return $cart_info | Array
     *                    本函数为从数据库中得到购物车中的数据，然后结合产品表
     *                    在加入一些产品数据，最终补全所有需要的信息。
     */
    public function getCartOrderInfo()
    {
        $bdminArr = [];
        if (!$this->_cart_info) {
            // editForm[shipping_method][5]
            $postParam = Yii::$app->request->post('editForm');
            //var_dump($postParam);exit;
            $postParam = \Yii::$service->helper->htmlEncode($postParam);
            $postShippingMethod = (isset($postParam['shipping_method']) && is_array($postParam['shipping_method']) ) ? $postParam['shipping_method'] : '';
            $postCartCoupon = (isset($postParam['cart_coupon']) && is_array($postParam['cart_coupon']) ) ? $postParam['cart_coupon'] : '';
            $cartOrderInfo = Yii::$service->cart->getCartOrderInfo($postShippingMethod, $postCartCoupon);
            
            $cart_info = $cartOrderInfo['cart_info'];
            if (!is_array($cart_info) || empty($cart_info)) {
                return null;
            }
            foreach ($cart_info as $bdmin_user_id => $bdminCart) {
                $products = $bdminCart['products'];
                $bdminArr[] = $bdmin_user_id;
                $cart_info[$bdmin_user_id]['grand_total'] = Yii::$service->helper->format->numberFormat($cart_info[$bdmin_user_id]['grand_total']);
                $cart_info[$bdmin_user_id]['product_total'] = Yii::$service->helper->format->numberFormat($cart_info[$bdmin_user_id]['product_total']);
                
                
                foreach ($products  as $k => $product_one) {
                    $cart_info[$bdmin_user_id]['products'][$k]['name'] = Yii::$service->store->getStoreAttrVal($product_one['product_name'], 'name');
                    // 设置图片
                    if (isset($product_one['product_image']['main']['image'])) {
                        $image = $product_one['product_image']['main']['image'];
                        $cart_info[$bdmin_user_id]['products'][$k]['imgUrl'] = Yii::$service->product->image->getResize($image,[100,100],false);
                    }
                    $custom_option = isset($product_one['custom_option']) ? $product_one['custom_option'] : '';
                    $custom_option_sku = $product_one['custom_option_sku'];
                    // 将在产品页面选择的颜色尺码等属性显示出来。
                    $custom_option_info_arr = $this->getProductOptions($product_one, $custom_option_sku);
                    $cart_info[$bdmin_user_id]['products'][$k]['custom_option_info'] = $custom_option_info_arr;
                    // 设置相应的custom option 对应的图片
                    $custom_option_image = isset($custom_option[$custom_option_sku]['image']) ? $custom_option[$custom_option_sku]['image'] : '';
                    if ($custom_option_image) {
                        $cart_info[$bdmin_user_id]['products'][$k]['image'] = $custom_option_image;
                    } 
                }
            }
            $this->_cart_info = [
                'cart_details' => $cart_info,
                'all_count'  => $cartOrderInfo['all_count'],
                'all_shipping_cost' => Yii::$service->helper->format->numberFormat($cartOrderInfo['all_shipping_cost']),
                'all_shipping_base_cost' => Yii::$service->helper->format->numberFormat($cartOrderInfo['all_shipping_base_cost']),
                'all_total' => Yii::$service->helper->format->numberFormat($cartOrderInfo['all_total']),
                'all_base_total' => Yii::$service->helper->format->numberFormat($cartOrderInfo['all_base_total']) ,
                
                'all_discount' => Yii::$service->helper->format->numberFormat($cartOrderInfo['all_discount']),
                'all_base_discount' => Yii::$service->helper->format->numberFormat($cartOrderInfo['all_base_discount']) ,
                
                'all_product_total' => Yii::$service->helper->format->numberFormat($cartOrderInfo['all_product_total']),
                'all_product_base_total' => Yii::$service->helper->format->numberFormat($cartOrderInfo['all_product_base_total']) ,
                
            ];
            if (!empty($bdminArr)) {
                $this->bdmin_info = Yii::$service->bdminUser->bdminUser->getIdAndPersonArrByIds($bdminArr);
            }
        }
        
        return $this->_cart_info;
    }

    /**
     * 将产品页面选择的颜色尺码等显示出来，包括custom option 和spu options部分的数据.
     */
    public function getProductOptions($product_one, $custom_option_sku)
    {
        $custom_option_info_arr = [];
        $custom_option = isset($product_one['custom_option']) ? $product_one['custom_option'] : '';
        $custom_option_sku = $product_one['custom_option_sku'];
        if (isset($custom_option[$custom_option_sku]) && !empty($custom_option[$custom_option_sku])) {
            $custom_option_info = $custom_option[$custom_option_sku];
            foreach ($custom_option_info as $attr=>$val) {
                if (!in_array($attr, ['qty', 'sku', 'price', 'image'])) {
                    $attr = str_replace('_', ' ', $attr);
                    $attr = ucfirst($attr);
                    $custom_option_info_arr[$attr] = $val;
                }
            }
        }

        $spu_options = $product_one['spu_options'];
        if (is_array($spu_options) && !empty($spu_options)) {
            foreach ($spu_options as $label => $val) {
                $custom_option_info_arr[$label] = $val;
            }
        }

        return $custom_option_info_arr;
    }
    
    /**
     * @param $current_shipping_method | String  当前选择的货运方式
     * @return Array，数据格式为：
     *                                    * [
     *      'method'=> $method,
     *      'label' => $label,
     *      'name'  => $name,
     *      'cost'  => $symbol.$currentCurrencyCost,
     *      'check' => $check,
     *      'shipping_i' => $shipping_i,
     * ]
     * 得到所有的，有效shipping method数组。
      */
    public function getShippingsInfo($cartShippingMethod)
    {
        $country = $this->_default_address['country'];
        $state = $this->_default_address['state'];
        $cartProductInfo = Yii::$service->cart->quoteItem->getCartProductInfo();
        //echo $country ;
        $product_weight = $cartProductInfo['product_weight'];
        $product_volume_weight = $cartProductInfo['product_volume_weight'];
        $product_final_weight = max($product_weight, $product_volume_weight);
        $current_shipping_method = Yii::$service->shipping->getCurrentShippingMethod($custom_shipping_method, $cartShippingMethod, $country, $state, $product_final_weight);

        $this->_shipping_method = $current_shipping_method;
        // 得到所有，有效的shipping method
        $shippingArr = $this->getShippingArr($product_final_weight, $current_shipping_method, $country, $state);

        return $shippingArr;
    }
    
    
    
}
