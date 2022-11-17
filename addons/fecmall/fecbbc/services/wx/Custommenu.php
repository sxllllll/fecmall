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
 * 公众号，自定义菜单管理
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Custommenu extends Service
{
    
    public function createMenu($postJsonStr)
    {
        $accessToken = Yii::$service->wx->getAccessTokenFromCache();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$accessToken";
        
        return $this->curlPostData($url, $postJsonStr);
    }
    
    
    
    public function curlPostData($url, $postJsonStr, $timeout = 10){
        //对空格进行转义
        $url = str_replace(' ','+',$url);
        
        //$data = json_encode($data);
        $url = urldecode($url);
        //echo $url ;exit;
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);  //定义超时3秒钟  
    
        // POST数据
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, 
            CURLOPT_HTTPHEADER, 
            [
            'Accept: application/json',
            'Content-Type: application/json',
            'Content-Length: ' . strlen($postJsonStr)
            ]
            );

        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postJsonStr);
    
        //执行并获取url地址的内容
        $output = curl_exec($ch);
        //echo $output ;
        //释放curl句柄
        curl_close($ch);
        //var_dump($output);exit;
        return $output;
    }
    
}
