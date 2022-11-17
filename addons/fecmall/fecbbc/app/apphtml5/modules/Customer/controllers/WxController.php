<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Customer\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class WxController extends AppfrontController
{
    public $blockNamespace = 'fecbbc\app\apphtml5\modules\Customer\block';
    
    public function actionToken()
    {
        echo $this->getBlock()->getEchoStr();
        exit;
    }
    
    public function actionLoginbridge()
    {
        $code = Yii::$app->request->get('code');
        $state = Yii::$app->request->get('state');
        echo $code;
        list($access_token, $openid) = Yii::$service->wx->h5login->getAccessTokenAndOpenidByCode($code);
        // $openid 进行登陆
        //  查看customer 是否存在openid，如果存在，直接登陆
        if ($identity = Yii::$service->customer->getByOpenid($openid)) {
            if (Yii::$service->customer->loginByIdentity($identity)) {
                
                return Yii::$service->url->redirectByUrlKey('customer/account');
            } else {
                
                return Yii::$service->url->redirectHome;
            }
        }
        
        return Yii::$service->url->redirectByUrlKey('customer/account/phoneregister', ['openid' => $openid]);
        
        //  如果不存在，则跳转到绑定手机的页面，提示用户进行手机绑定，将 openid 存储到session中
        
        
        //var_dump($access_token, $openid);
    }
    
    
    
    public function actionTest()
    {
        //$qr = Yii::$service->wx->getQrTicket();
        
        //var_dump($qr);
    }
    


}
