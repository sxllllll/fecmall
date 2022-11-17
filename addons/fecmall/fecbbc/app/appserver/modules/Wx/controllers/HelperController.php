<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appserver\modules\Wx\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0 
 */
class HelperController extends AppserverController
{
    /**
     * 产品图片生成海报
     */
    public function actionQrcodeproduct()
    {
        $product_id = Yii::$app->request->post('product_id');
        $wx_url = Yii::$app->request->post('page');
        if (!$product_id || !$wx_url) {
            
        }
        $code = Yii::$service->helper->appserver->status_success;
        $wxData = [
            'id' => $product_id,
            'type' => 'product',
            'wx_url' => $wx_url,
        ];
        $productModel = Yii::$service->product->getByPrimaryKey($product_id);
        
        $priceInfo = Yii::$service->product->price->getCurrentCurrencyProductPriceInfo($productModel['price'],$productModel['special_price'],$productModel['special_from'],$productModel['special_to']);
        
        
       // $priceInfo = Yii::$service->product->price->getCartPrice( $productModel['price'],$productModel['special_price'],$productModel['special_from'],$productModel['special_to'],1,0,[]);
        $productInfo = [
            'name' => Yii::$service->store->getStoreAttrVal($productModel['name'], 'name'),
            'price_info' => $priceInfo,
            'img_path' => Yii::$service->product->image->getResizeDir($productModel['image']['main']['image'], [400,250]),
            
        ];
        // var_dump($productInfo);
        $qrcodeimg = Yii::$service->wxmicro->shareqrcode->getWxMicroPosterImgUrl($wxData, $productInfo);
        $data = [
            'qrcodeimg' => $qrcodeimg,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    public function actionLiveroomlist()
    {
        
        $roomList = Yii::$service->wxmicro->liveplayer->getRoomList();
        if ($roomList) {
            
            $code = Yii::$service->helper->appserver->status_success;
            $data = [
                'roomList' => $roomList,
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
    }
    
    
    
    
    
    
    
    
    
    
    
}