<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\services\bdminUser;

use Yii;
use fecshop\services\Service;

/**
 * Order services.
 *
 * @property \fecshop\services\order\Item $item
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Shipping extends Service
{
    // 模板支付类型：卖家支付运费，免邮
    public $type_cost_bdmin = 'bdmin_shipping_cost';
    // 模板支付类型：买家支付运费
    public $type_cost_customer = 'customer_shipping_cost';
    
    /**
     * $storagePrex , $storage , $storagePath 为找到当前的storage而设置的配置参数
     * 可以在配置中更改，更改后，就会通过容器注入的方式修改相应的配置值
     */
    public $storage = 'ShippingMysqldb';   //   ShippingMysqldb | ShippingMongodb 当前的storage，如果在config中配置，那么在初始化的时候会被注入修改

    /**
     * 设置storage的path路径，
     * 如果不设置，则系统使用默认路径
     * 如果设置了路径，则使用自定义的路径
     */
    public $storagePath = 'fecbbc\services\bdminUser\shipping';
    
    protected $_shipping;
    
    public function init()
    {
        parent::init();
        
        $currentService = $this->getStorageService($this);
        $this->_shipping = new $currentService();
    }
    // 动态更改为mongodb model
    public function changeToMongoStorage()
    {
        $this->storage     = 'ShippingMongodb';
        $currentService = $this->getStorageService($this);
        $this->_shipping = new $currentService();
    }
    
    // 动态更改为mongodb model
    public function changeToMysqlStorage()
    {
        $this->storage     = 'ShippingMysqldb';
        $currentService = $this->getStorageService($this);
        $this->_shipping = new $currentService();
    }
    
    
    /**
     * 得到表的id字段。
     */
    public function getPrimaryKey()
    {
        return $this->_shipping->getPrimaryKey();
    }
    
    /**
     * @param $primaryKey | Int
     * @return Object($this->_orderModel)
     * 通过主键值，返回Order Model对象
     */
    public function getByPrimaryKey($primaryKey)
    {
        return $this->_shipping->getByPrimaryKey($primaryKey);
    }
    
    
    
    public function getLabelByPrimaryKey($primaryKey)
    {
        return $this->_shipping->getLabelByPrimaryKey($primaryKey);
    }
    
    
    /**
     * @param $filter|array
     * @return Array;
     *              通过过滤条件，得到coupon的集合。
     *              example filter:
     *              [
     *                  'numPerPage' 	=> 20,
     *                  'pageNum'		=> 1,
     *                  'orderBy'	    => ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     *                  'where'			=> [
     *                      ['>','price',1],
     *                      ['<=','price',10]
     * 			            ['sku' => 'uk10001'],
     * 		            ],
     * 	                'asArray' => true,
     *              ]
     * 根据$filter 搜索参数数组，返回满足条件的订单数据。
     */
    public function coll($filter = '')
    {
        return $this->_shipping->coll($filter);
    }
    
    /**
     * Save the customer info.
     * @param array $param
     * 数据格式如下：
     * ['email' => 'xxx', 'password' => 'xxxx','firstname' => 'xxx','lastname' => 'xxx']
     * @param type | string,  `admin` or `bdmin`
     * @return bool
     * mongodb save system config
     */
    public function save($param)
    {
        return $this->_shipping->save($param);
    }
    
    /**
     * remove Static Block.
     */
    public function remove($ids)
    {
        return $this->_shipping->remove($ids);
    }
    
    
    public function getThemesByBdminUserId($bdmin_user_id) 
    {
        return $this->_shipping->getThemesByBdminUserId($bdmin_user_id) ;
    }
    
    
    public function getBdminDefaultShippingMethod($bdmin_user_id) 
    {
        return $this->_shipping->getBdminDefaultShippingMethod($bdmin_user_id);
    }
    
    
    
    
}