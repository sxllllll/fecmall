<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\services\payment;

//use fecshop\models\mysqldb\IpnMessage;
use fecshop\services\Service;
use yii\base\InvalidConfigException;
use Yii;

/**
 * Payment wxpay services.
 * @author Alex Chang<1692576541@qq.com>
 * @since 1.0
 */
class WxpayJsApi extends \fecshop\services\payment\WxpayJsApi
{
    public $devide;

    public $configFile;

    public $subjectMaxLength = 30;

    public $tradeType;

    public $scanCodeBody = '微信支付';

    public $deviceInfo = 'WEB';

    public $expireTime = 600;

    protected $_order;
    
    public $_order_model_arr;
    
    // 允许更改的订单状态，不存在这里面的订单状态不允许修改
    protected $_allowChangOrderStatus;
    
    public function init()
    {
        ini_set('date.timezone', 'Asia/Shanghai');
        parent::init();
        $wxpayConfigFile = Yii::getAlias($this->configFile);
        if (!is_file($wxpayConfigFile)) {
            throw new InvalidConfigException('wxpay config file:['.$wxpayConfigFile.'] is not exist');
        }
        $appId = Yii::$app->store->get('payment_wxpay', 'wechat_service_app_id');
        $appSecret = Yii::$app->store->get('payment_wxpay', 'wechat_service_app_secret');
        $mchKey = Yii::$app->store->get('payment_wxpay', 'merchant_key');
        $mchId = Yii::$app->store->get('payment_wxpay', 'merchant_mch_id');
        define('WX_APP_ID', $appId);
        define('WX_APP_SECRET', $appSecret);
        define('WX_MCH_KEY', $mchKey);
        define('WX_MCH_ID', $mchId);
        require_once($wxpayConfigFile);
        
        $wxpayApiFile       = Yii::getAlias('@fecshop/lib/wxpay/lib/WxPay.Api.php');
        //$wxpayDataFile      = Yii::getAlias('@fecshop/lib/wxpay/lib/WxPay.Data.php');
        $wxpayNotifyFile    = Yii::getAlias('@fecshop/lib/wxpay/lib/WxPay.Notify.php');
        //$wxpayExceptionFile = Yii::getAlias('@fecshop/lib/wxpay/lib/WxPay.Exception.php');
        $wxpayJsApiPayPayFile = Yii::getAlias('@fecshop/lib/wxpay/example/WxPay.JsApiPay.php');
        //$wxpayNativePayFile = Yii::getAlias('@fecshop/lib/wxpay/example/WxPay.NativePay.php');
        $wxpayLogFile       = Yii::getAlias('@fecshop/lib/wxpay/example/log.php');
        
        require_once($wxpayApiFile);
        //require_once($wxpayDataFile);
        require_once($wxpayNotifyFile);
        //require_once($wxpayExceptionFile);
        require_once($wxpayJsApiPayPayFile);
        require_once($wxpayLogFile);
        //交易类型
        //JSAPI--公众号支付、NATIVE--原生扫码支付、APP--app支付，统一下单接口trade_type的传参可参考这里
        //MICROPAY--刷卡支付，刷卡支付有单独的支付接口，不调用统一下单接口
        $this->tradeType     = 'JSAPI';
        
        
        $this->_allowChangOrderStatus = [
            Yii::$service->order->payment_status_pending,
            Yii::$service->order->payment_status_processing,
        ];
    }

    /**
     * 接收IPN消息的url，接收微信支付的异步消息，进而更改订单状态。
     */
    public function ipn()
    {
        $notifyFile       = Yii::getAlias('@fecshop/services/payment/wxpay/notify.php');
        require_once($notifyFile);
        \Yii::info('begin ipn', 'fecshop_debug');
        $notify = new \PayNotifyCallBack();
        $notify->Handle(false);
    }

