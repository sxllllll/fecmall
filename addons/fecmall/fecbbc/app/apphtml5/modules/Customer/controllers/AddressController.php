<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Customer\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AddressController extends AppfrontController
{
    public $enableCsrfValidation = true;
    public $blockNamespace = 'fecbbc\app\apphtml5\modules\Customer\block';

    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account/login');
        }
        Yii::$service->page->theme->layoutFile = 'customer_address.php';
    }

    /**
     * 用户货运地址列表
     */
    public function actionIndex()
    {
        $data = $this->getBlock()->getLastData();
        return $this->render($this->action->id, $data);
    }
    
    // 地址编辑
    public function actionEdit()
    {
        $address = Yii::$app->request->post('editForm');
        if (is_array($address) && !empty($address)) {
            if ($this->getBlock()->save($address)) {
                
                return Yii::$service->url->redirectByUrlKey('customer/address/index');
            }
        }
        $data = $this->getBlock()->getLastData();
        return $this->render($this->action->id, $data);
    }
    
    // 地址删除
    public function actionRemove() 
    {
        $address_id = Yii::$app->request->get('address_id');
        $customer_id = Yii::$app->user->identity->id;
        Yii::$service->customer->address->remove($address_id, $customer_id);
        Yii::$service->url->redirectByUrlKey('customer/address/index');
        return;
    }

}
