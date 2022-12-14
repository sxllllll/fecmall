<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Payment\block\paypal\standard;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Start extends \fecshop\app\apphtml5\modules\Payment\block\paypal\standard\Start
{
    
    public function startPayment()
    {
        $methodName_ = 'SetExpressCheckout';
        $nvpStr_ = Yii::$service->payment->paypal->getStandardTokenNvpStr();
        //echo $nvpStr_;exit;
        // 通过接口，得到token信息
        $checkoutReturn = Yii::$service->payment->paypal->PPHttpPost5($methodName_, $nvpStr_);
        //var_dump($checkoutReturn);exit;
        if (strtolower($checkoutReturn['ACK']) == 'success') {
            $token = $checkoutReturn['TOKEN'];
            $trade_no = Yii::$service->order->getSessionTradeNo();
            //echo $increment_id ;exit;
            # 将token写入到订单中
            //var_dump([$trade_no,$token]); exit;
            Yii::$service->order->updateTokenByTradeNo($trade_no,$token);
            $redirectUrl = Yii::$service->payment->paypal->getStandardCheckoutUrl($token);
            Yii::$service->url->redirect($redirectUrl);
            return;
        } elseif (strtolower($checkoutReturn['ACK']) == 'failure') {
            echo $checkoutReturn['L_LONGMESSAGE0'];
        } else {
            var_dump($checkoutReturn);
        }
    }
}
