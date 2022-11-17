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
class LoginController extends \fecshop\app\appserver\modules\Customer\controllers\LoginController
{
    
    // 绑定账户
    public function actionBindaccount()
    {
        $wxCode = Yii::$app->request->post('code');
        //echo $wxCode;
        // 通过code 和 微信的一些验证信息，得到微信的信息uid
        $wxUserInfo = Yii::$service->helper->wx->getUserInfoByCode($wxCode);
        // 如果通过code获取微信信息（api获取）失败
        if (!$wxUserInfo) {
            // code  获取openid失败
            $code = Yii::$service->helper->appserver->account_wx_get_user_info_fail;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        // 得到 openid  和  session_key
        $wx_openid = $wxUserInfo['openid'];
        $wx_session_key = $wxUserInfo['session_key'];
        
        if (!$wx_openid || !$wx_session_key) {
            $code = Yii::$service->helper->appserver->no_account_openid_and_session_key;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        if (Yii::$service->customer->getByWxMicroOpenid($wx_openid)) {
            // 已经存在绑定的用户，绑定失败
            $code = Yii::$service->helper->appserver->account_has_account_openid;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $phone = Yii::$app->request->post('phone');
        $captcha = Yii::$app->request->post('captcha');
        // 验证码不正确
        if (!Yii::$service->sms->isCorrectRegisterCapchaCode($phone, $captcha)) {
            $code = Yii::$service->helper->appserver->account_register_captcha_fail;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $param = [
            'phone' => $phone,
            'openid' => $wx_openid,
            'session_key' => $wx_session_key,
        ];
        $access_token = Yii::$service->customer->phoneRegisterAndGetLoginAccessToken($param);
        if (!$access_token) {
            $code = Yii::$service->helper->appserver->account_register_fail;
            $data = [
                'errors' => Yii::$service->helper->errors->get(),
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $code = Yii::$service->helper->appserver->status_success;
        $data = [ ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
    }
    
     // 绑定账户
    public function actionBindaccount2()
    {   
        $wxCode = Yii::$app->request->post('code');
        //echo $wxCode;
        // 通过code 和 微信的一些验证信息，得到微信的信息uid
        $wxUserInfo = Yii::$service->helper->wx->getUserInfoByCode($wxCode);
        // 如果通过code获取微信信息（api获取）失败
        if (!$wxUserInfo) {
            // code  获取openid失败
            $code = Yii::$service->helper->appserver->account_wx_get_user_info_fail;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        // 得到 openid  和  session_key
        $wx_openid = $wxUserInfo['openid'];
        $wx_session_key = $wxUserInfo['session_key'];
        
        $encryptedData = Yii::$app->request->post('encryptedData');
        $iv = Yii::$app->request->post('iv');
        //$wx_session_key = Yii::$app->request->post('session_key');
        //$wx_openid = Yii::$app->request->post('openid');

        if (!$wx_openid || !$wx_session_key || !$encryptedData || !$iv) {
            $code = Yii::$service->helper->appserver->no_account_openid_and_session_key;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        if (Yii::$service->customer->getByWxMicroOpenid($wx_openid)) {
            // 已经存在绑定的用户，绑定失败
            $code = Yii::$service->helper->appserver->account_has_account_openid;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $resultData = $this->PhoneIpn($encryptedData,$iv,$wx_session_key);
        if (is_array($resultData)) {
            $phone = $resultData['phoneNumber'];
        } 

        if (!$phone) {
            $code = Yii::$service->helper->appserver->account_register_fail;
            $data = $resultData;//[ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }    

        $param = [
            'phone' => $phone,
            'openid' => $wx_openid,
            'session_key' => $wx_session_key,
        ];
        $access_token = Yii::$service->customer->phoneRegisterAndGetLoginAccessToken($param);
        if (!$access_token) {
            $code = Yii::$service->helper->appserver->account_register_fail;
            $data = [
                'errors' => Yii::$service->helper->errors->get(),
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $code = Yii::$service->helper->appserver->status_success;
        $data = [ ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
    }

    public function PhoneIpn($encryptedData,$iv,$sessionKey)
    {
        $wxBizDataCrypt = Yii::getAlias('@fecbbc/lib/wxapp/wxBizDataCrypt.php');
        require_once($wxBizDataCrypt);
        
        $pc = new \WXBizDataCrypt(Yii::$app->store->get('payment_wxpay', 'wechat_micro_app_id' ),$sessionKey);
        $decryptData = "";  //解密后的明文
        $errCode = $pc->decryptData($encryptedData, $iv, $decryptData);
        if ($errCode == 0) {
            return json_decode($decryptData,true);
        } else {
            return $errCode;
        }       
    }
}