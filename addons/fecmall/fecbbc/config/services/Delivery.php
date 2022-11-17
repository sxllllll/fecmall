<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    'delivery' => [
        'class' => 'fecbbc\services\Delivery',
        'childService' => [
            'kdiniao' => [
                'class' => 'fecbbc\services\delivery\Kdiniao',
            ],
        ],
    ],
];
