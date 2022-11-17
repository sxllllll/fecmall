<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\services\customer;

use fecshop\services\Service;
use yii\db\Query;
use Yii;

/**
 * Product ProductMysqldb Service 未开发。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Wxqrcodelog extends Service
{
    public $numPerPage = 20;
    
    protected $_modelName = '\fecbbc\models\mysqldb\customer\CustomerWxQrCodeLog';

    protected $_model;
    
    
    public function init()
    {
        parent::init();
        list($this->_modelName, $this->_model) = \Yii::mapGet($this->_modelName);
    }
    
    public function getPrimaryKey()
    {
        return 'id';
    }
    
    
    public function getByPrimaryKey($primaryKey = null)
    {
        if ($primaryKey) {
            $one = $this->_model->findOne($primaryKey);
            return $this->unserializeData($one) ;
        } else {
            return new $this->_modelName();
        }
    }
    
    /*
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     * 		'where'			=> [
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
        
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }
    
    public function findByOpenid($openid)
    {
        $one = $this->_model->findOne([
            'openid' => $openid
        ]);
        if ($one['openid']) {
            
            return $one;
        }
        
        return null;
        
    }
    
    public function findOpenidByEventKeyAndClear($eventKey) {
        $one = $this->_model->findOne([
            'eventkey' => $eventKey
        ]);
        if ($one['openid']) {
            $one['eventkey'] = null;
            $one['updated_at'] = time();
            $one->save();
            
            return $one['openid'];
        }
        
        return '';
    }
    
    /**
     * 更新记录
     *
     */
    public function updateWxQrCode($openid, $eventKey)
    {
        // 1.查询$eventKey，是否存在，如果存在，则清空
        $data = $this->_model->find()->where([ 'eventkey' => $eventKey,])
                ->andWhere(['<>', 'openid', $openid])
                ->all();
        if (is_array($data) && !empty($data)) {
            foreach ($data as $one) {
                $one->eventkey = null;
                $one->updated_at = time();
                $one->save();
            }
        }
        // 2.查询是否存在 $openid，如果存在，则更新，如果不存在，则保存。
        $one = $this->_model->findOne([
            'openid' => $openid
        ]);
        if (!$one['openid']) {
            $one = new $this->_modelName;
            $one->eventkey = $eventKey;
            $one->openid = $openid;
            $one->created_at = time();
            $one->updated_at = time();
            return $one->save();
        }
        if ($one['eventkey'] != $eventKey) {
            $one['eventkey'] = $eventKey;
            $one->updated_at = time();
            return $one->save();
        }
        
        return true;
    }

    /**
     * @param $one|array , 产品数据数组
     * @param $originUrlKey|string , 产品的原来的url key ，也就是在前端，分类的自定义url。
     * 保存产品（插入和更新），以及保存产品的自定义url
     * 如果提交的数据中定义了自定义url，则按照自定义url保存到urlkey中，如果没有自定义urlkey，则会使用name进行生成。
     */
    public function save($one)
    {
        
    }
    
    
    /**
     * @param $ids | Array or String
     * 删除产品，如果ids是数组，则删除多个产品，如果是字符串，则删除一个产品
     * 在产品产品的同时，会在url rewrite表中删除对应的自定义url数据。
     */
    public function remove($ids)
    {
        
    }
    
    
}
