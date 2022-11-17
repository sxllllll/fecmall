<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appbdmin\modules\Sales\block\bdminconfig;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecbbc\app\appbdmin\interfaces\base\AppbdminbaseBlockEditInterface;
use fecbbc\app\appbdmin\modules\AppbdminbaseBlockEdit;
use Yii;

/**
 * block cms\staticblock.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manager extends AppbdminbaseBlockEdit implements AppbdminbaseBlockEditInterface
{
    public $_saveUrl;
    // 需要配置
    public $_key = 'bdmin_config';
    public $_type;
    
    protected $_bdmin_user_id;
    protected $_attrArr = [
        'home_meta_title',
        'home_meta_keywords',
        'home_meta_description',
        'home_top_banner',
        'home_h5_top_banner',
        'bdmin_logo',
    ];
    
    public function init()
    {
        $identity = Yii::$app->user->identity;
        $this->_bdmin_user_id = $identity['id'];
         // 需要配置
        $this->_saveUrl = CUrl::getUrl('sales/bdminconfig/managersave');
        $this->_editFormData = 'editFormData';
        $this->setService();
        $this->_param = CRequest::param();
        $this->_one = $this->_service->getByKey($this->_bdmin_user_id, $this->_key);
        if ($this->_one['value']) {
            $this->_one['value'] = unserialize($this->_one['value']);
        }
    }
    
    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        $id = ''; 
        if (isset($this->_one['id'])) {
           $id = $this->_one['id'];
        } 
        return [
            'id'            =>   $id, 
            'editBar'      => $this->getEditBar(),
            'textareas'   => $this->_textareas,
            'lang_attr'   => $this->_lang_attr,
            'saveUrl'     => $this->_saveUrl,
        ];
    }
    public function setService()
    {
        $this->_service = Yii::$service->storeBdminConfig;
    }
    public function getEditArr()
    {
        $deleteStatus = Yii::$service->customer->getStatusDeleted();
        $activeStatus = Yii::$service->customer->getStatusActive();
        
        return [
            [
                'label'  => Yii::$service->page->translate->__('Bdmin Logo'),
                'name' => 'bdmin_logo',
                'display' => [
                    'type' => 'inputImage',
                ],
                'remark' => '',
            ],
            
            // 需要配置
            [
                'label'  => Yii::$service->page->translate->__('Home Meta Title'),
                'name' => 'home_meta_title',
                'display' => [
                    'type' => 'inputString',
                    'lang' => true,
                ],
                'remark' => '',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Home Meta Keywords'),
                'name' => 'home_meta_keywords',
                'display' => [
                    'type' => 'inputString',
                    'lang' => true,
                ],
                'remark' => '',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Home Meta Description'),
                'name' => 'home_meta_description',
                'display' => [
                    'type' => 'inputString',
                    'lang' => true,
                ],
                'remark' => '',
            ],
           
            
            [
                'label' => Yii::$service->page->translate->__('Home Top Banner') ,
                'name'  => 'home_top_banner',
                'display' => [
                    'type' => 'textarea',
                    'rows'    => 14,
                    'cols'    => 100,
                ],
                'require' => 0,
            ],
            
            
            [
                'label' => Yii::$service->page->translate->__('Home H5 Top Banner') ,
                'name'  => 'home_h5_top_banner',
                'display' => [
                    'type' => 'textarea',
                    'rows'    => 14,
                    'cols'    => 100,
                ],
                'require' => 0,
            ],
            
            
        ];
    }
    
    public function getArrParam(){
        $request_param = CRequest::param();
        $this->_param = $request_param[$this->_editFormData];
        $param = [];
        $attrVals = [];
        foreach($this->_param as $attr => $val) {
            if (in_array($attr, $this->_attrArr)) {
                // 过滤js代码
                if ($attr == 'home_top_banner') {
                    $val = \yii\helpers\HtmlPurifier::process($val);
                }
                $attrVals[$attr] = $val;
            } else {
                $param[$attr] = $val;
            }
        }
        $param['value'] = $attrVals;
        $param['key'] = $this->_key;
        $param['bdmin_user_id'] = $this->_bdmin_user_id;
        
        return $param;
    }
    
    /**
     * save article data,  get rewrite url and save to article url key.
     */
    public function save()
    {
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        // 设置 bdmin_user_id 为 当前的user_id
        $this->_service->saveConfig($this->getArrParam());
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
    
    
    
    public function getVal($name, $column){
        if (is_object($this->_one) && property_exists($this->_one, $name) && $this->_one[$name]) {
            
            return $this->_one[$name];
        }
        $content = $this->_one['value'];
        if (is_array($content) && !empty($content) && isset($content[$name])) {
            
            return $content[$name];
        }
        
        return '';
    }
}