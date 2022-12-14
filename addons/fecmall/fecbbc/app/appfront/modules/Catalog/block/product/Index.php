<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Catalog\block\product;

// use fecshop\app\appfront\modules\Catalog\helpers\Review as ReviewHelper;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends \fecshop\app\appfront\modules\Catalog\block\product\Index
{
    
    public function getLastData()
    {
        $reviewHelper = $this->_reviewHelper;
        $appName = Yii::$service->helper->getAppName();
        $product_small_img_width = Yii::$app->store->get($appName.'_catalog','product_small_img_width');
        $product_small_img_height = Yii::$app->store->get($appName.'_catalog','product_small_img_height');
        $product_middle_img_width = Yii::$app->store->get($appName.'_catalog','product_middle_img_width');
        $productImgMagnifier = Yii::$app->store->get($appName.'_catalog','productImgMagnifier');
        if (!$this->initProduct()) {
            Yii::$service->url->redirect404();
            return;
        }
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        $reviewHelper::initReviewConfig();
        list($review_count, $reviw_rate_star_average, $reviw_rate_star_info) = $reviewHelper::getReviewAndStarCount($this->_product);
        $this->filterProductImg($this->_product['image']);
        $groupAttrInfo = Yii::$service->product->getGroupAttrInfo($this->_product['attr_group']);
        $groupAttrArr = $this->getGroupAttrArr($groupAttrInfo);
        if (Yii::$app->request->isAjax) {
            $this->getAjaxProductHtml($product_small_img_width, $product_small_img_height, $product_middle_img_width, $productImgMagnifier, $this->_product[$productPrimaryKey]);
        }
        $productBdminUserId = $this->_product['bdmin_user_id'];
        $bdminUser = Yii::$service->bdminUser->bdminUser->getByPrimaryKey($productBdminUserId);
        $bdminName = isset($bdminUser['person']) ? $bdminUser['person'] : '';
        return [
            'groupAttrArr'              => $groupAttrArr,
            'name'                      => Yii::$service->store->getStoreAttrVal($this->_product['name'], 'name'),
            'image_thumbnails'          => $this->_image_thumbnails,
            'image_detail'              => $this->_image_detail,
            'sku'                       => $this->_product['sku'],
            'is_in_stock'                     => $this->_product['is_in_stock'],
            'package_number'            => $this->_product['package_number'],
            'spu'                       => $this->_product['spu'],
            'attr_group'                => $this->_product['attr_group'],
            'review_count'              => $review_count,
            'reviw_rate_star_average'   => $reviw_rate_star_average,
            'reviw_rate_star_info'      => $reviw_rate_star_info,
            'price_info'                => $this->getProductPriceInfo(),
            'tier_price'                => $this->_product['tier_price'],
            'media_size' => [
                'small_img_width'       => $product_small_img_width,
                'small_img_height'      => $product_small_img_height,
                'middle_img_width'      => $product_middle_img_width,
            ],
            'productImgMagnifier'       => $productImgMagnifier,
            'options'                   => $this->getSameSpuInfo(),
            'custom_option'             => $this->_product['custom_option'],
            'short_description'         => Yii::$service->store->getStoreAttrVal($this->_product['short_description'], 'short_description'),
            'description'               => Yii::$service->store->getStoreAttrVal($this->_product['description'], 'description'),
            '_id'                       => $this->_product[$productPrimaryKey],
            'buy_also_buy'              => $this->getProductBuyAlsoBuy(),
            'bdminName'   => $bdminName,
            'bdminUserId'  => $productBdminUserId,
            'productModel'  => $this->_product,
            'productM'  => $this->_product,
        ];
    }
    
    /**
     * @param $products | Array ??????????????????
     * ajax????????????????????????????????????
     * ?????????wap??????????????????????????????????????????????????????????????????????????????
     */
    protected function getAjaxProductHtml($product_small_img_width, $product_small_img_height, $product_middle_img_width, $productImgMagnifier, $product_id)
    {
        $imageParam = [
            'media_size' => [
                'small_img_width'       => $product_small_img_width,
                'small_img_height'      => $product_small_img_height,
                'middle_img_width'      => $product_middle_img_width,
            ],
            'image' => $this->_image_thumbnails,
            'productImgMagnifier' => $productImgMagnifier,
        ];
        $imgHtml = Yii::$service->page->widget->render('product/image',$imageParam);
        $options = $this->getSameSpuInfo();
        $optionsHtml = Yii::$service->page->widget->render('product/options', ['options' => $options]);
        $price_info = $this->getProductPriceInfo();
        $couponHtml = Yii::$service->page->widget->render('product/coupon', ['productModel' => $this->_product]);
        
        $priceHtml = Yii::$service->page->widget->render('product/price', ['price_info' => $price_info]);
        $tierPriceHtml =Yii::$service->page->widget->render('product/tier_price', ['tier_price' => $this->_product['tier_price']]);
        echo json_encode([
            'imgHtml'       => $imgHtml,
            'optionsHtml'   => $optionsHtml,
            'couponHtml'   =>  $couponHtml,
            'priceHtml' => $priceHtml,
            'tierPriceHtml' => $tierPriceHtml,
            'product_id'    => (string)$product_id,
            'sku'               => $this->_product['sku'],
        ]);
        exit;
    }
    
    /**
     * @return Array??????spu?????????sku???spu?????????????????????????????????
     * ??????spu?????????sku???spu????????????????????????????????????????????????????????????sku
     * ???????????????https://fecshop.appfront.fancyecommerce.com/raglan-sleeves-letter-printed-crew-neck-sweatshirt-53386451-77774122
     * ???spu?????????sku??????????????????????????????????????????
     */
    protected function getSameSpuInfo()
    {
        $groupAttr = Yii::$service->product->getGroupAttr($this->_product['attr_group']);
        // ????????????????????????spu??????????????????????????? ['color','size','myyy']
        $this->_productSpuAttrArr = Yii::$service->product->getSpuAttr($this->_product['attr_group']);
        if (!is_array($this->_productSpuAttrArr) || empty($this->_productSpuAttrArr)) {
            
            return;
        }
        // ???????????????spu?????????????????????
        $select = ['name', 'image', 'url_key'];
        $data = $this->getSpuData($select);
        // ????????????spu????????????????????????sku???????????????????????????????????????
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
                // ????????????spuAttr???????????????????????????????????????????????????????????????
                return;
            }
        }
        
        $spuValColl = [];
        // ??????????????????spu???
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
                // ??????????????????spu????????????????????????????????????????????????
                $showAsTopVal = $one[$this->_spuAttrShowAsTop];
                if ($showAsTopVal) {
                    if (!isset($this->_spuAttrShowAsTopArr[$this->_spuAttrShowAsTop])) {
                        $this->_spuAttrShowAsTopArr[$showAsTopVal] = $one;
                    }
                }
            }
        }
        
        // ????????????spu??????????????????????????????
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
