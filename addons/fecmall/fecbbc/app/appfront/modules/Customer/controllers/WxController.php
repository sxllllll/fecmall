<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Customer\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class WxController extends AppfrontController
{
    public $enableCsrfValidation = false;
    
    public $blockNamespace = 'fecbbc\app\appfront\modules\Customer\block';
    
    /**
     * 微信推送消息 - 服务端接口
     */
    public function actionToken()
    {
        \Yii::info('openid:xxxxx', 'fecshop_debug');
        echo $this->getBlock()->getEchoStr();
        // 接收微信通知消息
        Yii::$service->wx->qrcode->qrCodeIpn();
        exit;
    }
    
    /**
     * ajax轮询查看账户状态（等待用户微信扫码推送消息更新数据库信息）
     */
    public function actionQrcodequery()
    {
        $eventKey = Yii::$app->request->get('eventKey');
        list ($resultCode, $openid) = Yii::$service->customer->queryQrCodeAndLogin($eventKey);
        echo json_encode([
            'status' => 'success',
            'code' => $resultCode,
            'openid' => $openid,
        ]);exit;
    }
    /**
     * ajax请求得到二维码信息
     */
    public function actionQrcode()
    {
        $qr = Yii::$service->wx->qrcode->getQrCodeInfo();
        $scene_id = isset($qr['scene_id']) ? $qr['scene_id'] : '';
        $src = isset($qr['src']) ? $qr['src'] : '';
        if (!$src || !$scene_id ) {
            echo json_encode([
                'status' => 'fail',
            ]);exit;
        }
        
        echo json_encode([
            'status' => 'success',
            'src' => $src,
            'scene_id' => $scene_id,
        ]);exit;
    }
    


}
