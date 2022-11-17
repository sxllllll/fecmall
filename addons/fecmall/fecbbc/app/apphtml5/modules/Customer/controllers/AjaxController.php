<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Customer\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AjaxController extends \fecshop\app\apphtml5\modules\Customer\controllers\AjaxController
{
    
    public function actionProduct()
    {
        $result_arr = [];
        if (Yii::$app->request->isAjax) {
            $result_arr['loginStatus'] = false;
            $result_arr['favorite'] = false;
            $product_id = Yii::$app->request->get('product_id');
            if (!Yii::$app->user->isGuest) {
                $result_arr['loginStatus'] = true;
                if ($product_id) {
                    $favorite = Yii::$service->product->favorite->getByProductIdAndUserId($product_id);
                    $favorite ? ($result_arr['favorite'] = true) : '';
                }
            }
            if ($product_id) {
                // 添加csrf数据
                //$csrfName = \fec\helpers\CRequest::getCsrfName();
                //$csrfVal = \fec\helpers\CRequest::getCsrfValue();
                //$result_arr['csrfName'] = $csrfName;
                //$result_arr['csrfVal'] = $csrfVal;
                $result_arr['product_id'] = $product_id;
            }
        }
        $result_arr['cart_qty'] = Yii::$service->cart->getCartItemQty();
        echo json_encode($result_arr);
        exit;
    }

}
