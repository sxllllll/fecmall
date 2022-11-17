<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\services;

use Yii;
use yii\base\InvalidConfigException;

/**
 * Shipping services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Shipping extends \fecshop\services\Shipping
{
    public $shippingConfig;

    public $shippingCsvDir;

    // 存放运费csv表格的文件路径。
    // 体积重系数，新体积重计算 = 长(cm) * 宽(cm) * 高(cm) / 体积重系数 ， 因此一立方的体积的商品，体积重为200Kg
    public $volumeWeightCoefficient = 5000;

    // 在init函数初始化，将shipping method的配置值加载到这个变量
    protected $_shipping_methods ;

    // 是否缓存shipping method 配置数据（因为csv部分需要读取csv文件，稍微耗时一些，可以选择放到缓存里面）
    protected $_cache_shipping_methods_config = 0;

    // 可用的shipping method，计算出来的值保存到这个类变量中。
    protected $_available_shipping;

    // 缓存key
    const  CACHE_SHIPPING_METHODS_CONFIG = 'cache_shipping_methods_config';

    /**
     * 1.从配置中取出来所有的shipping method
     * 2.对于公式为csv的shipping method，将对应的csv文件中的配置信息取出来
     * 3.如果开启了数据缓存，那么直接从缓存中读取。
     * 最后合并成一个整体的配置文件。赋值与-> $this->_shipping_methods
     */
    public function init()
    {
        parent::init();
    }
    
    /**
     * @param $bdmin_user_id
     */
    //public function getAvailableShippingMethods($bdmin_user_id)
    //{
    //    return $this->getThemesByBdminUserId($bdmin_user_id);
    //}
    
     /**
     * @proeprty $shipping_method 货运方式的key
     * @proeprty $weight 产品的总重量
     * @proeprty $country 货运国家
     * @return array 当前货币下的运费的金额。
     * 此处只做运费计算，不管该shipping是否可用。
     * 结果数据示例：
     * [
     *  'currCost'   => 66,
     *  'baseCost'   => 11,
     * ]
     */
    public function getShippingCost2($bdmin_user_id, $product_weight, $selectedShippingMethod)
    {
        
        if (!$selectedShippingMethod) {
            $selectedShippingMethod = Yii::$service->bdminUser->shipping->getBdminDefaultShippingMethod($bdmin_user_id);
        }
        $filter = [
            'where' => [
                ['bdmin_user_id' => $bdmin_user_id]
            ],
            'asArray' => true,
            'fetchAll' => true,
        ];
        $data = Yii::$service->bdminUser->shipping->coll($filter);
        $coll = $data['coll'];
        $available_methods = [];
        $has = false;
        $baseShippingCost = 0 ;
        $shippingCost = 0 ;
        $selectedShippingMethodLabel = '';
        $shippingPrimaryKey = Yii::$service->bdminUser->shipping->getPrimaryKey();
        $selectedShippingMethodIndex = 0;
        if (is_array($coll)) {
            $i=0;
            foreach ($coll as $one) {
                $_id = (string)$one[$shippingPrimaryKey];
                // 计算运费
                $first_weight = $one['first_weight'];
                $first_cost = $one['first_cost'];
                $next_weight = $one['next_weight'];
                $next_cost = $one['next_cost'];
                $s_baseShippingCost = 0;
                $s_shippingCost = 0;
                // 如果不是免邮，则进行计算
                if ($one['type'] != Yii::$service->bdminUser->shipping->type_cost_bdmin) {
                    
                    $s_baseShippingCost = $this->computeShippingCost($product_weight, $first_weight, $first_cost, $next_weight, $next_cost);
                    $s_shippingCost = Yii::$service->page->currency->getCurrentCurrencyPrice($s_baseShippingCost);
                }
                
                if ($selectedShippingMethod == $_id) {
                    $has = true;
                    $selectedShippingMethodIndex = $i;
                    $baseShippingCost = $s_baseShippingCost ;
                    $shippingCost = $s_shippingCost ;
                    $selectedShippingMethodLabel = Yii::$service->store->getStoreAttrVal($one['label'], 'label');
                }
                $available_methods[$i] = [
                    'id' => $_id,
                    'label'  => Yii::$service->store->getStoreAttrVal($one['label'], 'label'),
                    'selected' => $has ? true : false,
                    'base_cost' => $s_baseShippingCost,
                    'current_cost' => $s_shippingCost,
                ];
                $has = false;
                $i++;
            }
        }
        return [
            'currCost' => $shippingCost,
            'baseCost' => $baseShippingCost,
            'shipping_method' => $selectedShippingMethod,
            'shipping_method_index' => $selectedShippingMethodIndex,
            'shipping_method_label' => $selectedShippingMethodLabel,
            'shippings'  => $available_methods,
        ];
        
    }
    // 计算运费。
    public function computeShippingCost($product_weight, $first_weight, $first_cost, $next_weight, $next_cost, $s=0)
    {
        if (!$first_weight || !$next_weight) {
            return 0;
        }
        if ($s == 0) {
            //$product_weight = (float)$product_weight;
            if ($product_weight <= $first_weight) {
                return $first_cost;
            }
        }
        $s++;
        if ($product_weight <= $first_weight + $next_weight) {
            
            return $first_cost + $next_cost;
        } else {
            $first_weight = $first_weight + $next_weight;
            $first_cost = $first_cost + $next_cost;
            
            return $this->computeShippingCost($product_weight, $first_weight, $first_cost, $next_weight, $next_cost, $s);
        }
    }
    
    /**
     * @param $long | Float ,长度，单位cm
     * @param $width | Float ,宽度，单位cm
     * @param $high | Float ,高度，单位cm
     * @return 体积体积，单位cm
     */
    public function getVolume($long, $width, $high)
    {
        return Yii::$service->helper->format->numberFormat($long * $width * $high);
    }
    
    /**
     * @param $shipping_method | String
     * @return 得到货运方式的名字
     */
    public function getShippingLabelByMethod($shipping_method)
    {
        $s = $this->_shipping_methods[$shipping_method];
        return isset($s['label']) ? $s['label'] : '';
    }
    
}
