<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Customer\block\address;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Edit
{
    protected $_address;
    
    public function initAddress()
    {
        //$address = Yii::$app->request->post('editForm');
        $this->_address_id = Yii::$app->request->get('address_id');
        $addressModel = Yii::$service->customer->address->getByPrimaryKey($this->_address_id);
        $identity = Yii::$app->user->identity;
        $customer_id = $identity['id'];
        if ($addressModel['address_id']) {
            // 该id必须是当前用户的
            if ($customer_id == $addressModel['customer_id']) {
                foreach ($addressModel as $k=>$v) {
                    $this->_address[$k] = $v;
                }
            }
        }
    }
    
    public function getLastData()
    {
        $this->initAddress();
        if (empty($this->_address)) {
            $this->_address = [];
        }
        
        return [
            'address' => $this->_address,
        ];
    }
    
    public function save($addressInfo)
    {
        $identity = Yii::$app->user->identity;
        $addressInfo['customer_id'] = $identity['id'];
        $addressInfo['country'] = 'CN';
        $addressInfo['is_default'] == 1 ? ($addressInfo['is_default'] = 1) : ($addressInfo['is_default'] = 2);
        $defaultAddressId = Yii::$service->customer->address->getDefualtAddressId();
        if (!$defaultAddressId || ($defaultAddressId == $addressInfo['address_id'])) {
            $addressInfo['is_default'] = 1;
        }
        
        $saveStatus = Yii::$service->customer->address->save($addressInfo);
        if (!$saveStatus) {
            Yii::$service->page->message->addByHelperErrors();
            return false;
        }
        
        return true;
    }    
    // 用户下单，地址第一次保存
    /*
    public function saveFirst($addressInfo)
    {
        $identity = Yii::$app->user->identity;
        $addressInfo['customer_id'] = $identity['id'];
        $addressInfo['country'] = 'CN';
        $addressInfo['is_default'] = 1;
        $saveStatus = Yii::$service->customer->address->save($addressInfo);
        if (!$saveStatus) {
            Yii::$service->page->message->addByHelperErrors();
            return false;
        }
        
        return true;
    }  
    */
}
