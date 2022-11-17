<?php
/**
 * Fecmall Addons Config File
 */

// set namespace alisa
Yii::setAlias('@fecbbc', dirname(dirname(dirname(__DIR__))).'/addons/fecmall/fecbbc/');
// 加入阿里云通信sdk
Yii::setAlias('@Aliyun', dirname(dirname(dirname(__DIR__))).'/addons/fecmall/fecbbc/lib/aliyun-dysms-php-sdk/api_sdk/lib');

// common
$commonServices = [];
foreach (glob(__DIR__ . '/config/services/*.php') as $filename) {
    $commonServices = array_merge($commonServices, require($filename));
}
$commonComponents = [];
foreach (glob(__DIR__ . '/config/components/*.php') as $filename) {
    $commonComponents = array_merge($commonComponents, require($filename));
}
// appfront
$appfrontConfig = require(__DIR__ . '/app/appfront/config.php');
// apphtml5
$apphtml5Config = require(__DIR__ . '/app/apphtml5/config.php');
// appserver
$appserverConfig = require(__DIR__ . '/app/appserver/config.php');
// appapi
$appapiConfig = require(__DIR__ . '/app/appapi/config.php');
// appbdmin 
$appbdminConfig = require(__DIR__ . '/app/appbdmin/config.php');
// appadmin
$appadminConfig  = require(__DIR__ . '/app/appadmin/config.php');
// console
$consoleConfig = require(__DIR__ . '/app/console/config.php');

return [
    // 插件信息
    'info'  => [
        'name' => 'fecbbc',
        'author' => 'Fecmall'
    ],
    // 插件管理部分
    'administer' => [
        'install' => [
            'class' => 'fecbbc\administer\Install',
            // 其他引入的属性，类似yii2组件的方式写入即可
            'test' => 'test_data',
        ],
        'upgrade' => [
            'class' => 'fecbbc\administer\Upgrade',
        ],
        'uninstall' => [
            'class' => 'fecbbc\administer\Uninstall',
        ],
    ], 
    // 各个入口的配置
    'app' => [
        // 公共层部分配置
        'common' => [
            'enable' => true,
            // 公用层的具体配置下载下面
            'config' => [
                'components' => $commonComponents,
                'services' => $commonServices,
                // 重写model和block
                'fecRewriteMap' => [
                    '\fecshop\models\mysqldb\Product'  => '\fecbbc\models\mysqldb\Product',
                    '\fecshop\models\mysqldb\customer\CustomerRegister'  => '\fecbbc\models\mysqldb\customer\CustomerRegister',
                    '\fecshop\models\mysqldb\customer\CustomerLogin'  => '\fecbbc\models\mysqldb\customer\CustomerLogin',
                    '\fecshop\models\mysqldb\Customer'  => '\fecbbc\models\mysqldb\Customer',
                    // '\fecshop\app\appfront\modules\Customer\block\address\Edit'  => '\fectb\app\appfront\modules\Customer\block\address\Edit',
                ],
            ]
        ],
        // 1.appfront层
        'appfront' => [
            // appfront入口的开关，如果false，则会失效
            'enable' => true,
            'config' => $appfrontConfig,
        ],
        // html5入口
        'apphtml5' =>[
            // apphtml5入口的开关，如果false，则会失效
            'enable' => true,
            'config' => $apphtml5Config,
        ],
        // appserver入口（vue 微信小程序等api）
        'appserver' =>[
            // appserver入口的开关，如果false，则会失效
            'enable' => true,
            'config' => $appserverConfig,
        ],
        // appapi入口，和第三方交互的api
        'appapi' =>[
            'enable' => true,
            'config' => $appapiConfig,
        ],
        'appbdmin' => [
            'enable' => true,
            'config' => $appbdminConfig,
        ],
        // 后台部分
        'appadmin' =>[
            'enable' => true,
            'config' => $appadminConfig,
        ],
        // console，命令行脚本端
        'console' =>[
            'enable' => true,
            'config' => $consoleConfig,
        ],
    ],
    
    
];

