<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    'wx' => [
        'class' => 'fecbbc\services\Wx', 
        'childService' => [
            'qrcode' => [
                'class' => 'fecbbc\services\wx\Qrcode',
            ],
            'h5share' => [
                'class' => 'fecbbc\services\wx\H5share',
            ],
            'h5login' => [
                'class' => 'fecbbc\services\wx\H5login',
            ],
            'custommenu' => [
                'class' => 'fecbbc\services\wx\Custommenu',
            ],
            'micro' => [
                'class' => 'fecbbc\services\wx\Micro',
            ],
        ],
    ],
];