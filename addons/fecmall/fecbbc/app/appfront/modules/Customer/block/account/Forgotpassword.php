<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Customer\block\account;

use fecshop\app\appfront\helper\mailer\Email;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Forgotpassword
{
    public function getLastData()
    {
        $this->breadcrumbs(Yii::$service->page->translate->__('Forgot Password'));
        return [];
    }
    
    // 面包屑导航
    protected function breadcrumbs($name)
    {
        if (Yii::$app->controller->module->params['forgot_password_breadcrumbs']) {
            Yii::$service->page->breadcrumbs->addItems(['name' => $name]);
        } else {
            Yii::$service->page->breadcrumbs->active = false;
        }
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
