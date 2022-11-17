<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appserver\modules\Wx\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0 
 */
class HomeController extends \fecshop\app\appserver\modules\Wx\controllers\HomeController
{
    //   general/start/first
    public function actionIndex()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        
        // 首页走马灯大图部分
        $homeData = [];
        $home_bigimg_imgurl_1 = Yii::$app->store->get('appserver_home', 'home_bigimg_imgurl_1');
        $home_bigimg_linkurl_1 = Yii::$app->store->get('appserver_home', 'home_bigimg_linkurl_1');
        $home_bigimg_imgurl_2 = Yii::$app->store->get('appserver_home', 'home_bigimg_imgurl_2');
        $home_bigimg_linkurl_2 = Yii::$app->store->get('appserver_home', 'home_bigimg_linkurl_2');
        if ($home_bigimg_imgurl_1 && $home_bigimg_imgurl_2) {
            // 后台上传的值
            $homeData[] = [
                'linkUrl' => $home_bigimg_linkurl_1,
                'picUrl' => Yii::$service->image->getUrlByRelativePath($home_bigimg_imgurl_1),
            ];
            $homeData[] = [
                'linkUrl' => $home_bigimg_linkurl_2,
                'picUrl' => Yii::$service->image->getUrlByRelativePath($home_bigimg_imgurl_2),
            ];
        } else {
            // 默认值
            $homeData[] = [
                'linkUrl' => '',
                'picUrl' => Yii::$service->image->getImgUrl('wx/6a202e5f215489f5082b5293476f301c.jpg'),
            ];
            $homeData[] = [
                'linkUrl' => '',
                'picUrl' => Yii::$service->image->getImgUrl('wx/2829495b358a87480dcf0abf4b77c9b7.jpg'),
            ];
        }
        
        // 首页的4个banner
        $hotData = [];
        $home_bannerimg_imgurl_1 = Yii::$app->store->get('appserver_home', 'home_bannerimg_imgurl_1');
        $home_bannerimg_linkurl_1 = Yii::$app->store->get('appserver_home', 'home_bannerimg_linkurl_1');
        $home_bannerimg_imgurl_2 = Yii::$app->store->get('appserver_home', 'home_bannerimg_imgurl_2');
        $home_bannerimg_linkurl_2 = Yii::$app->store->get('appserver_home', 'home_bannerimg_linkurl_2');
        $home_bannerimg_imgurl_3 = Yii::$app->store->get('appserver_home', 'home_bannerimg_imgurl_3');
        $home_bannerimg_linkurl_3 = Yii::$app->store->get('appserver_home', 'home_bannerimg_linkurl_3');
        $home_bannerimg_imgurl_4 = Yii::$app->store->get('appserver_home', 'home_bannerimg_imgurl_4');
        $home_bannerimg_linkurl_4 = Yii::$app->store->get('appserver_home', 'home_bannerimg_linkurl_4');
        if ($home_bannerimg_imgurl_1 && $home_bannerimg_imgurl_2 && $home_bannerimg_imgurl_3 && $home_bannerimg_imgurl_4) {
            $hotData[] = [
                'linkUrl' => $home_bannerimg_linkurl_1,
                'picUrl' => Yii::$service->image->getUrlByRelativePath($home_bannerimg_imgurl_1),
            ];
            $hotData[] = [
                'linkUrl' => $home_bannerimg_linkurl_2,
                'picUrl' => Yii::$service->image->getUrlByRelativePath($home_bannerimg_imgurl_2),
            ];
            $hotData[] = [
                'linkUrl' => $home_bannerimg_linkurl_3,
                'picUrl' => Yii::$service->image->getUrlByRelativePath($home_bannerimg_imgurl_3),
            ];
            $hotData[] = [
                'linkUrl' => $home_bannerimg_linkurl_4,
                'picUrl' => Yii::$service->image->getUrlByRelativePath($home_bannerimg_imgurl_4),
            ];
        } else {
            // 默认值
            $hotData[] = [
                'linkUrl' => '/pages/goods-detail/goods-detail?id=115781',
                'picUrl' => Yii::$service->image->getImgUrl('wx/f9d34e8258cdef1dbcb5e1de65bdb404.jpg'),
            ];
            $hotData[] = [
                'linkUrl' => '/pages/goods-detail/goods-detail?id=115781',
                'picUrl' => Yii::$service->image->getImgUrl('wx/a7658f2456e708e6be03d393cef0d368.jpg'),
            ];
            $hotData[] = [
                'linkUrl' => '/pages/goods-detail/goods-detail?id=115781',
                'picUrl' => Yii::$service->image->getImgUrl('wx/868b8a0e1065f7123c949fb404049ce0.jpg'),
            ];
            $hotData[] = [
                'linkUrl' => '/pages/goods-detail/goods-detail?id=115781',
                'picUrl' => Yii::$service->image->getImgUrl('wx/6480b6574ab76caf1d86a9fc327a62e8.jpg'),
            ]; 
        }
        // 四个导航图标
        $salesData = [];
        $salesData[] = [
            'linkUrl' => '/pages/live-player/live-player',
            'picUrl' => Yii::$service->image->getImgUrl('addons/fecbbc/qnav2.png'),
            'title'  => Yii::$service->page->translate->__('直播'),
        ];
        $salesData[] = [
            'linkUrl' => '/pages/coupons/coupons',
            'picUrl' => Yii::$service->image->getImgUrl('addons/fecbbc/qnav4.png'),
            'title'  => Yii::$service->page->translate->__('Get Coupon'),
        ];
        $salesData[] = [
            'linkUrl' => '/pages/fav-list/fav-list',
            'picUrl' => Yii::$service->image->getImgUrl('addons/fecbbc/qnav3.png'),
            'title'  => Yii::$service->page->translate->__('My Favorite'),
        ];
        $salesData[] = [
            'linkUrl' => '/pages/cate/cate',
            'picUrl' => Yii::$service->image->getImgUrl('addons/fecbbc/h5/01b097e06ac9fc78bbcc3d3e0dfbe01fcc.png'),
            'title'  => Yii::$service->page->translate->__('All Category'),
        ];
        
        
        $productData = $this->getProduct();
        
        
        $currencys = Yii::$service->page->currency->getCurrencys();
        $currentCurrencyCode = Yii::$service->page->currency->getCurrentCurrency();
        $currencyList = [];
        $currencyCodeList = [];
        foreach ($currencys as $currency) {
            $currencyList[] = $currency['symbol']. '' . $currency['code'];
            $currencyCodeList[] = $currency['code'];
        }
        $currency = [
            'currencyList' => $currencyList,
            'currencyCodeList' => $currencyCodeList,
            'currentCurrency' => $currentCurrencyCode
        ];
        $microshare = $this->getMicroShareInfo();
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            //'home' => $homeData,
            'banners'  => $homeData,
            'products' => $productData,
            'topgoods'  => [
                'remark' => '备注',
                'value'  => Yii::$service->page->translate->__('Feature Product'),  //'人气推荐',
            ],
            'hot'  => $hotData,
            'sales' => $salesData,
            'currency'  => $currency,
            'microshare' => $microshare,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    
    public function getProduct(){
        $homeFeaturedSku = [];
        $home_product_skus = Yii::$app->store->get('appserver_home', 'home_product_skus');
        if ($home_product_skus) {
            $homeFeaturedSku = explode(',', $home_product_skus);
        } else {
            // 默认产品数据
            $homeFeaturedSku = [
                'p10001-kahaki-xl','32332', '432432',
                'sk2001-blue-zo', 'sk0008',
                'sk0004', 'sk0003',
                'sk0002', 'sk1000-black',
            ];
            
        }
        return $this->getProductBySkus($homeFeaturedSku);
    }
    
