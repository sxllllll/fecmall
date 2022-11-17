<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appserver\modules\Catalog\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;
use fecshop\app\appserver\modules\Catalog\helpers\Review as ReviewHelper;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductController extends \fecshop\app\appserver\modules\Catalog\controllers\ProductController
{
    
    // 网站信息管理
    public function actionIndex()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        /**
         * 通过Yii::mapGet() 得到重写后的class类名以及对象。Yii::mapGet是在文件@fecshop\yii\Yii.php中
         */
        list($this->_reviewHelperName,$this->_reviewHelper) = Yii::mapGet($this->_reviewHelperName);  
        
        $appName = Yii::$service->helper->getAppName();
        $middle_img_width = Yii::$app->store->get($appName.'_catalog','product_middle_img_width');
        
        if(!$this->initProduct()){
            $code = Yii::$service->helper->appserver->product_not_active;
            $data = '';
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data,$message);
            
            return $responseData;
        }
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        $reviewHelper = $this->_reviewHelper;
        $reviewHelper::initReviewConfig();
        $ReviewAndStarCount = $reviewHelper::getReviewAndStarCount($this->_product);
        list($review_count, $reviw_rate_star_average, $reviw_rate_star_info) = $ReviewAndStarCount;
        //list($review_count, $reviw_rate_star_average, $reviw_rate_star_info) = $reviewHelper::getReviewAndStarCount($this->_product);
        $this->filterProductImg($this->_product['image']);
        $groupAttrInfo = Yii::$service->product->getGroupAttrInfo($this->_product['attr_group']);
        $groupAttrArr = $this->getGroupAttrArr($groupAttrInfo);
        $custom_option_attr_info = Yii::$service->product->getCustomOptionAttrInfo($this->_product['attr_group']);
        //var_dump($custom_option_attr_info);exit;
        $custom_option_showImg_attr = $this->getCustomOptionShowImgAttr($custom_option_attr_info);
        //var_dump($custom_option_showImg_attr );exit;
        $thumbnail_img = [];
        $image = $this->_image_thumbnails;
        if(isset($image['gallery']) && is_array($image['gallery']) && !empty($image['gallery'])){
            $gallerys = $image['gallery'];
            $gallerys = \fec\helpers\CFunc::array_sort($gallerys,'sort_order',$dir='asc');
            if(is_array($image['main']) && !empty($image['main'])){
                $main_arr[] = $image['main'];
                $gallerys = array_merge($main_arr,$gallerys);
            }	
        }else if(is_array($image['main']) && !empty($image['main'])){
            $main_arr[] = $image['main'];
            $gallerys = $main_arr;
        }
        if(is_array($gallerys) && !empty($gallerys)){
            foreach($gallerys as $gallery){
                $image = $gallery['image'];
                $thumbnail_img[] = Yii::$service->product->image->getResize($image,$middle_img_width,false);
            }
        }
        $custom_option = $this->getCustomOption($this->_product['custom_option'],$middle_img_width); 
        $reviewHelper = new ReviewHelper;
        $reviewHelper->product_id = $this->_product[$productPrimaryKey];
        $reviewHelper->spu = $this->_product['spu'];
        $productReview = $reviewHelper->getLastData();
        $code = Yii::$service->helper->appserver->status_success;
        $productImage = isset($this->_product['image']['main']['image']) ? $this->_product['image']['main']['image'] : '' ;
        
        $productName = Yii::$service->store->getStoreAttrVal($this->_product['name'], 'name');
        $main_image = Yii::$service->product->image->getResize($productImage,[320, 350],false);
        $microshare = $this->getMicroShareInfo($productName, $main_image);
        
        $data = [
            'product' => [
                'groupAttrArr'              => $groupAttrArr,
                'name'                      => $productName,
                'sku'                       => $this->_product['sku'],
                
                'main_image' => $main_image,
                'package_number'            => $this->_product['package_number'],
                'spu'                       => $this->_product['spu'],
                'thumbnail_img'             => $thumbnail_img,
                'productReview'             => $productReview,
                'custom_option_showImg_attr'=> $custom_option_showImg_attr,
                'image_detail'              => $this->_image_detail,
                'attr_group'                => $this->_product['attr_group'],
                'review_count'              => $review_count,
                'reviw_rate_star_average'   => $reviw_rate_star_average,
                'reviw_rate_star_info'      => $reviw_rate_star_info,
                'price_info'                => $this->getProductPriceInfo(),
                'tier_price'                => $this->getTierPrice(),
                'options'                   => $this->getSameSpuInfo(),
                'custom_option'             => $custom_option,
                'description'               => Yii::$service->store->getStoreAttrVal($this->_product['description'], 'description'),
                '_id'                       => (string)$this->_product[$productPrimaryKey],
                'buy_also_buy'              => $this->getProductBySkus(),
                
            ],
            'microshare' => $microshare,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data,$message);
        
        return $responseData;
    }
    
    /**
     *  微信分享的信息
     */
    public function getMicroShareInfo($productName, $main_image)
    {
        
        return [
            'isDistribute' => false,
            'pageTitle' => $productName,
            'pageImgUrl' => $main_image,
            'distributeCode' => '',
        ];
    }
    
    public function actionCoupons()
    {
        $productId = Yii::$app->request->get('product_id');
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'coupons' => $this->getCoupons($productId),
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data,$message);
        
        return $responseData;
    }
    

    
    public function getCoupons($productId)
    {
        $productModel = Yii::$service->product->getByPrimaryKey($productId);
        $coupons = Yii::$service->coupon->getProductActiveCouponList($productModel);
        $coupon_ids = [];
        foreach ($coupons as $one) {
            $coupon_ids[] = $one['id'];
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
        $baseSymbol = Yii::$service->page->currency->getBaseSymbol();
        foreach ($coupons as $k=>$one) {
            $coupon_id = $coupons[$k]['id'];
            if (isset($customer_coupon_ids[$coupon_id])) {
                $coupons[$k]['fetched'] = true;
            } else {
                $coupons[$k]['fetched'] = false;
            }
            $coupons[$k]['baseSymbol'] = $baseSymbol;
            $coupons[$k]['assign_begin_at_str'] = date('Y.m.d', $one['assign_begin_at']);
            $coupons[$k]['assign_end_at_str'] = date('Y.m.d', $one['assign_end_at']);
        }
        
        return $coupons;
    }
    
    
    
    
    public function actionFavorite()
    {
        if (Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        if (Yii::$app->user->isGuest) {
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $product_id = Yii::$app->request->get('product_id');
        $product = Yii::$service->product->getByPrimaryKey($product_id);
        $favType = Yii::$app->request->get('type');
        $identity   = Yii::$app->user->identity;
        $user_id    = $identity->id;
        if ($favType == 'del') {
            $delStatus = Yii::$service->product->favorite->removeByProductIdAndUserId($product_id, $user_id);
            if (!$delStatus) {
                $code = Yii::$service->helper->appserver->product_favorite_fail;
                $data = [];
                $message = Yii::$service->helper->errors->get(true);
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data,$message);
                
                return $responseData;
            }else{
                $code = Yii::$service->helper->appserver->status_success;
                $data = [
                    'content' => 'success',
                ];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
            }
        } else {
            //$addStatus = Yii::$service->product->favorite->add($product_id, $user_id);
            $bdmin_user_id = $product['bdmin_user_id'];
            $addStatus = Yii::$service->product->favorite->addFav($bdmin_user_id, $product_id, $user_id);
            
            if (!$addStatus) {
                $code = Yii::$service->helper->appserver->product_favorite_fail;
                $data = [];
                $message = Yii::$service->helper->errors->get(true);
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data,$message);
                
                return $responseData;
            }else{
                $code = Yii::$service->helper->appserver->status_success;
                $data = [
                    'content' => 'success',
                ];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
            }
        }
        
        
        // 收藏失败，需要登录
        
        
    }
    
    
    /**
     * @return Array得到spu下面的sku的spu属性的数据。用于在产品
     * 得到spu下面的sku的spu属性的数据。用于在产品详细页面显示其他的sku
     * 譬如页面：https://fecshop.appfront.fancyecommerce.com/raglan-sleeves-letter-printed-crew-neck-sweatshirt-53386451-77774122
     * 该spu的其他sku的颜色尺码也在这里显示出来。
     */
    protected function getSameSpuInfo()
    {
        $groupAttr = Yii::$service->product->getGroupAttr($this->_product['attr_group']);
        // 当前的产品对应的spu属性组的属性，譬如 ['color','size','myyy']
        $this->_productSpuAttrArr = Yii::$service->product->getSpuAttr($this->_product['attr_group']);
        if (!is_array($this->_productSpuAttrArr) || empty($this->_productSpuAttrArr)) {
            
            return;
        }
        // 得到当前的spu下面的所有的值
        $select = ['name', 'image', 'url_key'];
        $data = $this->getSpuData($select);
        // 如果某个spu规格属性，在某个sku中不存在值，则就会被清除掉
        if (is_array($data) && !empty($data)) {
            foreach ($this->_productSpuAttrArr as $k=>$spuAttr){
                foreach ($data as $one) {
                    if (!isset($one[$spuAttr]) || !$one[$spuAttr]) {
                        unset($this->_productSpuAttrArr[$k]);
                        break;
                    }
                }
            }
        }
        
        $this->_spuAttrShowAsTop = $this->_productSpuAttrArr[0];
        //var_dump($this->_productSpuAttrArr);exit;
        $this->_spuAttrShowAsImg = Yii::$service->product->getSpuImgAttr($this->_product['attr_group']);
        $this->_currentSpuAttrValArr = [];
        foreach ($this->_productSpuAttrArr as $spuAttr) {
            if (isset($this->_product['attr_group_info']) && $this->_product['attr_group_info']) {  // mysql
                $attr_group_info = $this->_product['attr_group_info'];
                $spuAttrVal = $attr_group_info[$spuAttr];
            } else {
                $spuAttrVal = isset($this->_product[$spuAttr]) ? $this->_product[$spuAttr] : '';
            }
            
            if ($spuAttrVal) {
                $this->_currentSpuAttrValArr[$spuAttr] = $spuAttrVal;
            } else {
                // 如果某个spuAttr的值为空，则退出，这个说明产品数据有问题。
                return;
            }
        }
        
        $spuValColl = [];
        // 通过值，找到spu。
        $reverse_val_spu = [];
        if (is_array($data) && !empty($data)) {
            foreach ($data as $one) {
                $reverse_key = '';
                foreach ($this->_productSpuAttrArr as $spuAttr) {
                    $spuValColl[$spuAttr][$one[$spuAttr]] = $one[$spuAttr];
                    $reverse_key .= $one[$spuAttr];
                }

                //$active = 'class="active"';
                $one['main_img'] = isset($one['image']['main']['image']) ? $one['image']['main']['image'] : '';
                $one['url'] = Yii::$service->url->getUrl($one['url_key']);
                $reverse_val_spu[$reverse_key] = $one;
                $showAsImgVal = $one[$this->_spuAttrShowAsImg];
                if ($showAsImgVal) {
                    if (!isset($this->_spuAttrShowAsImgArr[$this->_spuAttrShowAsImg])) {
                        $this->_spuAttrShowAsImgArr[$showAsImgVal] = $one;
                    }
                }
                // 显示在顶部的spu属性（当没有图片属性的时候使用）
                $showAsTopVal = $one[$this->_spuAttrShowAsTop];
                if ($showAsTopVal) {
                    if (!isset($this->_spuAttrShowAsTopArr[$this->_spuAttrShowAsTop])) {
                        $this->_spuAttrShowAsTopArr[$showAsTopVal] = $one;
                    }
                }
            }
        }
        
        // 得到各个spu属性对应的值的集合。
        foreach ($spuValColl as $spuAttr => $attrValArr) {
            $spuValColl[$spuAttr] = array_unique($attrValArr);
            $spuValColl[$spuAttr] = $this->sortSpuAttr($spuAttr, $spuValColl[$spuAttr]);
        }

        $spuShowArr = [];
        foreach ($spuValColl as $spuAttr => $attrValArr) {
            $attr_coll = [];
            foreach ($attrValArr as $attrVal) {
                $attr_info = $this->getSpuAttrInfo($spuAttr, $attrVal, $reverse_val_spu);
                $attr_coll[] = $attr_info;
            }
            $spuShowArr[] = [
                'label' => $spuAttr,
                'value' => $attr_coll,
            ];
        }

        return $spuShowArr;
    }
    
    
    
}
