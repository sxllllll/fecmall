<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    'refund' => [
        'class' => 'fecbbc\services\Refund',
        'childService' => [
            'orderCancel' => [
                'class' => 'fecbbc\services\refund\OrderCancel',
            ],
            'orderReturn' => [
                'class' => 'fecbbc\services\refund\OrderReturn',
            ],
        
        
        ]
    ],
];