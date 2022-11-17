<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\services;

use Yii;

/**
 * BdminUser services. 用来给后台的用户提供数据。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Customer extends \fecshop\services\Customer
{
    public $wxQrCodeHasAccount = 1;
    public $wxQrCodeNoAccount = 2;
    public $wxQrCodeNoLog = 3;
    
    protected $_customerRegisterModelName = '\fecbbc\models\mysqldb\customer\CustomerRegister';
    protected $_customerModelName = '\fecbbc\models\mysqldb\Customer';
    protected $_customerLoginModelName = '\fecshop\models\mysqldb\customer\CustomerLogin';
    
    /**
     * @param $eventKey | string
     * 用户扫描后，pc ajax进行查询
     */
    public function queryQrCodeAndLogin($eventKey)
    {
        $openid = Yii::$service->customer->wxqrcodelog->findOpenidByEventKeyAndClear($eventKey);
        if (!$openid) {
            
            return [$this->wxQrCodeNoLog, ''];
        }
        $identity = $this->getByOpenid($openid);
        if (!$identity) {
            
            return [$this->wxQrCodeNoAccount, $openid];
        }
        // 进行登陆 
        $this->loginByIdentity($identity);
        
        return [$this->wxQrCodeHasAccount, $openid];
    }
    
    
    public function getByOpenid($openid)
    {
        $identity = $this->_customerModel->findOne(['wx_openid' => $openid]);
        if (!isset($identity['id']) ||  !$identity['id']) {
            
            return null;
        }
        
        return $identity;
    } 
    
    // 登陆
    public function loginByIdentity($identity, $duration=0)
    {
        // 执行登陆
        if (!$duration) {
            if (Yii::$service->session->timeout) {
                $duration = Yii::$service->session->timeout;
            }
        }
        //var_dump($identity);exit;
        if (\Yii::$app->user->login($identity, $duration)) {
            // 执行购物车合并等操作。
            Yii::$service->cart->mergeCartAfterUserLogin();
            
            return true;
        }
    }
    
    
    /**
     * @param $openid | string
     * @param $eventKey | string
     * 扫码关注公众号后，微信推送消息给fecmall，进而接收数据，保存
     */
    public function updateWxQrCode($openid, $eventKey)
    {
        // 检查 $openid 是否存在,如果存在，直接返回
        //$one = $this->_customerModel->findOne(['wx_openid' => $openid]);
        //if (isset($one['id']) && $one['id']) {
        //    
        //    return true;
        //}
        // 写入log表
        return Yii::$service->customer->wxqrcodelog->updateWxQrCode($openid, $eventKey);
    }
    
    
    /**
     * Register customer account.
     * @param array $param
     * 数据格式如下：
     * ```php
     * [
     *      'email',
     *      'firstname',
     *      'lastname',
     *      'password'
     * ]
     * ```
     * @return bool whether the customer is registered ok
     */
    public function register($param)
    {
        $model = $this->_customerRegisterModel;
        if ($model->validate()) {
            $model->created_at = time();
            $model->updated_at = time();
            $model->firstname= $param["firstname"];
            $model->lastname= $param["firstname"];
            $model->password=$param["password"];
            $model->email= $param["email"] ;
            $saveStatus = $model->save();
            if (!$saveStatus) {
                $errors = $model->errors;
                Yii::$service->helper->errors->addByModelErrors($errors);
                return false;
            }
            // 发送注册信息到trace系统
            Yii::$service->page->trace->sendTraceRegisterInfoByApi($model->phone);
            return true;
        } else {
            $errors = $model->errors;
            Yii::$service->helper->errors->addByModelErrors($errors);

            return false;
        }
    }
    /**
     * 微信公众号扫码关注成功后，进行微信和手机号绑定操作
     */
    public function phoneRegisterAndLogin($param)
    {
        $phone = $param['phone'];
        $openid = $param['openid'];
        $appName = isset($param['app_name']) ? $param['app_name'] : '';
        // 查看openid是否已经绑定手机（该用户微信是否已经绑定其他的手机），如果存在，则报错返回
        $customer = $this->_customerModel->findOne(['wx_openid' => $openid]);
        if ($customer['id']) {
            Yii::$service->helper->errors->add('wx openid has bind account, can not bind again');
                
            return false;
        }
        $customer = $this->_customerModel->findOne(['phone' => $phone]);
        // 手机号已经存在账户
        if ($customer['id']) {
            // 如果该手机号账户，是否已经绑定微信，如果已经绑定，则无法继续绑定。
            if ($customer->wx_openid) {
                Yii::$service->helper->errors->add('account has bind wx openid, can not bind again');
                
                return false;
            }
            // 查看 customer_wx_qr_code_log 表，是否存在该openid, 如果不存在，则非法
            if ($appName == 'appfront') {
                $wxqrcodelog = Yii::$service->customer->wxqrcodelog->findByOpenid($openid);
                if (!$wxqrcodelog) {
                    Yii::$service->helper->errors->add('openid is illegal');
                    
                    return false;
                }
            }
            
            $customer->wx_openid = $openid;
            $customer->updated_at = time();
            if (!$customer->save()) {
                Yii::$service->helper->errors->add('customer save fail');
                
                return false;
            }
            // 进行登陆
            $this->loginByIdentity($customer);
            
            return true;
        } 
        // 手机号不存在，注册新用户，手机账户绑定微信
        $param['password'] = Yii::$service->helper->createNoncestr(8);
        $model = $this->_customerRegisterModel;
        $model->attributes = $param;
        if ($model->validate()) {
            $model->created_at = time();
            $model->updated_at = time();
            $model->wx_password_is_set = 2;
            $model->wx_openid = $openid;
            $saveStatus = $model->save();
            if (!$saveStatus) {
                $errors = $model->errors;
                Yii::$service->helper->errors->addByModelErrors($errors);
                
                return false;
            }
            // 发送注册信息到trace系统
            Yii::$service->page->trace->sendTraceRegisterInfoByApi($model->phone);
            // 进行登陆 
            $this->loginByIdentity($model);
            
            return true;
        } else {
            $errors = $model->errors;
            Yii::$service->helper->errors->addByModelErrors($errors);

            return false;
        }
    }
    
    
    /**
     * 微信小程序登陆，绑定手机
     */
    public function phoneRegisterAndGetLoginAccessToken($param)
    {
        $phone = $param['phone'];
        $openid = $param['openid'];
        $session_key = $param['session_key'];
        // 查看openid是否已经绑定手机（该用户微信是否已经绑定其他的手机），如果存在，则报错返回
        $customer = $this->_customerModel->findOne(['wx_micro_openid' => $openid]);
        if ($customer['id']) {
            Yii::$service->helper->errors->add('wx openid has bind account, can not bind again');
                
            return false;
        }
        $customer = $this->_customerModel->findOne(['phone' => $phone]);
        // 手机号已经存在账户
        if ($customer['id']) {
            // 如果该手机号账户，是否已经绑定微信，如果已经绑定，则无法继续绑定。
            if ($customer->wx_micro_openid) {
                Yii::$service->helper->errors->add('account has bind wx openid, can not bind again');
                
                return false;
            }
            // 查看 customer_wx_qr_code_log 表，是否存在该openid, 如果不存在，则非法
            //$wxqrcodelog = Yii::$service->customer->wxqrcodelog->findByOpenid($openid);
            //if (!$wxqrcodelog) {
            //    Yii::$service->helper->errors->add('openid is illegal');
            //    
            //    return false;
            //}
            $customer->wx_micro_openid = $openid;
            $customer->wx_session_key = $session_key;
            $customer->updated_at = time();
            $customer->generateAccessToken();
            $customer->access_token_created_at = time();
            if (!$customer->save()) {
                Yii::$service->helper->errors->add('customer save fail');
                
                return false;
            }
            // 进行登陆
            $this->loginByIdentity($customer);
            $this->setHeaderAccessToken($customer->access_token);
            return $customer->access_token;
        } 
        // 手机号不存在，注册新用户，手机账户绑定微信
        $param['password'] = Yii::$service->helper->createNoncestr(8);
        $model = $this->_customerRegisterModel;
        $model->attributes = $param;
        if ($model->validate()) {
            $model->created_at = time();
            $model->updated_at = time();
            $model->wx_password_is_set = 2;
            $model->wx_micro_openid = $openid;
            $model->wx_session_key = $session_key;
            $model->generateAccessToken();
            $model->access_token_created_at = time();
            $saveStatus = $model->save();
            if (!$saveStatus) {
                $errors = $model->errors;
                Yii::$service->helper->errors->addByModelErrors($errors);
                
                return false;
            }
            // 发送注册信息到trace系统
            Yii::$service->page->trace->sendTraceRegisterInfoByApi($model->phone);
            // 进行登陆 
            $this->loginByIdentity($model);
            $this->setHeaderAccessToken($model->access_token);
            
            return $model->access_token;
        } else {
            $errors = $model->errors;
            Yii::$service->helper->errors->addByModelErrors($errors);

            return false;
        }
    }
    
    /**
     * Save the customer info.
     * @param array $param
     * 数据格式如下：
     * ['email' => 'xxx', 'password' => 'xxxx','firstname' => 'xxx','lastname' => 'xxx']
     * @return bool
     */
    public function save($param)
    {
        $primaryKey = $this->getPrimaryKey();
        $primaryVal = isset($param[$primaryKey]) ? $param[$primaryKey] : '';
        if ($primaryVal) {
            $model = $this->_customerRegisterModel;
            $model->attributes = $param;
            if (!$model->validate()) {
                $errors = $model->errors;
                Yii::$service->helper->errors->addByModelErrors($errors);
                
                return false;
            }
            $model = $this->getByPrimaryKey($primaryVal);
            if ($model[$primaryKey]) {
                unset($param[$primaryKey]);
                $param['updated_at'] = time();
                $password = isset($param['password']) ? $param['password'] : '';
                if ($password) {
                    $model->setPassword($password);
                    unset($param['password']);
                }
                
                $saveStatus = Yii::$service->helper->ar->save($model, $param);
                if ($saveStatus) {
                    
                    return true;
                } else {
                    $errors = $model->errors;
                    Yii::$service->helper->errors->addByModelErrors($errors);

                    return false;
                }
            }
        } else {
            if ($this->register($param)) {
                return true;
            }
        }
        return false;
    }
    
    
    /**
     * @param array $data
     *
     * example:
     *
     * ```php
     * $data = ['email' => 'user@example.com', 'password' => 'your password'];
     * $loginStatus = \Yii::$service->customer->login($data);
     * ```
     *
     * @return bool
     */
    public function login($data)
    {
        $model = $this->_customerLoginModel;
        $model->password = $data['password'];
        $model->email = $data['email'];
        $loginStatus = $model->login();
        $errors = $model->errors;
        if (empty($errors)) {
            // 合并购物车数据
            Yii::$service->cart->mergeCartAfterUserLogin();
            // 发送登录信息到trace系统
            Yii::$service->page->trace->sendTraceLoginInfoByApi($data['email']);
        } else {
            Yii::$service->helper->errors->addByModelErrors($errors);
        }

        return $loginStatus;
    }
    
    
    /** AppServer 部分使用的函数
     * @param $email | String
     * @param $password | String
     * 无状态登录，通过email 和password进行登录
     * 登录成功后，合并购物车，返回accessToken
     * ** 该函数是未登录用户，通过参数进行登录需要执行的函数。
     */
    public function loginAndGetAccessToken($email, $password)
    {
        $header = Yii::$app->request->getHeaders();
        if (isset($header['access-token']) && $header['access-token']) {
            $accessToken = $header['access-token'];
        }
        // 如果request header中有access-token，则查看这个 access-token 是否有效
        if ($accessToken) {
            $identity = Yii::$app->user->loginByAccessToken($accessToken);
            if ($identity !== null) {
                $access_token_created_at = $identity->access_token_created_at;
                $timeout = Yii::$service->session->timeout;
                if ($access_token_created_at + $timeout > time()) {
                    return $accessToken;
                }
            }
        }
        // 如果上面access-token不存在
        $data = [
            'email'     => $email,
            'password'  => $password,
        ];
        
        if (Yii::$service->customer->login($data)) {
            $identity = Yii::$app->user->identity;
            $identity->generateAccessToken();
            $identity->access_token_created_at = time();
            $identity->save();
            // 执行购物车合并等操作。
            Yii::$service->cart->mergeCartAfterUserLogin();
            $this->setHeaderAccessToken($identity->access_token);
            return $identity->access_token;
        }
    }
    
    /**
     * Check whether the given phone is registered
     * @param string $phone
     * @return bool whether the given phone is registered
     */
    public function isRegistered($phone)
    {
        $customer = $this->_customerModel->findOne(['phone' => $phone]);
        if ($customer['phone']) {
            
            return true;
        } else {
            return false;
        }
    }
    
    
     /**
     * Change customer's password
     * @param string $password
     * @param int|string|IdentityInterface $identity this can be customer id, customer email, or customer
     * @return bool
     * @throws \InvalidArgumentException if $identity is invalid
     */
    public function changePassword($password, $identity)
    {
        if (is_int($identity)) {
            $customer_id = $identity;
            $customerModel = $this->_customerModel->findIdentity($customer_id);
        } elseif (is_string($identity)) {
            $phone = $identity;
            $customerModel = $this->_customerModel->findByPhone($phone);
        } elseif (is_object($identity) && $identity instanceof IdentityInterface) {
            $customerModel = $identity;
        } else {
            throw new \InvalidArgumentException('$identity can only be customer id, customer email, or customer');
        }
        if ($customerModel['id']) {
            $customerModel->updated_at = time();
            $customerModel->setPassword($password);
            $customerModel->save();
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @param $password|string
     * @param $customerId|int or String or Object
     * change  customer password.
     * 更改密码，然后，清空token
     */
    public function changePasswordAndClearToken($password, $identity)
    {
        if (is_int($identity)) {
            $customer_id = $identity;
            $customerModel = $this->_customerModel->findIdentity($customer_id);
        } elseif (is_string($identity)) {
            $phone = $identity;
            $customerModel = $this->_customerModel->findByPhone($phone);
        } elseif (is_object($identity)) {
            $customerModel = $identity;
        } else {
            Yii::$service->helper->errors->add('identity is not right');

            return null;
        }
        if (strlen($password) < 6  || strlen($password) > 30) {
            Yii::$service->helper->errors->add('Password length must be greater than 6, less than 30');

            return null;
        }
        $customerModel->setPassword($password);
        $customerModel->removePasswordResetToken();
        $customerModel->updated_at = time();
        $customerModel->save();

        return true;
    }
    
    /**
     * Get customer by email address
     * @param string $email
     * @return \fecshop\models\mysqldb\Customer|null return customer or null if not found
     */
    public function getUserIdentityByPhone($phone)
    {
        $one = $this->_customerModel->findByPhone($phone);
        if ($one['id']) {
            
            return $one;
        } else {
            
            return null;
        }
    }
    // 得到可用的账户
    public function getAvailableUserIdentityByPhone($phone)
    {
        $one = $this->_customerModel->findAvailableByPhone($phone);
        if ($one['id']) {
            
            return $one;
        } else {
            
            return null;
        }
    }
    /**
     * 生成resetToken，用来找回密码
     * @param string|IdentityInterface $identify identity can be customer phone, or customer object
     * @return string|null 生成的resetToken，如果生成失败返回false
     */
    public function generatePasswordResetToken($identify)
    {
        if (is_string($identify)) {
            $phone = $identify;
            $one = $this->getUserIdentityByPhone($phone);
        } else {
            $one = $identify;
        }
        if ($one) {
            $one->generatePasswordResetToken();
            $one->updated_at = time();
            $one->save();

            return $one->password_reset_token;
        }
        return false;
    }
    
    /**
     * @param $user_ids | Array ， 子项为Int类型
     * @return Array ，数据格式为：
     * ['id' => 'email']
     * 得到customer id 和customer email的对应数组。
     */
    public function getPhoneByIds($user_ids)
    {
        $arr = [];
        if (is_array($user_ids) && !empty($user_ids)) {
            $data = $this->_customerModel->find()->where([
                'in', 'id', $user_ids,
            ])->all();
            if (is_array($data) && !empty($data)) {
                foreach ($data as $one) {
                    $arr[$one['id']] = $one['phone'];
                }
            }
        }

        return $arr;
    }
    
    /**
     * 生成resetToken，用来找回密码
     * @param string|IdentityInterface $identify identity can be customer email, or customer object
     * @return string|null 生成的resetToken，如果生成失败返回false
     */
    public function generateRegisterEnableToken($identify)
    {
        if (is_string($identify)) {
            $phone = $identify;
            $one = $this->getAvailableUserIdentityByPhone($phone);
        } else {
            $one = $identify;
        }
        if ($one) {
            $one->generateRegisterEnableToken();
            $one->updated_at = time();
            $one->save();

            return $one->register_enable_token;
        }
        return false;
    }
    
    
}