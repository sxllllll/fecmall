<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Customer\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AccountController extends \fecshop\app\appfront\modules\Customer\controllers\AccountController
{
    public $blockNamespace = 'fecbbc\app\appfront\modules\Customer\block';
    public function init()
    {
        parent::init();
        Yii::$service->page->theme->layoutFile = 'account.php';
    }
    
    /**
     * 账户中心.
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account/login');
        }
        Yii::$service->page->theme->layoutFile = 'customer.php';
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
    
   /**
     * 注册.
     */
    public function actionRegister()
    {
        if (Yii::$service->store->isAppServerMobile()) {
            $urlPath = 'customer/account/register';
            Yii::$service->store->redirectAppServerMobile($urlPath);
        }
        if (!Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account');
        }
        $param = Yii::$app->request->post('editForm');
        if (!empty($param) && is_array($param)) {
            $param = \Yii::$service->helper->htmlEncode($param);
            $registerStatus = $this->getBlock()->register($param);
            if ($registerStatus) {
                $appName = Yii::$service->helper->getAppName();
                $registerSuccessAutoLogin = Yii::$app->store->get($appName.'_account', 'registerSuccessAutoLogin');
                $registerSuccessRedirectUrlKey = Yii::$app->store->get($appName.'_account', 'registerSuccessRedirectUrlKey');
                if ($registerSuccessAutoLogin == Yii::$app->store->enable) {
                    Yii::$service->customer->login($param);
                }
                if (!Yii::$app->user->isGuest) {
                    // 注册成功后，跳转的页面，如果值为false， 则不跳转。
                    $urlKey = 'customer/account';
                    if ($registerSuccessRedirectUrlKey) {
                        $urlKey = $registerSuccessRedirectUrlKey;
                    }

                    return Yii::$service->customer->loginSuccessRedirect($urlKey);
                }
            }
        }
        $data = $this->getBlock()->getLastData($param);

        return $this->render($this->action->id, $data);
    }
    
    // 手机注册
    public function actionPhoneregister()
    {
        if (!Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account');
        }
        $param = Yii::$app->request->post('editForm');
        if (!empty($param) && is_array($param)) {
            $param = \Yii::$service->helper->htmlEncode($param);
            $status = $this->getBlock()->registerAndLogin($param);
            if ($status) {
                if (!Yii::$app->user->isGuest) {
                    // 注册成功后，跳转的页面，如果值为false， 则不跳转。
                    $urlKey = 'customer/account';
                    return Yii::$service->customer->loginSuccessRedirect($urlKey);
                }
            }
        }
        $data = $this->getBlock()->getLastData($param);

        return $this->render($this->action->id, $data);
    }
    
    /**
     * 【账户注册】获取手机验证码
     */
    public function actionPhoneregistercaptchacode()
    {
        $phoneNumber = Yii::$app->request->get('phone');
        if (!Yii::$service->sms->sendRegisterCapchaCode($phoneNumber)) {
            $errors = Yii::$service->helper->errors->get(',');
            echo json_encode([
                'status' => 'fail',
                'content' => $errors,
            ]);
            exit;
        }
        echo json_encode([
            'status' => 'success',
        ]);
        exit;
    }
    
    /**
     * 登录.
     */
    public function actionLogin()
    {
        if (Yii::$service->store->isAppServerMobile()) {
            $urlPath = 'customer/account/login';
            Yii::$service->store->redirectAppServerMobile($urlPath);
        }
        if (!Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account');
        }
        $rl = Yii::$app->request->get('rl');
        if ($rl) {
            $redirectUrl = base64_decode($rl);
            Yii::$service->customer->setLoginSuccessRedirectUrl($redirectUrl);
        }
        $param = Yii::$app->request->post('editForm');
        if (!empty($param) && is_array($param)) {
            $this->getBlock()->login($param);
            if (!Yii::$app->user->isGuest) {
                return Yii::$service->customer->loginSuccessRedirect('customer/account');
            }
        }
        $data = $this->getBlock()->getLastData($param);

        return $this->render($this->action->id, $data);
    }
    
    /**
     * 【忘记密码】获取手机验证码
     */
    public function actionForgotpasswordcaptchacode()
    {
        $phoneNumber = Yii::$app->request->get('phone');
        if (!Yii::$service->sms->sendForgotPasswordCapchaCode($phoneNumber)) {
            $errors = Yii::$service->helper->errors->get(',');
            echo json_encode([
                'status' => 'fail',
                'content' => $errors,
            ]);
            exit;
        }
        echo json_encode([
            'status' => 'success',
        ]);
        exit;
    }
    
    /**
     * 忘记密码？
     */
    public function actionForgotpassword()
    {
        if (!Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account');
        }
         $editForm = Yii::$app->request->post('editForm');
        if (!empty($editForm)) {
            $resetToken = $this->getBlock()->verifyCaptchaCodeAndGetPasswordResetToken($editForm);
            if ($resetToken) {
                $redirectUrl = Yii::$service->url->getUrl('customer/account/resetpassword',['resetToken' => $resetToken]);
                return Yii::$service->url->redirect($redirectUrl);
            }
            Yii::$service->page->message->addByHelperErrors();
        }
        
        $data = $this->getBlock()->getLastData();
        return $this->render($this->action->id, $data);
    }
    /**
     * 忘记密码 - 充值密码
     */
    public function actionResetpassword()
    {
        $editForm = Yii::$app->request->post('editForm');
        if (!empty($editForm)) {
            $resetStatus = $this->getBlock()->resetPassword($editForm);
            if ($resetStatus) {
                // 重置成功，跳转
                $resetSuccessUrl = Yii::$service->url->getUrl('customer/account/resetpasswordsuccess');
                return Yii::$service->url->redirect($resetSuccessUrl);
            }
        }
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
    /**
     * 忘记密码 - 充值密码成功
     */
    public function actionResetpasswordsuccess()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
    
    
    /**
     * 审核
     */
    public function actionBdmin()
    {
        
        $param = Yii::$app->request->post('editForm');
        if (!empty($param) && is_array($param)) {
            $param = \Yii::$service->helper->htmlEncode($param);
            $registerStatus = $this->getBlock()->bdminAudit($param);
            
        }
        
        $data = $this->getBlock()->getLastData($param);

        return $this->render($this->action->id, $data);
    }
}
