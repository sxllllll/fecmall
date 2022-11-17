<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Customer\block\account;

use fecshop\app\appfront\helper\mailer\Email;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Bdmin extends \yii\base\BaseObject
{
    
    public function getLastData($param)
    {
        $username = isset($param['username']) ? $param['username'] : '';
        $password = isset($param['password']) ? $param['password'] : '';
        $person = isset($param['person']) ? $param['person'] : '';
        
        $telephone = isset($param['telephone']) ? $param['telephone'] : '';
        $email = isset($param['email']) ? $param['email'] : '';
        $bdmin_bank_name = isset($param['bdmin_bank_name']) ? $param['bdmin_bank_name'] : '';
        $bdmin_bank = isset($param['bdmin_bank']) ? $param['bdmin_bank'] : '';
        $bdmin_bank_account = isset($param['bdmin_bank_account']) ? $param['bdmin_bank_account'] : '';
        $tax_point = isset($param['tax_point']) ? $param['tax_point'] : '';
        
        $invoice = isset($param['invoice']) ? $param['invoice'] : '';
        $authorized_brand = isset($param['authorized_brand']) ? $param['authorized_brand'] : '';
        $authorized_type = isset($param['authorized_type']) ? $param['authorized_type'] : '';
        $authorized_role = isset($param['authorized_role']) ? $param['authorized_role'] : '';
        
        $authorized_at = isset($param['authorized_at']) ? $param['authorized_at'] : '';
        $cooperationed_at = isset($param['cooperationed_at']) ? $param['cooperationed_at'] : '';
        $shipping_date = isset($param['shipping_date']) ? $param['shipping_date'] : '';
        $order_date = isset($param['order_date']) ? $param['order_date'] : '';
        $remark = isset($param['remark']) ? $param['remark'] : '';
        
        return [
            'username'        => $username,
            'person'        => $person,
            
            'telephone'         => $telephone,
            'email'            => $email,
            'bdmin_bank_name'            => $bdmin_bank_name,
            'bdmin_bank' => $bdmin_bank,
            'bdmin_bank_account'   => $bdmin_bank_account,
            'tax_point'   => $tax_point,
            'invoice'   => $invoice,
            'authorized_brand'   => $authorized_brand,
            
            'authorized_type'   => $authorized_type,
            'authorized_role'   => $authorized_role,
            'authorized_at'   => $authorized_at,
            'cooperationed_at'   => $cooperationed_at,
            'shipping_date'   => $shipping_date,
            'order_date'   => $order_date,
            'remark'   => $remark,
            
            'authorizedTypeOptions' => $this->getAuthorizedTypeOptions($authorized_type),
            'authorizedRoleOptions' => $this->getAuthorizedRolesOptions($authorized_role),
            //'auditStatus' => $auditStatus,
        ];
    }
    
    public function getAuthorizedTypeOptions($pType='')
    {
        $authorizedTypes = Yii::$service->bdminUser->bdminUser->getAuthorizedTypes();
        $str = '';
        foreach ($authorizedTypes as $authorizedType => $label) {
            $selected = '';
            if ($pType == $authorizedType) {
                $selected = 'selected="selected"';
            }
            $str .= '<option '.$selected.' value="'.$authorizedType.'">'.$label.'</option>';
        }
        
        return $str;
    }
    
    public function getAuthorizedRolesOptions($aRole='')
    {
        $authorizedRoles = Yii::$service->bdminUser->bdminUser->getAuthorizedRoles();
        $str = '';
        foreach ($authorizedRoles as $authorizedRole => $label) {
            $selected = '';
            if ($aRole == $authorizedRole) {
                $selected = 'selected="selected"';
            }
            $str .= '<option '.$selected.' value="'.$authorizedRole.'">'.$label.'</option>';
        }
        
        return $str;
    }
    
    public function bdminAudit($param)
    {
        $fileFullDir = Yii::getAlias(Yii::$service->bdminUser->bdminUser->uploadZipPath);
        $zipFileName = $this->saveUploadZipFile($fileFullDir);
        
        if (!$zipFileName) {
            Yii::$service->page->message->addByHelperErrors();
            
            return false;
        }
        $param['zip_file'] = $zipFileName;   
        
        
        $param['customer_id'] = Yii::$app->user->identity->id;
        if (!Yii::$service->bdminUser->bdminUser->bdminCreate($param)) {
            Yii::$service->page->message->addByHelperErrors();
            
            return false;
        }
        Yii::$service->page->message->addCorrect('我们已经接收到您的经销商申请，请耐心等待我们的回复');
        
        return true;
    }
    
    public $upload_id = 0;
    
    # 1.保存前台上传的文件。
	public function saveUploadZipFile($fileFullDir)
    {
        $this->upload_id += 1;
        $name = $_FILES["zip_file"]["name"];
        $fileDir = 'bdmin_' . $this->upload_id . time().'_'.rand(1000,9999);
        $fileDir .='.zip';
        $fileFullDir = $fileFullDir . $fileDir;
        
        if(!strstr($name,'.zip')){
            Yii::$service->helper->errors->add('upload file is not zip format file');
            
            return false;
        }
        
        $tmpFile = $_FILES["zip_file"]["tmp_name"];
        if (!$this->checkZipFile($tmpFile)) {
            Yii::$service->helper->errors->add('upload file is not zip format file');
            
            return false;
        }
   
        $result = @move_uploaded_file($tmpFile, $fileFullDir);
        
		return $fileDir;
	}
    
    public function checkZipFile($filePath)
    {
        $zip = new \ZipArchive();
        $res = $zip->open($filePath, \ZipArchive::CHECKCONS);
        //var_dump($filePath);
        //var_dump($res);exit;
        if ($res !== TRUE) {
            
            return false;
        }
        
        return true;
    }

}
