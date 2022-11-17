<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Catalog\controllers;


use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ReviewproductController extends \fecshop\app\appfront\modules\Catalog\controllers\ReviewproductController
{
    
    public $enableCsrfValidation = true;
    public $blockNamespace = 'fecbbc\app\appfront\modules\Catalog\block';
    
    public function init()
    {
        parent::init();
        Yii::$service->page->theme->layoutFile = 'customer.php';
    }

    // 增加评论
    public function actionAdd()
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account/login');
        }
        if ( Yii::$app->user->isGuest) {
            $currentUrl = Yii::$service->url->getCurrentUrl();
            Yii::$service->customer->setLoginSuccessRedirectUrl($currentUrl);

            // 如果评论产品必须登录用户，则跳转到用户登录页面
            return Yii::$service->url->redirectByUrlKey('customer/account/login');
        }
        $editForm = Yii::$app->request->post('editForm');
        $editForm = \Yii::$service->helper->htmlEncode($editForm);
        if (!empty($editForm) && is_array($editForm)) {
            $editForm['product_id'] = Yii::$app->request->get('product_id');
            $editForm['order_id'] = Yii::$app->request->get('order_id');
            $editForm['item_id'] = Yii::$app->request->get('item_id');
            $saveStatus = $this->getBlock()->saveOrderReview($editForm);
            if ($saveStatus) {
                $_id = $editForm['product_id'];
                $product = Yii::$service->product->getByPrimaryKey($_id);
                $spu = $product['spu'];
                if ($spu && $_id) {
                    $url = Yii::$service->url->getUrl('customer/productreview', ['spu' => $spu, '_id'=>$_id]);
                    return $this->redirect($url);
                }
            }
        }
        //echo 1;exit;
        $data = $this->getBlock()->getLastData($editForm);

        return $this->render($this->action->id, $data);
    }

    public function actionLists()
    {
        $data = $this->getBlock()->getLastData($editForm);

        return $this->render($this->action->id, $data);
    }
    
}