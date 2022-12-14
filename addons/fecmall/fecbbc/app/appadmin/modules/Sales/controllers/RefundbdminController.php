<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\app\appadmin\modules\Sales\controllers;

use fecshop\app\appadmin\modules\Sales\SalesController;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class RefundbdminController extends SalesController
{
    public $enableCsrfValidation = true;
    public $blockNamespace = 'fecbbc\\app\\appadmin\\modules\\Sales\\block';
    
    public function actionManager()
    {
        $data = $this->getBlock()->getLastData();
    
        return $this->render($this->action->id, $data);
    }
    
    public function actionManageredit()
    {
        $primaryKey = Yii::$service->refund->getPrimaryKey();
        $refund_id = Yii::$app->request->get($primaryKey);
        //$this->orderHasRole($refund_id);
        
        $data = $this->getBlock()->getLastData();
        return $this->render($this->action->id, $data);
    }
    
    public function actionManagereditsave()
    {
          
        $primaryKey = Yii::$service->refund->getPrimaryKey();
        $editFormData = Yii::$app->request->post('editFormData');
        $refund_id = $editFormData['id'];
        //$this->orderHasRole($refund_id);
        
        $data = $this->getBlock('manageredit')->save();
    }
}
