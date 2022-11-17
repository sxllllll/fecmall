<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Payment\controllers;

use fecshop\app\apphtml5\modules\Payment\PaymentController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class WxpayjsapiController extends \fecshop\app\apphtml5\modules\Payment\controllers\WxpayjsapiController
{
    public $enableCsrfValidation = false;
    
    protected $_trade_no;
    protected $_order_models;
    
    public function initFunc()
    {
        $homeUrl = Yii::$service->url->homeUrl();
        $this->_trade_no = Yii::$service->order->getSessionTradeNo();
        if (!$this->_trade_no) {
            Yii::$service->url->redirect($homeUrl);
            exit;
        }

        $this->_order_models = Yii::$service->order->getOrderModelsByTradeNo($this->_trade_no);
        if (!is_array($this->_order_models) || empty($this->_order_models)) {
            Yii::$service->url->redirect($homeUrl);
            exit;
        }
    }
    
    /**
     * 支付开始页面.
     */
    public function actionStart()
    {
        $this->initFunc();
        //Yii::$service->page->theme->layoutFile = 'wxpay_jsapi.php';
        $data = Yii::$service->payment->wxpayJsApi->getScanCodeStart();
        $data['success_url'] = Yii::$service->payment->getStandardSuccessRedirectUrl();
        return $this->render($this->action->id, $data);
        
    }
    
    /**
     * IPN消息推送地址
     * IPN过来后，不清除session中的 increment_id ，也不清除购物车
     * 仅仅是更改订单支付状态。
     */
    public function actionIpn()
    {
        Yii::$service->payment->wxpay->ipn();
    }

}