    public function getProductBySkus($skus)
    {
        if (is_array($skus) && !empty($skus)) {
            $productPrimaryKey = Yii::$service->product->getPrimaryKey();
            $filter['select'] = [
                $productPrimaryKey,
                'sku', 'spu', 'name', 'image',
                'price', 'special_price',
                'special_from', 'special_to',
                'url_key', 'score',
            ];
            $filter['where'] = ['in', 'sku', $skus];
            $products = Yii::$service->product->getProducts($filter);
            //var_dump($products);
            $products = Yii::$service->category->product->convertToCategoryInfo($products);
            $product_return = [];
            if(is_array($products) && !empty($products)){
                foreach($products as $k=>$v){
                    
                    $priceInfo = Yii::$service->product->price->getCurrentCurrencyProductPriceInfo($v['price'], $v['special_price'],$v['special_from'],$v['special_to']);
                    $price = isset($priceInfo['price']) ? $priceInfo['price'] : '';
                    $special_price = isset($priceInfo['special_price']) ? $priceInfo['special_price'] : '';
                    
                    
                    $product_return[] = [
                        'name' => $v['name'],
                        'pic'  => Yii::$service->product->image->getResize($v['image'],296,false),
                        'special_price'  => $special_price,
                        'price'  => $price,
                        'id'  => $v['product_id'],
                    ];
                }
                
            }
            return $product_return;
        }
    }
    /**
     *  微信分享的信息
     */
    public function getMicroShareInfo()
    {
        $homePageTitle = Yii::$service->wx->micro->homePageTitle;
        $homePageImgUrl = Yii::$service->wx->micro->homePageImgUrl;
        
        return [
            'isDistribute' => false,
            'pageTitle' => $homePageTitle,
            'pageImgUrl' => $homePageImgUrl,
            'distributeCode' => '',
        ];
    }
}