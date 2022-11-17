<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Checkout\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CartController extends \fecshop\app\apphtml5\modules\Checkout\controllers\CartController
{
    public $blockNamespace = 'fecbbc\app\apphtml5\modules\Checkout\block';
    
    public function init()
    {
        parent::init();
        Yii::$service->page->theme->layoutFile = 'cart.php';
    }
    /**
     * 把产品加入到购物车.
     */
    public function actionAdd()
    {
        $custom_option = Yii::$app->request->post('custom_option');
        $product_id = Yii::$app->request->post('product_id');
        $qty = Yii::$app->request->post('qty');
        $buy_now = Yii::$app->request->post('buy_now');
        //$custom_option  = \Yii::$service->helper->htmlEncode($custom_option);
        $product_id = \Yii::$service->helper->htmlEncode($product_id);
        $qty = \Yii::$service->helper->htmlEncode($qty);
        
        $qty = abs(ceil((int) $qty));
        if ($qty && $product_id) {
            if ($custom_option) {
                $custom_option_sku = json_decode($custom_option, true);
            }
            if (empty($custom_option_sku)) {
                $custom_option_sku = null;
            }
            $item = [
                'product_id' => $product_id,
                'qty'        =>  $qty,
                'custom_option_sku' => $custom_option_sku,
            ];
            $innerTransaction = Yii::$app->db->beginTransaction();
            try {
                if ($buy_now == 'buy_now') {
                    $addToCart = Yii::$service->cart->addProductToBuyNowCart($item);
                } else {
                    $addToCart = Yii::$service->cart->addProductToCart($item);
                }
                if ($addToCart) {
                    echo json_encode([
                        'status' => 'success',
                        'items_count' => Yii::$service->cart->quote->getCartItemCount(),
                    ]);
                    $innerTransaction->commit();
                    exit;
                } else {
                    $errors = Yii::$service->helper->errors->get(',');
                    echo json_encode([
                        'status' => 'fail',
                        'content'=> Yii::$service->page->translate->__($errors),
                        //'items_count' => Yii::$service->cart->quote->getCartItemCount(),
                    ]);
                    $innerTransaction->rollBack();
                    exit;
                }
            } catch (\Exception $e) {
                $innerTransaction->rollBack();
                echo json_encode([
                    'status' => 'fail',
                    'content'=> $e->getMessage(),
                    //'items_count' => Yii::$service->cart->quote->getCartItemCount(),
                ]);
            }
        }
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
