<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appserver\modules\Wx\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0 
 */
class StartController extends AppserverController
{
    //   general/start/first
    public function actionFirst()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            
            return [];
        }
        $startImageUrl = '';
        $start_page_img_url = Yii::$app->store->get('appserver_home', 'start_page_img_url');
        $start_page_title = Yii::$app->store->get('appserver_home', 'start_page_title');
        $start_page_remark = Yii::$app->store->get('appserver_home', 'start_page_remark');
        if ($start_page_img_url) $startImageUrl = Yii::$service->image->getUrlByRelativePath($start_page_img_url);
        // 设置默认值
        if (!$start_page_title) $start_page_title = 'Fecmall';
        if (!$start_page_remark) $start_page_remark = 'Wx Micro Program';
        // 返回值
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            [
                'picUrl' => $startImageUrl, // start页大图
                'title' => Yii::$service->page->translate->__($start_page_title), 
                'remark' => Yii::$service->page->translate->__($start_page_remark),
            ],
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    
}