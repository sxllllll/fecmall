<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Cms\block\home;

use Yii;
use fecshop\app\appfront\modules\Cms\block\home\Index as FecIndex;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends  FecIndex
{
    public function getConfigArr($str)
    {
        if (!$str) {
            return null;
        }
        $arr = explode(',', $str);
        
        return $arr;
    }
    
    public function getLastData()
    {
        $this->initHead();
        
        $hot_product_skus = Yii::$app->store->get('fecbbc_info', 'hot_product_skus'); 
        $chaoliu_s_skus_1 = Yii::$app->store->get('fecbbc_info', 'chaoliu_s_skus_1'); 
        $chaoliu_s_skus_2 = Yii::$app->store->get('fecbbc_info', 'chaoliu_s_skus_2'); 
        $chaoliu_x_skus_1 = Yii::$app->store->get('fecbbc_info', 'chaoliu_x_skus_1'); 
        $chaoliu_x_skus_2 = Yii::$app->store->get('fecbbc_info', 'chaoliu_x_skus_2'); 
        $new_product_skus = Yii::$app->store->get('fecbbc_info', 'new_product_skus'); 
        
        $hot_product_sku_arr = $this->getConfigArr($hot_product_skus);
        $chaoliu_s_sku_1_arr = $this->getConfigArr($chaoliu_s_skus_1);
        $chaoliu_s_sku_2_arr = $this->getConfigArr($chaoliu_s_skus_2);
        $chaoliu_x_sku_1_arr = $this->getConfigArr($chaoliu_x_skus_1);
        $chaoliu_x_sku_2_arr = $this->getConfigArr($chaoliu_x_skus_2);
        $new_product_sku_arr = $this->getConfigArr($new_product_skus);
        
        return [
            'hot_products' => $this->getProductBySkus($hot_product_sku_arr),
            'chaoliu_s1_products' => $this->getProductBySkus($chaoliu_s_sku_1_arr),
            'chaoliu_s2_products' => $this->getProductBySkus($chaoliu_s_sku_2_arr),
            'chaoliu_x1_products' => $this->getProductBySkus($chaoliu_x_sku_1_arr),
            'chaoliu_x2_products' => $this->getProductBySkus($chaoliu_x_sku_2_arr),
            'new_products' => $this->getProductBySkus($new_product_sku_arr),
        ];
        
    }
    
    public function getHomeFeaturedProducts()
    {
        $featured_skus = Yii::$app->controller->module->params['home_featured_products'];

        return $this->getProductBySkus($featured_skus);
    }
    
    public function getHomeNewProducts()
    {
        $home_new_products = Yii::$app->controller->module->params['home_new_products'];

        return $this->getProductBySkus($home_new_products);
    }
    
    public function getHomeOnSaleProducts()
    {
        $home_onsale_products = Yii::$app->controller->module->params['home_onsale_products'];

        return $this->getProductBySkus($home_onsale_products);
    }
    
}