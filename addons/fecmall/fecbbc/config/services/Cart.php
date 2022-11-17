<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    'cart' => [
        'class' => 'fecbbc\services\Cart',
        // 子服务
        'childService' => [
            'info' => [
                'class' => 'fecbbc\services\cart\Info',
            ],
            'quote' => [
                'class' => 'fecbbc\services\cart\Quote',
            ],
            'quoteItem' => [
                'class' => 'fecbbc\services\cart\QuoteItem',
            ],
        ],
    ],
];
