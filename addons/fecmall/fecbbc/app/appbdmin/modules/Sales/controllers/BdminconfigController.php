<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appbdmin\modules\Sales\controllers;

use fecbbc\app\appbdmin\modules\Sales\SalesController;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0 
 */
class BdminconfigController extends SalesController
{
    public $enableCsrfValidation = true;
    
    public function actionManager()
    {
        $data = $this->getBlock()->getLastData();
        return $this->render($this->action->id, $data);
    }
    
    
    public function actionManagersave()
    {
        
        $primaryKey = Yii::$service->customer->getPrimaryKey();
        
        $data = $this->getBlock('manager')->save();
    }
    
}
