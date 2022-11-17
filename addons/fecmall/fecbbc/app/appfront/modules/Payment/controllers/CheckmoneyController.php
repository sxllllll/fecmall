<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Payment\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CheckmoneyController extends AppfrontController
{
    public $enableCsrfValidation = true;

    /**
     * 支付开始页面.
     */
    public function actionStart()
    {
        $currentOrderInfos = Yii::$service->order->getCurrentOrderInfos();
        $payment_method = '';
        foreach ($currentOrderInfos as $currentOrderInfo) {
            $payment_method = $currentOrderInfo['payment_method'];
            break;
        }
        if ($payment_method) {
            $complateUrl = Yii::$service->payment->getStandardSuccessRedirectUrl($payment_method);
            if ($complateUrl) {
                // 清空购物车
                Yii::$service->cart->clearCartProductAndCoupon();
                Yii::$service->url->redirect($complateUrl);
                exit;
            }
        }

        $homeUrl = Yii::$service->url->homeUrl();
        Yii::$service->url->redirect($homeUrl);
    }

}