    /**
     * @param $data | Array 数据格式如下：
     *   array(18) {
     *       ["appid"]=> string(18) "wx426b3015555a46be"
     *       ["attach"]=>string(24) "微信支付测试产品"
     *       ["bank_type"]=>string(3) "CFT"
     *       ["cash_fee"]=>string(1) "1"
     *       ["device_info"]=>string(3) "WEB"
     *       ["fee_type"]=> string(3) "CNY"
     *       ["is_subscribe"]=>string(1) "N"
     *       ["mch_id"]=>string(10) "1900009851"
     *       ["nonce_str"]=> string(32) "e91xn1hwgyw9ox5zecdag1l86vrhi94l"
     *       ["openid"]=>string(28) "oHZx6uKw5nrwZmEfgIX8poeQIucw"
     *       ["out_trade_no"]=>string(10) "1100000953"
     *       ["result_code"]=>string(7) "SUCCESS"
     *       ["return_code"]=>string(7) "SUCCESS"
     *       ["sign"]=>string(32) "589AC2046E667584FF1967C3C091259A"
     *       ["time_end"]=>string(14) "20171106160124"
     *       ["total_fee"]=>string(1) "1"
     *       ["trade_type"]=>string(6) "NATIVE"
     *       ["transaction_id"]=>string(28) "4200000006201711062859872774"
     *   }
     *  在微信sdk验证数据安全性后，会执行该函数，用来验证订单的金额的正确性
     *  如果订单数据没有问题，则更改订单状态。
     */
    public function ipnUpdateOrder($data)
    {
        \Yii::info('ipn order process', 'fecshop_debug');
        $out_trade_no    = $data['out_trade_no'];
        $transaction_id = $data['transaction_id'];
        $total_fee      = $data['total_fee'];
        $fee_type       = $data['fee_type'];
        
        $orders = Yii::$service->order->getOrderModelsByTradeNo($out_trade_no);
        $this->_order_model_arr = $orders;
        if (!is_array($orders) || empty($orders)) {
            Yii::$service->helper->errors->add('order: {out_trade_no} is not exist.', ['out_trade_no' => $out_trade_no]);
            
            return false;
        }
        $order_payment_method = '';
        $grandTotal = 0;
        if (is_array($orders) || !empty($orders)) {
            foreach ($orders as $order) {
                $grandTotal += $order['grand_total'];
            }
        }
        $order_total_amount  = $grandTotal;
        //$order_total_amount = Yii::$service->page->currency->getCurrencyPrice($grandTotal, 'CNY');
        \Yii::info('check order totla amouont['.($order_total_amount * 100).' == '.$total_fee.']', 'fecshop_debug');
        if(bccomp($order_total_amount * 100, $total_fee) !== 0){
            return false;
        }
        \Yii::info('updateOrderInfo', 'fecshop_debug');
        // 更改订单状态
        if ($this->updateOrderInfo($out_trade_no, $transaction_id, false)) { //支付成功调用服务执行订单状态改变，清空购物车和发送邮件操作
            \Yii::info('updateOrderInfo Success', 'fecshop_debug');
            return true;
        }
    }
    
    public function getOpenidUrl($baseUrl)
    {
        $tools = new \JsApiPay();
        return $tools->GetOpenidUrl($baseUrl);
    }
    
    /**
     * @param $code | string  传递的微信code
     */
    public function getScanCodeStart($code = "")
    {
        // 根据订单得到json格式的微信支付参数。
        $trade_info = $this->getStartBizContentAndSetPaymentMethod();
        if (!$trade_info) {
            Yii::$service->helper->errors->add('generate wxpay bizContent error');
           
            return false;
        }
        
        //①、获取用户openid
        $tools = new \JsApiPay();
        if (!$code) {
            $openId = $tools->GetOpenid();
        } else {
            $openId = $tools->GetOpenidByCode($code);
        }
        //echo $openId;exit;
        
        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        //$notify_url = Yii::$service->url->getUrl("payment/wxpayjsapi/ipn");    ////获取支付配置中的返回ipn url
        $notify_url = Yii::$service->payment->getStandardIpnUrl(); 
        //$notify = new \NativePay();
        //$input  = new \WxPayUnifiedOrder();
        $input->SetBody($this->scanCodeBody);
        //$input->SetAttach("商店的额外的自定义数据");
        $input->SetAttach($trade_info['subject']);
        $input->SetDevice_info($this->deviceInfo);  // 设置设备号
        if ($trade_info['coupon_code']) {
            $input->SetGoods_tag($trade_info['coupon_code']); //设置商品标记，代金券或立减优惠功能的参数
        }
        $input->SetOut_trade_no($trade_info['out_trade_no']); // Fecshop 订单号
        $orderTotal = $trade_info['total_amount'] * 100; //微信支付的单位为分,所以要乘以100
        $input->SetTotal_fee($orderTotal);
        $input->SetTime_start(date("YmdHis"));
        
        $input->SetTime_expire($this->getShangHaiExpireTime($this->expireTime));
        $input->SetNotify_url($notify_url); //通知地址 改成自己接口通知的接口，要有公网域名,测试时直接行动此接口会产生日志
        $input->SetTrade_type($this->tradeType);
        $input->SetProduct_id($trade_info['product_ids']); //此为二维码中包含的商品ID
        $input->SetOpenid($openId);
        //var_dump($input);
        $order = \WxPayApi::unifiedOrder($input);
        //echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
        //$this->printf_info($order);
        $isJsonFormat = true;
        if ($code) {
            $isJsonFormat = false;
        }
        $jsApiParameters = $tools->GetJsApiParameters($order, $isJsonFormat);
        //var_dump($jsApiParameters);
        //获取共享收货地址js函数参数
        $editAddress = $tools->GetEditAddressParameters($isJsonFormat);
        //var_dump($editAddress);
        //echo '3333333333333333<br>';
        return [
            'jsApiParameters' => $jsApiParameters,
            'editAddress' => $editAddress,
            'total_amount' => $trade_info['total_amount'],
            'out_trade_no' => $trade_info['out_trade_no'],
        ];
        
    }
    
