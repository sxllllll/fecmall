<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Customer\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class OrderController extends \fecshop\app\appfront\modules\Customer\controllers\OrderController
{
    
    public $blockNamespace = 'fecbbc\app\appfront\modules\Customer\block';

    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account/login');
        }
        Yii::$service->page->theme->layoutFile = 'customer.php';
    }
    // 订单列表
    public function actionIndex()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
    // 订单详细
    public function actionView()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
    // 确认收货
    public function actionReceive()
    {
        if (!$this->getBlock()->orderReceive()) {
            Yii::$service->page->message->addByHelperErrors();
        }
        return Yii::$service->url->redirectByUrlKey('customer/order/index');
    }
    // 用户在账户中心延迟收货时间
    public function actionDelayreceive()
    {
        $order_id = Yii::$app->request->get('order_id');
        $customer_id = $this->getCustomerId();
        $delayReceiveStatus = Yii::$service->order->process->customerDelayReceiveOrderById($order_id, $customer_id);
        if (!$delayReceiveStatus) {
            Yii::$service->page->message->addByHelperErrors();
        }
        
        return Yii::$service->url->redirectByUrlKey('customer/order/index');
    }
    
    // 订单发货
    public function actionShipping()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
    // 再次购买
    public function actionReorder()
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account/login');
        }
        return $this->getBlock()->getLastData();
        //return $this->render($this->action->id,$data);
    }
    // 订单取消
    public function actionCancel()
    {
        $increment_id = Yii::$app->request->get('order_increment_id');;
        $customer_id = $this->getCustomerId();
        // 事务处理
        $innerTransaction = Yii::$app->db->beginTransaction();
        try { 
            $requestCancelStatus = Yii::$service->order->process->customerRequestCancelByIncrementId($increment_id, $customer_id);
            if (!$requestCancelStatus) {
                throw new \Exception('customer request order cancel fail');
            }
            $innerTransaction->commit();
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            $errorMessage = $e->getMessage();
            Yii::$service->helper->errors->add($errorMessage);
        }
        Yii::$service->page->message->addByHelperErrors();
        return Yii::$service->url->redirectByUrlKey('customer/order');
    }
    // 用户在账户中心取消订单撤回
    public function actionCancelback()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        
        $increment_id = Yii::$app->request->get('order_increment_id');
        $customer_id = $this->getCustomerId();
        $cancelBackStatus = Yii::$service->order->process->customerCancelBackByIncrementId($increment_id, $customer_id);
        if (!$cancelBackStatus) {
            // 
        }
        Yii::$service->page->message->addByHelperErrors();
        return Yii::$service->url->redirectByUrlKey('customer/order');
    }
   
    
    // 订单评价 - 产品列表
    public function actionReviewproduct()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
    public function getCustomerId()
    {
        $identity = Yii::$app->user->identity;
        
        return $identity->id;
    }
    
    // 订单售后
    public function actionAftersale()
    {
        $order_id = Yii::$app->request->get('order_id');
        $order_info = Yii::$service->order->getOrderInfoById($order_id);
        $can_after_sale = Yii::$service->order->info->isCustomerCanAfterSale($order_info);
        if (!$can_after_sale) {
            return Yii::$service->url->redirectByUrlKey('customer/order');
        }
        
        $data = $this->getBlock()->getLastData($order_info);

        return $this->render($this->action->id, $data);
    }
    // 退货查看
    public function actionReturnview(){
        $item_id = Yii::$app->request->get('item_id');
        // 参数
        if (!$item_id) {
            return Yii::$service->url->redirectByUrlKey('customer/order');
        }
        $order_item = Yii::$service->order->item->getByPrimaryKey($item_id);
        // 找到order
        $order_id = $order_item['order_id'];
        if (!$order_id) {
            return Yii::$service->url->redirectByUrlKey('customer/order');
        }
        // 判断权限，是否是当前用户的order
        $order_info = Yii::$service->order->getOrderInfoById($order_id);
        $identity = Yii::$app->user->identity;
        $customer_id = $identity->id;
        if ($order_info['customer_id'] != $customer_id) { 
            Yii::$service->page->message->addError('you do not have role operate it');
            
            return Yii::$service->url->redirectByUrlKey('customer/order');
        }
        // 如果存在退货信息，那么进行跳转
        $afterSaleOne = Yii::$service->order->afterSale->getByItemId($item_id);
        if ($afterSaleOne['order_id']) { 
            return Yii::$service->url->redirectByUrlKey('customer/order/returnstatus', ['as_id' => $afterSaleOne['id']]);
        }
        $data = $this->getBlock()->getLastData($afterSaleOne, $order_info, $order_id, $item_id );

        return $this->render($this->action->id, $data);
    }
    // 提交退货请求
    public function actionReturnsubmit(){
        $editForm = Yii::$app->request->post('editForm');
        $item_id = $editForm['item_id'];
        $return_qty = $editForm['return_qty'];
        // 参数判断。
        if (!$return_qty) {
            Yii::$service->page->message->addError('return qty is empty');
            
            return Yii::$service->url->redirectByUrlKey('customer/order/returnview');
        }    
        if (!$item_id) {
            Yii::$service->page->message->addError('return qty is empty');
            
            return Yii::$service->url->redirectByUrlKey('customer/order/returnview');
            
        }   
        
        $orderItemModel = Yii::$service->order->item->getByPrimaryKey($item_id);
        $order_id = $orderItemModel['order_id'];
        $orderItemQty = $orderItemModel['qty'];
        if ($return_qty > $orderItemQty) {
            Yii::$service->page->message->addError('return qty can not >' .  $orderItemQty);
            
            return Yii::$service->url->redirectByUrlKey('customer/order/returnview');
        }
        // 参数
        if (!$order_id) {
            Yii::$service->page->message->addError('order id is empty');
            
            return Yii::$service->url->redirectByUrlKey('customer/order/returnview');
        }
        // 订单的customer_id 是否是当前用户
        $identity = Yii::$app->user->identity;
        if ($orderItemModel['customer_id'] != $identity->id) {
            Yii::$service->page->message->addError('you do not have role to operate order');
            
            return Yii::$service->url->redirectByUrlKey('customer/order/returnview');
        }
        
        $orderModel = Yii::$service->order->getByPrimaryKey($order_id);
        // 退款请求处理
        $as_id = Yii::$service->order->afterSale->requestReturn($orderModel, $orderItemModel, $return_qty);
        if (!$as_id) {
            
            return Yii::$service->url->redirectByUrlKey('customer/order');
        }
        
        return Yii::$service->url->redirectByUrlKey('customer/order/returnstatus', ['as_id' => $as_id]);
    }
    // 已经发起的退货信息查看
    public function actionReturnstatus(){
        $as_id = Yii::$app->request->get('as_id');
        
        // 参数
        if (!$as_id) {
            return Yii::$service->url->redirectByUrlKey('customer/order');
        }    
        // 得到退货信息
        $afterSaleOne = Yii::$service->order->afterSale->getInfoByPrimaryKey($as_id);
        if (!is_array($afterSaleOne) || empty($afterSaleOne)) {
            
            return Yii::$service->url->redirectByUrlKey('customer/order');
        }
        
        $customer_id = $this->getCustomerId();
        // 判断当前的customer_id是否等于订单中的customer_id
        if ($customer_id && $afterSaleOne['customer_id'] != $customer_id) {
            Yii::$service->helper->errors->add('you donot have role operate this order after sale, current customer_id:{customer_id} is not equel to order after sale customer_id:{order_customer_id}', [
                'customer_id' => $customer_id, 
                'order_customer_id' => $afterSaleOne['customer_id'] 
            ]);
            
            return Yii::$service->url->redirectByUrlKey('customer/order');
        }
        
        $data = $this->getBlock()->getLastData($afterSaleOne);

        return $this->render($this->action->id, $data);
    }

    // 撤销退货请求
    public function actionReturncancel()
    {
        $as_id = Yii::$app->request->get('as_id');
        $customer_id = $this->getCustomerId();
        if (!Yii::$service->order->afterSale->customerCancelReturnByAsId($as_id, $customer_id)) {
            
            return Yii::$service->url->redirectByUrlKey('customer/order/returnstatus', ['as_id' => $as_id]);
        }
        
        return Yii::$service->url->redirectByUrlKey('customer/order/returnstatus', ['as_id' => $as_id]);
    }
    // 退货商品发货
    public function actionReturndispatch()
    {
        $editForm = Yii::$app->request->post('editForm');
        $as_id = $editForm['as_id'];
        $tracking_company = $editForm['tracking_company'];
        $tracking_number = $editForm['tracking_number'];
        $customer_id = $this->getCustomerId();
        
        if (!Yii::$service->order->afterSale->customerDispatchReturnByAsId($as_id,  $tracking_company, $tracking_number, $customer_id)) {
            Yii::$service->page->message->addByHelperErrors();
            
            return Yii::$service->url->redirectByUrlKey('customer/order/returnstatus', ['as_id' => $as_id]);
        }
        
        return Yii::$service->url->redirectByUrlKey('customer/order/returnstatus', ['as_id' => $as_id]);
    }
    
    
    
    
    
}
