<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product;

use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
use Yii;

/**
 * Product Service is the component that you can get product info from it.
 *
 * @property \fecshop\services\Image | \fecshop\services\Product\Image $image image service or product image sub-service
 * @property \fecshop\services\product\Info $info product info sub-service
 * @property \fecshop\services\product\Stock $stock stock sub-service of product service
 *
 * @method getByPrimaryKey($primaryKey) get product model by primary key
 * @see \fecshop\services\Product::getByPrimaryKey()
 * @method getEnableStatus() get enable status
 * @see \fecshop\services\Product::getEnableStatus()
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AttrGroup extends Service
{
    /**
     * $storagePrex , $storage , $storagePath 为找到当前的storage而设置的配置参数
     * 可以在配置中更改，更改后，就会通过容器注入的方式修改相应的配置值
     */
    public $storage  = 'AttrGroupMysqldb';   // AttrGroupMysqldb | AttrGroupMongodb 当前的storage，如果在config中配置，那么在初始化的时候会被注入修改

    /**
     * 设置storage的path路径，
     * 如果不设置，则系统使用默认路径
     * 如果设置了路径，则使用自定义的路径
     */
    public $storagePath = '';

    /**
     * @var \fecshop\services\product\ProductInterface 根据 $storage 及 $storagePath 配置的 Product 的实现
     */
    protected $_attrGroup;

    public function init()
    {
        parent::init();
        // 从数据库配置中得到值, 设置成当前service存储，是Mysqldb 还是 Mongodb
        $currentService = $this->getStorageService($this);
        $this->_attrGroup = new $currentService();
    }
    // 动态更改为mongodb model
    public function changeToMongoStorage()
    {
        $this->storage     = 'AttrGroupMongodb';
        $currentService = $this->getStorageService($this);
        $this->_attrGroup = new $currentService();
    }
    
    // 动态更改为mongodb model
    public function changeToMysqlStorage()
    {
        $this->storage     = 'AttrGroupMysqldb';
        $currentService = $this->getStorageService($this);
        $this->_attrGroup = new $currentService();
    }

    public function getEnableStatus()
    {
        return $this->_attrGroup->getEnableStatus();
    }
    
    /**
     * get artile's primary key.
     */
    public function getPrimaryKey()
    {
        return $this->_attrGroup->getPrimaryKey();
    }

    /**
     * get  model by primary key.
     */
    public function getByPrimaryKey($primaryKey)
    {
        return $this->_attrGroup->getByPrimaryKey($primaryKey);
    }
    
    
    /**
     * get  model by name
     */
    public function getByName($name)
    {
        return $this->_attrGroup->getByName($name);
    }
    
    public function coll($filter = '')
    {
        return $this->_attrGroup->coll($filter);
    }

    /**
     * @param $one|array , save one data .
     * @param $originUrlKey|string , article origin url key.
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    public function save($one)
    {
        if (!$this->checkSaveOne($one)) {
            
            return false;
        }
        $attr_ids = $one['attr_ids'];
        if (is_array($attr_ids)) {
            $one['attr_ids'] = serialize($attr_ids);
        }
        
        return $this->_attrGroup->save($one);
    }
    
    public function checkSaveOne($one)
    {
        $statusArr = [1,2];
        $status = $one['status'];
        if (!in_array($status, $statusArr)) {
            Yii::$service->helper->errors->add('status is not correct');
            
            return false;
        }
        $attr_ids = $one['attr_ids'];
        if (!$attr_ids) {
            Yii::$service->helper->errors->add('attr_ids can not empty');
            
            return false;
        }
        if (is_array($attr_ids) && !empty($attr_ids)) {
            foreach ($attr_ids as $one) {
                if (!isset($one['attr_id']) || !$one['attr_id']) {
                    Yii::$service->helper->errors->add('attr_ids item attr_id can not empty');
                    
                    return false;
                }
            }
        }
        return true;
    }
    
    public function remove($ids)
    {
        return $this->_attrGroup->remove($ids);
    }
    
    public function getActiveAllColl()
    {
        return $this->_attrGroup->getActiveAllColl();
    }
    
    /**
     * get artile model by primary key.
     */
    public function getByRemoteId($remoteId)
    {
        return $this->_attr->getByRemoteId($remoteId);
    }
    
    public function getAttrGroupArr()
    {
        return $this->_attrGroup->getAttrGroupArr();
    }
    
}
