<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appadmin\modules\Config\block\appserverhome;

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
    public $_key = 'appserver_home';
    public $_type;
    protected $_attrArr = [
        'start_page_img_url',
        'start_page_title',
        'start_page_remark',
        
        'home_bigimg_imgurl_1',
        'home_bigimg_linkurl_1',
        'home_bigimg_imgurl_2',
        'home_bigimg_linkurl_2',
        
        'home_bannerimg_imgurl_1',
        'home_bannerimg_linkurl_1',
        'home_bannerimg_imgurl_2',
        'home_bannerimg_linkurl_2',
        'home_bannerimg_imgurl_3',
        'home_bannerimg_linkurl_3',
        'home_bannerimg_imgurl_4',
        'home_bannerimg_linkurl_4',
        /*
        'home_tip_imgurl_1',
        'home_tip_linkurl_1',
        'home_tip_title_1',
        'home_tip_imgurl_2',
        'home_tip_linkurl_2',
        'home_tip_title_2',
        'home_tip_imgurl_3',
        'home_tip_linkurl_3',
        'home_tip_title_3',
        'home_tip_imgurl_4',
        'home_tip_linkurl_4',
        'home_tip_title_4',
        */
        
        'home_product_skus',
    ];
    
    public function init()
    {
        // 需要配置
        $this->_saveUrl = CUrl::getUrl('config/appserverhome/managersave');
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
        $deleteStatus = Yii::$service->customer->getStatusDeleted();
        $activeStatus = Yii::$service->customer->getStatusActive();
        
        return [
            // 需要配置
            
            [
                'label'  => Yii::$service->page->translate->__('Start Page Title'),
                'name' => 'start_page_title',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '微信小程序启动页的标题，默认值：Fecmall',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Start Page Remark'),
                'name' => 'start_page_remark',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '微信小程序启动页的备注文字，默认值：Wx Micro Program',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Start Page Img'),
                'name' => 'start_page_img_url',
                'display' => [
                    'type' => 'inputImage',
                ],
                'remark' => '微信小程序启动页的大图',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Home Big Link Url 1'),
                'name' => 'home_bigimg_linkurl_1',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '微信小程序首页走马灯大图1-点击对应的链接 , 填写微信小程序的url，譬如：/pages/goods-detail/goods-detail?id=115781',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Home Big Img 1'),
                'name' => 'home_bigimg_imgurl_1',
                'display' => [
                    'type' => 'inputImage',
                ],
                'remark' => '微信小程序首页走马灯大图1-图片',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Home Big Link Url 2'),
                'name' => 'home_bigimg_linkurl_2',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '微信小程序首页走马灯大图2-点击对应的链接, 填写微信小程序的url，譬如：/pages/goods-detail/goods-detail?id=115781',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Home Big Img 2'),
                'name' => 'home_bigimg_imgurl_2',
                'display' => [
                    'type' => 'inputImage',
                ],
                'remark' => '微信小程序首页走马灯大图2-图片',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Home Banner Link Url 1'),
                'name' => 'home_bannerimg_linkurl_1',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '微信小程序首页的Banner-1点击对应的链接, 填写微信小程序的url，譬如：/pages/goods-detail/goods-detail?id=115781',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Home Banner Img 1'),
                'name' => 'home_bannerimg_imgurl_1',
                'display' => [
                    'type' => 'inputImage',
                ],
                'remark' => '微信小程序首页首页的Banner-1-图片',
            ],
            
             [
                'label'  => Yii::$service->page->translate->__('Home Banner Link Url 2'),
                'name' => 'home_bannerimg_linkurl_2',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '微信小程序首页的Banner-2点击对应的链接, 填写微信小程序的url，譬如：/pages/goods-detail/goods-detail?id=115781',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Home Banner Img 2'),
                'name' => 'home_bannerimg_imgurl_2',
                'display' => [
                    'type' => 'inputImage',
                ],
                'remark' => '微信小程序首页首页的Banner-2-图片',
            ],
            
            
            [
                'label'  => Yii::$service->page->translate->__('Home Banner Link Url 3'),
                'name' => 'home_bannerimg_linkurl_3',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '微信小程序首页的Banner-3点击对应的链接, 填写微信小程序的url，譬如：/pages/goods-detail/goods-detail?id=115781',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Home Banner Img 3'),
                'name' => 'home_bannerimg_imgurl_3',
                'display' => [
                    'type' => 'inputImage',
                ],
                'remark' => '微信小程序首页首页的Banner-3-图片',
            ],
            
            
            [
                'label'  => Yii::$service->page->translate->__('Home Banner Link Url 4'),
                'name' => 'home_bannerimg_linkurl_4',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '微信小程序首页的Banner-4点击对应的链接, 填写微信小程序的url，譬如：/pages/goods-detail/goods-detail?id=115781',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Home Banner Img 4'),
                'name' => 'home_bannerimg_imgurl_4',
                'display' => [
                    'type' => 'inputImage',
                ],
                'remark' => '微信小程序首页首页的Banner-4-图片',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Home Product Skus'),
                'name' => 'home_product_skus',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '首页的产品列表，这里填写sku，多个sku用英文逗号, 隔开',
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