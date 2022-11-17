<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Checkout\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CartController extends \fecshop\app\appfront\modules\Checkout\controllers\CartController
{
    public $blockNamespace = 'fecbbc\app\appfront\modules\Checkout\block';
    
    public function init()
    {
        parent::init();
        Yii::$service->page->theme->layoutFile = 'cart.php';
    }
    
    public function actionIndex()
    {
        if (Yii::$service->store->isAppServerMobile()) {
            $urlPath = 'checkout/cart';
            Yii::$service->store->redirectAppServerMobile($urlPath);
        }
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
    
    public function actionSelectall()
    {
        $checked = Yii::$app->request->post('checked');
        $items = Yii::$app->request->post('items');
        $checked = $checked == 1 ? true : false; 
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            // $status = Yii::$service->cart->selectAllItem($checked);
            $status = Yii::$service->cart->selectChoseItem($checked, $items);
            if ($status) {
                echo json_encode([
                    'status' => 'success',
                ]);
                $innerTransaction->commit();
            } else {
                echo json_encode([
                    'status' => 'fail',
                    'content' => Yii::$service->helper->errors->get(',')
                ]);
                $innerTransaction->rollBack();
            }
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            echo $e->getMessage();
        }
        exit;
    }
    
}
