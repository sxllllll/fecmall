<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
namespace fecbbc\models\mysqldb\bdminUser;
use fecbbc\models\mysqldb\BdminUser;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class BdminUserApplyForm extends BdminUserForm {
    public function rules()
    {
        $current_rules = [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'validateUsername'],
            ['username', 'string', 'min' => 4, 'max' => 20],
            
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['username', 'string', 'min' => 1, 'max' => 60],
            
            ['telephone', 'filter', 'filter' => 'trim'],
            ['telephone', 'required'],
            // ['telephone', 'string', 'min' => 1, 'max' => 50],
            ['telephone','match','pattern'=>'/^[1][23456789][0-9]{9}$/'],
            
            ['person', 'filter', 'filter' => 'trim'],
            ['password', 'validatePasswordFormat'],
            
            ['bdmin_bank', 'filter', 'filter' => 'trim'],
            ['bdmin_bank', 'required'],
            ['bdmin_bank', 'string', 'min' => 2, 'max' => 100],
            
            
            ['bdmin_bank_name', 'filter', 'filter' => 'trim'],
            ['bdmin_bank_name', 'required'],
            ['bdmin_bank_name', 'string', 'min' => 2, 'max' => 100],
            
            
            ['bdmin_bank_account', 'filter', 'filter' => 'trim'],
            ['bdmin_bank_account', 'required'],
            ['bdmin_bank_account', 'string', 'min' => 2, 'max' => 100],
            
            ['tax_point', 'filter', 'filter' => 'trim'],
            ['tax_point', 'required'],
            
            ['invoice', 'filter', 'filter' => 'trim'],
            ['invoice', 'required'],
            ['invoice', 'string', 'min' => 1, 'max' => 100],
            
            ['authorized_brand', 'filter', 'filter' => 'trim'],
            ['authorized_brand', 'required'],
            ['authorized_brand', 'string', 'min' => 1, 'max' => 100],
            
            ['authorized_type', 'filter', 'filter' => 'trim'],
            ['authorized_type', 'required'],
            
            ['authorized_role', 'filter', 'filter' => 'trim'],
            ['authorized_role', 'required'],
            
           // ['authorized_letter', 'filter', 'filter' => 'trim'],
           // ['authorized_letter', 'required'],
          //  ['authorized_letter', 'string', 'min' => 1, 'max' => 150],
            
            ['authorized_at', 'filter', 'filter' => 'trim'],
            //['authorized_at', 'date'],
            ['authorized_at', 'required'],
            ['authorized_at','match','pattern'=>'/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/'],
            
          //  ['cooperation_letter', 'filter', 'filter' => 'trim'],
          //  ['cooperation_letter', 'required'],
          //  ['cooperation_letter', 'string', 'min' => 1, 'max' => 150],
            
            ['cooperationed_at', 'filter', 'filter' => 'trim'],
            ['cooperationed_at', 'required'],
            ['cooperationed_at','match','pattern'=>'/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/'],
            //['authorized_at', 'date'],
            
            ['shipping_date', 'filter', 'filter' => 'trim'],
            ['shipping_date', 'required'],
            
            ['order_date', 'filter', 'filter' => 'trim'],
            ['order_date', 'required'],
            
            ['remark', 'filter', 'filter' => 'trim'],
            
            ['zip_file', 'filter', 'filter' => 'trim'],
        ];

        return $current_rules ;
    }
    
    
}




