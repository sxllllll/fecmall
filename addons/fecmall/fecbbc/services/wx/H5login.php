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
 * h5公众号登陆部分
 * 文档地址：https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html#3
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class H5login extends Service
{
    /**
     * @param $redirectUrlKey | string, 登陆oauth验证返回的url
     * 得到oauth  Authorize验证的url，访问该Authorize url后，将会返回code的url： `redirect_uri/?code=CODE&state=STATE`。
     */
    public function getOauthAuthorizeUrl($redirectUrlKey)
    {
        $appid = Yii::$service->wx->appId;
        $redirect_uri = Yii::$service->url->getUrl($redirectUrlKey);
        $scope = 'snsapi_base';
        $state = Yii::$service->helper->createNoncestr(8);
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=$scope&state=$state#wechat_redirect";
    
        return $url;
    }
    /**
     * @param $code | string
     * 得到accessToken
     */
    public function getAccessTokenAndOpenidByCode($code)
    {
        if (!$code) {
            return ['', ''];
        }
        $appid = Yii::$service->wx->appId;
        $appSecret = Yii::$service->wx->appSecret;
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appSecret&code=$code&grant_type=authorization_code";
        $result = Yii::$service->wx->curlGet($url);
        
        
        $access_token = isset($result['access_token']) ? $result['access_token'] : '';
        $openid = isset($result['openid']) ? $result['openid'] : '';
        
        return [$access_token, $openid];
    }
    
    
    
    
    
    
    
}
