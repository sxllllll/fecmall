<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\services\order\processlog;

//use fecshop\models\mysqldb\order\Item as MyOrderItem;
use fecshop\services\Service;
use Yii;

/**
 * Cart items services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProcessLogMysqldb extends \fecshop\services\Service
{
    
    
    protected $_modelName = '\fecbbc\models\mysqldb\order\ProcessLog';

    protected $_model;
    
    
    public function init()
    {
        parent::init();
        list($this->_modelName, $this->_model) = \Yii::mapGet($this->_modelName);
    }
    /**
     * 得到order 表的id字段。
     */
    public function getPrimaryKey()
    {
        return 'id';
    }
    
    /**
     * @param $primaryKey | Int
     * @return Object($this->_orderModel)
     * 通过主键值，返回Order Model对象
     */
    public function getByPrimaryKey($primaryKey)
    {
        $one = $this->_model->findOne($primaryKey);
        $primaryKey = $this->getPrimaryKey();
        if ($one[$primaryKey]) {
            return $one;
        } else {
            return new $this->_modelName();
        }
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
        $query  = $this->_model->find();
        $query  = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        $coll   = $query->all();
        
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }
    
    /**
     *
     * customer 添加order log
     */
    public function customerAdd($order, $type, $remark='') {
        if (!Yii::$service->order->processLog->isEnable()) {
            
            return false;
        }
        if (!Yii::$app->user->isGuest) {
            $param = $this->getAddParam($order, $type, $remark);
            $identity = Yii::$app->user->identity;
            $customer_id = $identity->id;
            $param['customer_id'] = $customer_id;
            
            return $this->add($param, $type);
        }
        
        return false;
    }
    /**
     *
     * bdmin 添加order log
     */
    public function bdminAdd($order, $type, $remark='') {
        if (!Yii::$service->order->processLog->isEnable()) {
            
            return false;
        }
        $param = [];
        if (!Yii::$app->user->isGuest) {
            $param = $this->getAddParam($order, $type, $remark);
            $identity = Yii::$app->user->identity;
            $bdmin_user_id = $identity->id;
            $param['bdmin_user_id'] = $bdmin_user_id;
            
            return $this->add($param, $type);
        }
        
        return false;
    }
    /**
     *
     * admin 添加order log
     */
    public function adminAdd($order, $type, $remark='') {
        if (!Yii::$service->order->processLog->isEnable()) {
            
            return false;
        }
        if (!Yii::$app->user->isGuest) {
            $param = $this->getAddParam($order, $type, $remark);
            $identity = Yii::$app->user->identity;
            $admin_user_id = $identity->id;
            $param['admin_user_id'] = $admin_user_id;
            
            return $this->add($param, $type);
        }
        
        return false;
    }
    
    /**
     *
     * console 添加order log
     */
    public function consoleAdd($order, $type, $remark='') {
        if (!Yii::$service->order->processLog->isEnable()) {
            
            return false;
        }
        $param = $this->getAddParam($order, $type, $remark);
        
        return $this->add($param, $type);
    }
    
    public function getAddParam($order, $type, $remark='') {
        $param = [];
        $param['remark'] = $remark;
        $param['order_id'] = $order['order_id'];
        $param['increment_id'] = $order['increment_id'];
        $param['customer_id'] = $order['customer_id'];
        $param['bdmin_user_id'] = $order['bdmin_user_id'];
        return $param;
    }
    
    public function add($param, $type)
    {
        if (!Yii::$service->order->processLog->isEnable()) {
            return false;
        }
        $model = new $this->_modelName();
        $model->created_at = time();
        $model->updated_at = time();
        $model->order_id = $param['order_id'];
        $model->increment_id = $param['increment_id'];
        !$param['customer_id'] || $model->customer_id = $param['customer_id'];
        !$param['bdmin_user_id'] || $model->bdmin_user_id = $param['bdmin_user_id'];
        !$param['admin_user_id'] || $model->admin_user_id = $param['admin_user_id'];
        !$param['remark'] || $model->remark = $param['remark'];
        $model->type = $type;
        
        return $model->save();
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
    public function save($param, $type)
    {
        
    }
      
}
