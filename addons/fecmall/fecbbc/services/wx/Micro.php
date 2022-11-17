<?php

/*
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license/
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
class Micro extends Service
{
    
    // 首页-微信小程序分享 ，依次为标题，描述，图片
    public $homePageTitle = 'Fecmall微信小程序首页分享';
    public $homePageImgUrl = 'addons/fecbbc/wx_share.png';
    // 分类-微信分享 - 标题使用分类的title，下面是描述和图片
    public $categoryPageImgUrl = 'addons/fecbbc/wx_share.png';
    
    public $shareSuccessAlert = '分享成功';
    
    
    public function init()
    {
        parent::init();
        
        
        $homePageTitle = Yii::$app->store->get('base_fecphone', 'wx_micro_home_page_title');
        if ($homePageTitle) {
            $this->homePageTitle = $homePageTitle;
        }
        
        $homePageImgUrl = Yii::$app->store->get('base_fecphone', 'wx_micro_home_page_image_url');
        if ($homePageImgUrl) {
            $this->homePageImgUrl = $homePageImgUrl;
        } else {
            $this->homePageImgUrl = Yii::$service->image->getImgUrl($this->homePageImgUrl);
        }
        
        $categoryPageImgUrl = Yii::$app->store->get('base_fecphone', 'wx_micro_category_page_image_url');
        if ($categoryPageImgUrl) {
            $this->categoryPageImgUrl = $categoryPageImgUrl;
        } else {
            $this->categoryPageImgUrl = Yii::$service->image->getImgUrl($this->categoryPageImgUrl);
        }
    }
    
    
    
    
    
}