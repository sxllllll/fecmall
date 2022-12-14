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

/**
 * Cart services. 购物车service， 执行购物车部分对应的方法。
 *
 * @property \fecshop\services\cart\Coupon $coupon coupon sub-service of cart
 * @property \fecshop\services\cart\Info $info info sub-service of cart
 * @property \fecshop\services\cart\Quote $quote quote sub-service of cart
 * @property \fecshop\services\cart\QuoteItem $quoteItem quoteItem sub-service of cart
 *
 * @method getCartInfo($activeProduct, $shippingMethod = '', $country = '', $region = '*') see [[\fecshop\services\Cart::getCartInfo()]]
 * @method mergeCartAfterUserLogin() see [[\fecshop\services\Cart::mergeCartAfterUserLogin()]]
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class StoreBdminConfig extends \fecshop\services\Service
{
    
    public $numPerPage = 20;

    protected $_modelName = '\fecbbc\models\mysqldb\StoreBdminConfig';

    protected $_model;
    
    //protected $_serilizeAttr = [
    //    "service_db",
    //];
    
    public function init()
    {
        parent::init();
        list($this->_modelName, $this->_model) = Yii::mapGet($this->_modelName);
    }
    
    public function getPrimaryKey()
    {
        return 'id';
    }

    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            $one = $this->_model->findOne($primaryKey);
            
            return $one;
        } else {
            return new $this->_modelName();
        }
    }
    
    public function getByKey($bdmin_user_id, $key)
    {
        if ($key) {
            $one = $this->_model->findOne(['key' => $key, 'bdmin_user_id' => $bdmin_user_id]);
            
            return $one;
        }
        
        return null;
    }
    

    /*
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
            'where'			=> [
                ['>','price',1],
                ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     */
    public function coll($filter = '')
    {
        $query = $this->_model->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        $coll = $query->all();
        //if (!empty($coll)) {
           // foreach ($coll as $k => $one) {
                //if (in_array($one['key'], $this->_serilizeAttr)) {
                //    $one['key'] = unserialize($one)
                //}
                
                //foreach ($this->_lang_attr as $attr) {
                //    $one[$attr] = $one[$attr] ? unserialize($one[$attr]) : '';
                //}
                $coll[$k] = $one;
            //}
        //}
        //var_dump($one);
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }
    
    public function getAllConfig()
    {
        $arr = [];
        $baseConfigs = $this->_model->find()->select(['key', 'value'])->asArray()->all();
        foreach ($baseConfigs as $one) {
            $arr[$one['key']] = unserialize($one['value']);
        }
        
        return $arr;
    }
    
    public function getBdminConfig($bdmin_user_id)
    {
        $arr = [];
        $baseConfigs = $this->_model->find()->where(['bdmin_user_id' => $bdmin_user_id])->select(['key', 'value'])->asArray()->all();
        foreach ($baseConfigs as $one) {
            $arr[$one['key']] = unserialize($one['value']);
        }
        
        return $arr;
    }

    /**
     * @param $one|array
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    public function save($one)
    {
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        
        if ($primaryVal) {
            $model = $this->_model->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('config {primaryKey} is not exist', ['primaryKey' => $this->getPrimaryKey()]);

                return;
            }
        } else {
            $model = new $this->_modelName();
            $model->created_at = time();
        }
        $model->updated_at = time();
        
        $primaryKey = $this->getPrimaryKey();
        $model      = Yii::$service->helper->ar->save($model, $one);
        $primaryVal = $model[$primaryKey];

        return true;
    }
    // 保存配置值
    public function saveConfig($one)
    {
        if (!$one['key'] || !$one['value']) {
            return false;
        }
        $model = $this->_model->findOne(['key' => $one['key'], 'bdmin_user_id' => $one['bdmin_user_id']]);
        if (!$model['id']) {
            $model = new $this->_modelName();
            $model->created_at = time();
            $model->key = $one['key'];
            $model->bdmin_user_id = $one['bdmin_user_id'];
        }
        if (is_array($one['value'])) {
            $model->value = serialize($one['value']);
        } else {
            $model->value = $one['value'];
        }
        $model->updated_at = time();
        
        return $model->save();
    }

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
            foreach ($ids as $id) {
                $model = $this->_model->findOne($id);
                $model->delete();
            }
        }

        return true;
    }
    
    
}