    //打印输出数组信息
    function printf_info($data)
    {
        foreach($data as $key=>$value){
            echo "<font color='#00ff55;'>$key</font> : $value <br/>";
        }
    }
    
    public function getShangHaiExpireTime($expire_time)
    {
        $timezone_out = date_default_timezone_get();
        date_default_timezone_set('Asia/Shanghai');
        $r_time = date("YmdHis", time() + $expire_time);
        date_default_timezone_set($timezone_out);
        
        return $r_time;
    }
    
    public function scanCodeCheckTradeIsSuccess($out_trade_no)
    {
        $result = Yii::$service->payment->wxpay->queryOrderByOut($out_trade_no);
        if (is_array($result) && !empty($result)) {
            $trade_state  = $result['trade_state']; //最终的交易状态，必须为SUCCESS才是交易成功
            $return_code  = $result['result_code'];
            $trade_type   = $result['trade_type']; //获取交易方式,这里使用的是扫码支付native
            $out_trade_no = $result['out_trade_no'];
            $total_amount = $result['total_fee'];
            $seller_id    = $result['mch_id'];
            $auth_app_id  = $result['appid'];
            $trade_no     = $result['transaction_id'];
            
            $checkOrderStatus = Yii::$service->payment->wxpay->checkOrder($trade_state, $return_code, $trade_type, $out_trade_no, $total_amount, $seller_id, $auth_app_id);
            if ($checkOrderStatus) {
                return $this->updateOrderInfo($out_trade_no, $trade_no);
            }
        }
    }
    
    /**
     * 通过微信接口查询交易信息
     * @param unknown $out_trade_no
     */
    public function queryOrderByOut($out_trade_no)
    {
        $input  = new \WxPayOrderQuery();
        $input->SetOut_trade_no($out_trade_no);
        $result = \WxPayApi::orderQuery($input);
        
        return $result;
    }
    
    /**
     * 把返回的支付参数方式改成数组以适应微信的api
     * 生成二维码图片会用到这个函数
     */
    protected function getStartBizContentAndSetPaymentMethod()
    {
        $currentOrderInfos = Yii::$service->order->getCurrentOrderInfos();
        if (!is_array($currentOrderInfos) || empty($currentOrderInfos)) {
            return null;
        }
        
        $out_trade_no = Yii::$service->order->getSessionTradeNo();
        $total_amount = 0;
        $subject = '';
        $subject_arr = [];
        $payment_method = '';
        $productIds = '';
        foreach ($currentOrderInfos as $currentOrderInfo) {
            if (!isset($currentOrderInfo['products']) || !is_array($currentOrderInfo['products'])) {
                return null;
            }
            // subject
            foreach ($currentOrderInfo['products'] as $product) {
                $subject_arr[] = $product['name'];
                $productIds = $product['product_id'];
            }
            
            // total_amount
            $total_amount += $currentOrderInfo['grand_total'];
            $payment_method = $currentOrderInfo['payment_method'];
        }
        if (!empty($subject_arr)) {
            $subject = implode(',', $subject_arr);
        }
        // 初始化当前的支付方式
        Yii::$service->payment->setPaymentMethod($payment_method);
        return [
            // param 参看：https://docs.open.alipay.com/common/105901
            'out_trade_no' => $out_trade_no,
            //'product_code' => $this->_productCode,
            'total_amount' => $total_amount,
            'subject'      => $subject,
            'coupon_code' => '',
            'product_ids'  => $productIds,
            
            //'body'         => '',
        ];
    }

