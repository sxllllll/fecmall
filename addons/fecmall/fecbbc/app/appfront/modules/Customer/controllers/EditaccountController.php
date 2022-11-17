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
class EditaccountController extends \fecshop\app\appfront\modules\Customer\controllers\EditaccountController
{
    public $blockNamespace = 'fecbbc\app\appfront\modules\Customer\block';
    public $enableCsrfValidation = true;
    
    public function init()
    {
        parent::init();
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

