<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appadmin\modules\Config\block\fecbbc;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use Yii;

/**
 * block cms\staticblock.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manager extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    public $_saveUrl;
    // 需要配置
    public $_key = 'fecbbc_info';
    public $_type;
    protected $_attrArr = [
        'hot_searchs',
        'hot_product_skus',
        'chaoliu_s_skus_1',
        'chaoliu_s_skus_2',
        'chaoliu_x_skus_1',
        'chaoliu_x_skus_2',
        'new_product_skus',
        
        'kuaidiniao_business_id',
        'kuaidiniao_app_key',
        
        
    ];
    
    public function init()
    {
        // 需要配置
        $this->_saveUrl = CUrl::getUrl('config/fecbbc/managersave');
        $this->_editFormData = 'editFormData';
        $this->setService();
        $this->_param = CRequest::param();
        $this->_one = $this->_service->getByKey([
            'key' => $this->_key,
        ]);
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
        $this->_service = Yii::$service->storeBaseConfig;
    }
    public function getEditArr()
    {
        
        return [
            [
                'label' => Yii::$service->page->translate->__('Hot Searchs'),
                'name'  => 'hot_searchs',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
                'remark' =>  'PC端，顶部：热门搜索词，多个词用英文逗号隔开'
            ],
            [
                'label' => Yii::$service->page->translate->__('Hot Product Skus'),
                'name'  => 'hot_product_skus',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
                'remark' =>  'PC端，首页：热门产品sku列表，多个sku用逗号隔开，填写12个sku'
            ],
            [
                'label' => Yii::$service->page->translate->__('Chaoliu Top Sku1'),
                'name'  => 'chaoliu_s_skus_1',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
                'remark' =>  'PC端，首页：潮流上装sku列表1，填写6个sku'
            ],
            [
                'label' => Yii::$service->page->translate->__('Chaoliu Top Sku2'),
                'name'  => 'chaoliu_s_skus_2',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
                'remark' =>  'PC端，首页：潮流上装sku列表2，填写5个sku'
            ],
            
            
            [
                'label' => Yii::$service->page->translate->__('Chaoliu Bottom Skus1'),
                'name'  => 'chaoliu_x_skus_1',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
                'remark' =>  'PC端，首页：潮流下装sku列表1，填写6个sku'
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Chaoliu Bottom Skus2'),
                'name'  => 'chaoliu_x_skus_2',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
                'remark' =>  'PC端，首页：潮流下装sku列表2，填写5个sku'
            ],
            
            
             [
                'label' => Yii::$service->page->translate->__('New Product Skus'),
                'name'  => 'new_product_skus',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
                'remark' =>  'PC端，首页：新产品列表，填写sku数，5的倍数'
            ],
            
            
            [
                'label' => Yii::$service->page->translate->__('KuaiDiNiaoBusinessId'),
                'name'  => 'kuaidiniao_business_id',
                'display' => [
                    'type' => 'inputString',
                ],
                //'require' => 1,
                'remark' =>  '快递鸟api-EBusinessID'
            ],
            [
                'label' => Yii::$service->page->translate->__('KuaiDiNiaoAppKey'),
                'name'  => 'kuaidiniao_app_key',
                'display' => [
                    'type' => 'inputString',
                ],
                //'require' => 1,
                'remark' =>  '快递鸟api-AppKey'
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
                $attrVals[$attr] = $val;
            } else {
                $param[$attr] = $val;
            }
        }
        $param['value'] = $attrVals;
        $param['key'] = $this->_key;
        
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