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
    /**
     * 申请商家类型通知
     */
    const APPLY_SELLER_NOTICE_TYPE = 100;
    
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
            $param["is_audit"] = 2; // 申请中
            $param["cid"] = $identity["id"]; // 用户id
            $param["tax_point"] = 1;
            $param["email"] = $identity["email"];
            $model->setAttributes($param,false) ;
            $save = $model->saveData( $seller["id"] ?? 0  );
            if( !$save ) {
                $code = Yii::$service->helper->appserver->customer_apply_seller_fail;
                return Yii::$service->helper->appserver->getResponseData($code, $param);
            }
            // 加入通知
            $notice = [
                "type" => self::APPLY_SELLER_NOTICE_TYPE,
                "progress" => 2,
                "to_id" =>  $identity["id"] ,
            ];
            Yii::$service->notices->insert($notice);
            return Yii::$service->helper->appserver->getResponseData($code, $data);
        }

        $code = Yii::$service->helper->appserver->customer_reapply_seller;
        return Yii::$service->helper->appserver->getResponseData($code, $seller);
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

    /**
     * 获取通知信息
     */
    public function actionNotices (){
        $header = Yii::$app->request->getHeaders();
        if ( !( isset($header['access-token']) && $header['access-token'] ) ) {
            return Yii::$service->helper->appserver->invalid_token();
        }
        $id = Yii::$app->cache->get($header['access-token']);
        if( empty($id) ) {
            $code = Yii::$service->helper->appserver->customer_invalid_userid;
            return Yii::$service->helper->appserver->getResponseData( $code , [] );
        }
        $params = Yii::$app->request->get();
        $page = $params["page"] ?? 1 ;
        if ( $page <= 0 ) $page = 1;
        $data = Yii::$service->notices->getNotices( $id , intval( $params["type"] ?? 0 ) , intval( $page ) ,intval( $params["pagesize"] ?? 15 ) );
        return Yii::$service->helper->appserver->success( $data );
    }

    /**
     * 设置通知查看状态
     */
    public function actionNoticeRead(){
        $id = Yii::$app->request->get("id" , 0);
        if ( empty($id) ) {
            return Yii::$service->helper->appserver->invalid_params("id");
        }
        $save = Yii::$service->notices->updateInRead( $id );
        if ( empty( $save ) ) {
            return Yii::$service->helper->appserver->db_failed();
        }
        return Yii::$service->helper->appserver->success();
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