<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\services;

use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use Yii;

/**
 * Product Service is the component that you can get product info from it.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Product extends \fecshop\services\Product
{
    
    /**
     * @param productId string
     * @param $bdmin_user_id int  商户id
     * @return boolean
     */
    public function isBdminHasEditRole($productId, $bdmin_user_id)
    {
        return $this->_product->isBdminHasEditRole($productId, $bdmin_user_id);
    }
    
    public function tbSave($editSpu, $one, $spuArr)
    {
        return $this->_product->tbSave($editSpu, $one, $spuArr);
    }
    public function getByEditSpu($editSpu)
    {
        return $this->_product->getByEditSpu($editSpu);
    }
    //
    public function getSpuSelectSpuAttrVal($spu, $spuAttrs, $spuImgAttr)
    {
        return $this->_product->getSpuSelectSpuAttrVal($spu, $spuAttrs, $spuImgAttr);
    }
    
    public function isProductSpuHasDeputy($spu)
    {
        
        return $this->_product->isProductSpuHasDeputy($spu);
    }

    public function initProductDeputy($productModel)
    {
        
        return $this->_product->initProductDeputy($productModel);
    }
    public function bulkenable($productIds)
    {
        
        return $this->_product->bulkenable($productIds);
    }
    public function bulkdisable($productIds)
    {
        
        return $this->_product->bulkdisable($productIds);
    }
    public function removeByProductIdsWithSameSpu($productIds)
    {
        
        return $this->_product->removeByProductIdsWithSameSpu($productIds);
    }
}