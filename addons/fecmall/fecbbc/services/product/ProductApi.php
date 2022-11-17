<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\services\product;

use fecshop\models\mongodb\Product;
use fecshop\services\Service;
use Yii;

/**
 * Product Categoryapi Service
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 * 由于产品部分的api导入操作比较复杂，因此单独一个services文件来处理产品导入。
 */
class ProductApi extends \fecshop\services\product\ProductApi
{
    
    public function insertByShopfwItem($product, $spu, $sku, $is_deputy)
    {
        $this->checkShopfwPostDataRequireAndInt($product);
        if (!empty($this->_error)) {
            
            return [
                'code'    => 400,
                'message' => '',
                'error'  => $this->_error,
            ];
        }
        $bdminUserId = '';
        if (!Yii::$app->user->isGuest) {
            $bdminUserId = Yii::$app->user->identity->id;
            $this->_param['bdmin_user_id'] = $bdminUserId;
        }
        
        $this->_param['is_deputy'] = $is_deputy;
        $this->_param['sku'] = $sku;
        $this->_param['spu'] = $spu;
        
        Yii::$service->product->addGroupAttrs($this->_param['attr_group']);
        $originUrlKey   = 'catalog/product/index';
        $saveData       = Yii::$service->product->save($this->_param, $originUrlKey);
        //$errors         = Yii::$service->helper->errors->get();
        //if (!$errors) {
        $saveData = $saveData->attributes;
        if (isset($saveData['_id'])) {
            $saveData['id'] = (string)$saveData['_id'];
            unset($saveData['_id']);
        }
        
        if ($saveData['name']) {
            $saveData['name'] = unserialize($saveData['name']);
        }
        if ($saveData['description']) {
            $saveData['description'] = unserialize($saveData['description']);
        }
        if ($saveData['image']) {
            $saveData['image'] = unserialize($saveData['image']);
        }
        if ($saveData['short_description']) {
            $saveData['short_description'] = unserialize($saveData['short_description']);
        }
        if ($saveData['meta_description']) {
            $saveData['meta_description'] = unserialize($saveData['meta_description']);
        }
        if ($saveData['meta_keywords']) {
            $saveData['meta_keywords'] = unserialize($saveData['meta_keywords']);
        }
        if ($saveData['attr_group_info']) {
            $saveData['attr_group_info'] = unserialize($saveData['attr_group_info']);
        }
        
        return $saveData;
    }
    
    
    
}
