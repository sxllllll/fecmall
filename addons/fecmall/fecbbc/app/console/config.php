<?php
/**
 * FecMall file.
 * doc:https://packagist.org/packages/hightman/xunsearch.
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    'modules' => [
        'order' => [
            'controllerMap' => [
                'process' => 'fecbbc\app\console\modules\Order\controllers\ProcessController',  
            ],
        ],
        'statistics' => [
            'class' => '\fecbbc\app\console\modules\Statistics\Module',
        ],
        'product' => [
            'controllerMap' => [
                'fectbgoods' => 'fecbbc\app\console\modules\Product\controllers\FectbgoodsController',  
            ],
        ],
    ],
    'params' => [
        
    ],
];
