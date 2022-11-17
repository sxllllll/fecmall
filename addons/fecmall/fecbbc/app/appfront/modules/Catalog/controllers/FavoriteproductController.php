<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Catalog\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class FavoriteproductController extends \fecshop\app\appfront\modules\Catalog\controllers\FavoriteproductController
{
    public $enableCsrfValidation = false;
    // 增加收藏
    public function actionFavo()
    {
        $product_id = Yii::$app->request->get('product_id');
        $product = Yii::$service->product->getByPrimaryKey($product_id);
        //没有登录的用户跳转到登录页面
        if (Yii::$app->user->isGuest) {
            $url = Yii::$service->url->getUrl($product['url_key']);
            Yii::$service->customer->setLoginSuccessRedirectUrl($url);
            //return Yii::$service->url->redirectByUrlKey('customer/account/login');
            echo json_encode([
                'status' => 200,
                'loginStatus' => false,
                'content' => 'customer not login account',
            ]);exit;
        }
        $identity = Yii::$app->user->identity;
        $user_id = $identity->id;
        
        $favoModel =Yii::$service->product->favorite->getByProductIdAndUserId($product_id, $user_id);
        if ($favoModel) {
            Yii::$service->product->favorite->removeByProductIdAndUserId($product_id, $user_id);
            echo json_encode([
                'status' => 200,
                'loginStatus' => true,
                'favoriteStatus' => false,
                'content' => '',
                'product_id' => $product_id,
                'user_id' => $user_id,
            ]);
        } else {
            $bdmin_user_id = $product['bdmin_user_id'];
            Yii::$service->product->favorite->addFav($bdmin_user_id, $product_id, $user_id);
            echo json_encode([
                'status' => 200,
                'loginStatus' => true,
                'favoriteStatus' => true,
                'content' => '',
            ]);
        }
        exit;
        
    }
}
