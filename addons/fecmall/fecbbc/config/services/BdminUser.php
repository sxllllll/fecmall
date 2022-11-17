<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    'bdminUser' => [
        'class' => 'fecbbc\services\BdminUser',
        'childService' => [
            'bdminUser' => [
                'class' => 'fecbbc\services\bdminUser\BdminUser',
            ],
            'userLogin' => [
                'class' => 'fecbbc\services\bdminUser\UserLogin',
            ],
            'shipping' => [
                'class' => 'fecbbc\services\bdminUser\Shipping',
            ],
        ],
    ],
];
