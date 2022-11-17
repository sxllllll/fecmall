<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Coupon\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CustomerController extends AppfrontController
{
    public function init()
    {
        parent::init();
        
    }

    // 分类页面。
    public function actionRegistergift()
    {
        $data = $this->getBlock()->getLastData();
        return $this->render($this->action->id, $data);
    }

    
    
}
