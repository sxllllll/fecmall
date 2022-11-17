<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    'statistics' => [
        'class' => 'fecbbc\services\Statistics',
        'childService' => [
            'order' => [
                'class' => 'fecbbc\services\statistics\Order',
            ],
            'bdminMonth' => [
                'class' => 'fecbbc\services\statistics\BdminMonth',
            ],
        ],
    ],
];