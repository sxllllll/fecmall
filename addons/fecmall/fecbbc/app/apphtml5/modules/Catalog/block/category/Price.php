<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Catalog\block\category;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Price  extends \yii\base\BaseObject
{
    public $productModel;

    public function getLastData()
    {
        $price = $this->productModel['price'];
        $special_price = $this->productModel['special_price'];
        $special_from = $this->productModel['special_from'];
        $special_to = $this->productModel['special_to'];
        
        return  Yii::$service->product->price->getCurrentCurrencyProductPriceInfo($price, $special_price, $special_from, $special_to);
    }
}
