<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecyo\services;

use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Wxmicro extends Service
{
    // 微信服务号-开发者id
    public $appId = '';
    // 微信服务号-开发者密钥
    public $appSecret = '';
    
    public $tokenCacheTimeOut = 3600;  // token的缓存时间，不能大于7200  ，cache保存token的过期时间
    
    public $apiBaseUrl = 'https://api.weixin.qq.com/cgi-bin';
    /**
     * 参数初始化
     */
    public function init()
    {
        parent::init();
        // 微信小程序配置
        $this->appId = Yii::$app->store->get('payment_wxpay', 'wechat_micro_app_id' );
        $this->appSecret = Yii::$app->store->get('payment_wxpay', 'wechat_micro_app_secret');

        $tokenCacheTimeOut = Yii::$app->store->get('base_fecphone', 'wx_fuwuhao_token_time_out');
        if ($tokenCacheTimeOut) {
            $this->tokenCacheTimeOut = $tokenCacheTimeOut;
        }
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
        $isReflush = Yii::$app->cache->get('wx_micro_api_need_relush_all');
        if (!$isReflush) {
            Yii::$service->wxmicro->reflushAllTokenCache();
        }
        return $accessToken = Yii::$app->cache->get('wx_micro_api_access_token');
    }
     /**
     * 刷新所有cache存储的 Ticket;
     */
    public function reflushAllTokenCache($forceAll=false)
    {
        //echo '######';
        $isReflush = Yii::$app->cache->get('wx_micro_api_need_relush_all');
        if (!$isReflush || $forceAll ) {
            // 将 access_token 写入cache
            $accessToken = $this->getAccessTokenByApi();
            Yii::$app->cache->set('wx_micro_api_access_token', $accessToken, $this->tokenCacheTimeOut);
            // 将 ticket写入 cache
            //$ticket = Yii::$service->wx->h5share->getJsApiTicketByAccessToken($accessToken);
            //Yii::$app->cache->set('wx_api_jssdk_ticket', $ticket, $this->tokenCacheTimeOut);
            // 全部刷新后，设置刷新后的值位1
            Yii::$app->cache->set('wx_micro_api_need_relush_all', 1, $this->tokenCacheTimeOut);
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
