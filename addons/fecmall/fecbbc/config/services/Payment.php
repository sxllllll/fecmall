<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    'payment' => [
        'class' => 'fecbbc\services\Payment',
        'childService' => [
            'paypal' => [
                'class'    => 'fecbbc\services\payment\Paypal',
            ],
            'alipay' => [
                'class'         => 'fecbbc\services\payment\Alipay',
            ],
            'wxpay' => [ //注意参数要与WxPay.Config中的一致
        		'class'         => 'fecbbc\services\payment\Wxpay', 
            ],
            'wxpayH5' => [ //注意参数要与WxPay.Config中的一致
        		'class'         => 'fecbbc\services\payment\WxpayH5', 
            ],
            'wxpayJsApi' => [ //注意参数要与WxPay.Config中的一致
        		'class'         => 'fecbbc\services\payment\WxpayJsApi', 
            ],
            'wxpayMicro' => [ //注意参数要与WxPay.Config中的一致
        		'class'         => 'fecbbc\services\payment\WxpayMicro', 
            ],
        ],
    ],
];
