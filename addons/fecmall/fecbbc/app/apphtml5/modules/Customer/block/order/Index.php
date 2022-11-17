<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Customer\block\order;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
{
    protected $numPerPage = 10;
    protected $pageNum;
    protected $orderBy;
    protected $_page = 'p';

    /**
     * 初始化类变量.
     */
    public function initParam()
    {
        $this->pageNum = (int) Yii::$app->request->get('p');
        $this->pageNum = ($this->pageNum >= 1) ? $this->pageNum : 1;
        $this->orderBy = ['created_at' => SORT_DESC];
    }
    
    public function getLastData()
    {
        $this->initParam();
        $identity = Yii::$app->user->identity;
        $this->customer_id = $identity['id'];
        $this->pageNum = (int) Yii::$app->request->get('p');
        $this->pageNum = ($this->pageNum >= 1) ? $this->pageNum : 1;
        $this->orderBy = ['created_at' => SORT_DESC];
        $return_arr = [];
        if ($this->customer_id) { 
            $filter = [
                'numPerPage'    => $this->numPerPage,
                'pageNum'        => $this->pageNum,
                'orderBy'        => $this->orderBy,
                'where'            => [
                    ['customer_id' => $this->customer_id],
                ],
                'asArray' => true,
            ];
            
            // order_status=waiting_payment
            $param_order_status = Yii::$app->request->get('order_status');
            if ($param_order_status == 'waiting_payment') {
                $filter['where'][] = ['in', 'order_status', [
                    Yii::$service->order->payment_status_pending,
                ]];
                $filter['where'][] = ['in', 'order_operate_status', [
                    Yii::$service->order->operate_status_normal,
                ]];
            } else if ($param_order_status == 'waiting_shipping') {
                $filter['where'][] = ['in', 'order_status', [
                     Yii::$service->order->status_processing,
                    Yii::$service->order->status_processing,
                ]];
                $filter['where'][] = ['in', 'order_operate_status', [
                    Yii::$service->order->operate_status_normal,
                ]];
            } else if ($param_order_status == 'waiting_receive') {
                $filter['where'][] = ['in', 'order_status', [
                    Yii::$service->order->status_dispatched,
                ]];
            } else if ($param_order_status == 'waiting_review') {
                $filter['where'][] = ['in', 'order_status', [
                    Yii::$service->order->status_completed,
                ]];
                $filter['where'][] = ['is_reviewed' => Yii::$service->product->review->isNotReviewed];
            }
            
            $customer_order_list = Yii::$service->order->getorderinfocoll($filter);
            $order_list = $customer_order_list['coll'];
            $count = $customer_order_list['count'];
            $orderArr = [];
            if(is_array($order_list)){
                foreach($order_list as $k=>$order){
                    $currencyCode = $order['order_currency_code'];
                    $order['currency_symbol'] = Yii::$service->page->currency->getSymbol($currencyCode);
                    $orderArr[] = $this->getOrderArr($order);
                }
            }
            
            $return_arr['order_list'] = $orderArr;
            $pageToolBar = $this->getProductPage($count);
            $return_arr['pageToolBar'] = $pageToolBar;
            
        }
        
        return $return_arr;
    }

    public function getOrderArr($order){
        $order_status = Yii::$service->order->info->getLabelStatus($order);
        $orderInfo = [];
        $orderInfo['created_at'] = $order['created_at'];
        $orderInfo['updated_at'] = $order['updated_at'];
        $orderInfo['increment_id'] = $order['increment_id'];
        $orderInfo['order_id'] = $order['order_id'];
        $orderInfo['order_status'] = Yii::$service->page->translate->__($order_status);
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
        $orderInfo['payment_method_label'] = Yii::$service->page->translate->__($order['payment_method']);
        $orderInfo['shipping_method'] = Yii::$service->page->translate->__($order['shipping_method']);
        $orderInfo['tracking_number'] = $order['tracking_number'];

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
        
        $orderInfo['can_payment'] = Yii::$service->order->info->isCustomerCanPayment($order);
        $orderInfo['can_cancel'] = Yii::$service->order->info->isCustomerCanCancel($order);
        $orderInfo['can_received'] = Yii::$service->order->info->isCustomerCanReceive($order);
        $orderInfo['can_after_sale'] = Yii::$service->order->info->isCustomerCanAfterSale($order);
        $orderInfo['can_cancel_back'] = Yii::$service->order->info->isCustomerCanCancelBack($order);
        $orderInfo['can_delay_receive'] = Yii::$service->order->info->isCustomerCanDelayReceiveOrder($order);
        $orderInfo['can_query_shipping'] = Yii::$service->order->info->isCustomerCanQueryTracking($order);
        $orderInfo['can_reorder'] = Yii::$service->order->info->isCustomerCanReOrder($order);
        $orderInfo['can_review'] = Yii::$service->order->info->isCustomerCanReview($order);   
            
        //var_dump($orderInfo);
        return $orderInfo;
    }
    
    

    protected function getProductPage($countTotal)
    {
        if ($countTotal <= $this->numPerPage) {
            return '';
        }
        $config = [
            'class'        => 'fecshop\app\appfront\widgets\Page',
            'view'        => 'widgets/page.php',
            'pageNum'        => $this->pageNum,
            'numPerPage'    => $this->numPerPage,
            'countTotal'    => $countTotal,
            'page'            => $this->_page,
        ];

        return Yii::$service->page->widget->renderContent('customer_order_page', $config);
    }
}
