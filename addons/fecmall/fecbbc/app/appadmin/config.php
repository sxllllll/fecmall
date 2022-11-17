<?php
/**
 * FecMall file.
 * doc:https://packagist.org/packages/hightman/xunsearch.
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    'params' => [
        // 设置模板路径：bbc base theme dir
        // 'bbcBaseThemeDir' => '@fecbbc/app/appadmin/theme/fecbbc',
    ],
    // 重写model和block
    'fecRewriteMap' => [
        // 默认淘宝产品编辑模式，如果您不想使用淘宝模式产品，那么请将下面的注释 '//'去掉
        //'\fecbbc\app\appadmin\modules\Catalog\block\productinfo\Index'  => '\fecbbc\app\appadmin\modules\Catalog\block\productinfo\Indexorigin',
        //'\fecbbc\app\appadmin\modules\Catalog\block\productinfo\Managerbatchedit'  => '\fecbbc\app\appadmin\modules\Catalog\block\productinfo\Managerbatcheditorigin',
        '\fecshop\app\appadmin\modules\Catalog\block\productupload\Manager'  => '\fecbbc\app\appadmin\modules\Catalog\block\productupload\Manager',
    
    ],
    
    'modules' => [
        'config' => [ // sales 模块
            'controllerMap' => [
                'fecbbc' => 'fecbbc\app\appadmin\modules\Config\controllers\FecbbcController',
                'order' => 'fecbbc\app\appadmin\modules\Config\controllers\OrderController',
                'fecphone' => 'fecbbc\app\appadmin\modules\Config\controllers\FecphoneController',
                'appserverhome' => 'fecbbc\app\appadmin\modules\Config\controllers\AppserverhomeController',
            ],
        ],
        'catalog' => [
            'controllerMap' => [
                'productinfo' => 'fecbbc\app\appadmin\modules\Catalog\controllers\ProductinfoController',          
                'productreview' => 'fecbbc\app\appadmin\modules\Catalog\controllers\ProductreviewController',        
                'productfavorite' => 'fecbbc\app\appadmin\modules\Catalog\controllers\ProductfavoriteController',        
            
            ],
        ],
        'cms' => [
            'controllerMap' => [
                'homepage' => 'fecbbc\app\appadmin\modules\Cms\controllers\HomepageController',       
                'baseinfo' => 'fecbbc\app\appadmin\modules\Cms\controllers\BaseinfoController',                      
            ],
        ],
        'customer' => [
            'controllerMap' => [
                'account' => 'fecbbc\app\appadmin\modules\Customer\controllers\AccountController',          
            ],
        ],
        'sales' => [
            'controllerMap' => [
                'orderinfo' => 'fecbbc\app\appadmin\modules\Sales\controllers\OrderinfoController',      
                'ordersettle' => 'fecbbc\app\appadmin\modules\Sales\controllers\OrdersettleController',                    
                'returnlist' => 'fecbbc\app\appadmin\modules\Sales\controllers\ReturnlistController',          
                'refund' => 'fecbbc\app\appadmin\modules\Sales\controllers\RefundController',      
                'refundbdmin' => 'fecbbc\app\appadmin\modules\Sales\controllers\RefundbdminController',                 
                'orderlog' => 'fecbbc\app\appadmin\modules\Sales\controllers\OrderlogController',      
                
            ],
        ],
        'supplier' => [
            'class' => '\fecbbc\app\appadmin\modules\Supplier\Module',
            'params'=> [

            ],
        ],
    ],
    'components' => [
        // yii2 语言组件配置，关于Yii2国际化，可以参看：http://www.yiichina.com/doc/guide/2.0/tutorial-i18n
        'i18n' => [
            'translations' => [
                'appadmin' => [
                    'basePaths' => [
                        '@fecbbc/app/appadmin/languages',
                    ],
                ],
            ],
        ],
    ],
    'services' => [
        'page' => [
            'childService' => [
                'theme' => [
                    'thirdThemeDir' => [
                        '@fecbbc/app/appadmin/theme/fecbbc',  // 后台模板路径
                    ],
                    // 默认淘宝产品编辑模式，如果您不想使用淘宝模式产品，那么请将下面的注释 '//'去掉
                    'viewFileConfig' => [
                    //    'catalog/productinfo/managerbatchedit' => '@fecbbc/app/appadmin/theme/fecbbc/catalog/productinfo/managerbatchedit_origin.php',
                    ],
                ],
            ],
        ],
        'admin' => [
            'childService' => [
                'urlKey' => [
                    'urlKeyTags' => [
                        'sales_order_process' => 'Sales-Order-Process',
                        'config_fecbbc_manager' => 'Config-Fecbbc',
                        'config_fecphone' 		=> 'Config-Fecphone',
                        ///
                        'supplier-account' 							=> 'Supplier Account',
                        'supplier-apply' 							=> 'Supplier Apply',
                        'order-after-sale' 							=> 'Order After Sale',
                        'order-refund' 							    => 'Refund',
                        'order-statistics' 							=> 'Order Statistics',
                        'config-homepage' 							=> 'Config Home Page',
                        'config-baseinfo' 							=> 'Config Base Info',
                        'order-log' 							            => 'Order Log',
                    ],
                ],
                
                'page' => [
                    
                ],
                'menu' => [
                    'menuConfig' => [
                        'config' => [
                            'child' => [
                                'services' => [
                                    'child' => [
                                        'fecbbc_manager' => [
                                            'label' => 'Fecbbc Config',
                                            'url_key' => '/config/fecbbc/manager',
                                        ],
                                        'fecphone_manager' => [
                                            'label' => 'FecPhone Config',
                                            'url_key' => '/config/fecphone/manager',
                                        ],
                                    ],
                                ],
                            ],
                        ], 
                        ///
                        'Supplier' => [
                            'label' => 'Manager Supplier',
                            'child' => [
                                'Supplier' => [
                                    'label' => 'Manager Supplier',
                                    'child' => [
                                        'apply' => [
                                            'label' => 'Supplier Apply',
                                            'url_key' => '/supplier/apply/manager',
                                        ],
                                        'account' => [
                                            'label' => 'Manager Supplier',
                                            'url_key' => '/supplier/account/index',
                                        ],
                                        'order_month_settle' => [
                                            'label' => 'Order Month Settle',
                                            'url_key' => '/sales/ordersettle/manager',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'sales' => [
                            'label' => 'Mall Sales',
                            'child' => [
                                'coupon' => [
                                    'active' => false,
                                ],
                                'order' => [
                                    'label' => 'Order Info',
                                    'child' => [
                                        'order_manager' => [
                                            'label' => 'All Order List',
                                            'url_key' => '/sales/orderinfo/manager',
                                        ],
                                        'order_return_list' => [
                                            'label' => 'Return List',
                                            'url_key' => '/sales/returnlist/manager',
                                        ],
                                        'admin_refund' => [
                                            'label' => 'Admin Refund',
                                            'url_key' => '/sales/refund/manager',
                                        ],
                                        
                                        'order_operate_log' => [
                                            'label' => 'Order Operate Log',
                                            'url_key' => '/sales/orderlog/manager',
                                        ],
                                    ],
                                ],
                                
                            ],
                        ],
                        /*
                        'cms' => [
                            'child' => [
                                'base_info' => [
                                    'label' => 'Base Info Config',
                                    'url_key' => '/cms/baseinfo/manager',
                                ],
                                'home_page' => [
                                    'label' => 'Home Page Config',
                                    'url_key' => '/cms/homepage/manager',
                                ],
                            ],
                        ],
                        */
                    ],
                    
                ],
            ],
        ],
        
    ],
];
