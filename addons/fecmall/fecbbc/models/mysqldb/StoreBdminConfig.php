<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\models\mysqldb;

use yii\db\ActiveRecord;
use yii\base\InvalidValueException;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class StoreBdminConfig extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%store_bdmin_config}}';
    }

}


