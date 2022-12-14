<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
namespace fecbbc\app\appapi\modules\V2\controllers;

use fecshop\app\appapi\modules\AppapiController;
use Yii;

class AccountController extends AppapiController
{
    // login and get token
    public function actionLogin(){
        $username       = Yii::$app->request->post('username');
        $password       = Yii::$app->request->post('password');
        $accessToken = Yii::$service->bdminUser->loginAndGetAccessToken($username, $password);
        if($accessToken){
            return [
                'access-token' => $accessToken,
                'status'       => 'success',
                'code'         => 200,
            ];
        }else{
            return [
                'access-token' => '',
                'status'       => 'error',
                'code'         => 401,
            ];
        }
        
    }
}
