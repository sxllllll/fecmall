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
use fecshop\services\Service;
use Yii;

/**
 * Product Service is the component that you can get product info from it.
 *
 * @property \fecshop\services\Image | \fecshop\services\Product\Image $image image service or product image sub-service
 * @property \fecshop\services\product\Info $info product info sub-service
 * @property \fecshop\services\product\Stock $stock stock sub-service of product service
 *
 * @method getByPrimaryKey($primaryKey) get product model by primary key
 * @see \fecshop\services\Product::actionGetByPrimaryKey()
 * @method getEnableStatus() get enable status
 * @see \fecshop\services\Product::actionGetEnableStatus()
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Sms extends Service
{
    // 【账户注册】将验证码存储到session的key
    //public $sessionStorageRegisterCapchaCodeKey = 'session_storage_phone_register_rand_number';
    // 【账户注册】将验证码存储到session的key
    //public $sessionStorageRegisterPhoneKey = 'session_storage_phone_register_number';
    // 【账户忘记密码】将验证码存储到session的key
    //public $sessionStorageForgotPasswordCapchaCodeKey = 'session_storage_phone_forgot_password_rand_number';
    // 【账户忘记密码】将验证码存储到session的key
    //public $sessionStorageForgotPasswordPhoneKey = 'session_storage_phone_forgot_password_number';
    
    // 【账户注册】将验证码存储到session的key
    public $sessionRegisterCaptchaKey = 'register';
    // 【账户忘记密码】将验证码存储到session的key
    public $sessionForgotPasswordCaptchaKey = 'forgot_password';
    
    // 验证码存储的最大超时时间（秒）
    public $sessionStorageCapchaCodeTimeout = 600;
    // 订单是否发送短信
    public $isOrderPaymentSendSms = false;
    public $orderPaymentSendSms = 1;
    public $orderPaymentNotSendSms = 2;
    /**
     * $storagePrex , $storage , $storagePath 为找到当前的storage而设置的配置参数
     * 可以在配置中更改，更改后，就会通过容器注入的方式修改相应的配置值
     */
    public $storage = 'Alisms'; //    当前的storage，如果在config中配置，那么在初始化的时候会被注入修改

    /**
     * 设置storage的path路径，
     * 如果不设置，则系统使用默认路径
     * 如果设置了路径，则使用自定义的路径
     */
    public $storagePath = '';

    /**
     * @var \fecshop\services\product\ProductInterface 根据 $storage 及 $storagePath 配置的 Product 的实现
     */
    protected $_sms;

    public function init()
    {
        parent::init();
        // 引入
        $currentService = $this->getStorageService($this);
        $this->_sms = new $currentService();
        
        $sessionStorageCapchaCodeTimeout = Yii::$app->store->get('base_fecphone', 'session_storage_capcha_code_timeout');
        if ($sessionStorageCapchaCodeTimeout) {
            $this->sessionStorageCapchaCodeTimeout = $sessionStorageCapchaCodeTimeout;
        }
        // 是否发送订单支付短信
        $order_payment_is_send_sms = Yii::$app->store->get('base_fecphone', 'order_payment_is_send_sms');
        $this->isOrderPaymentSendSms = ($order_payment_is_send_sms == $this->orderPaymentSendSms) ? true : false;
    }
    /**
     * @param $phoneNumber | string, 发送验证码的手机号
     * 【账户注册部分】
     * 通过该函数，发送帐号注册的手机验证码
     */
    public function sendRegisterCapchaCode($phoneNumber)
    {
        if (!Yii::$service->sms->checkPhoneFormat($phoneNumber)) {
            
            return false;
        }
        //if (Yii::$service->customer->isRegistered($phoneNumber)) {
        //    Yii::$service->helper->errors->add('phone account is have registered');
        //    
        // /   return false;
        //}
        
        return $this->_sms->sendRegisterCapchaCode($phoneNumber);
    }
    /**
     * 【账户注册部分】
     * 生成手机验证码4位数字, 并且保存到session里面
     * 手机号码和手机验证码会写入session, 以供后面核验
     */
    public function getSmsRegisterCapchaCode($phoneNumber){
        $randCode = $this->generateCapchaCode();
        
        Yii::$service->customer->smscode->setSmsCode($this->sessionRegisterCaptchaKey, $phoneNumber, $randCode);
        
        //Yii::$service->session->set($this->sessionStorageRegisterCapchaCodeKey, $randCode, $this->sessionStorageCapchaCodeTimeout);
        // 将手机号写入session
        //Yii::$service->session->set($this->sessionStorageRegisterPhoneKey, $phoneNumber, $this->sessionStorageCapchaCodeTimeout);
         
        return $randCode;
    }
    
    /**
     * @param $randNumber | string， 
     * 【账户注册部分】
     * 用户提交的验证码，进行验证，是否是正确的验证码（从session services中读取）
     * 必须进行手机号和session中的手机号验证，否则会出问题（用户先输入自己的手机号，获取验证码后，然后提交注册表单的时候改了个其他的手机号，就会出问题）
     */
    public function isCorrectRegisterCapchaCode($phoneNumber, $capchaCode) {
        
        return Yii::$service->customer->smscode->verifySmsCode($this->sessionRegisterCaptchaKey, $phoneNumber, $capchaCode, $this->sessionStorageCapchaCodeTimeout);
        
        
        /*
        $str = Yii::$service->session->get($this->sessionStorageRegisterCapchaCodeKey);
        if (!$str || $str != $capchaCode) {
            
            return false;
        }
        $phone = Yii::$service->session->get($this->sessionStorageRegisterPhoneKey);
        if (!$phone || $phone != $phoneNumber) {
            
            return false;
        }
        
        return true;
        */
    }
    
    
    
    
    /**
     * @param $phoneNumber | string, 发送验证码的手机号
     * 【忘记密码】
     * 通过该函数，发送忘记密码的手机验证码
     */
    public function sendForgotPasswordCapchaCode($phoneNumber)
    {
        if (!Yii::$service->sms->checkPhoneFormat($phoneNumber)) {
            
            return false;
        }
        if (!Yii::$service->customer->isRegistered($phoneNumber)) {
            Yii::$service->helper->errors->add('phone account is not registered');
            
            return false;
        }
        return $this->_sms->sendForgotPasswordCapchaCode($phoneNumber);
    }
    
    /**
     * 【忘记密码】
     * 生成手机验证码4位数字, 并且保存到session里面
     * 手机号码和手机验证码会写入session, 以供后面核验
     */
    public function getSmsForgotPasswordCapchaCode($phoneNumber){
        $randCode = $this->generateCapchaCode();
        Yii::$service->customer->smscode->setSmsCode($this->sessionForgotPasswordCaptchaKey, $phoneNumber, $randCode);
         
        //Yii::$service->session->set($this->sessionStorageForgotPasswordCapchaCodeKey, $randCode, $this->sessionStorageCapchaCodeTimeout);
        // 将手机号写入session
        //Yii::$service->session->set($this->sessionStorageForgotPasswordPhoneKey, $phoneNumber, $this->sessionStorageCapchaCodeTimeout);
         
        return $randCode;
    }
    
    /**
     * @param $randNumber | string， 
     * 【忘记密码】
     * 用户提交的验证码，进行验证，是否是正确的验证码（从session services中读取）
     * 必须进行手机号和session中的手机号验证，否则会出问题（用户先输入自己的手机号，获取验证码后，然后提交注册表单的时候改了个其他的手机号，就会出问题）
     */
    public function isCorrectForgotPasswordCapchaCode($phoneNumber, $capchaCode) {
        
        return Yii::$service->customer->smscode->verifySmsCode($this->sessionForgotPasswordCaptchaKey, $phoneNumber, $capchaCode, $this->sessionStorageCapchaCodeTimeout);
        /*
        $str = Yii::$service->session->get($this->sessionStorageForgotPasswordCapchaCodeKey);
        if (!$str || $str != $capchaCode) {
            
            return false;
        }
        $phone = Yii::$service->session->get($this->sessionStorageForgotPasswordPhoneKey);
        if (!$phone || $phone != $phoneNumber) {
            
            return false;
        }
        
        return true;
        */
    }
    
    /**
     * @param $phoneNumber | string, 发送验证码的手机号
     * @param $incrementId | string， 订单编号
     * 通过该函数，发送忘记密码的手机验证码
     */
    public function sendOrderPaymentInfo($phoneNumber, $incrementId)
    {
        \Yii::info('sendOrderPaymentInfo', 'fecshop_debug');
        if (!$this->isOrderPaymentSendSms) {
            // 如果订单发送短信的配置为false，则直接返回true, 不需要进行短信的发送。
            \Yii::info('！isOrderPaymentSendSms', 'fecshop_debug');
            return true;
        }
        \Yii::info('isOrderPaymentSendSms', 'fecshop_debug');
        if (!$phoneNumber || !$incrementId) {
            
            return false;
        }
        if (!Yii::$service->sms->checkPhoneFormat($phoneNumber)) {
            
            return false;
        }
        \Yii::info('sendOrderPaymentInfo', 'fecshop_debug');
        return $this->_sms->sendOrderPaymentInfo($phoneNumber, $incrementId);
    }
    
    /**
     * 生成手机验证码6位数字验证码
     */
    public function generateCapchaCode(){
        $randCode = mt_rand(100000,999999);
        
        return $randCode;
    }
    /**
     * @param $phoneNumber | string, 手机号
     * @return boolean
     * 使用正则表达式，验证手机号的格式
     */
    function checkPhoneFormat($phoneNumber){
        $check = '/^(1(([35789][0-9])|(47)))\d{8}$/';
        if (!preg_match($check, $phoneNumber)) {
            
            return false;
        } 
        
        return true;
    }
    
    
    
    
    
    
    
    
    
}
