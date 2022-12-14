<?php
/**
 * FecMall file.
 * doc:https://packagist.org/packages/hightman/xunsearch.
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    'bootstrap' => ['store'],
    // 重写model和block
    'fecRewriteMap' => [
        // 【默认淘宝产品编辑模式】，如果您不想使用淘宝模式产品，那么请将下面的注释 '//'去掉
        //'\fecbbc\app\appbdmin\modules\Catalog\block\productinfo\Index'  => '\fecbbc\app\appbdmin\modules\Catalog\block\productinfo\Indexorigin',
        //'\fecbbc\app\appbdmin\modules\Catalog\block\productinfo\Managerbatchedit'  => '\fecbbc\app\appbdmin\modules\Catalog\block\productinfo\Managerbatcheditorigin',
        
    ],
    'modules'=>[
        'catalog' => [
            'class' => '\fecbbc\app\appbdmin\modules\Catalog\Module',
        ],
        'cms' => [
            'class' => '\fecbbc\app\appbdmin\modules\Cms\Module',
        ],
        'customer' => [
            'class' => '\fecbbc\app\appbdmin\modules\Customer\Module',
        ],
        'fecbdmin' => [
            'class' => '\fecadmin\Module',
            'controllerMap' => [
                'login' => [
                    'class' => 'fecbbc\app\appbdmin\modules\Fecbdmin\controllers\LoginController',
                ],
                'logout' => [
                    'class' => 'fecbbc\app\appbdmin\modules\Fecbdmin\controllers\LogoutController',
                ],
                'account' => [
                    'class' => 'fecbbc\app\appbdmin\modules\Fecbdmin\controllers\AccountController',
                ],
                'cache' => [
                    'class' => 'fecbbc\app\appbdmin\modules\Fecbdmin\controllers\CacheController',
                ],
                'config' => [
                    'class' => 'fecbbc\app\appbdmin\modules\Fecbdmin\controllers\ConfigController',
                ],
                'logtj' => [
                    'class' => 'fecbbc\app\appbdmin\modules\Fecbdmin\controllers\LogtjController',
                ],
                'log' => [
                    'class' => 'fecbbc\app\appbdmin\modules\Fecbdmin\controllers\LogController',
                ],
                'myaccount' => [
                    'class' => 'fecbbc\app\appbdmin\modules\Fecbdmin\controllers\MyaccountController',
                ],
                'index' => [
                    'class' => 'fecbbc\app\appbdmin\modules\Fecbdmin\controllers\IndexController',
                ],
                'error' => [
                    'class' => 'fecbbc\app\appbdmin\modules\Fecbdmin\controllers\ErrorController',
                ],
                'systemlog' => [
                    'class' => 'fecbbc\app\appbdmin\modules\Fecbdmin\controllers\SystemlogController',
                ],
                'resource' => [
                    'class' => 'fecbbc\app\appbdmin\modules\Fecbdmin\controllers\ResourceController',
                ],
                'role' => [
                    'class' => 'fecbbc\app\appbdmin\modules\Fecbdmin\controllers\RoleController',
                ],
            ],
        ],
        'sales' => [
            'class' => '\fecbbc\app\appbdmin\modules\Sales\Module',
        ],
        'system' => [
            'class' => '\fecbbc\app\appbdmin\modules\System\Module',
        ],
    ],
    'components' => [
        'store' => [
            'appName' => 'appbdmin',
        ],
        'user' => [
            'identityClass' => 'fecbbc\models\mysqldb\BdminUser',
            'enableAutoLogin' => true,
        ],
        'i18n' => [
            'translations' => [
                'appbdmin' => [
                    //'class' => 'yii\i18n\PhpMessageSource',
                    'class' => 'fecshop\yii\i18n\PhpMessageSource',
                    'basePaths' => [
                        '@fecbbc/app/appbdmin/languages',
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'fecbdmin/error',
        ],

        'urlManager' => [
            'rules' => [
                '' => 'fecbdmin/index/index',
            ],
        ],
    ],
    'params' => [
        // 'appbdminBaseTheme'     => '@fecbbc/app/appbdmin/theme/fecbbc',
        'appbdminBaseLayoutName'=> 'main_bdmin.php',
        'appName'               => 'appbdmin',
    ],
    'services' => [
        'page' => [
            'childService' => [
                'theme' => [
                    'thirdThemeDir' => [
                        '@fecbbc/app/appbdmin/theme/fecbbc',  // 后台模板路径
                    ],
                    // 【默认淘宝产品编辑模式】，如果您不想使用淘宝模式产品，那么请将下面的注释 '//'去掉
                    'viewFileConfig' => [
                    //    'catalog/productinfo/managerbatchedit' => '@fecbbc/app/appbdmin/theme/fecbbc/catalog/productinfo/managerbatchedit_origin.php',
                    ],
                ],
            ],
        ],
        'bdmin' => [
            'class' => 'fecbbc\services\Bdmin',
            // 子服务
            'childService' => [
                'menu' => [
                    'class'        => 'fecbbc\services\bdmin\Menu',
                    'menuConfig' => [
                        // 一级大类
                        'catalog' => [
                            'label' => 'Category & Prodcut',
                            'child' => [
                                // 二级类
                                'product_manager' => [
                                    'label' => 'Manager Product',
                                    'child' => [
                                        // 三级类
                                        'product_info_manager' => [
                                            'label' => 'Product Info',
                                            'url_key' => '/catalog/productinfo/index',
                                        ],
                                        // 三级类
                                        'product_review_manager' => [
                                            'label' => 'Product Reveiew',
                                            'url_key' => '/catalog/productreview/index',
                                        ],
                                        
                                        'product_favorite_manager' => [
                                            'label' => 'Product Favorite',
                                            'url_key' => '/catalog/productfavorite/index',
                                        ],
                                    ]
                                ]
                            ]
                        ],
                        /*
                        'CMS' => [
                            'label' => 'CMS',
                            'child' => [
                                'base_info' => [
                                    'label' => 'Base Info Config',
                                    'url_key' => '/cms/baseinfo/manager',
                                ],
                                'shipping_theme_info' => [
                                    'label' => 'Shipping Theme Config',
                                    'url_key' => '/cms/shipping/manager',
                                ],
                                
                                'home_page' => [
                                    'label' => 'Home Page Config',
                                    'url_key' => '/cms/homepage/manager',
                                ],
                                
                                
                            ],
                        ],
                        */
                        'sales' => [
                            'label' => 'Sales',
                            'child' => [
                                'order_info' => [
                                    'label' => 'Order Info',
                                    'child' => [
                                        'order_manager' => [
                                            'label' => 'All Order List',
                                            'url_key' => '/sales/orderinfo/manager',
                                        ],
                                    ],
                                ],
                                'order' => [
                                    'label' => 'Order Process',
                                    'child' => [
                                        'order_pending_edit_manager' => [
                                            'label' => 'Pending Order Edit',
                                            'url_key' => '/sales/orderedit/manager',
                                        ],
                                        'order_cancel_manager' => [
                                            'label' => 'Order Cancel Audit',
                                            'url_key' => '/sales/ordercancel/manager',
                                        ],
                                        'order_audit_manager' => [
                                            'label' => 'Order Info Audit',
                                            'url_key' => '/sales/orderaudit/manager',
                                        ],
                                        'order_export' => [
                                            'label' => 'Dispatch Order Export',
                                            'url_key' => '/sales/orderexport/manager',
                                        ],
                                        'order_dispatch' => [
                                            'label' => 'Order Dispatch',
                                            'url_key' => '/sales/orderdispatch/manager',
                                        ],
                                        'order_received' => [
                                            'label' => 'Order Received List',
                                            'url_key' => '/sales/orderreceived/manager',
                                        ],
                                    ],
                                ],
                                
                                'order_after_sale' => [
                                    'label' => 'Order After Sale',
                                    'child' => [
                                        'order_return_wainting' => [
                                            'label' => 'Return Wainting',
                                            'url_key' => '/sales/returnwaiting/manager',
                                        ],
                                        'order_return_received' => [
                                            'label' => 'Return Receive',
                                            'url_key' => '/sales/returnreceive/manager',
                                        ],
                                        'order_return_refund' => [
                                            'label' => 'Return Refund',
                                            'url_key' => '/sales/returnrefund/manager',
                                        ],
                                    ],
                                ],
                                
                                'order_month_settle' => [
                                    'label' => 'Order Month Settle',
                                    'url_key' => '/sales/ordersettle/manager',
                                ],
                                
                                'order_operate_log' => [
                                    'label' => 'Order Operate Log',
                                    'url_key' => '/sales/orderlog/manager',
                                ],
                                'shipping_theme_info' => [
                                    'label' => 'Shipping Theme Config',
                                    'url_key' => '/cms/shipping/manager',
                                ],
                                'coupon_manager' => [
                                    'label' => 'Coupon Manager',
                                    'url_key' => '/sales/coupon/manager',
                                ],
                                'bdmin_config_manager' => [
                                    'label' => 'Bdmin Config Manager',
                                    'url_key' => '/sales/bdminconfig/manager',
                                ],
                            ],
                        ],
                        /*
                        'customer' => [
                            'label' => 'Manager User',
                            'child' => [
                                'account' => [
                                    'label' => 'Manager Account',
                                    'url_key' => '/customer/account/index',
                                ],
                                'uuidurl' => [
                                    'label' => 'Uuid Url Generate',
                                    'url_key' => '/customer/uuidurl/generate',
                                ],
                            ],
                        ],
                        */
                        'dashboard' => [
                            'label' => 'Dashboard',
                            'child' => [
                                'adminuser' => [
                                    'label' => 'Admin User',
                                    'child' => [
                                        'myaccount' => [
                                            'label' => 'My Account',
                                            'url_key' => '/fecbdmin/myaccount/index',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    
    
    
    ],
];