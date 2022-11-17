<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Customer\block\editaccount;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends \fecshop\app\apphtml5\modules\Customer\block\editaccount\Index
{
    public function getLastData()
    {
        $identity = Yii::$app->user->identity;

        return [
            'phone'        => $identity['phone'],
            'wx_password_is_set'        => $identity['wx_password_is_set'],
            'actionUrl'        => Yii::$service->url->getUrl('customer/editaccount'),
        ];
    }

    /**
     * @param $editForm|array
     * 保存修改后的用户信息。
     */
    public function saveAccount($editForm)
    {
        if (is_array($editForm) && !empty($editForm)) {
            $editForm = \Yii::$service->helper->htmlEncode($editForm);
            $identity = Yii::$app->user->identity;
            $firstname = $editForm['firstname'] ? $editForm['firstname'] : '';
            
            if (!$firstname) {
                Yii::$service->page->message->addError('first name can not empty');

                return;
            }

            $identity->firstname = $firstname;

            if ($identity->validate()) {
                $identity->save();
                // Yii::$service->page->message->addCorrect('edit account info success');
                
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
        }
    }
}
