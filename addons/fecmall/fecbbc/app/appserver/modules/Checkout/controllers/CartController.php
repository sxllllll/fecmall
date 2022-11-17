<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecbbc\app\appserver\modules\Checkout\controllers;

use fecshop\app\appserver\modules\AppserverTokenController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CartController extends \fecshop\app\appserver\modules\Checkout\controllers\CartController
{
    public function actionIndex()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        // 检查用户是否登陆
        if (Yii::$app->user->isGuest) {
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $currency_info = Yii::$service->page->currency->getCurrencyInfo();
        $code = Yii::$service->helper->appserver->status_success;
        $cart_info = $this->getCartInfo();
        
        $data = [
            'cart_info' => $cart_info,
            'currency'  => $currency_info,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

        return $responseData;
    }

    /** @return data example
     *	[
     *				'coupon_code' 	=> $coupon_code,
     *				'grand_total' 	=> $grand_total,
     *				'shipping_cost' => $shippingCost,
     *				'coupon_cost' 	=> $couponCost,
     *				'product_total' => $product_total,
     *				'products' 		=> $products,
     *	]
     *			上面的products数组的个数如下：
     *			$products[] = [
     *					    'item_id' => $one['item_id'],
     *						'product_id' 		=> $product_id ,
     *						'qty' 				=> $qty ,
     *						'custom_option_sku' => $custom_option_sku ,
     *						'product_price' 	=> $product_price ,
     *						'product_row_price' => $product_row_price ,
     *						'product_name'		=> $product_one['name'],
     *						'product_url'		=> $product_one['url_key'],
     *						'product_image'		=> $product_one['image'],
     *						'custom_option'		=> $product_one['custom_option'],
     *						'spu_options' 		=> $productSpuOptions,
     *				];
     */
    public function getCartInfo()
    {
        $cart_info = Yii::$service->cart->getCartInfo(false);

        if (isset($cart_info['products']) && is_array($cart_info['products'])) {
            $bdmin_user_ids = [];
            $bdminSelectedArr = [];
            foreach ($cart_info['products'] as $bdmin_user_id => $cartProducts) {
                $bdmin_user_ids[] = $bdmin_user_id;
                $bdminSelected = true;
                if (is_array($cartProducts) && !empty($cartProducts)) {
                    foreach ($cartProducts as $k=>$product_one) {
                        $cart_info['products'][$bdmin_user_id][$k]['bdmin_user_id'] = $bdmin_user_id;
                        $cart_info['products'][$bdmin_user_id][$k]['name'] = Yii::$service->store->getStoreAttrVal($product_one['product_name'], 'name');
                        // 设置图片
                        if (isset($product_one['product_image']['main']['image'])) {
                            $productImg = $product_one['product_image']['main']['image'];
                            $cart_info['products'][$bdmin_user_id][$k]['image'] = $productImg;
                            $cart_info['products'][$bdmin_user_id][$k]['img_url'] = Yii::$service->product->image->getResize($productImg,[150,150],false);
                        }
                        unset($cart_info['products'][$bdmin_user_id][$k]['product_name']);
                        unset($cart_info['products'][$bdmin_user_id][$k]['product_image']);
                        // 将在产品页面选择的颜色尺码等属性显示出来。
                        $custom_option_info_arr = $this->getProductOptions($product_one);
                        $cart_info['products'][$bdmin_user_id][$k]['custom_option_info'] = $custom_option_info_arr;
                        
                        $activeStatus = Yii::$service->cart->quoteItem->activeStatus;
                        if ($product_one['active'] == $activeStatus) {
                            $cart_info['products'][$bdmin_user_id][$k]['active'] = 1;
                        } else {
                            $cart_info['products'][$bdmin_user_id][$k]['active'] = 0;
                            $bdminSelected = false;
                        }
                        //$cart_info['products'][$bdmin_user_id][$k]['active'] = ($product_one['active'] == $activeStatus) ? 1 : 0;
                        if ( isset($product_one['spu_options']) && is_array($product_one['spu_options']) ) {
                            $spu_options_arr = [];
                            foreach ($product_one['spu_options'] as $op_k => $op_v) {
                                $spu_options_arr[] = $op_k . ': ' . $op_v;
                            }
                            $cart_info['products'][$bdmin_user_id][$k]['spu_options_str'] = implode(',', $spu_options_arr);
                        }
                
                    }
                } else {
                    $bdminSelected = false;
                }
                $bdminSelectedArr[$bdmin_user_id] = $bdminSelected;
            }
            $bdminUserArr = Yii::$service->bdminUser->bdminUser->getIdAndPersonArrByIds($bdmin_user_ids);
            $cart_info['bdmin'] = [];
            if (is_array($bdminUserArr)) {
                foreach ($bdminUserArr as $bdminUserId => $bdminUserName) {
                    $bdminSelected = $bdminSelectedArr[$bdminUserId];
                    $cart_info['bdmin'][$bdminUserId] = [
                        'bdminUserName' => $bdminUserName,
                        'bdminUserSelected' => $bdminSelected,
                        
                    ];
                }
            }
            
        }

        return $cart_info;
    }
    
    public function actionSelectitems()
    {
        $checked = Yii::$app->request->post('checked');
        $items = Yii::$app->request->post('items');
        $items = explode(',', $items);
        $checked = $checked == 'true' ? true : false; 
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            // $status = Yii::$service->cart->selectAllItem($checked);
            $status = Yii::$service->cart->selectChoseItem($checked, $items);
            if (!$status) {
                throw new \Exception('cart select items fail');
            }
            $innerTransaction->commit();
        } catch (\Exception $e) {
            Yii::$service->helper->errors->add($e->getMessage());
            $innerTransaction->rollBack();
            $code = Yii::$service->helper->appserver->cart_product_select_fail;
            $data = [
                'errors' => Yii::$service->helper->errors->get(','),
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
}
