<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\services\sms;

use Yii;
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendBatchSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
use fecshop\services\Service;

ini_set("display_errors", "yes");
Config::load();
/**
 * Url Services
 *
 * @property \fecshop\services\url\Category $category category sub-service of url
 * @property \fecshop\services\url\Rewrite $rewrite rewrite sub-service of url
 *
 * Url Service
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Alisms extends Service
{
    public $accessKeyId = "";
    public $accessKeySecret = "";
    public $signName = '';
    // 注册账户发送验证码，对应的alisms的模板code
    public $registerAccountCaptchaTemplateCode = '';
    // 账户忘记密码
    public $forgotPasswordCaptchaTemplateCode = '';
    // 订单支付
    public $orderPaymentTemplateCode = '';
    
    static $acsClient = null;
    
    public function init()
    {
        parent::init();
        $this->accessKeyId  = Yii::$app->store->get('base_fecphone', 'access_key_id');
        $this->accessKeySecret  = Yii::$app->store->get('base_fecphone', 'access_key_secret');
        $this->signName = Yii::$app->store->get('base_fecphone', 'sign_name');
        $this->registerAccountCaptchaTemplateCode = Yii::$app->store->get('base_fecphone', 'register_account_captcha_template_code');
        $this->forgotPasswordCaptchaTemplateCode = Yii::$app->store->get('base_fecphone', 'forgot_password_captcha_template_code');
        $this->orderPaymentTemplateCode = Yii::$app->store->get('base_fecphone', 'order_payment_template_code');
        
    }
    
    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public function getAcsClient() {
        if(static::$acsClient == null) {
            //产品名称:云通信短信服务API产品,开发者无需替换
            $product = "Dysmsapi";
            //产品域名,开发者无需替换
            $domain = "dysmsapi.aliyuncs.com";
            // 暂时不支持多Region
            $region = "cn-hangzhou";
            // 服务结点
            $endPointName = "cn-hangzhou";
            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $this->accessKeyId, $this->accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }
    
    /**
     * @param $phoneNumber | string, 发送验证码的手机号
     * 通过该函数，发送帐号注册的手机验证码
     */
    public function sendRegisterCapchaCode($phoneNumber){
        
        $signName = $this->signName;
        $capchaCode = Yii::$service->sms->getSmsRegisterCapchaCode($phoneNumber);
        $data = [
            'code' => $capchaCode,
        ];
        $this->sendSms($phoneNumber, $signName, $this->registerAccountCaptchaTemplateCode, $data);  
    
        return $capchaCode;
        
    }
    /**
     * @param $phoneNumber | string, 发送验证码的手机号
     * 通过该函数，发送帐号注册的手机验证码
     */
    public function sendForgotPasswordCapchaCode($phoneNumber){
        
        $signName = $this->signName;
        $capchaCode = Yii::$service->sms->getSmsForgotPasswordCapchaCode($phoneNumber);
        $data = [
            'code' => $capchaCode,
        ];
        $this->sendSms($phoneNumber, $signName, $this->forgotPasswordCaptchaTemplateCode, $data);  
    
        return $capchaCode;
        
    }
    /**
     * @param $phoneNumber | string, 发送验证码的手机号
     * 通过该函数，发送帐号注册的手机验证码
     */
    public function sendOrderPaymentInfo($phoneNumber, $incrementId){
        $signName = $this->signName;
        $data = [
            'increment_id' => $incrementId,
        ];
        \Yii::info('sendOrderPaymentInfo - phoneNumber:'.$phoneNumber, 'fecshop_debug');
        \Yii::info('sendOrderPaymentInfo - signName:'.$signName, 'fecshop_debug');
        \Yii::info('sendOrderPaymentInfo - orderPaymentTemplateCode:'.$this->orderPaymentTemplateCode, 'fecshop_debug');
        \Yii::info('sendOrderPaymentInfo - incrementId:'.$incrementId, 'fecshop_debug');
        $this->sendSms($phoneNumber, $signName, $this->orderPaymentTemplateCode, $data);  
    
        return true;
        
    }
    /**
     * @param $phoneNumber | string, 手机号字符串
     * @param $signName | string，签名
     * @param $templateCode | string， 阿里大鱼 短信模板编号，这个需要去阿里云设置
     * @param $data | array  ，格式：[  'code' => $capchaCode ]，这个里面的数组key:`code`，对应阿里大鱼短信模板里面的{code}
     * @param $outId | string, 选填
     * 进行单条短讯发送服务
     * @return stdClass
     */
    public function sendSms($phoneNumber, $signName, $templateCode, $data, $outId="yourOutId") {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($phoneNumber);
        
        // 必填，【"短信签名"】设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName($signName);

        // 必填，【SMS_0000001】设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($templateCode);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        /**
         *   [  // 短信模板中字段的值
         *    "code"=>"12345",
         *    "product"=>"dsd"
         *   ]
         */
        $request->setTemplateParam(json_encode($data, JSON_UNESCAPED_UNICODE));

        // 可选，设置流水号
        $request->setOutId($outId);

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        $request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $acsResponse = $this->getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }
    
    
    
    
    
    
    
    /**
     * 下面的函数 -- 暂时忽略
     *  例子保留
     */
    /*
    public function sendSuperUserPhone($phoneNumber){
        if (!$phoneNumber) {
            return false;
        }
        $signName = $this->signName;
        $templateCode = 'SMS_163439261';
        $yzm = Yii::$service->session->getMobileSuperRandNumber();
        $data = [
            'code' => $yzm,
        ];
        Yii::$service->sms->sendSms($phoneNumber, $signName, $templateCode, $data);  
    
        return $yzm;
    }
    
    public function sendUserOrderSms($phoneNumber){
        if (!$phoneNumber) {
            return false;
        }
        if (!Yii::$service->customer->isCorrectRolePhoneNumber($phoneNumber)) {
            return false;
        }
        $signName = $this->signName;
        $templateCode = 'SMS_163439233';
        $yzm = Yii::$service->session->getMobileRandNumber($phoneNumber);
        $data = [
            'code' => $yzm,
        ];
        Yii::$service->sms->sendSms($phoneNumber, $signName, $templateCode, $data);  
    
        return $yzm;
    }
    
    public function sendOrderSubscriberSms(
        $phoneNumber,
        $subscribe_person,
        $subscribe_address,
        $increment_id,
        $orderCreatedDate,
        $skuInfo
    ){
        if (!$phoneNumber) {
            return false;
        }
        $signName = $this->signName;
        $templateCode = 'SMS_163851876';
        //$yzm = Yii::$service->session->getMobileRandNumber();
        //${sku} 于 ${xdsj} 下单成功，订单号${ddh}，订购人姓名：${dgrxm}，手机：${dgrsj}，地址：${dgrdz}
        
        $data = [
            'sku' => mb_substr($skuInfo, 0,20) ,// 产品信息
            'sku2' => mb_substr($skuInfo, 20,20) ,
            'sku3' => mb_substr($skuInfo, 40,20) ,
            'sku4' => mb_substr($skuInfo, 60,20) ,
            'sku5' => mb_substr($skuInfo, 80,20) ,
            'sku6' => mb_substr($skuInfo, 100,20) ,
            'sku7' => mb_substr($skuInfo, 120,20) ,
            'sku8' => mb_substr($skuInfo, 140,20) ,
            'sku9' => mb_substr($skuInfo, 160,20) ,
            'sku10' => mb_substr($skuInfo, 180,20) ,
            'xdsj' => $orderCreatedDate,  // 下单时间
            'ddh' => (string)$increment_id,  // 订单号
            'dgrxm' => mb_substr($subscribe_person, 0,20),  // 订购人姓名
            'dgrsj' => $phoneNumber,  // 订购人手机
            'dgrdz' => mb_substr($subscribe_address, 0,20),  // 订购人地址
            'dgrdz2' => mb_substr($subscribe_address, 20,20),
            'dgrdz3' => mb_substr($subscribe_address, 40,20),
            'dgrdz4' => mb_substr($subscribe_address, 60,20),
            'dgrdz5' => mb_substr($subscribe_address, 80,20),
        ];
        //var_dump($data);
        $d = Yii::$service->sms->sendSms($phoneNumber, $signName, $templateCode, $data);  
        //var_dump($d);exit;
        return true;
    }
    */
    
    

    /**
     * 批量发送短信
     * @return stdClass
     */
    /*
    public function sendBatchSms() {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendBatchSmsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填:待发送手机号。支持JSON格式的批量调用，批量上限为100个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
        $request->setPhoneNumberJson(json_encode(array(
            "1500000000",
            "1500000001",
        ), JSON_UNESCAPED_UNICODE));

        // 必填:短信签名-支持不同的号码发送不同的短信签名
        $request->setSignNameJson(json_encode(array(
            "云通信",
            "云通信"
        ), JSON_UNESCAPED_UNICODE));

        // 必填:短信模板-可在短信控制台中找到
        $request->setTemplateCode("SMS_1000000");

        // 必填:模板中的变量替换JSON串,如模板内容为"亲爱的${name},您的验证码为${code}"时,此处的值为
        // 友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
        $request->setTemplateParamJson(json_encode(array(
            array(
                "name" => "Tom",
                "code" => "123",
            ),
            array(
                "name" => "Jack",
                "code" => "456",
            ),
        ), JSON_UNESCAPED_UNICODE));

        // 可选-上行短信扩展码(扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段)
        // $request->setSmsUpExtendCodeJson("[\"90997\",\"90998\"]");

        // 发起访问请求
        $acsResponse = $this->getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }
    */
    /**
     * 短信发送记录查询
     * @return stdClass
     */
    /*
    public function querySendDetails() {

        // 初始化QuerySendDetailsRequest实例用于设置短信查询的参数
        $request = new QuerySendDetailsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填，短信接收号码
        $request->setPhoneNumber("12345678901");

        // 必填，短信发送日期，格式Ymd，支持近30天记录查询
        $request->setSendDate("20170718");

        // 必填，分页大小
        $request->setPageSize(10);

        // 必填，当前页码
        $request->setCurrentPage(1);

        // 选填，短信发送流水号
        $request->setBizId("yourBizId");

        // 发起访问请求
        $acsResponse = $this->getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }
    */
    
}