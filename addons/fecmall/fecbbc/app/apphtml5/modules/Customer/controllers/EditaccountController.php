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
class EditaccountController extends \fecshop\app\apphtml5\modules\Customer\controllers\EditaccountController
{
    public $enableCsrfValidation = true;
    public $blockNamespace = 'fecbbc\app\apphtml5\modules\Customer\block';
    
    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account/login');
        }
         Yii::$service->page->theme->layoutFile = 'customer.php';
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account/login');
        }
        $editForm = Yii::$app->request->post('editForm');
        if (!empty($editForm)) {
            $this->getBlock()->saveAccount($editForm);
        }
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
}
