<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Checkout\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CartInfoController extends AppfrontController
{



    public function actionIndex()
    {
        $arr = [];
        $arr['product_total'] = 0;
        $cartInfos = Yii::$service->cart->quoteItem->getCartProductInfo();
        if (is_array($cartInfos) && !empty($cartInfos)) {
            $currency_info = Yii::$service->page->currency->getCurrencyInfo();
            $arr['symbol'] = $currency_info['symbol'];
            $product_total = isset($cartInfos['product_total']) ? $cartInfos['product_total'] : 0;
            $arr['product_total'] =  Yii::$service->helper->format->numberFormat($product_total);
            $products = $cartInfos['products'];
            if (is_array($products) && !empty($products)) {
                foreach ($products as $product) {
                    $product_image = isset($product['product_image']['main']['image']) ? $product['product_image']['main']['image'] : '';
                    $name = $product['name'];
                    $product_price = $product['product_price'];
                    $qty = $product['qty'];
                    $arr['products'][] = [
                        'product_image' => Yii::$service->product->image->getResize($product_image, [100,100], false)  ,
                        'name' => $name,
                        'product_price' => Yii::$service->helper->format->numberFormat($product_price),
                        'qty' => $qty,
                        'product_url' => Yii::$service->url->getUrl($product['product_url']),
                        'custom_option_info' => $this->getProductOptions($product),
                    ];
                }
            }
        }
        echo json_encode($arr); exit;
    }
    
    /**
     * 将产品页面选择的颜色尺码等显示出来，包括custom option 和spu options部分的数据.
     */
    public function getProductOptions($product_one)
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
        $str = '';
        if (is_array($custom_option_info_arr) && !empty($custom_option_info_arr)) {
            foreach ($custom_option_info_arr as $label => $val) {
                $str .= '<p>'.Yii::$service->page->translate->__(ucwords($label)).':'.Yii::$service->page->translate->__($val).'</p>';
                
            }
        }
        
        return $str;
    }

    
}
