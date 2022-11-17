<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    'order' => [
        'class' => 'fecbbc\services\Order',
        'childService' => [
            'item' => [
                'class' => 'fecbbc\services\order\Item',
            ],
            'info' => [
                'class' => 'fecbbc\services\order\Info',
            ],
            'process' => [
                'class' => 'fecbbc\services\order\Process',
            ],
            'afterSale' => [
                'class' => 'fecbbc\services\order\AfterSale',
            ],
            'processLog' => [
                'class' => 'fecbbc\services\order\ProcessLog',
            ],
        ],
    ],
];