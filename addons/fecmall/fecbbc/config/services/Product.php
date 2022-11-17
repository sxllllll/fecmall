<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    'product' => [
        'class' => 'fecbbc\services\Product',
        'storagePath' => 'fecbbc\\services\\product',
        'productSpuShowOnlyOneSku' => false,
        'childService' => [
            'review' => [
                'class' => 'fecbbc\services\product\Review',
            ],
            'favorite' => [
                'class' => 'fecbbc\services\product\Favorite',
            ],
            'stock' => [
                'class' => 'fecbbc\services\product\Stock',
            ],
           
            'productapi' => [
                'class' => 'fecbbc\services\product\ProductApi',
            ],
        ],
    ],
];
