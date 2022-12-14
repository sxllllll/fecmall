<?php
/**
 * FecMall file.
 * doc:https://packagist.org/packages/hightman/xunsearch.
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
return [
    'xunsearch' => [
        'class' => 'hightman\xunsearch\Connection', // 此行必须
        'iniDirectory' => '@fecbbc/config/xunsearch',    // 搜索 ini 文件目录，默认：@vendor/hightman/xunsearch/app
        'charset' => 'utf-8',   // 指定项目使用的默认编码，默认即时 utf-8，可不指定
    ],
];
