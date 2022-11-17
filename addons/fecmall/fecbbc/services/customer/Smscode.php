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
class Smscode extends Service
{
    public $numPerPage = 20;
    
    protected $_modelName = '\fecbbc\models\mysqldb\customer\Smscode';

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
    
    
    /**
     * @param $key | string, 短信验证码的类型
     * @param $phone | string, 手机号
     * @param $code | string, 验证码
     * @param $timeout | int, 超时时间
     * 核验手机验证码是否有效
     */
    public function verifySmsCode($key, $phone, $code, $timeout = 600)
    {
        $one = $this->_model->findOne([
            'key' => $key,
            'phone' => $phone,
            'code' => $code,
            
        ]);
        if (!$one['id']) {
            
            return false;
        }
        $updatedAt = $one['updated_at'];
        if (!$updatedAt || ($updatedAt + $timeout < time())) {
            
            return false;
        }
        
        return true;
    }
    /**
     * @param $key | string, 短信验证码的类型
     * @param $phone | string, 手机号
     * @param $code | string, 验证码
     * 核验手机验证码是否有效
     */
    public function setSmsCode($key, $phone, $code)
    {
        $one = $this->_model->findOne([
            'key' => $key,
            'phone' => $phone,
        ]);
        if (!$one['id']) {
            $one = new $this->_modelName;
            $one['key'] = $key;
            $one['phone'] = $phone;
            $one['created_at'] = time();
        }
        $one['code'] = $code;
        $one['updated_at'] = time();
        
        return $one->save();
    }
    
    
}
