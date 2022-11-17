<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Customer\block\editpassword;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
{
    public function getLastData()
    {
        $identity = Yii::$app->user->identity;

        return [
            'phone'        => $identity['phone'],
            'wx_password_is_set'        => $identity['wx_password_is_set'],
            'actionUrl'        => Yii::$service->url->getUrl('customer/editpassword'),
        ];
    }

    /**
     * @param $editForm|array
     * 保存修改后的用户信息。
     */
    public function savePassword($editForm)
    {
        if (is_array($editForm) && !empty($editForm)) {
            $editForm = \Yii::$service->helper->htmlEncode($editForm);
            $identity = Yii::$app->user->identity;
             // 值为1，代表已经设置密码，2代表没有设置密码（譬如微信扫码的用户，默认密码没有设置）
            $wx_password_is_set = $identity['wx_password_is_set'];
            
            $current_password = $editForm['current_password'] ? $editForm['current_password'] : '';
            $password = $editForm['password'] ? $editForm['password'] : '';
            $confirmation = $editForm['confirmation'] ? $editForm['confirmation'] : '';
            
            if (!$current_password && ($wx_password_is_set == 1)) {
                Yii::$service->page->message->addError('current password can not empty');

                return;
            }
            if (!$password || !$confirmation) {
                Yii::$service->page->message->addError('password and confirmation password can not empty');

                return;
            }
            if ($password != $confirmation) {
                Yii::$service->page->message->addError('password and confirmation password  must be equal');

                return;
            }
            if (($wx_password_is_set == 1) && !$identity->validatePassword($current_password)) {
                Yii::$service->page->message->addError('Current password is not right,If you forget your password, you can retrieve your password by forgetting your password in login page');

                return;
            }
            $identity->setPassword($password);
            $identity['wx_password_is_set'] = 1;
            if ($identity->validate()) {
                $identity->save();
                
                return true;
            } else {
                $errors = $identity->errors;
                if (is_array($errors) && !empty($errors)) {
                    foreach ($errors as $error) {
                        if (is_array($error) && !empty($error)) {
                            foreach ($error as $er) {
                                Yii::$service->page->message->addError($er);
                            }
                        }
                    }
                }
            }
            return false;
        }
    }
}
