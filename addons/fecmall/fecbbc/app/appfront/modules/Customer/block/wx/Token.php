<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Customer\block\wx;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Token  
{
    
    
    public function getEchoStr()
    {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        list($generateSignature, $generateSimestamp,  $generateNonce) = Yii::$service->wx->getSignature($timestamp, $nonce);
        if ($generateSignature == $signature){
            
            return $echoStr;
        }
        
        return '';
    }

    
}
