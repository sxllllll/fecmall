<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\app\appbdmin\modules\Sales\block\coupon;

use fec\helpers\CRequest;
use fec\helpers\CUrl;
use fecbbc\app\appbdmin\interfaces\base\AppbdminbaseBlockEditInterface;
use fecbbc\app\appbdmin\modules\AppbdminbaseBlockEdit;
use Yii;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit extends AppbdminbaseBlockEdit implements AppbdminbaseBlockEditInterface
{
    public $_saveUrl;
    
    public function init()
    {
        $this->_saveUrl = CUrl::getUrl('sales/coupon/managereditsave');
        parent::init();
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        return [
            'editBar'       => $this->getEditBar(),
            'textareas'    => $this->_textareas,
            'lang_attr'    => $this->_lang_attr,
            'saveUrl'       => $this->_saveUrl,
            'typeOptions' => $this->getTypeOptions(),
            'coupon_id'    => $this->_one['id'],
            'condition_product_skus'    => $this->_one['condition_product_skus'] ? implode(',', $this->_one['condition_product_skus']) : '' ,
        ];
    }

    public function setService()
    {
        $this->_service = Yii::$service->coupon;
    }

    public function getEditArr()
    {
        return [
            
            [
                'label'  => Yii::$service->page->translate->__('Coupon Name'),
                'name' => 'name',
                'display'  => [
                    'type' => 'inputString',
                    
                ],
                'require' => 1,
                 
            ],
            [
                'label'  => Yii::$service->page->translate->__('Coupon Code'),
                'name' => 'code',
                'display'  => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Coupon Total Count'),
                'name' => 'total_count',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Discount Cost'),
                'name' => 'discount_cost',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Use Condition'),
                'name' => 'use_condition',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
           
            [
                'label'  => Yii::$service->page->translate->__('Assign Begin At'),
                'name' => 'assign_begin_at',
                'display' => [
                    'type' => 'inputDate',
                ],
                'require' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Assign End At'),
                'name' => 'assign_end_at',
                'display' => [
                    'type' => 'inputDate',
                ],
                'require' => 1,
            ],
            
           
            [
                'label'  => Yii::$service->page->translate->__('Remark'),
                'name' => 'remark',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            
            
            [
                'label' => Yii::$service->page->translate->__('Is Show In Product'),
                'name'  => 'is_show_in_product_page',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        1 => Yii::$service->page->translate->__('Enable'),
                        2 => Yii::$service->page->translate->__('Disable'),
                    ],
                ],
                'require' => 1,
                'default' => 1,
            ],
        ];
    }
    
    public function getTypeOptions()
    {
        $str = '';
        $this->_one['condition_product_type'];
        $str .= '<option '.($this->_one['condition_product_type'] == Yii::$service->coupon->condition_product_type_all ? 'selected="selected"' : '').' value="'.Yii::$service->coupon->condition_product_type_all.'">'.Yii::$service->page->translate->__('All Product').'</option>';
        $str .= '<option '.($this->_one['condition_product_type'] == Yii::$service->coupon->condition_product_type_sku ? 'selected="selected"' : '').' value="'.Yii::$service->coupon->condition_product_type_sku.'">'.Yii::$service->page->translate->__('Specify Sku').'</option>';
        $str .= '<option '.($this->_one['condition_product_type'] == Yii::$service->coupon->condition_product_type_category_id ? 'selected="selected"' : '').'  value="'.Yii::$service->coupon->condition_product_type_category_id.'">'.Yii::$service->page->translate->__('Specify Category').'</option>';
        
        return $str;
    }

    /**
     * save article data,  get rewrite url and save to article url key.
     */
    public function save()
    {
        $request_param = CRequest::param();
        $this->_param = $request_param[$this->_editFormData];
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        $this->_param['assign_begin_at'] = strtotime($this->_param['assign_begin_at']);
        $this->_param['assign_end_at'] = strtotime($this->_param['assign_end_at']);
        $condition_product_type = $this->_param['condition_product_type'];
        
        $condition_product_skus = Yii::$app->request->post('condition_product_skus');
        $condition_product_category_ids = Yii::$app->request->post('condition_product_category_ids');
        if ($condition_product_skus) {
            $condition_product_skus = explode(',', trim($condition_product_skus));
        }
         if ($condition_product_category_ids) {
            $condition_product_category_ids = explode(',', trim($condition_product_category_ids));
        }
        if ($condition_product_type == Yii::$service->coupon->condition_product_type_all) {
            $this->_param['condition_product_skus'] = '';
            $this->_param['condition_product_category_ids'] = ''; 
        } else if ($condition_product_type == Yii::$service->coupon->condition_product_type_sku) {
            $this->_param['condition_product_skus'] = $condition_product_skus;
            $this->_param['condition_product_category_ids'] = ''; 
        } else if ($condition_product_type == Yii::$service->coupon->condition_product_type_category_id) {
            $this->_param['condition_product_skus'] = '';
            $this->_param['condition_product_category_ids'] = $condition_product_category_ids; 
        }
        $this->_param['bdmin_user_id'] = Yii::$app->user->identity->id;
        $this->_service->save($this->_param);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message'    => Yii::$service->page->translate->__('Save Success'),
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => $errors,
            ]);
            exit;
        }
    }
    
    // 批量删除
    public function delete()
    {
        $ids = '';
        if ($id = CRequest::param($this->_primaryKey)) {
            $ids = $id;
        } elseif ($ids = CRequest::param($this->_primaryKey.'s')) {
            $ids = explode(',', $ids);
        }
        $this->_service->remove($ids);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message'    => Yii::$service->page->translate->__('Remove Success'),
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => $errors,
            ]);
            exit;
        }
    }
}
