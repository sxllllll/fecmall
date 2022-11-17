<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecmall.com/license/
 */
namespace fecbbc\app\apphtml5\modules\Catalog\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ShopController extends AppfrontController
{
    
    public $blockNamespace = 'fecbbc\app\apphtml5\modules\Catalog\block';
    
    // 网站信息管理
    public function actionIndex()
    {
        $bdmin_user_id = Yii::$app->request->get('bdmin_user_id');
        if (!$bdmin_user_id) {
            Yii::$service->url->redirectHome();
        }
        // 验证是否合法
        if (!Yii::$service->bdminUser->bdminUser->isActiveBdminUser($bdmin_user_id)) {
            Yii::$service->url->redirectHome();
        }
        Yii::$service->page->theme->layoutFile = 'home.php';
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
    
}
