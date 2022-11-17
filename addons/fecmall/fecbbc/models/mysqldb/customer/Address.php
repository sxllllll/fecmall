<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\models\mysqldb\customer;

use yii\db\ActiveRecord;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Address extends ActiveRecord
{
    const STATUS_DELETED = 10;
    const STATUS_ACTIVE = 1;

    public static function tableName()
    {
        return '{{%customer_address}}';
    }
    
    public function rules()
    {
        $rules = [
            
            ['first_name', 'filter', 'filter' => 'trim'],
            ['first_name', 'required'],
            ['first_name', 'string', 'length' => [1, 50]],
            
            ['telephone', 'filter', 'filter' => 'trim'],
            ['telephone', 'required'],
            ['telephone', 'string', 'length' => [1, 50]],
            
            ['street1', 'filter', 'filter' => 'trim'],
            ['street1', 'required'],
            ['street1', 'string', 'length' => [1, 500]],
            
            ['city', 'filter', 'filter' => 'trim'],
            ['city', 'required'],
            ['city', 'string', 'length' => [1, 50]],
            
            ['state', 'filter', 'filter' => 'trim'],
            ['state', 'required'],
            ['state', 'string', 'length' => [1, 50]],
            
            ['area', 'filter', 'filter' => 'trim'],
            ['area', 'required'],
            ['area', 'string', 'length' => [1, 50]],
            
            ['country', 'filter', 'filter' => 'trim'],
            ['country', 'required'],
            ['country', 'string', 'length' => [1, 50]],
        ];

        return $rules;
    }
    
    
}
