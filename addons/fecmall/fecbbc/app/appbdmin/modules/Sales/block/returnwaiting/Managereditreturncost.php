<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\app\appbdmin\modules\Sales\block\returnwaiting;

use fec\helpers\CUrl;
use Yii;
use fecbbc\app\appbdmin\interfaces\base\AppbdminbaseBlockEditInterface;
use fecbbc\app\appbdmin\modules\AppbdminbaseBlockEdit;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Managereditreturncost extends AppbdminbaseBlockEdit implements AppbdminbaseBlockEditInterface
{
    public $_saveUrl;

    public function init()
    {
        //$this->_saveUrl = CUrl::getUrl('sales/returnwaiting/managereditretur');
        parent::init();
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        $bdmin_user_id = Yii::$app->user->identity->id;
        if ($bdmin_user_id != $this->_one['bdmin_user_id']) { // 权限
            
            return [];
        }
        return [
            'editBar'      => $this->getEditBar(),
            'saveUrl'     => $this->_saveUrl,
        ];
    }
    public function setService()
    {
        $this->_service = Yii::$service->order->afterSale;
    }
    
    
    public function getEditArr()
    {
        $deleteStatus = Yii::$service->customer->getStatusDeleted();
        $activeStatus = Yii::$service->customer->getStatusActive();

        return [
            [
                'label'  => Yii::$service->page->translate->__('Id'),
                'name' => 'id',
                'display' => [
                    'type' => 'inputString',

                ],
                'require' => 0,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Order Id'),
                'name' => 'order_id',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Increment Id'),
                'name' => 'increment_id',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Payment Method'),
                'name' => 'payment_method',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            
             [
                'label'  => Yii::$service->page->translate->__('Customer Id'),
                'name' => 'customer_id',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            
             [
                'label'  => Yii::$service->page->translate->__('item id'),
                'name' => 'item_id',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            
            
             [
                'label'  => Yii::$service->page->translate->__('Status'),
                'name' => 'status',
                'display' => [
                    'type' => 'select',
                    'data' => Yii::$service->order->afterSale->getAllReturnStatusArr(),
                ],
                'require' => 1,
                'default' => 1,
            ],
            
            
            
            
             [
                'label'  => Yii::$service->page->translate->__('sku'),
                'name' => 'sku',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],

            [
                'label'  => Yii::$service->page->translate->__('Custom Option Sku'),
                'name' => 'custom_option_sku',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Product Id'),
                'name' => 'product_id',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Currency Code'),
                'name' => 'currency_code',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Base Return Price'),
                'name' => 'base_price',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Return Price'),
                'name' => 'price',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
            
            [
                'label'  => Yii::$service->page->translate->__('Currency Rate'),
                'name' => 'order_to_base_rate',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Return Qty'),
                'name' => 'qty',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Return Tracking Number'),
                'name' => 'tracking_number',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
           
           
        ];
    }
    
    public function save(){
        $editForm = Yii::$app->request->post('editFormData');
        $refund_id = $editForm['id'];
        $refundModel = Yii::$service->refund->getByPrimaryKey($refund_id);
        $bdmin_user_id = Yii::$app->user->identity->id;
        if ($bdmin_user_id !=  $refundModel['bdmin_user_id']) {
            
            return ;
        }
        $status = $refundModel['status'];
        if (is_array($editForm) && $refundModel['id']) {
            $refundModel['customer_bank'] = $editForm['customer_bank'];
            $refundModel['customer_bank_name'] = $editForm['customer_bank_name'];
            $refundModel['customer_bank_account'] = $editForm['customer_bank_account'];
            $refundModel->save();
            if (
                $status == Yii::$service->refund->status_payment_pending
                && $editForm['status'] == Yii::$service->refund->status_payment_confirmed
            ) {
                if (!Yii::$service->refund->payReturnRefund($refund_id, 'bdmin')) {
                    $errors = Yii::$service->helper->errors->get();
                    echo json_encode([
                        'statusCode' => '300',
                        'message' => $errors ,
                    ]);
                    exit;
                }
            }
            
            
        }
        echo  json_encode([
            'statusCode' => '200',
            'message' => Yii::$service->page->translate->__('Save Success'),
        ]);
        exit;
    }
   
}
