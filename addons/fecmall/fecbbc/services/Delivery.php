<?php


namespace fecbbc\services;

use Yii;

class Delivery extends \fecshop\services\Service
{
    // 物流公司：https://market.aliyun.com/products/56928004/cmapi021863.html?spm=5176.730005.productlist.d_cmapi021863.ebSZX2#sku=yuncode1586300000
    // 得到所有的物流公司
    public function getCompanyArr()
    {
        $arr = [
            'SF' => '顺丰速运',
            'HTKY' => '百世快递',
            'EMS' => 'EMS',
            'HTKY' => '百世快递',
            'ZTO' => '中通快递',
            'STO' => '申通快递',
            'YD' => '韵达速递',
            'YTO' => '圆通速递',
            'HHTT' => '天天快递',
            'YZPY' => '邮政快递包裹',
            'DBL' => '德邦快递',
            'JD' => '京东快递',
            'UC' => '优速快递',
            'ZJS' => '宅急送',
            'TNT' => 'TNT快递',
            'UPS' => 'UPS',
            'DHL' => 'DHL',
            'FEDEX' => 'FEDEX联邦(国内件）',
            'FEDEX_GJ' => 'FEDEX联邦(国际件）',
        ];
        
        return $arr;
    }
    // Yii::$service->delivery->isCorrectCompanyCode($companyCode)
    public function isCorrectCompanyCode($companyCode)
    {
        $arr = $this->getCompanyArr();
        if (isset($arr[$companyCode])) {
            
            return true;
        }
        
        return false;
    }
    
    public function getShipperName($shipperCode)
    {
        $arr = Yii::$service->delivery->getCompanyArr();
        if (isset($arr[$shipperCode])) {
            return $arr[$shipperCode];
        }
        
        return '';
    }
}
