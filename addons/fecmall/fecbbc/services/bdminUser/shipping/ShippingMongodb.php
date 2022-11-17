<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\services\bdminUser\shipping;

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
class ShippingMongodb extends Service
{
    
    protected $_modelName = '\fecbbc\models\mongodb\bdminUser\ShippingTheme';

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
        return '_id';
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
    
    public function getLabelByPrimaryKey($primaryKey)
    {
        $shippingM = $this->getByPrimaryKey($primaryKey);
        if (isset($shippingM['label']) && $shippingM['label']) {
            
            return Yii::$service->store->getStoreAttrVal($shippingM['label'], 'label');
        }
        
        return '';
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
        $primaryKey = $this->getPrimaryKey();
        $primaryVal = isset($param[$primaryKey]) ? $param[$primaryKey] : '';
        $model = $this->_model;
        $model->attributes = $param;
        // 验证数据。
        if (!$model->validate()) {
            $errors = $model->errors;
            Yii::$service->helper->errors->addByModelErrors($errors);
            
            return false;
        }
            
        if ($primaryVal) {
            $model = $this->getByPrimaryKey($primaryVal);
            if (!$model[$primaryKey]) {
                Yii::$service->helper->errors->add('Shipping Theme {primaryKey} is not exist', ['primaryKey' => $this->getPrimaryKey()]);

                return false;
            } 
        } else {
            $model = new $this->_modelName();
            $model->created_at = time();
            $primaryVal = new \MongoDB\BSON\ObjectId();
            $model->{$this->getPrimaryKey()} = $primaryVal;
        }
        
        $model->updated_at = time();
        unset($param['_id']);
        $saveStatus = Yii::$service->helper->ar->save($model, $param);
        if ($saveStatus) {
            
            return true;
        } else {
            $errors = $model->errors;
            Yii::$service->helper->errors->addByModelErrors($errors);

            return false;
        }
    }
    
    /**
     * remove Static Block.
     */
    public function remove($ids)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $model = $this->_model->findOne($id);
                $model->delete();
            }
        } else {
            $id = $ids;
            $model = $this->_model->findOne($id);
            $model->delete();
        }

        return true;
    }
    
    public function getThemesByBdminUserId($bdmin_user_id) 
    {
        $filter = [
            'where' => [
                ['bdmin_user_id' => $bdmin_user_id]
            ],
            'fetchAll' => true,
        ];
        $data = $this->coll($filter);
        $coll = $data['coll'];
        $arr = [];
        if (is_array($coll)) {
            foreach ($coll as $one) {
                $_id = (string)$one['_id'];
                $arr[$_id] =  Yii::$service->fecshoplang->getDefaultLangAttrVal($one['label'], 'label');
            }
        }
        
        return $arr;
    }
    
    public function getBdminDefaultShippingMethod($bdmin_user_id)
    {
        $one = $this->_model->findOne(['bdmin_user_id' => $bdmin_user_id]);
        if (isset($one[$this->getPrimaryKey()]) && $one[$this->getPrimaryKey()]) {
            return (string)$one[$this->getPrimaryKey()];
        }
        return null;
    }
    
    
    
    
    
    
}