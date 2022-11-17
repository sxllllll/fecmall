<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appserver\modules\Customer\controllers;

use AliyunMNS\Requests\PeekMessageRequest;
use fecbbc\models\mysqldb\Customer;
use fecbbc\services\bdminUser\BdminUser;
use fecshop\app\appserver\modules\AppserverTokenController;
use fecbbc\models\mysqldb\BdminUser as DbminModel;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AccountController extends AppserverTokenController
{
    
    public function actionIndex(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        if (Yii::$app->user->isGuest) {
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        //$leftMenu = $this->getLeftMenu();
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            //'menuList' => $leftMenu,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }

    /**
     * 上传文件
     */
    public function actionUpload(){
        if( empty( $_FILES ) ) {
            return null;
        }
        $up = Yii::$service->image->saveUploadImg( $_FILES["file"] );
        if( count($up) > 1 ) {
            return $up[1];
        }
        return null;
    }

    /**
     * 申请成为商家
     */
    public function actionApplySeller(){
        $param = Yii::$app->getRequest()->post();

        $header = Yii::$app->request->getHeaders();
        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        if (!( isset($header['access-token']) && $header['access-token'] )) {
            $code = Yii::$service->helper->appserver->status_invalid_token; // 无效数据：token无效
            return Yii::$service->helper->appserver->getResponseData($code, $data);
        }
        $identity = Customer::findIdentityByAccessToken( $header['access-token'] );
        if( empty ( $identity ) || empty( $identity["email"] ) ) {
            $code = Yii::$service->helper->appserver->customer_apply_seller_fail;
            return Yii::$service->helper->appserver->getResponseData($code, $data);
        }
        $model=  new DbminModel;
        // 是否已申请商家
        $seller = $model->findById( $identity["id"] );

        if( empty( $seller ) || $seller["is_audit"] == 3 ) {  // 未申请或已拒绝申请 可提交
            $param["audit_at"] = time(); // 审核时间
            $param["is_audit"] = 1; // 申请中
            $param["cid"] = $identity["id"]; // 用户id
            $param["tax_point"] = 1;
            $param["email"] = $identity["email"];
            $model->setAttributes($param,false) ;
            $save = $model->saveData( $seller["id"] ?? 0  );
            if( !$save ) {
                $code = Yii::$service->helper->appserver->customer_apply_seller_fail;
                return Yii::$service->helper->appserver->getResponseData($code, $data);
            }
            return Yii::$service->helper->appserver->getResponseData($code, $data);
        }

        $code = Yii::$service->helper->appserver->customer_reapply_seller;
        return Yii::$service->helper->appserver->getResponseData($code, $data);
    }

    /**
     * 获取用户信息
     */
    public function actionInfo(){
        $header = Yii::$app->request->getHeaders();
        if (isset($header['access-token']) && $header['access-token']) {
            $accessToken = $header['access-token'];
        } else {
            return null;
        }

        /** @var \fecshop\models\mysqldb\Customer|null $identity */
        $identity = Customer::findIdentityByAccessToken($accessToken);
        $info = $identity->toArray();
        if ( !empty( $info["password_hash"] ) ) {
            unset( $info["password_hash"] );
            unset($info["auth_key"]);
            unset($info["password_reset_token"]);
            unset($info["register_enable_token"]);

        }
        return $info;
    }
    
    public function getLeftMenu()
    {
        $leftMenu = \Yii::$app->getModule('customer')->params['leftMenu'];
        if (is_array($leftMenu) && !empty($leftMenu)) {
            $arr = [];
            foreach ($leftMenu as $name => $url) {
                $name = Yii::$service->page->translate->__($name);
                $arr[$name] = $url;
            }
            return $arr;
        }else{
            return [];
        }
        
    }
    
    /**
     * 登出账户.
     */
    public function actionLogout()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        if (Yii::$app->user->isGuest) {
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        Yii::$service->customer->logoutByAccessToken();
        //Yii::$service->cart->clearCart();
        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
       
        
    }
    
    
    public function actionForgotpassword()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        //$forgotPasswordParam = \Yii::$app->getModule('customer')->params['forgotPassword'];
        //$forgotCaptcha = isset($forgotPasswordParam['forgotCaptcha']) ? $forgotPasswordParam['forgotCaptcha'] : false;
        $appName = Yii::$service->helper->getAppName();
        $forgotCaptcha = Yii::$app->store->get($appName.'_account', 'forgotPasswordCaptcha');
        $forgotCaptcha = ($forgotCaptcha == Yii::$app->store->enable)  ? true : false;
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'forgotCaptchaActive' => $forgotCaptcha,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
    }
    
    
    
}