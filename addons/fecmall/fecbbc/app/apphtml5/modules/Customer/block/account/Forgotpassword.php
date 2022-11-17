<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Customer\block\account;

use fecshop\app\apphtml5\helper\mailer\Email;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Forgotpassword
{
    public function getLastData()
    {
        return [];
    }
    
    public function verifyCaptchaCodeAndGetPasswordResetToken($editForm)
    {
        $phone = $editForm['phone'];
        $captcha = $editForm['captcha'];
        
        if (!$phone) {
            Yii::$service->helper->errors->add('customer phone is empty');
            
            return false;
        }
        // 验证手机号码是否正确
        if (!Yii::$service->sms->isCorrectForgotPasswordCapchaCode($phone, $captcha)) {
            Yii::$service->helper->errors->add('captcha is not correct');
            
            return false;
        }
        // 判断手机号是否存在
        if (!Yii::$service->customer->isRegistered($phone)) {
            Yii::$service->helper->errors->add('phone account is not registered');
            
            return false;
        }
        $password_reset_token = Yii::$service->customer->generatePasswordResetToken($phone);
        if (!$password_reset_token) {
            Yii::$service->helper->errors->add('generate password reset token fail');
            
            return false;
        }
        
        return $password_reset_token;
    }
    
}
