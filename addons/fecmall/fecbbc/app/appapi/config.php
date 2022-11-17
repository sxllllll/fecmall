<?php
/**
 * FecMall file.
 * doc:https://packagist.org/packages/hightman/xunsearch.
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    'params' => [],
    'modules' => [
        'v2' => [
            'class' => '\fecbbc\app\appapi\modules\V2\Module',
            'params'=> [

            ],
        ],
        'v1' => [
            'controllerMap'=> [
                'account' => 'fecbbc\app\appapi\modules\V1\controllers\AccountController',       
            ],
        ],
    ],
    'components' => [
        'user' => [
            // 【默认】不开启速度限制的 User Model
            'identityClass' => 'fecbbc\models\mysqldb\BdminUser',
            // 开启速度限制的 User Model
            //'identityClass' => 'fecshop\models\mysqldb\adminUser\AdminUserAccessToken',
            
            //'enableAutoLogin' => true,
            // 关闭session
            'enableSession'     => false,
        ],
    ],
];
