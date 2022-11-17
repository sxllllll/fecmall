<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Payment\controllers;

use Yii;
use fecshop\app\apphtml5\modules\AppfrontController;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Wxpayh5Controller extends \fecshop\app\apphtml5\modules\Payment\controllers\Wxpayh5Controller
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
        $objectxml = Yii::$service->payment->wxpayH5->getScanCodeStart();
        if (!$objectxml['mweb_url']) {
            return Yii::$service->url->redirectByUrlKey('customer/order');
        }
        $returnUrl =  Yii::$service->payment->getStandardReturnUrl(); 
        $return_Url = urlencode($returnUrl);
        $url = $objectxml['mweb_url'] . '&redirect_url=' . $return_Url;
        
        return Yii::$service->url->redirect($url);
        
    }
    
    public function actionReview()
    {
        $isPayed = Yii::$app->request->get('isPayed');
        if (!$isPayed) {
            return $this->render($this->action->id, ['wainting' => 'wainting']);
        }
        $this->initFunc();
        $out_trade_no = Yii::$service->order->getSessionTradeNo();
        //$out_trade_no = Yii::$service->order->getSessionIncrementId();
        $reviewStatus = Yii::$service->payment->wxpay->scanCodeCheckTradeIsSuccess($out_trade_no);
        if($reviewStatus){
            $successRedirectUrl = Yii::$service->payment->getStandardSuccessRedirectUrl();
            return Yii::$service->url->redirect($successRedirectUrl);
        }else{
            $errors = Yii::$service->helper->errors->get('<br/>');
            $data = [
                'errors' => $errors,
            ];
            return $this->render($this->action->id, $data);
        }
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
