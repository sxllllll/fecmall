<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\services\wx;

use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
use Yii;

/**
 * 扫描二维码关注公众号，实现pc端登陆
 */
class Qrcode extends Service
{
    
    
    public $pcQrCodeReflush = 2000;  // pc二维码登陆，ajax请求服务端的频率
    public $pcQrCodeTimeout = 60000; // pc二维码登陆，超时的最大时间。
    
    /**
     * 参数初始化
     */
    public function init()
    {
        parent::init();
        $pcQrCodeReflush = Yii::$app->store->get('base_fecphone', 'wx_fuwuhao_pc_qr_code_reflush');
        if ($pcQrCodeReflush) {
            $this->pcQrCodeReflush = $pcQrCodeReflush * 1000;
        }
        $pcQrCodeTimeout = Yii::$app->store->get('base_fecphone', 'wx_fuwuhao_pc_qr_code_timeout');
        if ($pcQrCodeTimeout) {
            $this->pcQrCodeTimeout = $pcQrCodeTimeout * 1000;
        }
    }
    
    /**
     * 【微信二维码登陆 - 获取二维码信息】
     *  获取生成的二维码的详细信息
     */
    public function getQrCodeInfo()
    {
        $accessToken = Yii::$service->wx->getAccessTokenFromCache();
        $qrCode = $this->getQrCodeByAccessToken($accessToken);
        
        return $qrCode;
    }
    
    // 生成的临时二维码的过期时间
    public $qrTimeOut = 604800;
    /**
     * 【微信二维码登陆 - 获取二维码信息】
     * @param $accessToken | string
     * 通过$accessToken，得到二维码的详细信息
     */
    protected function getQrCodeByAccessToken($accessToken){
		$url = Yii::$service->wx->apiBaseUrl. "/qrcode/create?scope=snsapi_userinfo&access_token=$accessToken";
        $scene_id = $this->getSceneId();
        $data = [
            'expire_seconds' => $this->qrTimeOut,
            'action_name' => 'QR_SCENE',
            'action_info' => [
                'scene' => [
                    'scene_id' => $scene_id,
                ],
            ],
        ];
        
        $result = Yii::$service->wx->curlPost($url, $data);
        //var_dump( $result);
        $obj = json_decode($result);
        $ticket=$obj->ticket;
        
        return [
            'src'=>"https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket", 
            'scene_id'=>$scene_id
        ];
    }
    /**
     * 【微信二维码登陆 - scene_id】
     * 生成的临时二维码的scene_id
     */
    public function getSceneId()
    {
        $max_int = pow(2, 31) - 1;
        $scene_id = rand(0, $max_int);
        
        return $scene_id;
    }
    
    /**
     * 【微信通知消息处理 - 用户扫描公众号二维码】
     * /customer/wx/token ，在微信公众号后台设置的`服务器地址(URL)`,用于接收微信通知部分，进行处理的函数
     * 处理内容：用户扫描公众号二维码，关注公众号后，处理用户的的消息通知
     * 进行解密，然后把接收到关注公众号的用户openid和eventKey，保存到表中
     */
    public function qrCodeIpn()
    {
        $wxBizMsgCryptFile = Yii::getAlias('@fecbbc/lib/wx/wxBizMsgCrypt.php');
        require($wxBizMsgCryptFile);
        
        $timestamp  = $_GET['timestamp'];
        $nonce = $_GET["nonce"];
        $msg_signature  = $_GET['msg_signature'];
        $encrypt_type = $_GET['encrypt_type'];
        $postStr = file_get_contents("php://input");
        if ($encrypt_type == 'aes'){
            $pc = new \WXBizMsgCrypt(Yii::$service->wx->token, Yii::$service->wx->encodingAESKey, Yii::$service->wx->appId);      
            $decryptMsg = "";  //解密后的明文
            $errCode = $pc->decryptMsg($msg_signature, $timestamp, $nonce, $postStr, $decryptMsg);
            $postStr = $decryptMsg;
            $postObj = json_decode(json_encode(simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA)),true);
            $openid = $postObj['FromUserName'];
            $eventKey = $postObj['EventKey'];
            if ($openid && $eventKey ) {
                // 更新用户的微信信息。
                Yii::$service->customer->updateWxQrCode($openid, $eventKey);
            }
        }
    }
}
