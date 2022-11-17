<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\app\appbdmin\modules\Sales\controllers;

use fecbbc\app\appbdmin\modules\Sales\SalesController;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CouponController extends SalesController
{
    public $enableCsrfValidation = true;
    
    public function actionManager()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function actionManageredit()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function actionManagereditsave()
    {
        $data = $this->getBlock('manageredit')->save();
    }

    public function actionManagerdelete()
    {
        $this->getBlock('manageredit')->delete();
    }
    
    // catalog product
    public function actionGetproductcategory()
    {
        $this->getBlock()->getProductCategory();
    }
    
}
