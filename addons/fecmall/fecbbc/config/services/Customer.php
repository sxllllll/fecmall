<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    'customer' => [
        'class' => 'fecbbc\services\Customer',
        // 子服务
        'childService' => [
            'address' => [
                'class' => 'fecbbc\services\customer\Address',
            ],
            'wxqrcodelog' => [
                'class' => 'fecbbc\services\customer\Wxqrcodelog',
            ],
            'smscode' => [
                'class' => 'fecbbc\services\customer\Smscode',
            ],
        ],
    ],
];
