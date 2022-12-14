<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\models\mysqldb\customer;

use Yii;
use fecbbc\models\mysqldb\Customer;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CustomerRegister extends Customer
{
    private $_admin_user;

    private $_rules;

    public function setCustomerRules($rules)
    {
        $this->_rules = $rules;
    }

    public function rules()
    {
        $parent_rules = parent::rules();
        $current_rules = [
            ['id', 'filter', 'filter' => 'trim'],
            ['phone', 'filter', 'filter' => 'trim'],
//            ['phone','match','pattern'=>'/^[1][23456789][0-9]{9}$/'],
//            ['phone', 'validatePhone'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'validateEmail'],
            ['password', 'filter', 'filter' => 'trim'],
            ['password', 'string', 'length' => [6, 30]],
            // 注册账户，名字不作为必填选项
            //['firstname', 'filter', 'filter' => 'trim'],
            // ['firstname', 'string', 'length' => [1, 50]],
             // 注册账户，名字不作为必填选项
            //['lastname', 'filter', 'filter' => 'trim'],
            // ['lastname', 'string', 'length' => [1, 50]],

            ['is_subscribed', 'validateIsSubscribed'],
        ];

        $rules = array_merge($parent_rules, $current_rules);
        if (is_array($this->_rules)) {
            $rules = array_merge($rules, $this->_rules);
        }

        return $rules;
    }

    public function validateIsSubscribed($attribute, $params)
    {
        if ($this->is_subscribed != 1) {
            $this->is_subscribed = 2;
        }
    }


    public function validateEmail($attribute, $params)
    {
        if ($this->id) {
            $one = \fecshop\models\mysqldb\Customer::find()
                ->where(' id != :id AND email = :email ', [':id'=>$this->id, ':email'=>$this->email])
                ->one();
            if ($one['id']) {
                $this->addError($attribute, 'this email is exist!');
            }
        } else {
            $one = Customer::find()
                ->where('email = :email', [':email' => $this->email])
                ->one();
            if ($one['id']) {
                $this->addError($attribute, 'this email is exist!');
            }
        }
    }

    public function validatePhone($attribute, $params)
    {
        if ($this->id) {
            $one = Customer::find()
                ->where(' id != :id AND phone = :phone ', [':id'=>$this->id, ':phone'=>$this->phone])
                ->one();
            if ($one['id']) {
                $this->addError($attribute, 'this phone is exist!');
            }
        } else {
            $one = Customer::find()
                ->where('phone = :phone', [':phone' => $this->phone])
                ->one();
            if ($one['id']) {
                $this->addError($attribute, 'this phone is exist!');
            }
        }
    }

    public function setPassword($password)
    {
        if ($this->password) {
            $this->password_hash = \Yii::$app->security->generatePasswordHash($password, 6);
            $this->password = '';
        }
    }

    // 重写保存方法
    public function save($runValidation = true, $attributeNames = null)
    {
        // 如果password为空，则return
        if (!$this->password) {
            return false;
        }
        // 如果auth_key为空，则重置
        if (!$this->auth_key) {
            $this->generateAuthKey();
        }
        // 如果access_token为空，则重置
        if (!$this->access_token) {
            $this->generateAccessToken();
        }

        // 设置password
        $this->setPassword($this->password);
        return parent::save($runValidation, $attributeNames);
    }

}