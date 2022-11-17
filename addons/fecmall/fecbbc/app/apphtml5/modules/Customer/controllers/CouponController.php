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
class CouponController extends AppfrontController
{
    public $enableCsrfValidation = true;
    public $blockNamespace = 'fecbbc\app\apphtml5\modules\Customer\block';

    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account/login');
        }
        Yii::$service->page->theme->layoutFile = 'customer_coupon.php';
    }

    /**
     * 优惠券列表
     */
    public function actionIndex()
    {
        $data = $this->getBlock()->getLastData();
        return $this->render($this->action->id, $data);
    }
    
}
