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
class Login
{
    public function getLastData($param = '')
    {
        $phone = isset($param['phone']) ? $param['phone'] : '';
        $this->breadcrumbs(Yii::$service->page->translate->__('Login'));
        return [
            'phone' => $phone,
        ];
    }

    // 面包屑导航
    protected function breadcrumbs($name)
    {
        if (Yii::$app->controller->module->params['login_breadcrumbs']) {
            Yii::$service->page->breadcrumbs->addItems(['name' => $name]);
        } else {
            Yii::$service->page->breadcrumbs->active = false;
        }
    }

    public function login($param)
    {
        if (is_array($param) && !empty($param)) {
            Yii::$service->customer->login($param);
        }
        Yii::$service->page->message->addByHelperErrors();
    }

}
