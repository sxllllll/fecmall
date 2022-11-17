<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appserver\modules\Customer\controllers;

use fecshop\app\appserver\modules\AppserverTokenController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class OrderController extends \fecshop\app\appserver\modules\Customer\controllers\OrderController
{
    public $blockNamespace = 'fecbbc\app\appserver\modules\Customer\block';
    
    // 订单发货
    public function actionShipping()
    {
        return $this->getBlock()->getLastData();
    }
    
    // 确认收货
    public function actionReceive()
    {
        $order_increment_id = Yii::$app->request->get('order_increment_id');
        if (!$order_increment_id) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        if (Yii::$app->user->isGuest) {
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $customerId = Yii::$app->user->identity->id;
        if (!Yii::$service->order->process->customerReceiveOrderByIncrementId($order_increment_id, $customerId )) {
            
            $code = Yii::$service->helper->appserver->customer_order_receive_fail;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $code = Yii::$service->helper->appserver->status_success;
        $responseData = Yii::$service->helper->appserver->getResponseData($code, []);
        
        return $responseData;
    
    }
    
    // 再次购买
    public function actionReorder()
    {
        
        return $this->getBlock()->getLastData();
    }
    
    public function actionWxview(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $increment_id = Yii::$app->request->get('increment_id');
        if ($increment_id) {
            $order_info = Yii::$service->order->getOrderInfoByIncrementId($increment_id);
            if (isset($order_info['customer_id']) && !empty($order_info['customer_id'])) {
                $identity = Yii::$app->user->identity;
                $customer_id = $identity->id;
                if ($order_info['customer_id'] == $customer_id) {
                    $order_info = $this->getOrderArr($order_info);
                    $productArr = [];
                    //var_dump($order_info);exit;
                    if(is_array($order_info['products'])){
                        foreach($order_info['products'] as $product){
                            $custom_option_info_arr = [];
                            if (is_array($product['custom_option_info'])) {
                                foreach ($product['custom_option_info'] as $attr => $val) {
                                    $custom_option_info_arr[] = Yii::$service->page->translate->__($attr) .':'. Yii::$service->page->translate->__($val);
                                }
                            }
                            $custom_option_info_str = implode(',', $custom_option_info_arr);
                            $productArr[] = [
                                'item_id' => $product['item_id'],
                                'is_reviewed' => $product['is_reviewed'],
                                'imgUrl' => Yii::$service->product->image->getResize($product['image'],[100,100],false),
                                'name' => $product['name'],
                                'sku' => $product['sku'],
                                'qty' => $product['qty'],
                                'row_total' => $product['row_total'],
                                'price' => $product['price'],
                                'product_id' => $product['product_id'],
                                'custom_option_info' => $product['custom_option_info'],
                                'custom_option_info_str' =>$custom_option_info_str,
                            ];

                        }
                    }
                    $order_info['products'] = $productArr;
                    $code = Yii::$service->helper->appserver->status_success;
                    $data = [
                        'order'=> $order_info,
                    ];
                    $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

                    return $responseData;

                }
            }
        }


    }
    
    // 再次购买
    public function actionReview()
    {
        $orderReview =  Yii::$app->request->post('orderReview');
        if (!$orderReview) {
            
            return;
        }
        $orderReview = json_decode($orderReview, true);
        $orderId = isset($orderReview['orderId']) ? $orderReview['orderId'] : '';
        $reviews = isset($orderReview['reviews']) ? $orderReview['reviews'] : [];  // ["product_id"]=> string(2) "41" ["item_id"]=> string(3) "260" ["star"]=> string(1) "5"
        $arr = [];
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($reviews as $one) {
                $product_id = $one['product_id'];
                $item_id = $one['item_id'];
                $star = $one['star'];
                $review_content = $one['review'];
                $productModel  = Yii::$service->product->getByPrimaryKey($product_id);
                $arr['order_id'] = $orderId;
                $arr['rate_star'] = $star;
                $arr['item_id'] = $item_id;
                $arr['product_id'] = $product_id;
                $arr['review_content'] = $review_content;
                $arr['customer_id'] = Yii::$app->user->identity->id;
                $arr['customer_name'] = Yii::$app->user->identity->firstname;
                if (!Yii::$service->product->review->addOrderReview($arr)) {
                    throw new \Exception('product add review fail');
                }
            }
            $innerTransaction->commit();
        } catch (\Exception $e) {
            Yii::$service->helper->errors->add($e->getMessage());
            $innerTransaction->rollBack();
            $errors =  Yii::$service->helper->errors->get(',');
            
            $code = Yii::$service->helper->appserver->customer_order_review_fail;
            $data = [
                'message' => $errors,
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
            return $responseData;
        }
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    public function getOrderArr($order){
        $orderInfo = [];
        $orderInfo['created_at'] = date('Y-m-d H:i:s',$order['created_at']);
        $orderInfo['updated_at'] = date('Y-m-d H:i:s',$order['updated_at']);
        $orderInfo['increment_id'] = $order['increment_id'];
        $orderInfo['order_id'] = $order['order_id'];
        $orderInfo['order_status'] = $order['order_status'];
        $orderInfo['items_count'] = $order['items_count'];
        $orderInfo['total_weight'] = $order['total_weight'];
        $orderInfo['order_currency_code'] = $order['order_currency_code'];
        $orderInfo['order_to_base_rate'] = $order['order_to_base_rate'];
        $orderInfo['grand_total'] = $order['grand_total'];
        $orderInfo['base_grand_total'] = $order['base_grand_total'];
        $orderInfo['subtotal'] = $order['subtotal'];
        $orderInfo['base_subtotal'] = $order['base_subtotal'];
        $orderInfo['subtotal_with_discount'] = $order['subtotal_with_discount'];
        $orderInfo['base_subtotal_with_discount'] = $order['base_subtotal_with_discount'];
        $orderInfo['checkout_method'] = $order['checkout_method'];
        $orderInfo['customer_id'] = $order['customer_id'];
        $orderInfo['customer_group'] = $order['customer_group'];
        $orderInfo['customer_email'] = $order['customer_email'];
        $orderInfo['customer_firstname'] = $order['customer_firstname'];
        $orderInfo['customer_lastname'] = $order['customer_lastname'];
        $orderInfo['customer_is_guest'] = $order['customer_is_guest'];
        $orderInfo['coupon_code'] = $order['coupon_code'];
        $orderInfo['payment_method'] = $order['payment_method'];
        $orderInfo['payment_method_str'] = Yii::$service->page->translate->__($order['payment_method']);
        $orderInfo['shipping_method'] = $order['shipping_method'];
        $orderInfo['shipping_method_str'] = Yii::$service->page->translate->__($order['shipping_method']);
        $orderInfo['tracking_number'] = $order['tracking_number'];
        $orderInfo['tracking_company'] = $order['tracking_company'];
        $orderInfo['tracking_company_str'] = Yii::$service->delivery->getShipperName($order['tracking_company']);
        $orderInfo['is_reviewed'] = $order['is_reviewed'];
        $orderInfo['shipping_total'] = $order['shipping_total'];
        $orderInfo['base_shipping_total'] = $order['base_shipping_total'];
        $orderInfo['customer_telephone'] = $order['customer_telephone'];
        $orderInfo['customer_address_country'] = $order['customer_address_country'];
        $orderInfo['customer_address_state'] = $order['customer_address_state'];
        $orderInfo['customer_address_city'] = $order['customer_address_city'];
        $orderInfo['customer_address_zip'] = $order['customer_address_zip'];
        $orderInfo['customer_address_street1'] = $order['customer_address_street1'];
        $orderInfo['customer_address_street2'] = $order['customer_address_street2'];
        $orderInfo['customer_address_state_name'] = $order['customer_address_state_name'];
        $orderInfo['customer_address_country_name'] = $order['customer_address_country_name'];
        $orderInfo['currency_symbol'] = $order['currency_symbol'];
        $orderInfo['products'] = $order['products'];



        return $orderInfo;
    }
    
    
    
    // 订单售后
    public function actionAftersale()
    {
        $order_id = Yii::$app->request->get('order_id');
        $order_info = Yii::$service->order->getOrderInfoById($order_id);
        $can_after_sale = Yii::$service->order->info->isCustomerCanAfterSale($order_info);
        if (!$can_after_sale) {
            $code = Yii::$service->helper->appserver->customer_order_can_not_aftersale;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $order_info = $this->getCustomerOrderInfo($order_info);
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'order_info' =>$order_info,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
    }
    
    // 退货查看
    public function actionReturnview(){
        $item_id = Yii::$app->request->get('item_id');
        $order_id = Yii::$app->request->get('order_id');
        // 参数
        if (!$item_id || !$order_id) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $responseData = Yii::$service->helper->appserver->getResponseData($code, []);
            
            return $responseData;
        }
        $order_item = Yii::$service->order->item->getByPrimaryKey($item_id);
        // 找到order
        $order_id = $order_item['order_id'];
        if (!$order_id) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $responseData = Yii::$service->helper->appserver->getResponseData($code, []);
            
            return $responseData;
        }
        // 判断权限，是否是当前用户的order
        $order_info = Yii::$service->order->getOrderInfoById($order_id);
        $identity = Yii::$app->user->identity;
        $customer_id = $identity->id;
        if ($order_info['customer_id'] != $customer_id) { 
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $responseData = Yii::$service->helper->appserver->getResponseData($code, []);
            
            return $responseData;
        }
        // 如果存在退货信息，那么进行跳转
        $afterSaleOne = Yii::$service->order->afterSale->getByItemId($item_id);
        if ($afterSaleOne['order_id']) { 
            
            $code = Yii::$service->helper->appserver->customer_order_return_is_exist;
            $responseData = Yii::$service->helper->appserver->getResponseData($code, [
                'as_id' =>$afterSaleOne['id'],
            ]);
            
            return $responseData;
        }
        $product_arr = [];
        if (is_array($order_info['products'])) {
            foreach ($order_info['products'] as $product) {
                if ($product['item_id'] == $item_id) {
                    $product_arr[] = $product;
                }
            }
        }
        $order_info['products'] = $product_arr;
        $order_info = $this->getCustomerOrderInfo($order_info);
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'order_info' =>$order_info,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    public function getCustomerOrderInfo($order_info)
    {
        if (isset($order_info['customer_id']) && !empty($order_info['customer_id'])) {
            $identity = Yii::$app->user->identity;
            $customer_id = $identity->id;
            if ($order_info['customer_id'] == $customer_id) {
                $order_info = $this->getOrderArr($order_info);
                $productArr = [];
                if(is_array($order_info['products'])){
                    foreach($order_info['products'] as $product){
                        $custom_option_info_arr = [];
                        if (is_array($product['custom_option_info'])) {
                            foreach ($product['custom_option_info'] as $attr => $val) {
                                $custom_option_info_arr[] = Yii::$service->page->translate->__($attr) .':'. Yii::$service->page->translate->__($val);
                            }
                        }
                        $custom_option_info_str = implode(',', $custom_option_info_arr);
                        $productArr[] = [
                            'item_id' => $product['item_id'],
                            'is_reviewed' => $product['is_reviewed'],
                            'imgUrl' => Yii::$service->product->image->getResize($product['image'],[100,100],false),
                            'name' => $product['name'],
                            'sku' => $product['sku'],
                            'qty' => $product['qty'],
                            'row_total' => $product['row_total'],
                            'price' => $product['price'],
                            'product_id' => $product['product_id'],
                            'custom_option_info' => $product['custom_option_info'],
                            'custom_option_info_str' =>$custom_option_info_str,
                        ];

                    }
                }
                $order_info['products'] = $productArr;
                
                return $order_info;
            }
        }
            
        return [];
    }
    
    
    // 提交退货请求
    public function actionReturnsubmit(){
        $editForm = Yii::$app->request->post();
        $item_id = $editForm['item_id'];
        // $order_id = $editForm['order_id'];
        $return_qty = $editForm['return_qty'];
        // 参数判断。
        if (!$return_qty || !$item_id) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }   
        
        $orderItemModel = Yii::$service->order->item->getByPrimaryKey($item_id);
        $order_id = $orderItemModel['order_id'];
        $orderItemQty = $orderItemModel['qty'];
        if ($return_qty > $orderItemQty) {
            $code = Yii::$service->helper->appserver->customer_order_return_qty_limit;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        // 参数
        if (!$order_id) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        // 订单的customer_id 是否是当前用户
        $identity = Yii::$app->user->identity;
        if ($orderItemModel['customer_id'] != $identity->id) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $orderModel = Yii::$service->order->getByPrimaryKey($order_id);
        // 退款请求处理
        $as_id = Yii::$service->order->afterSale->requestReturn($orderModel, $orderItemModel, $return_qty);
        if (!$as_id) {
            $code = Yii::$service->helper->appserver->customer_order_return_submit_fail;
            $data = [
                'errors' => Yii::$service->helper->errors->get(','),
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = ['as_id' => $as_id];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    
    // 已经发起的退货信息查看
    public function actionReturnstatus(){
        $as_id = Yii::$app->request->get('as_id');
        // 参数
        if (!$as_id) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }    
        // 得到退货信息
        $afterSaleOne = Yii::$service->order->afterSale->getInfoByPrimaryKey($as_id);
        if (!is_array($afterSaleOne) || empty($afterSaleOne)) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $customer_id = $this->getCustomerId();
        // 判断当前的customer_id是否等于订单中的customer_id
        if ($customer_id && $afterSaleOne['customer_id'] != $customer_id) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $shipping_companys = [];
        $shippingCompany = Yii::$service->delivery->getCompanyArr();
        if (is_array($shippingCompany) && !empty($shippingCompany)) {
            foreach ($shippingCompany as $k=>$v) {
                $shipping_companys[] = [
                    'id' => $k,
                    'name' => $v,
                ];
            }
        }
        $productName = '';
        $productModel = Yii::$service->product->getByPrimaryKey($afterSaleOne['product_id']);
        if (isset($productModel['name']) && $productModel['name']) {
            $productName = Yii::$service->store->getStoreAttrVal($productModel['name'], 'name');
        }
        $data = [
            'after_sale'=> [
                'id' => $afterSaleOne['id'],
                'product_id' => $afterSaleOne['product_id'],
                'image' => Yii::$service->product->image->getResize($afterSaleOne['image'], [100, 100], false),
                'status' => Yii::$service->page->translate->__($afterSaleOne['status']),
                'can_cancel' => Yii::$service->order->info->isCustomerCanCancelAfterSaleReturndOrder($afterSaleOne),
                'sku' => $afterSaleOne['sku'],
                'product_name' => $productName,
                'custom_option_info' => $afterSaleOne['custom_option_info'],
                'base_price' => $afterSaleOne['base_price'],
                'price' => $afterSaleOne['price'],
                'qty' => $afterSaleOne['qty'],
                'currency_symbol' => Yii::$service->page->currency->getSymbol($afterSaleOne['currency_code']),
                'tracking_number' => $afterSaleOne['tracking_number'],
                'tracking_company' => $afterSaleOne['tracking_company'],
                'tracking_company_str' => $shippingCompany[$afterSaleOne['tracking_company']],
                'show_dispatch' => Yii::$service->order->info->isCustomerCanDispatchAfterSaleReturndOrder($afterSaleOne),
                'show_shipping' => Yii::$service->order->info->isCustomerCanShowAfterSaleReturndShipping($afterSaleOne),
            ],
            'shipping_companys' => $shipping_companys,
        ];
            
        $code = Yii::$service->helper->appserver->status_success;
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    // 撤销退货请求
    public function actionReturncancel()
    {
        $as_id = Yii::$app->request->post('as_id');
        $customer_id = $this->getCustomerId();
        if (!Yii::$service->order->afterSale->customerCancelReturnByAsId($as_id, $customer_id)) {
            $data = [ 'as_id' => $as_id ];
            $code = Yii::$service->helper->appserver->customer_order_return_cancel;
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $data = [ 'as_id' => $as_id ];
        $code = Yii::$service->helper->appserver->status_success;
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    // 退货商品发货
    public function actionReturndispatch()
    {
        $editForm = Yii::$app->request->post();
        $as_id = $editForm['as_id'];
        $tracking_company = $editForm['tracking_company'];
        $tracking_number = $editForm['tracking_number'];
        $customer_id = $this->getCustomerId();
        
        if (!Yii::$service->order->afterSale->customerDispatchReturnByAsId($as_id,  $tracking_company, $tracking_number, $customer_id)) {
            $data = [ 'as_id' => $as_id ];
            $code = Yii::$service->helper->appserver->customer_order_return_dispatch_fail;
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $data = [ 'as_id' => $as_id ];
        $code = Yii::$service->helper->appserver->status_success;
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    public function getCustomerId()
    {
        $identity = Yii::$app->user->identity;
        
        return $identity->id;
    }
    
    public function initWxWhere($where)
    {
        $wxRequestOrderStatus = Yii::$app->request->get("wxRequestOrderStatus");
        $this->numPerPage = 100;
        
        if (!$wxRequestOrderStatus || $wxRequestOrderStatus == 'all') {
            
            return $where;
        }
        if ($wxRequestOrderStatus == 1) {
            $where[] = ['in', 'order_status', Yii::$service->order->info->orderStatusPaymentPendingArr]; 
            $where[] = ['in', 'order_operate_status', Yii::$service->order->info->orderOperateStatusPaymentPendingArr]; 
        } else if ($wxRequestOrderStatus == 2) {
            $where[] = ['in', 'order_status', Yii::$service->order->info->orderStatusWaintingDispatchArr]; 
            $where[] = ['in', 'order_operate_status', Yii::$service->order->info->orderOperateStatusWaintingDispatchArr]; 
        } else if ($wxRequestOrderStatus == 3) {
            $where[] = ['in', 'order_status', Yii::$service->order->info->orderStatusWaintingReceiveArr]; 
            $where[] = ['in', 'order_operate_status', Yii::$service->order->info->orderOperateStatusWaintingReceiveArr]; 
        } else if ($wxRequestOrderStatus == 4) {
            $where[] = ['in', 'order_status', Yii::$service->order->info->orderStatusCompleteArr]; 
            $where[] = ['in', 'order_operate_status', Yii::$service->order->info->orderOperateStatusCompleteArr]; 
        }
        
        return $where;
    }
}
