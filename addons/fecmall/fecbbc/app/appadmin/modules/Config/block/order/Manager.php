<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appadmin\modules\Config\block\order;

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
class Manager extends \fecshop\app\appadmin\modules\Config\block\order\Manager
{
    public $_saveUrl;
    // 需要配置
    public $_key = 'order';
    public $_type;
    protected $_attrArr = [
        'increment_id',
        'requiredAddressAttr',
        'orderProductSaleInMonths',
        'minuteBeforeThatReturnPendingStock',
        'orderCountThatReturnPendingStock',
        'orderRemarkStrMaxLen',
        'guestOrder',
        
        
        //'order_auto_receive_max_day',
        
        'delay_receive_order_max_days',  // 延迟订单收货时间: 最大天数
        'delay_receive_order_days_per_time', // 延迟订单收货时间: 单次操作的天数，也就是用户在前端点击延迟收货后, 一次性延迟的天数
        /**
          * 延迟订单收货时间: 订单收货到期日期 - 当前日期  <= x 天，  可以触发延迟收货操作，譬如
          * 譬如这里设置为3，当日期为3天后就要被系统自动化自动设置为收货的时候（3天后就要被强制收货），
          * 这个时候用户可以在前端账户中心订单列表，进行延迟收货操作。
          */
        'delay_receive_order_trigger_days',
        // 订单收货最大间隔默认时间：订单发货后，超过x天，而且用户没有进行延迟订单收货操作，系统将自动将订单设置为订单已收货。
        'order_default_max_recevie_day',
        
    ];
    
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = CUrl::getUrl('config/order/managersave');
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
                'label' => Yii::$service->page->translate->__('Order IncrementId Format'),
                'name'  => 'increment_id',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '订单编号格式，目前只支持数字格式，譬如：1100000000'
            ],
            [
                'label'  => Yii::$service->page->translate->__('requiredAddressAttr'),
                'name' => 'requiredAddressAttr',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '订单必填字段，多个字段用英文逗号隔开，中间不要有空格',
            ],
            [
                'label'  => Yii::$service->page->translate->__('orderProductSaleInMonths'),
                'name' => 'orderProductSaleInMonths',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '计算商品销量的订单时间(月)范围（将最近x月内的订单产品销售个数累加销量值，0代表所有订单，设置后需要执行shell脚本）',
            ],
            [
                'label'  => Yii::$service->page->translate->__('minuteBeforeThatReturnPendingStock'),
                'name' => 'minuteBeforeThatReturnPendingStock',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '处理多少分钟后，支付状态为pending的订单，归还库存。',
            ],
            [
                'label'  => Yii::$service->page->translate->__('orderCountThatReturnPendingStock'),
                'name' => 'orderCountThatReturnPendingStock',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '支付状态为pending的订单，归还库存脚本，一次性处理多少个pending订单。',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('orderRemarkStrMaxLen'),
                'name' => 'orderRemarkStrMaxLen',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '订单备注字符的最大数',
            ],
            [
                'label' => Yii::$service->page->translate->__('guestOrder'),
                'name'  => 'guestOrder',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '是否支持游客下单, 开启后，Appserver(Api)入口不生效'
            ],
            /*
            [
                'label' => Yii::$service->page->translate->__('OrderAutoReveiveMaxDay'),
                'name'  => 'order_auto_receive_max_day',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
                'remark' =>  '订单发货后，X天后，脚本自动改成已收货'
            ],
            */
            [
                'label' => Yii::$service->page->translate->__('DelayReceiveOrderMaxDays'),
                'name'  => 'delay_receive_order_max_days',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
                'remark' =>  '延迟订单收货时间: 最大天数'
            ],
            [
                'label' => Yii::$service->page->translate->__('DelayReceiveOrderDaysPerTime'),
                'name'  => 'delay_receive_order_days_per_time',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
                'remark' =>  '延迟订单收货时间: 单次操作的天数，也就是用户在前端点击延迟收货后, 一次性延迟的天数'
            ],
            
            /**
              * 延迟订单收货时间: 订单收货到期日期 - 当前日期  <= x 天，  可以触发延迟收货操作，譬如
              * 譬如这里设置为3，当日期为3天后就要被系统自动化自动设置为收货的时候（3天后就要被强制收货），
              * 这个时候用户可以在前端账户中心订单列表，进行延迟收货操作。
              */
            [
                'label' => Yii::$service->page->translate->__('DelayReceiveOrderTriggerDays'),
                'name'  => 'delay_receive_order_trigger_days',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
                'remark' =>  '延迟订单收货时间: 订单收货到期日期 - 当前日期  <= x 天，  可以触发延迟收货操作（用户操作）'
            ],
            [
                'label' => Yii::$service->page->translate->__('OrderDefaultMaxRecevieDay'),
                'name'  => 'order_default_max_recevie_day',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
                'remark' =>  '订单收货最大间隔默认时间：订单发货后，超过x天，而且用户没有进行延迟订单收货操作，系统将自动将订单设置为订单已收货。'
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