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
        '\fecshop\app\appfront\modules\Catalog\block\category\Index'  => '\fecbbc\app\appfront\modules\Catalog\block\category\Index',
        '\fecshop\app\appfront\modules\Payment\block\paypal\standard\Start'  => '\fecbbc\app\appfront\modules\Payment\block\paypal\standard\Start',
        '\fecshop\app\appfront\modules\Payment\block\success\Index'  => '\fecbbc\app\appfront\modules\Payment\block\success\Index',
        '\fecshop\app\appfront\modules\Catalog\block\product\Index'  => '\fecbbc\app\appfront\modules\Catalog\block\product\Index',
        '\fecshop\app\appfront\modules\Catalog\block\category\Price'  => '\fecbbc\app\appfront\modules\Catalog\block\category\Price' ,
                    
        // '\fecshop\app\appfront\modules\Customer\block\address\Edit'  => '\fectb\app\appfront\modules\Customer\block\address\Edit',
    ],
    'modules' => [
        //'checkout' => [
        //    'controllerMap' => [
        //        'cartinfo' => 'fectfurnilife\app\appfront\modules\Checkout\controllers\CartInfoController',          
        //    ],
        //],
    ],
    'components' => [
        // yii2 语言组件配置，关于Yii2国际化，可以参看：http://www.yiichina.com/doc/guide/2.0/tutorial-i18n
        'i18n' => [
            'translations' => [
                'appfront' => [
                    'basePaths' => [
                        '@fecbbc/app/appfront/languages',
                    ],
                ],
            ],
        ],
    ],
    'modules' => [
        'payment' => [
            'controllerMap' => [
                'checkmoney' => 'fecbbc\app\appfront\modules\Payment\controllers\CheckmoneyController',       
           ],
        ],
        'customer' => [
            'controllerMap' => [
                'account' => 'fecbbc\app\appfront\modules\Customer\controllers\AccountController',       
                'editaccount' => 'fecbbc\app\appfront\modules\Customer\controllers\EditaccountController',      
                'coupon' => 'fecbbc\app\appfront\modules\Customer\controllers\CouponController',      
                'address' => 'fecbbc\app\appfront\modules\Customer\controllers\AddressController',      
                'order' => 'fecbbc\app\appfront\modules\Customer\controllers\OrderController',    
                'wx' => 'fecbbc\app\appfront\modules\Customer\controllers\WxController',                       
            ], 
            'params'=> [   
                'leftMenu'  => [ 
                    'My Coupon'             => 'customer/coupon',
                ],
            ],
        ],
        
        
        'catalog' => [
            'controllerMap' => [
                'reviewproduct' => 'fecbbc\app\appfront\modules\Catalog\controllers\ReviewproductController',  
                'favoriteproduct' => 'fecbbc\app\appfront\modules\Catalog\controllers\FavoriteproductController', 
                'shop' => 'fecbbc\app\appfront\modules\Catalog\controllers\ShopController',  
            ],
        ],
        'cms' => [
            'controllerMap' => [
                'home' => 'fecbbc\app\appfront\modules\Cms\controllers\HomeController',  
            ],
        ],
        'catalogsearch' => [
            'controllerMap' => [
                'index' => 'fecbbc\app\appfront\modules\Catalogsearch\controllers\IndexController',  
            ],
        ],
        'checkout' => [
            'controllerMap' => [
                'cartinfo' => 'fecbbc\app\appfront\modules\Checkout\controllers\CartInfoController',          
                'cart' => 'fecbbc\app\appfront\modules\Checkout\controllers\CartController', 
                'onepage' => 'fecbbc\app\appfront\modules\Checkout\controllers\OnepageController',    
            ],
        ],
        'coupon' => [
            'class' => '\fecbbc\app\appfront\modules\Coupon\Module',
        ],
    ],
    'services' => [
        'page' => [
            'childService' => [
                'widget' => [
                    'widgetConfig' => [
                        'base' => [
                            'header_mini' => [
                                'class' => 'fecshop\app\appfront\widgets\Headers',
                                // 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
                                'view'  => 'widgets/header_mini.php',
                                'cache' => [
                                    'timeout'    => 4500,
                                ],
                            ],
                            'header_shop' => [
                                'class' => 'fecshop\app\appfront\widgets\Headers',
                                // 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
                                'view'  => 'widgets/header_shop.php',
                                'cache' => [
                                    'timeout'    => 4500,
                                ],
                            ],
                        ],
                        'product' => [
                            'coupon' => [
                                'view'  => 'catalog/product/index/coupon.php',
                            ],
                        ],
                    ],
                ],
                'menu' => [
                    'behindCustomMenu' => [
                        [
                            'name'        => 'Fetch Coupon',            // 菜单名字
                            'urlPath'     => '/coupon/fetch/lists',    
                        ],
                        //[
                        //    'name'        => 'Newcomer Gift',            // 菜单名字
                        //    'urlPath'     => '/coupon/customer/registergift',    
                        //],
                    ],
                ],
            ],
        ],
    ],
];