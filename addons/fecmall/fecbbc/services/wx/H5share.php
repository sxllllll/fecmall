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
 * h5公众号分享部分
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class H5share extends Service
{
    // 首页微信分享 ，依次为标题，描述，图片
    public $homePageTitle = 'Fecmall首页分享';
    public $homePageDesc = '首页，更多品牌折扣，尽在Fecmall开源商城';
    public $homePageImgUrl = 'http://img.fancyecommerce.com/addons/fecphone/wx_share.png';
    // 分类页微信分享 - 标题使用分类的title，下面是描述和图片
    public $categoryPageDesc = '分类页，更多品牌折扣，尽在Fecmall开源商城';
    public $categoryPageImgUrl = 'http://img.fancyecommerce.com/addons/fecphone/wx_share.png';
    // 产品页微信分享
    public $productPageDefaultDesc = '产品页，更多品牌折扣，尽在Fecmall开源商城';
    
    public $shareSuccessAlert = '分享成功';
    public $shareUrlAddParamArr = []; //'aid' => 'yeeeyyyy'
    
    /**
     * 参数初始化
     */
    public function init()
    {
        parent::init();
        // 微信分享-首页部分
        $homePageTitle = Yii::$app->store->get('base_fecphone', 'wx_fuwuhao_home_page_title');
        if ($homePageTitle) {
            $this->homePageTitle = $homePageTitle;
        }
        $homePageDesc = Yii::$app->store->get('base_fecphone', 'wx_fuwuhao_home_page_desc');
        if ($homePageDesc) {
            $this->homePageDesc = $homePageDesc;
        }
        $homePageImgUrl = Yii::$app->store->get('base_fecphone', 'wx_fuwuhao_home_page_image_url');
        if ($homePageImgUrl) {
            $this->homePageImgUrl = $homePageImgUrl;
        }
        
        // 微信分享-分类部分
        $categoryPageDesc = Yii::$app->store->get('base_fecphone', 'wx_fuwuhao_category_page_default_desc');
        if ($categoryPageDesc) {
            $this->categoryPageDesc = $categoryPageDesc;
        }
        $categoryPageImgUrl = Yii::$app->store->get('base_fecphone', 'wx_fuwuhao_category_page_image_url');
        if ($categoryPageImgUrl) {
            $this->categoryPageImgUrl = $categoryPageImgUrl;
        }
        
        // 微信分享-产品部分
        $productPageDefaultDesc = Yii::$app->store->get('base_fecphone', 'wx_fuwuhao_product_page_default_desc');
        if ($productPageDefaultDesc) {
            $this->productPageDefaultDesc = $productPageDefaultDesc;
        }
        // 微信分享-分享成功后的输出框
        $shareSuccessAlert = Yii::$app->store->get('base_fecphone', 'wx_fuwuhao_share_success_alert');
        if ($shareSuccessAlert) {
            $this->shareSuccessAlert = $shareSuccessAlert;
        }
        
    }
    
    /**
     * 【生成签名信息】
     *  微信分享 - 根据分享的url等信息，生成签名信息
     *  生成的签名信息，是为了使用jssdk
     *  https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/JS-SDK.html#111
     */
    public function getSignPackage() 
    {
        $jsapiTicket = $this->getJsApiTicketFromCache();
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $urlOld = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $url = str_replace(":80","",$urlOld); //注意会默认添加80端口，这样会和公众号的js接口安全域名不一样导致出错，所以要切掉；
        // 不能用下面的处理，用了后，就无法分享。
        //if (strstr($url,'?')) {
        //    $url = substr($url, 0, strpos($url, '?'));  //去掉参数
        //}
        //$url = $this->getPageShareLink($url);
        
        //echo '#################'.$url.'##########';
        $timestamp = time();
        $nonceStr = Yii::$service->wx->getNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
          "appId"     => Yii::$service->wx->appId, 
          "nonceStr"  => $nonceStr,
          "timestamp" => $timestamp,
          "url"       => $url,
          "signature" => $signature,
          "rawString" => $string
        );
        
        return $signPackage; 
    }
    
    /**
     * 【分享url处理】
     * @param $urlLink | string, 分享的url
     * 通过计算，得到产品页面的分享url，将$shareUrlAddParamArr中的参数添加到分享的url中。
     * 如果页面存在参数，那么无效（微信不允许带有参数的url，加入额外的参数）
     */
    public function getPageShareLink($urlLink) {
        if (!$urlLink) {
            $urlLink = Yii::$service->url->getCurrentUrl();
        }
        if (strstr($urlLink, '?') || strstr($urlLink, '#')) {  // 存在参数的url不能继续加url
            
            return $urlLink;
        }
        if (!is_array($this->shareUrlAddParamArr) || empty($this->shareUrlAddParamArr)) {
            
            return $urlLink;
        }
        // 添加参数
        $addParamArr = [];
        $addParamStr = [];
        foreach ($this->shareUrlAddParamArr as $k=>$v) {
            $addParamArr[] =  $k.'='.$v;
        }
        $addParamStr = implode('&', $addParamArr);
        $urlLink = $addParamStr ? $urlLink.'?'.$addParamStr : $urlLink;
        
        return $urlLink;
    }
    
    /**
     * 【微信分享 - ticket】获取 Ticket;
     * 通过api远程得到 Ticket
     */
	public function getJsApiTicketByAccessToken($accessToken){
		//$res = json_decode($accessToken);
		//$accessToken = $res->access_token;
		$url = Yii::$service->wx->apiBaseUrl . "/ticket/getticket?type=jsapi&access_token=$accessToken";
		$result = Yii::$service->wx->curlGet($url);
		$errcode = $result['errcode'];
        $errmsg = $result['errmsg'];
        $ticket = $result['ticket'];
        $expires_in = $result['expires_in'];
        if ($errmsg == 'ok') {
            
            return $ticket;
        }
	}
    
    /**
     * 【微信分享 - JSSDK生成签名信息 - 得到Ticket】
     * 文档：https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/JS-SDK.html#111
     * 在微信公众号内打开的网页，如果要使用微信的JSSDK，需要进行
     * wx.config({
     *     debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
     *     appId: '', // 必填，公众号的唯一标识
     *     timestamp: , // 必填，生成签名的时间戳
     *     nonceStr: '', // 必填，生成签名的随机串
     *     signature: '',// 必填，签名
     *     jsApiList: [] // 必填，需要使用的JS接口列表
     *   });
     *  这里获取ticket是为了通过函数 getSignPackage 生成签名signature
     * 从缓存里面获取-获取 Ticket;
     */
    public function getJsApiTicketFromCache()
    {
        // 如果过期，就全部刷新一下cache
        $isReflush = Yii::$app->cache->get('wx_api_need_relush_all');
        if (!$isReflush) {
            Yii::$service->wx->reflushAllTokenCache();
        }
        // 如果取到的过期，就刷新一遍
        $ticket = Yii::$app->cache->get('wx_api_jssdk_ticket');
        
        return $ticket;
    }
    
    
    
}
