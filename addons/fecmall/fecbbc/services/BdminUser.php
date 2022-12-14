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
class BdminUser extends \fecshop\services\Service
{
    public function init()
    {
        parent::init();

    }

    // 是否存在申请信息
    public function isApply( int $id ){
        return Yii::$service->bdminUser->bdminUser->getInfo( $id );
    }

    // 申请
    public function save( int $id , $data){
        return Yii::$service->bdminUser->bdminUser->save( $id , $data );
    }

    /**
     * @param $data|array
     * 数组格式：['username'=>'xxx@xxx.com','password'=>'xxxx']
     */
    public function login($data)
    {
        return Yii::$service->bdminUser->userLogin->login($data);
    }

    /**
     * @param $ids | Int Array
     * @return 得到相应用户的数组。
     */
    public function getIdAndNameArrByIds($ids)
    {
        return Yii::$service->bdminUser->bdminUser->getIdAndNameArrByIds($ids);
    }
    

    /**
     * 得到所有的active 状态的user
     *
     */
    public function getAllActiveUser()
    {
        $filter = [
            'where' => [
                ['status' => Yii::$service->bdminUser->bdminUser->getActiveStatus()],
            ],
            'select' => ['id', 'username', 'email', 'person'],
            'asArray' => true,
            'fetchAll' => true,
        ];
        $data = Yii::$service->bdminUser->bdminUser->coll($filter);
        $coll = isset($data['coll']) ? $data['coll'] : '';
        if (is_array($coll ) && !empty($coll )) {
            return $coll;
        } else {
            return null;
        }
    }
    
    public function getAllActiveUserArr()
    {
        $coll = $this->getAllActiveUser();
        $arr = [];
        if (is_array($coll)) {
            foreach ($coll as $one) {
                $id = $one['id'];
                $userName = $one['username'];
                $arr[$id] = $userName;
            }
        }
        
        return $arr;
    }

    /** Appapi 部分使用的函数
     * @param $username | String
     * @param $password | String
     * @return mix string|null
     * Appapi 和 第三方进行数据对接部分的用户登陆验证
     */
    public function loginAndGetAccessToken($username, $password)
    {
        return Yii::$service->bdminUser->userLogin->loginAndGetAccessToken($username, $password);
    }



    public function setHeaderAccessToken($accessToken)
    {
        return Yii::$service->bdminUser->userLogin->setHeaderAccessToken($accessToken);
    }

    /** AppServer 部分使用的函数
     * @param $type | null or  Object
     * 从request headers中获取access-token，然后执行登录
     * 如果登录成功，然后验证时间是否过期
     * 如果不过期，则返回identity
     * ** 该方法为appserver用户通过access-token验证需要执行的函数。
     */
    public function loginByAccessToken($type = null)
    {
        return Yii::$service->bdminUser->userLogin->loginByAccessToken($type);
    }

    /**
     * 通过accessToek的方式，进行登出从操作。
     */
    public function logoutByAccessToken()
    {
        return Yii::$service->bdminUser->userLogin->logoutByAccessToken();
    }
}