    /**
     * 检查订单是否合法
     * 如果每项验证都通过则返回真
     */
    public function checkOrder($trade_state, $return_code, $trade_type, $out_trade_no, $total_amount, $seller_id, $auth_app_id)
    {
        if ($trade_state != 'SUCCESS') {
            Yii::$service->helper->errors->add('request trade_state is not equle to SUCCESS');
            return false;
        }
        if ($return_code != 'SUCCESS') {
            Yii::$service->helper->errors->add('request return_code is not equle to SUCCESS');
            return false;
        }
        $orders = Yii::$service->order->getOrderModelsByTradeNo($out_trade_no);
        $this->_order_model_arr = $orders;
        if (!is_array($orders) || empty($orders)) {
            Yii::$service->helper->errors->add('order: {out_trade_no} is not exist.', ['out_trade_no' => $out_trade_no]);
            
            return false;
        }
        $order_payment_method = '';
        $grandTotal = 0;
        if (is_array($orders) || !empty($orders)) {
            foreach ($orders as $order) {
                $grandTotal += $order['grand_total'];
                $order_payment_method = $order['payment_method'];
            }
        }
        // 初始化当前的支付方式
        Yii::$service->payment->setPaymentMethod($order_payment_method);
        
        $order_total_amount = $grandTotal;
        // $order_total_amount = Yii::$service->page->currency->getCurrencyPrice($grandTotal, 'CNY');
        if(bccomp($order_total_amount * 100, $total_amount) !== 0){
            Yii::$service->helper->errors->add('order increment id:{out_trade_no} , total_amount({total_amount}) is not equal to order_total_amount({order_total_amount})', ['out_trade_no'=>$out_trade_no , 'total_amount'=>$total_amount , 'order_total_amount'=>$order_total_amount ]);
            //return ['o' => $order_total_amount * 100, 't' => $total_amount]; //测试时便于观察订单金额和微信实际支付的金额，生产环境要注释掉
            return false;
        }
        /*
        if ((string)($order_total_amount * 100) != $total_amount) { //由于微信中是以分为单位所以必须乘以100，二维码页面也已经作了处理，单位都是分,$order_total_amount * 100要转为字符串再比较
            echo (string)($order_total_amount * 100);
            echo $total_amount;
            exit;
            Yii::$service->helper->errors->add('order increment id:{out_trade_no} , total_amount({total_amount}) is not equal to order_total_amount({order_total_amount})', ['out_trade_no'=>$out_trade_no , 'total_amount'=>$total_amount , 'order_total_amount'=>$order_total_amount ]);
            //return ['o' => $order_total_amount * 100, 't' => $total_amount]; //测试时便于观察订单金额和微信实际支付的金额，生产环境要注释掉
            return false;
        }
        */
    
        return true;
    }
    
    /**
     * 微信 支付成功后，对订单的状态进行修改
     * 如果支付成功，则修改订单状态为支付成功状态。
     * @param $out_trade_no | string ， fecshop的订单编号 increment_id
     * @param $trade_no | 微信支付交易号
     * @param isClearCart | boolean 是否清空购物车
     *
     */
    protected function updateOrderInfo($out_trade_no, $trade_no, $isClearCart=true)
    {
        if (!empty($out_trade_no) && !empty($trade_no)) {
            if ($this->paymentSuccess($out_trade_no, $trade_no)) {
                // 清空购物车
                if ($isClearCart) {
                    Yii::$service->cart->clearCartProductAndCoupon();
                }
                return true;
            }
        } else {
            Yii::$service->helper->errors->add('wxpay payment fail,resultCode: {result_code}', ['result_code' => $resultCode]);
            
            return false;
        }
    }

    /**
     * @param $out_trade_no | String 订单号
     * @param $sendEmail | boolean 是否发送邮件
     * 订单支付成功后，需要更改订单支付状态等一系列的处理。
     */
    protected function paymentSuccess($out_trade_no, $trade_no, $sendEmail = true)
    {
        //Yii::$service->store->currentLangCode = 'zh';
        // 得到订单
        if (!$this->_order_model_arr) {
            $this->_order_model_arr = Yii::$service->order->getOrderModelsByTradeNo($out_trade_no);
        }
        
        foreach ($this->_order_model_arr as $order_model) {
            // 【优化后的代码 ##】
            $orderstatus = Yii::$service->order->payment_status_confirmed;
            $updateArr['order_status']  = $orderstatus;
            $updateArr['txn_id']        = $trade_no; // 支付宝的交易号
            $updateColumn = $order_model->updateAll(
                $updateArr,
                [
                    'and',
                    ['order_id' => $order_model['order_id']],
                    ['in', 'order_status', $this->_allowChangOrderStatus],
                    ['order_operate_status' => Yii::$service->order->operate_status_normal],
                ]
            );
            if (!empty($updateColumn)) {
                // 发送邮件，以及其他的一些操作（订单支付成功后的操作）
                Yii::$service->order->orderPaymentCompleteEvent($order_model['increment_id']);
                // 订单操作日志
                $logType = Yii::$service->order->processLog->order_payment_confirm;
                Yii::$service->order->processLog->consoleAdd($order_model, $logType);
            }
        }
        
        return true;
    }
    
    // 支付宝的 标示
    public function getWxpayHandle()
    {
        return 'wxpay_standard';
    }
}
