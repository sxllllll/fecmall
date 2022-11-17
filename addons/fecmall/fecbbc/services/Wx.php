<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\services;

use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
use Yii;

/**
 * Product Service is the component that you can get product info from it.
 *
 * @property \fecshop\services\Image | \fecshop\services\Product\Image $image image service or product image sub-service
 * @property \fecshop\services\product\Info $info product info sub-service
 * @property \fecshop\services\product\Stock $stock stock sub-service of product service
 *
 * @method getByPrimaryKey($primaryKey) get product model by primary key
 * @see \fecshop\services\Product::getByPrimaryKey()
 * @method getEnableStatus() get enable status
 * @see \fecshop\services\Product::getEnableStatus()
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Wx extends Service
{
    // 开发者id
    public $appId = '';
    // 开发者密钥
    public $appSecret = '';
    public $encodingAESKey = '';
    public $token = '';
    public $tokenCacheTimeOut = 3600;  // token的缓存时间，不能大于7200  ，cache保存token的过期时间
    
    public $apiBaseUrl = 'https://api.weixin.qq.com/cgi-bin';
    /**
     * 参数初始化
     */
    public function init()
    {
        parent::init();
        // 从后台配置中读取配置。
        // $this->appId = Yii::$app->store->get('base_fecphone', 'wx_fuwuhao_app_id');
        // $this->appSecret = Yii::$app->store->get('base_fecphone', 'wx_fuwuhao_secret');
        $this->appId = Yii::$app->store->get('payment_wxpay', 'wechat_service_app_id');
        $this->appSecret = Yii::$app->store->get('payment_wxpay', 'wechat_service_app_secret');
        $this->encodingAESKey = Yii::$app->store->get('base_fecphone', 'wx_fuwuhao_encoding_aes_key');
        $this->token = Yii::$app->store->get('base_fecphone', 'wx_fuwuhao_token');
        $tokenCacheTimeOut = Yii::$app->store->get('base_fecphone', 'wx_fuwuhao_token_time_out');
        if ($tokenCacheTimeOut) {
            $this->tokenCacheTimeOut = $tokenCacheTimeOut;
        }
    }
    
    /**
     * 【基础函数 - 得到wx appId】
     * 得到微信的开发者IdappId
     */
    public function getWxAppId()
    {
        return $this->appId;
    }
    /**
     *  【微信通知基础验证 - - 生成签名】
     * @param $timestamp | string，时间戳
     * @param $nonce | string
     * @return Array
     * 生成签名
     */
    public function getSignature($timestamp='', $nonce='')
    {
        if (!$timestamp) {
            $timestamp = time();
        }
        if (!$nonce) {
            $nonce = $this->getNonceStr();
        }
        $tmpArr = array($this->token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $signature = sha1($tmpStr);

        return [$signature, $timestamp,  $nonce];
    }
    /**
     * 【微信通知基础验证 - - 生成签名】
     * 得到32位长的随机字符串
     */
    public function getNonceStr()
    {
        return Yii::$service->helper->createNoncestr(32);
    }
    
    
    /**********************token部分begin***********************/
    /**
     * 【基础部分 - accessToken】
     * 获取 access_token;
     * 通过api远程得到 access_token
     */
	public function getAccessTokenByApi(){
		$url = $this->apiBaseUrl . "/token?grant_type=client_credential&appid=" . $this->appId . "&secret=" . $this->appSecret; 
		$result = $this->curlGet($url);
        $access_token = $result['access_token'];
        $expires_in = $result['expires_in'];
		return $access_token;
	}
    /**
     * 从cache中读取access_token
     */
    public function getAccessTokenFromCache()
    {
        $isReflush = Yii::$app->cache->get('wx_api_need_relush_all');
        if (!$isReflush) {
            Yii::$service->wx->reflushAllTokenCache();
        }
        return $accessToken = Yii::$app->cache->get('wx_api_access_token');
    }
     /**
     * 刷新所有cache存储的 Ticket;
     */
    public function reflushAllTokenCache($forceAll=false)
    {
        //echo '######';
        $isReflush = Yii::$app->cache->get('wx_api_need_relush_all');
        if (!$isReflush || $forceAll ) {
            // 将 access_token 写入cache
            $accessToken = $this->getAccessTokenByApi();
            Yii::$app->cache->set('wx_api_access_token', $accessToken, $this->tokenCacheTimeOut);
            // 将 ticket写入 cache
            $ticket = Yii::$service->wx->h5share->getJsApiTicketByAccessToken($accessToken);
            Yii::$app->cache->set('wx_api_jssdk_ticket', $ticket, $this->tokenCacheTimeOut);
            // 全部刷新后，设置刷新后的值位1
            Yii::$app->cache->set('wx_api_need_relush_all', 1, $this->tokenCacheTimeOut);
        }
    }
    /**********************token部分 End ***********************/
    
    /**
     * @param $url | string, url
     * curl 发送Get请求
     */
    public function curlGet($url)
    {
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//绕过ssl验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
     
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        return json_decode($output, true);;
    }
    /**
     * @param $url | string, url
     * @param $data | array, 请求发送的post数据
     * @param $timeout | int，超时的时间
     * curl 发送post请求
     */
    public function curlPost($url, $data, $timeout = 10)
    {
        
        return \fec\helpers\CApi::getCurlData($url, 'post', $data, $timeout);
    }
}
