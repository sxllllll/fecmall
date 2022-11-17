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
class Phoneregister
{
    public function getLastData($param)
    {
        $phone = isset($param['phone']) ? $param['phone'] : '';
        $appName = Yii::$service->helper->getAppName();
        $registerPageCaptcha = Yii::$app->store->get($appName.'_account', 'registerPageCaptcha');
        $openid = Yii::$app->request->get('openid');
        return [
            'phone'            => $phone,
            'openid'            => $openid,
            'minPassLength' => Yii::$service->customer->getRegisterPassMinLength(),
            'maxPassLength' => Yii::$service->customer->getRegisterPassMaxLength(),
            'registerPageCaptcha' => ($registerPageCaptcha == Yii::$app->store->enable ? true : false),
        ];
    }

    public function registerAndLogin($param) 
    {
        $captcha = $param['captcha'];
        $phone = $param['phone'];
        $openid = $param['openid'];
        $appName = Yii::$service->helper->getAppName();
        $registerPageCaptcha = Yii::$app->store->get($appName.'_account', 'registerPageCaptcha');
        
        //$registerParam = \Yii::$app->getModule('customer')->params['register'];
        //$registerPageCaptcha = isset($registerParam['registerPageCaptcha']) ? $registerParam['registerPageCaptcha'] : false;
        // 如果开启了验证码，但是验证码验证不正确就报错返回。
        if (($registerPageCaptcha == Yii::$app->store->enable)  && !$captcha) {
            Yii::$service->page->message->addError(['Captcha can not empty']);

            return;
        } elseif ($captcha && $registerPageCaptcha && !\Yii::$service->sms->isCorrectRegisterCapchaCode($phone, $captcha)) {
            Yii::$service->page->message->addError(['Captcha is not right']);

            return;
        }
        if (!$openid) {
            Yii::$service->page->message->addError(['openid can not empty']);
            
            return;
        }
        $param['openid'] = $openid;
        $param['app_name'] = 'apphtml5';
        Yii::$service->customer->phoneRegisterAndLogin($param);
        
        $errors = Yii::$service->page->message->addByHelperErrors();
        if (!$errors) {
            
            return true;
        }
    }

}
