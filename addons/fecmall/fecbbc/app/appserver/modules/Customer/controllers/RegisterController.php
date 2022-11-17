<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appserver\modules\Customer\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;
use \Firebase\JWT\JWT;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class RegisterController extends \fecshop\app\appserver\modules\Customer\controllers\RegisterController
{
    /**
     * 【账户注册】获取手机验证码
     */
    public function actionPhoneregistercaptchacode()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $phoneNumber = Yii::$app->request->post('phone');
        if (!Yii::$service->sms->sendRegisterCapchaCode($phoneNumber)) {
            $errors = Yii::$service->helper->errors->get(',');
            
            $code = Yii::$service->helper->appserver->account_phone_send_sms_fail;
            $data = [ 'errors' => $errors ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    
    
}