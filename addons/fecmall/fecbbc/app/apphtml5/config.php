<?php
/**
 * FecMall file.
 * doc:https://packagist.org/packages/hightman/xunsearch.
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    // yii class rewrite map
    'yiiClassMap' => [
        // 'fecshop\app\appfront\helper\test\My' => '@appfront/helper/My.php',
    ],
    // 重写model和block
    'fecRewriteMap' => [
        '\fecshop\app\apphtml5\modules\Customer\block\account\Index'  => '\fecbbc\app\apphtml5\modules\Customer\block\account\Index',
        '\fecshop\app\apphtml5\modules\Payment\block\paypal\standard\Start'  => '\fecbbc\app\apphtml5\modules\Payment\block\paypal\standard\Start',
        '\fecshop\app\apphtml5\modules\Payment\block\success\Index'  => '\fecbbc\app\apphtml5\modules\Payment\block\success\Index',
        '\fecshop\app\apphtml5\modules\Catalog\block\product\Index'  => '\fecbbc\app\apphtml5\modules\Catalog\block\product\Index',
        '\fecshop\app\apphtml5\modules\Catalog\block\category\Price'  => '\fecbbc\app\apphtml5\modules\Catalog\block\category\Price' ,
        
        //'\fecshop\app\appfront\modules\Catalog\block\category\Index'  => '\fecbbc\app\appfront\modules\Catalog\block\category\Index',
        // '\fecshop\app\appfront\modules\Customer\block\address\Edit'  => '\fectb\app\appfront\modules\Customer\block\address\Edit',
    ],
    'components' => [
        // yii2 语言组件配置，关于Yii2国际化，可以参看：http://www.yiichina.com/doc/guide/2.0/tutorial-i18n
        'i18n' => [
            'translations' => [
                'apphtml5' => [
                    'basePaths' => [
                        '@fecbbc/app/apphtml5/languages',
                    ],
                ],
            ],
        ],
    ],
    'modules' => [
        'customer' => [
            'controllerMap' => [
                'account' => 'fecbbc\app\apphtml5\modules\Customer\controllers\AccountController',  
                'contacts' => 'fecbbc\app\apphtml5\modules\Customer\controllers\ContactsController',  
                'editaccount' => 'fecbbc\app\apphtml5\modules\Customer\controllers\EditaccountController',  
                'editpassword' => 'fecbbc\app\apphtml5\modules\Customer\controllers\EditpasswordController',  
                'productfavorite' => 'fecbbc\app\apphtml5\modules\Customer\controllers\ProductfavoriteController',  
                'productreview' => 'fecbbc\app\apphtml5\modules\Customer\controllers\ProductreviewController',  
                'order' => 'fecbbc\app\apphtml5\modules\Customer\controllers\OrderController',  
                'address' => 'fecbbc\app\apphtml5\modules\Customer\controllers\AddressController',  
                'ajax' => 'fecbbc\app\apphtml5\modules\Customer\controllers\AjaxController',
                'coupon' => 'fecbbc\app\apphtml5\modules\Customer\controllers\CouponController',  
                'wx' => 'fecbbc\app\apphtml5\modules\Customer\controllers\WxController',  
            ], 
        ],
        'catalogsearch'  => [
            'controllerMap' => [
                'text' => 'fecbbc\app\apphtml5\modules\Catalogsearch\controllers\TextController',  
                
            ],
        ],
        'catalog' => [
            'controllerMap' => [
                'categorylist' => 'fecbbc\app\apphtml5\modules\Catalog\controllers\CategorylistController',        
                'category'      => 'fecbbc\app\apphtml5\modules\Catalog\controllers\CategoryController',   
                'favoriteproduct' => 'fecbbc\app\apphtml5\modules\Catalog\controllers\FavoriteproductController', 
                'reviewproduct'      => 'fecbbc\app\apphtml5\modules\Catalog\controllers\ReviewproductController',     
                'shop'      => 'fecbbc\app\apphtml5\modules\Catalog\controllers\ShopController',                   
            ],
        ],
        'checkout' => [
            'controllerMap' => [
                'onepage' => 'fecbbc\app\apphtml5\modules\Checkout\controllers\OnepageController',    
                'cart' => 'fecbbc\app\apphtml5\modules\Checkout\controllers\CartController',                                    
            ],
        ],
        'coupon' => [
            'class' => '\fecbbc\app\apphtml5\modules\Coupon\Module',
        ],
        'payment' => [
            'controllerMap' => [
                'wxpayh5' => 'fecbbc\app\apphtml5\modules\Payment\controllers\Wxpayh5Controller',    
                'wxpayjsapi' => 'fecbbc\app\apphtml5\modules\Payment\controllers\WxpayjsapiController',   
                'checkmoney' => 'fecbbc\app\appfront\modules\Payment\controllers\CheckmoneyController',                       
            ],
        ],
    ],
    'services' => [
        'page' => [
            'childService' => [
                'widget' => [
                    'widgetConfig' => [
                        'base' => [
                            'header_navigation' => [
                                'view'  => 'widgets/header_navigation.php',
                            ],
                            'footer_navigation' => [
                                'view'  => 'widgets/footer_navigation.php',
                            ],
                            'afterContent' => [
                                'view'  => 'widgets/afterContent.php',
                            ],
                            'wx' => [
                                ///'class' => 'fecshop\app\appfront\widgets\Headers',
                                // 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
                                'view'  => '@fecbbc/app/apphtml5/theme/fecbbc/widgets/weixin.php',
                                //'cache' => [
                                //    'timeout'    => 4500,
                                //],
                            ],
                        ],
                        'category' => [
                            'price_favorite' => [
                                'class' 		=> 'fecshop\app\apphtml5\modules\Catalog\block\category\Price',
                                'view'  		=> 'catalog/category/price_favorite.php',
                            ],
                        ],
                        'product' => [
                            'coupon' => [
                                'view'  => 'catalog/product/index/coupon.php',
                            ],
                        ],
                    ],
                ],
                
            ],
        ],
    ],
];