<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\app\appbdmin\modules\Sales\block\orderdispatch;

use fec\helpers\CUrl;
use Yii;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Managerdispatch extends \yii\base\BaseObject
{
    public $_saveUrl;

    public function init()
    {
        parent::init();
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        $order_id = Yii::$app->request->get('order_id');
        //$order = Yii::$service->order->getByPrimaryKey($order_id);
        $order_info = Yii::$service->order->getByPrimaryKey($order_id);
        $bdmin_user_id = Yii::$app->user->identity->id;
        if ($bdmin_user_id != $order_info['bdmin_user_id']) { // 权限
            
            return [];
        }
        $order_arr = [
            'order_id' => $order_info['order_id'],
            'increment_id' => $order_info['increment_id'],
            'tracking_number' => $order_info['tracking_number'],
            'tracking_company' => $order_info['tracking_company'],
            'tracking_company_options' => $this->getOrderTrackingCompanyOptions($order_info['tracking_company']),
        ];
        return [
            'order' => $order_arr,
            //'editBar' 	=> $this->getEditBar(),
            //'textareas'	=> $this->_textareas,
            //'lang_attr'	=> $this->_lang_attr,
            'saveUrl' 	    => Yii::$service->url->getUrl('sales/orderdispatch/managerdispatchsave'),
        ];
    }
    // 得到tracking company html select options
    public function getOrderTrackingCompanyOptions($tracking_company)
    {
        $trackingCompanyArr = Yii::$service->delivery->getCompanyArr();
        $str = '';
        foreach ($trackingCompanyArr as $code => $label) {
            if ($tracking_company == $code) {
                $str .= '<option selected="selected" value="'.$code.'">'.$label.'</option>';
            } else {
                $str .= '<option value="'.$code.'">'.$label.'</option>';
            }
        }
        
        return $str;
    }
    
    public function save(){
        $editForm = Yii::$app->request->post('editForm');
        $order_id = $editForm['order_id'];
        if (!$order_id) {
            echo  json_encode([
                'statusCode' => '300',
                'message' => Yii::$service->page->translate->__('order id is empty'),
            ]);
            exit;
        }
        $tracking_company = $editForm['tracking_company'];
        if (!$tracking_company) {
            echo  json_encode([
                'statusCode' => '300',
                'message' => Yii::$service->page->translate->__('order tracking_company is empty'),
            ]);
            exit;
        }
        $tracking_number = $editForm['tracking_number'];
        if (!$tracking_number) {
            echo  json_encode([
                'statusCode' => '300',
                'message' => Yii::$service->page->translate->__('order tracking_number is empty'),
            ]);
            exit;
        }
        $bdmin_user_id = Yii::$app->user->identity->id;
        $innerTransaction = Yii::$app->db->beginTransaction();
        try { 
            if (!Yii::$service->order->process->bdminDispatchOrderById($bdmin_user_id, $order_id, $tracking_company, $tracking_number)) {
                throw new \Exception('dispatch order fail');
            }
            $innerTransaction->commit();
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            
            echo  json_encode([
                'statusCode' => '300',
                'message' => Yii::$service->page->translate->__('dispatch order fail'),
            ]);
            exit;
        }
        
        echo  json_encode([
            'statusCode' => '200',
            'message' => Yii::$service->page->translate->__('Dispatch Order Success'),
        ]);
        exit;
    }
    
}
