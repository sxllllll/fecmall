<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\models\mysqldb;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use Yii;
use yii\base\BaseObject;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class BdminUser extends \fecshop\models\mysqldb\AdminUser
{
    const AUDIT_STATUS_ACCEPT = 1;
    const AUDIT_STATUS_INIT = 2;
    const AUDIT_STATUS_REFUSE = 3;
    /**
     * @inheritdoc
     */
    # 设置table
    public static function tableName()
    {
        return '{{%bdmin_user}}';
    }

    
}    