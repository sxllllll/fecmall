<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Customer\block\order;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Returnstatus
{
    public function getLastData($afterSaleOne)
    {
        
        $companySelectOptions = $this->getCompanySelectOptions($afterSaleOne['tracking_company']);
        $data = [
            'after_sale'=> [
                'id' => $afterSaleOne['id'],
                'product_id' => $afterSaleOne['product_id'],
                'image' => $afterSaleOne['image'],
                'status' => Yii::$service->page->translate->__($afterSaleOne['status']),
                'can_cancel' => Yii::$service->order->info->isCustomerCanCancelAfterSaleReturndOrder($afterSaleOne),
                'sku' => $afterSaleOne['sku'],
                'custom_option_info' => $afterSaleOne['custom_option_info'],
                'base_price' => $afterSaleOne['base_price'],
                'price' => $afterSaleOne['price'],
                'qty' => $afterSaleOne['qty'],
                'currency_symbol' => Yii::$service->page->currency->getSymbol($afterSaleOne['currency_code']),
                'tracking_number' => $afterSaleOne['tracking_number'],
                'tracking_company' => $afterSaleOne['tracking_company'],
                'show_dispatch' => Yii::$service->order->info->isCustomerCanDispatchAfterSaleReturndOrder($afterSaleOne),
                'show_shipping' => Yii::$service->order->info->isCustomerCanShowAfterSaleReturndShipping($afterSaleOne),
                
            ],
            'companySelectOptions' => $companySelectOptions,
        ];
        
        return $data;
    }
    
    public function getCompanySelectOptions($trackingCompany)
    {
        $shippingCompany = Yii::$service->delivery->getCompanyArr();
        $str = '';
        if (!is_array($shippingCompany) && empty($shippingCompany)) {
            return '';
        }
        foreach ($shippingCompany as $code=>$label) {
            if ($code == $trackingCompany) {
                $str .= '<option  selected value="'.$code.'">'.$label.'</option>';
            } else {
                $str .= '<option value="'.$code.'">'.$label.'</option>';
            }
        }
        
        return $str;
    }
}
