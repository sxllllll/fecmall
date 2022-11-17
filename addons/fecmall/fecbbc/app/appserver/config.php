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
        //'\fecshop\app\appfront\modules\Catalog\block\category\Index'  => '\fecbbc\app\appfront\modules\Catalog\block\category\Index',
    ],
    'components' => [
        // yii2 语言组件配置，关于Yii2国际化，可以参看：http://www.yiichina.com/doc/guide/2.0/tutorial-i18n
        'i18n' => [
            'translations' => [
                'appserver' => [
                    'basePaths' => [
                        '@fecbbc/app/appserver/languages',
                    ],
                ],
            ],
        ],
    ],
    'modules' => [
        'customer' => [
            'controllerMap' => [
                'user' => 'fecbbc\app\appserver\modules\Customer\controllers\AccountController',
                'register' => 'fecbbc\app\appserver\modules\Customer\controllers\RegisterController',
                'login' => 'fecbbc\app\appserver\modules\Customer\controllers\LoginController',
                'coupon' => 'fecbbc\app\appserver\modules\Customer\controllers\CouponController',  
                'order' => 'fecbbc\app\appserver\modules\Customer\controllers\OrderController',  
                'productreview' => 'fecbbc\app\appserver\modules\Customer\controllers\ProductreviewController',  
                'contacts' => 'fecbbc\app\appserver\modules\Customer\controllers\ContactsController',  
            ], 
        ],
        'wx' => [
            'controllerMap' => [
                'home' => 'fecbbc\app\appserver\modules\Wx\controllers\HomeController',   
                'start' => 'fecbbc\app\appserver\modules\Wx\controllers\StartController',   
                'helper' => 'fecbbc\app\appserver\modules\Wx\controllers\HelperController',   
            ], 
        ],
        'coupon' => [
            'class' => '\fecbbc\app\appserver\modules\Coupon\Module',
        ],
        'catalog' => [
            'controllerMap' => [
                'product' => 'fecbbc\app\appserver\modules\Catalog\controllers\ProductController',   
                'category' => 'fecbbc\app\appserver\modules\Catalog\controllers\CategoryController',  
            ], 
        ],
        'checkout' => [
            'controllerMap' => [
                'onepage' => 'fecbbc\app\appserver\modules\Checkout\controllers\OnepageController',   
                'cart' => 'fecbbc\app\appserver\modules\Checkout\controllers\CartController',   
                'wx' => 'fecbbc\app\appserver\modules\Checkout\controllers\WxController', 
            ], 
        ]
    ],


];