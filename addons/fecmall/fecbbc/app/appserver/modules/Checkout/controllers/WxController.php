<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appserver\modules\Checkout\controllers;

use fecshop\app\appserver\modules\Checkout\controllers\WxController  as FecWxController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class WxController extends FecWxController
{
    public $enableCsrfValidation = false;

    // 得到customer address
    public function actionVerifyinfo()
    {
        $orderIncrementId = Yii::$app->request->post('orderId');
        if (!$orderIncrementId) {
            $data = [];
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        //Yii::$service->order->setCurrentOrderIncrementId($orderIncrementId);
        // 设置当前的交易号
        Yii::$service->order->setSessionTradeNo($orderIncrementId);
        $info = Yii::$service->payment->wxpayMicro->getScanCodeStart();
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = $info;
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
