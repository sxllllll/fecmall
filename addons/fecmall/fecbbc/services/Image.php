<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\services;

use fec\helpers\CDir;
use Yii;
use yii\base\InvalidValueException;

/**
 * Image services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Image extends \fecshop\services\Image
{
    
    /**
     * 得到logo的url
     */
    public function getH5LogoImgUrl()
    {
        $logoImg = Yii::$app->store->get('base_info', 'h5_logo_image');
        if ($logoImg) {
            
            return $this->getUrlByRelativePath($logoImg);
        }
        
        return Yii::$service->image->getImgUrl('addons/fecbbc/h5/logo_white.png');
    }
    
}