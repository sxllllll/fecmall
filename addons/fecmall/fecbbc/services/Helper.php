<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\services;

use Yii;
use Ramsey\Uuid\Uuid;

/**
 * Helper service.
 *
 * @property \fecbbc\services\helper\Appapi $appapi
 * @property \fecbbc\services\helper\Appserver $appserver appserver sub-service of helper service
 * @property \fecbbc\services\helper\AR $ar
 * @property \fecbbc\services\helper\Captcha $captcha
 * @property \fecbbc\services\helper\Country $country
 * @property \fecbbc\services\helper\Echart $echart
 * @property \fecbbc\services\helper\ErrorHandler $errorHandler
 * @property \fecbbc\services\helper\Errors $errors errors sub-service of helper service
 * @property \fecbbc\services\helper\Format $format
 * @property \fecbbc\services\helper\MobileDetect $mobileDetect
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Helper extends \fecshop\services\Helper
{
    protected $_app_name;

    protected $_param;
    /**
     * 用户在前端商城，是否只能看到绑定的供应商的产品
     * 其他的供应商的产品是否不能看到？
     */
    
    // 是否只有登陆用户才能查看产品价格
    public $loginShowProductPrice = false;
    // 是否可以显示并使用优惠券模块
    public $showCouponCode = false;
    
    
    
    // 是否只有登陆用户才能查看产品价格
    public function canShowProductPrice(){
        if ($this->loginShowProductPrice && Yii::$app->user->isGuest) {
            
            return false;
        } 
        
        return true;
    }
    // 是否可以显示并使用优惠券模块
    public function canShowUseCouponCode(){
        
        return $this->showCouponCode;
    }
    
}
