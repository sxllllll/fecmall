<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appserver\modules\Customer\controllers;

use fecshop\app\appserver\modules\AppserverTokenController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ContactsController extends AppserverTokenController
{
    public $enableCsrfValidation = false;

    public function init()
    {
        parent::init();
    }

    public function actionIndex()
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
        
        return $this->saveContactsInfo();
        
        /*
        $editForm = Yii::$app->request->post('editForm');
        if (!empty($editForm)) {
            $this->getBlock()->saveContactsInfo($editForm);
        }
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
        */
    }
    
    /**
     * 保存contacts 信息。
     */
    public function saveContactsInfo()
    {
        $name = Yii::$app->request->post('name');
        $email = Yii::$app->request->post('email');
        $telephone = Yii::$app->request->post('telephone');
        $comment = Yii::$app->request->post('contact_content');
        
        if (!name || !$telephone ||  !$comment || !$email ||  !\fec\helpers\CEmail::email_validation($email)) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $paramData = [
            'name'        => $name,
            'telephone' => $telephone,
            'comment'    => $comment,
            'email'        => $email,
        ];
        if (!Yii::$service->email->customer->sendContactsEmail($paramData)) {
            $code = Yii::$service->helper->appserver->account_contact_us_send_email_fail;
            $data = [
                'errors' => Yii::$service->helper->errors->gete(', ')
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    
    
    
}
