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
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ReturnwaitingController extends SalesController
{
    public $enableCsrfValidation = true;
    
    public function actionManager()
    {
        $data = $this->getBlock()->getLastData();
    
        return $this->render($this->action->id, $data);
    }
    /*
    public function actionManageredit()
    {
        $primaryKey = Yii::$service->order->getPrimaryKey();
        $order_id = Yii::$app->request->get($primaryKey);
        $this->returnHasRole($order_id);
        
        $data = $this->getBlock()->getLastData();
        return $this->render($this->action->id, $data);
    }
    */
    
    public function actionManageraccept(){
        $ids = Yii::$app->request->post('ids');
        $id_arr = explode(',', $ids);
        foreach ($id_arr as $as_id ) {
            $this->returnHasRole($as_id);
            
            $innerTransaction = Yii::$app->db->beginTransaction();
            try { 
                if (!Yii::$service->order->afterSale->bdminAuditAcceptReturnByAsId($as_id)) {
                    throw new \Exception('audit return accept fail');
                }
                $innerTransaction->commit();
            } catch (\Exception $e) {
                $innerTransaction->rollBack();
                
                echo  json_encode([
                    'statusCode' => '300',
                    'message' => Yii::$service->page->translate->__('audit return accept fail'),
                ]);
                exit;
            }
        }
        echo  json_encode([
            'statusCode' => '200',
            'message' => Yii::$service->page->translate->__('Save Success'),
        ]);
        exit;
    }
    
     public function actionManagereditreturncost()
    {
        $primaryKey = Yii::$service->order->afterSale->getPrimaryKey();
        $return_id = Yii::$app->request->get($primaryKey);
        $this->returnHasRole($return_id);
        
        $data = $this->getBlock()->getLastData();
        return $this->render($this->action->id, $data);
    }
    
    public function actionManagerrefuse(){
        $ids = Yii::$app->request->post('ids');
        $id_arr = explode(',', $ids);
        foreach ($id_arr as $as_id ) {
            $this->returnHasRole($as_id);
            $innerTransaction = Yii::$app->db->beginTransaction();
            try { 
                if (!Yii::$service->order->afterSale->bdminAuditRefuseReturnByAsId($as_id)) {
                    throw new \Exception('audit return refuse fail');
                }
                $innerTransaction->commit();
            } catch (\Exception $e) {
                $innerTransaction->rollBack();
                
                echo  json_encode([
                    'statusCode' => '300',
                    'message' => Yii::$service->page->translate->__('audit return refuse fail'),
                ]);
                exit;
            }
        }
        echo  json_encode([
            'statusCode' => '200',
            'message' => Yii::$service->page->translate->__('Save Success'),
        ]);
        exit;
    }
    
    
    /**
     * ?????????????????????????????????????????????
     */
    public function returnHasRole($as_id){
        $afterSale = Yii::$service->order->afterSale->getByPrimaryKey($as_id);
        $af_bdmin_user_id = isset($afterSale['bdmin_user_id']) ? $afterSale['bdmin_user_id'] : '';
        
        $identity = Yii::$app->user->identity;
        $currentUserId = $identity->id;
        if (!$af_bdmin_user_id || $currentUserId != $af_bdmin_user_id) {
            echo  json_encode([
                'statusCode' => '300',
                'message' => Yii::$service->page->translate->__('You do not have role to edit this after sale return'),
            ]);
            exit;
        }
        unset($afterSale);
    }
    
}
