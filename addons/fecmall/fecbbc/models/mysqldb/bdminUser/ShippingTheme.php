<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\models\mysqldb\bdminUser;

use yii\db\ActiveRecord;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ShippingTheme extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%bdmin_shipping_theme}}';
    }
    
}
