<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\services\bdminUser;

use Yii;
use fecshop\services\Service;

/**
 * BdminUser services. 用来给后台的用户提供数据。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class BdminUser extends Service
{
    public $numPerPage = 20;
    /**
     *  language attribute.
     */
    protected $_lang_attr = [];
    protected $_modelName = '\fecbbc\models\mysqldb\BdminUser';
    protected $_model;

    protected $_userFormModelName = '\fecbbc\models\mysqldb\bdminUser\BdminUserForm';
    protected $_userFormModel;
    
    protected $_userApplyFormModelName = '\fecbbc\models\mysqldb\bdminUser\BdminUserApplyForm';
    protected $_userApplyFormModel;
    
    protected $_userPassResetModelName = '\fecbbc\models\mysqldb\bdminUser\BdminUserResetPassword';
    protected $_userPassResetModel;

    public function init()
    {
        parent::init();
        list($this->_modelName, $this->_model) = \Yii::mapGet($this->_modelName);
        list($this->_userFormModelName, $this->_userFormModel) = \Yii::mapGet($this->_userFormModelName);
        list($this->_userApplyFormModelName, $this->_userApplyFormModel) = \Yii::mapGet($this->_userApplyFormModelName);
        list($this->_userPassResetModelName, $this->_userPassResetModel) = \Yii::mapGet($this->_userPassResetModelName);
    }
    /**
     * @param $data array
     * @return boolean
     * update current user password
     */
    public function resetCurrentPassword($data){
        $this->_userPassResetModel->attributes = $data;
        if ($this->_userPassResetModel->validate()) {
			$this->_userPassResetModel->updatePassword();
            
            return true;
        } else {
            $errors = $this->_userPassResetModel->errors;
			Yii::$service->helper->errors->addByModelErrors($errors);
            
            return false;
        }        
    }

    public function getPrimaryKey()
    {
        return 'id';
    }

    public function getActiveStatus(){
        $model = $this->_model;
        return $model::STATUS_ACTIVE;
    }
    public function getDeleteStatus(){
        $model = $this->_model;
        return $model::STATUS_DELETED;
    }

    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            $one = $this->_model->findOne($primaryKey);
            foreach ($this->_lang_attr as $attrName) {
                if (isset($one[$attrName])) {
                    $one[$attrName] = unserialize($one[$attrName]);
                }
            }
            
            return $one;
        } else {
            
            return new $this->_modelName();
        }
    }
    // 得到bdminUser by uuid
    public function getByUuid($uuidStr)
    {
        if ($uuidStr) {
            $one = $this->_model->findOne(['uuid' => $uuidStr]);
            if (isset($one[$this->getPrimaryKey()]) && $one[$this->getPrimaryKey()]) {
                
                return $one;
            }
        } 
        
        return null;
    }

    public function getInfo(int $id = 0 ){
        return $this->_model->findById($id);
    }

    public function save(int $id , $data ){
        $this->_model->attributes = $data;
        return $this->_model->saveData($id);

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
        if (!empty($coll)) {
            foreach ($coll as $k => $one) {
                foreach ($this->_lang_attr as $attr) {
                    $one[$attr] = $one[$attr] ? unserialize($one[$attr]) : '';
                }
                $coll[$k] = $one;
            }
        }
        //var_dump($one);
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }

    /**
     * @param $data array, user form data
     * @param $roles array, role id array
     * @return boolean
     * 保存用户的信息，以及用户的role信息。
     */
    /* 
    public function saveUserAndRole($data, $roles){
        $user_id = $this->save($data);
        if (!$user_id) {
            
            return false;
        }
        if (Yii::$service->admin->userRole->saveUserRole($user_id, $roles)) {
            
            return true;
        } 
        
        return false;
    }
    */
    /**
     * @param $data array, user form data
     * @return mix ，return save user id | null
     * 保存用户的信息。
     */
    public function save($data) {
        $primaryKey = $this->getPrimaryKey();
        $user_id = 0;
        if ($data[$primaryKey]) {
            $this->_userFormModel = $this->_userFormModel->findOne($data[$primaryKey]);
        }
        $this->_userFormModel->attributes = $data;
        
        if (!$data['access_token']) {
            $this->_userFormModel->access_token = '';
        }
        if (!$data['auth_key']) {
            $this->_userFormModel->auth_key = '';
        }
        if ($this->_userFormModel->validate()) {
            
            $this->_userFormModel['is_audit'] = $this->getAuditAcceptStatus();
            $this->_userFormModel->save();
            $user_id = $this->_userFormModel[$primaryKey];
        } else {
            $errors = $this->_userFormModel->errors;
            Yii::$service->helper->errors->addByModelErrors($errors);
            
            return null;
        }
        
        return $user_id;
    }
    /*
    public function removeUserAndRole($ids) {
        $removeIds = $this->remove($ids);
        if (is_array($removeIds) && !empty($removeIds)) {
            Yii::$service->admin->userRole->deleteByUserIds($removeIds);
            
            return true;
        } else {
            
            return false;
        }
    }
    */

    public function remove($ids){
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return null;
        }
        $removeIds = [];
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $model = $this->_model->findOne($id);
                if ($model->username !== 'admin') {
                    $model->delete();
                    $removeIds[] = $id;
                } else {
                    Yii::$service->helper->errors->add('you can not delete admin user');
                }
            }
        } else {
            $id = $ids;
            $model = $this->_model->findOne($id);
            if ($model->username !== 'admin') {
                $model->delete();
                $removeIds[] = $id;
            } else {
                Yii::$service->helper->errors->add('you can not delete admin user');
            }
        }

        return $removeIds;
    }
    
    
    // getActiveStatus()
    /**
     * @param $bdmin_user_id | int, 经销商id
     * @param boolean,
     * 经销商是否是active
     */
    public function isActiveBdminUser($bdmin_user_id)
    {
        $model = $this->getByPrimaryKey($bdmin_user_id);
        if (!$model['id']) {
            
            return false;
        }
        if ($model['status'] != $this->getActiveStatus()) {
            
            return false;
        }
        
        return true;
    }


    /**
     * @param $ids | Int Array
     * @return 得到相应用户的数组。
     */
    public function getIdAndNameArrByIds($ids)
    {
        $user_coll = $this->_model->find()
            ->asArray()
            ->select(['id', 'username'])
            ->where([
                'in', 'id', $ids,
            ])->all();
        $users = [];
        foreach ($user_coll as $one) {
            $users[$one['id']] = $one['username'];
        }

        return $users;
    }
    /**
     * @param $ids | Int Array
     * @return 得到相应用户的数组。
     */
    public function getIdAndPersonArrByIds($ids)
    {
        $user_coll = $this->_model->find()
            ->asArray()
            ->select(['id', 'person'])
            ->where([
                'in', 'id', $ids,
            ])->all();
        $users = [];
        foreach ($user_coll as $one) {
            $users[$one['id']] = $one['person'];
        }

        return $users;
    }
    
    public function getByUsername($username)
    {
        $one = $this->_model->findOne(['username' => $username]);
        if (!$one['username']) {
            
            return null;
        }
        return $one;
    }
    
    
    /**
     * 经销商提交审核
     */
    public function bdminCreate($param)
    {
        
        $model = new $this->_userApplyFormModel();
        $model->created_at = time();

        // 属性验证
        $model->attributes = $param;
        if (!$model->validate()) {  
            $errors = $model->errors;
            Yii::$service->helper->errors->addByModelErrors($errors);
            
            return false;
        }
        // 当用户重新提交，那么状态设置为init状态
        $model->audit_at = $this->getAuditInitStatus();
        $model->updated_at = time();
        $model->status = $this->getDeleteStatus();
        $model->is_audit = $this->getAuditInitStatus();
        $saveStatus = $model->save();
        
        return true;
    }
    
    
    
    /**
     * @param $bdminId | int ， bdmin id
     *  管理员审核通过
     */
    public function auditAccept($bdminId)
    {
        $allowStatus = [
            $this->getAuditInitStatus(),
            $this->getAuditRefuseStatus(),
        ];
        $updateColumns = $this->_model->updateAll(
            ['is_audit' => $this->getAuditAcceptStatus(), 'audit_at' => time(), 'status' => $this->getActiveStatus()],
            ['and', ['id' => $bdminId], ['in', 'is_audit', $allowStatus]]
        );
        if (empty($updateColumns)) {// 上面更新sql返回的更新行数如果为0，则说明更新失败，产品不存在，或者产品库存不够
            Yii::$service->helper->errors->add('bdmin audit accept fail ');
            
            return false;
        }
        
        return true;
    }
    /**
     * @param $bdminId | int ， bdmin id
     *  管理员审核拒绝
     */
    public function auditRefuse($bdminId)
    {
        $allowStatus = [
            $this->getAuditInitStatus(),
            $this->getAuditAcceptStatus(),
        ];
        $updateColumns = $this->_model->updateAll(
            ['is_audit' => $this->getAuditRefuseStatus(), 'audit_at' => time(), 'status' => $this->getDeleteStatus()],
            ['and', ['id' => $bdminId], ['in', 'is_audit', $allowStatus]]
        );
        if (empty($updateColumns)) {// 上面更新sql返回的更新行数如果为0，则说明更新失败，产品不存在，或者产品库存不够
            Yii::$service->helper->errors->add('bdmin audit refuse fail ');
            
            return false;
        }
        
        return true;
    }
    
    
    public function getAuditStatusArr()
    {
        
        return [
            $this->getAuditAcceptStatus()  => '审核通过',
            $this->getAuditInitStatus()  => '等待审核',
            $this->getAuditRefuseStatus()  => '审核拒绝',
        ];
    }
    
    public function getAuditAcceptStatus()
    {
        $model = $this->_model;
        
        return $model::AUDIT_STATUS_ACCEPT;
    }
    
    public function getAuditInitStatus()
    {
        $model = $this->_model;
        
        return $model::AUDIT_STATUS_INIT;
    }
    
    public function getAuditRefuseStatus()
    {
        $model = $this->_model;
        
        return $model::AUDIT_STATUS_REFUSE;
    }
    
    public $authorized_type_brand = 1;
    public $authorized_type_level_1 = 2;
    public $authorized_type_level_2 = 3;
    /**
     * 授权类型
     */
    public function getAuthorizedTypes()
    {
        return [
            $this->authorized_type_brand => '品牌授权',
            $this->authorized_type_level_1 => '一级授权',
            $this->authorized_type_level_2 => '二级授权',
        ];
    }
    
    public $authorized_role_1 = 1;
    public $authorized_role_gift_point = 2;
    public $authorized_role_2 = 3;
    public $authorized_role_dy = 4;
    public $authorized_role_sq = 5;
    public $authorized_role_all = 6;
    public $authorized_role_wx = 7;
    /**
     * 授权权限
     */
    public function getAuthorizedRoles()
    {
        return [
            $this->authorized_role_1 => '一类电商',
            $this->authorized_role_gift_point => '礼品积分兑换',
            $this->authorized_role_2 => '二类电商',
            $this->authorized_role_dy => '抖音授权',
            $this->authorized_role_sq => '社群授权',
            $this->authorized_role_all => '全网授权',
            $this->authorized_role_wx => '公众号+小程序+平台',
        ];
    }
    
    
    public $uploadZipPath = '@appadmin/uploads/product/';
    
}
