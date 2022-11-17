<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appadmin\modules\Supplier\block\apply;

use fec\helpers\CRequest;
use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    public $_saveUrl;

    public function init()
    {
        //$this->_saveUrl = CUrl::getUrl('distribute/account/managereditsave');
        parent::init();
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        
        $zip_file = $this->_one['zip_file'];
        return [
            'editBar'      => $this->getEditBar(),
            'textareas'   => $this->_textareas,
            'lang_attr'   => $this->_lang_attr,
            'saveUrl'     => $this->_saveUrl,
            'zip_file' => $zip_file,
        ];
    }

    public function setService()
    {
        $this->_service = Yii::$service->bdminUser->bdminUser;
    }

    public function getEditArr()
    {
        $deleteStatus = Yii::$service->customer->getStatusDeleted();
        $activeStatus = Yii::$service->customer->getStatusActive();

        return [
            [
                'label'  => Yii::$service->page->translate->__('Username'),
                'name' => 'username',
                'display' => [
                    'type' => 'inputString',

                ],
                'require' => 0,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Company Name'),
                'name' => 'person',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Telephone'),
                'name' => 'telephone',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Email'),
                'name' => 'email',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
            
            
            [
                'label'  => Yii::$service->page->translate->__('Audit Status'),
                'name' => 'is_audit',
                'display' => [
                    'type' => 'select',
                    'data' => Yii::$service->bdminUser->bdminUser->getAuditStatusArr(),
                ],
                'require' => 1,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Authorized Type'),
                'name' => 'authorized_type',
                'display' => [
                    'type' => 'select',
                    'data' => Yii::$service->bdminUser->bdminUser->getAuthorizedTypes(),
                ],
                'require' => 1,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Authorized Role'),
                'name' => 'authorized_role',
                'display' => [
                    'type' => 'select',
                    'data' => Yii::$service->bdminUser->bdminUser->getAuthorizedRoles(),
                ],
                'require' => 1,
            ],
            
            
            [
                'label'  => Yii::$service->page->translate->__('Bank Person Name'),
                'name' => 'bdmin_bank_name',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Bank Name'),
                'name' => 'bdmin_bank',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Bank Account'),
                'name' => 'bdmin_bank_account',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Tax Point'),
                'name' => 'tax_point',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Invoice'),
                'name' => 'invoice',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Authorized Brand'),
                'name' => 'authorized_brand',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
            
            
            [
                'label'  => Yii::$service->page->translate->__('Authorized At'),
                'name' => 'authorized_at',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Aooperationed At'),
                'name' => 'cooperationed_at',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Shipping Date'),
                'name' => 'shipping_date',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Order Date'),
                'name' => 'order_date',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Remark'),
                'name' => 'remark',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
        ];
    }

    /**
     * save article data,  get rewrite url and save to article url key.
     */
    public function save()
    {
    }

    // 批量删除
    public function delete()
    {
    }
    
    
    
    public function accept()
    {
        $ids = [];
        if ($id = CRequest::param($this->_primaryKey)) {
            $ids = [$id];
        } elseif ($ids = CRequest::param($this->_primaryKey.'s')) {
            $ids = explode(',', $ids);
        }
        // 事务处理
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($ids as $applyId) {
                if (!$this->_service->auditAccept($applyId)) {
                    throw new \Exception('distribute apply audit accept fail');
                }
            }
            $innerTransaction->commit();
        } catch (\Exception $e) {
            Yii::$service->helper->errors->add($e->getMessage());
            $innerTransaction->rollBack();
        }
        
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message' => Yii::$service->page->translate->__('Batch Audit Accept Success'),
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode'=>'300',
                'message'=>$errors,
            ]);
            exit;
        }
    }

    public function refuse()
    {
        $ids = [];
        if ($id = CRequest::param($this->_primaryKey)) {
            $ids = [$id];
        } elseif ($ids = CRequest::param($this->_primaryKey.'s')) {
            $ids = explode(',', $ids);
        }
        // 事务处理
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($ids as $applyId) {
                if (!$this->_service->auditRefuse($applyId)) {
                    throw new \Exception('distribute apply audit refuse fail');
                }
            }
            $innerTransaction->commit();
        } catch (\Exception $e) {
            Yii::$service->helper->errors->add($e->getMessage());
            $innerTransaction->rollBack();
        }
        
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message' => Yii::$service->page->translate->__('Batch Audit Refuse Success'),
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode'=>'300',
                'message'=>$errors,
            ]);
            exit;
        }
    }
    
    
}
