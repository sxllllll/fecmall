<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Customer\block\account;

use fecshop\app\apphtml5\helper\mailer\Email;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Login
{
    public function getLastData($param = '')
    {
        $phone = isset($param['phone']) ? $param['phone'] : '';

        return [
            'phone' => $phone,
        ];
    }

    public function login($param)
    {
        if (is_array($param) && !empty($param)) {
            Yii::$service->customer->login($param);
        }
        Yii::$service->page->message->addByHelperErrors();
    }

}
