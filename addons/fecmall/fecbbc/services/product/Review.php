<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\services\product;

//use fecshop\models\mongodb\product\Review as ReviewModel;
use fecshop\services\Service;
use Yii;
use yii\base\InvalidValueException;

/**
 * Product Review Service
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Review extends \fecshop\services\product\Review
{
    public $isReviewed = 1;
    public $isNotReviewed = 2;
    /**
     * @param $status | int
     *  是否是评论状态
     */
    public function hasReviewed($status)
    {
        if ($status == $this->isReviewed) {
            return true;
        }
        return false;
    }
    /**
     * 订单产品是否可以被评论
     * @param $orderStatus | string， 订单状态 $orderModel['order_status']
     * @param $orderItemIsReviewed | int 订单产品的评论状态 $orderItemModel['is_reviewed']
     */
    public function orderItemCanReview($orderModel, $orderItemModel)
    {
        if (!Yii::$service->order->info->isCustomerCanReview($orderModel)) {
            Yii::$service->helper->errors->add('order can not review');
            
            return false;
        }
        
        $orderItemIsReviewed = $orderItemModel['is_reviewed'];
        if ($this->hasReviewed($orderItemIsReviewed)) {
            
            return false;
        }
        
        return true;
    }
    
    public function addOrderReview($editForm)
    {
        
        $product_id = isset($editForm['product_id']) ? $editForm['product_id'] : '';
        if (!$product_id) {
            Yii::$service->helper->errors->add('Product id can not empty');

            return false;
        }
       
        $rate_star = isset($editForm['rate_star']) ? $editForm['rate_star'] : '';
        if (!$rate_star) {
            Yii::$service->helper->errors->add('Rate Star can not empty');

            return false;
        }
        
        $product_id = isset($editForm['product_id']) ? $editForm['product_id'] : '';
        if (!$product_id) {
            Yii::$service->helper->errors->add('Your product_id can not empty');

            return false;
        }
        
        $order_id = isset($editForm['order_id']) ? $editForm['order_id'] : '';
        if (!$order_id) {
            Yii::$service->helper->errors->add('order_id can not empty');

            return false;
        }
        $item_id = isset($editForm['item_id']) ? $editForm['item_id'] : '';
        if (!$item_id) {
            Yii::$service->helper->errors->add('item_id can not empty');

            return false;
        }
        
        $review_content = isset($editForm['review_content']) ? $editForm['review_content'] : '';
        if (!$review_content) {
            Yii::$service->helper->errors->add('Review content can not empty');

            return false;
        }
        
        $product = Yii::$service->product->getByPrimaryKey($product_id);
        if (!$product['spu']) {
            Yii::$service->helper->errors->add('product _id:'.$product_id.'  is not exist in product collection');

            return false;
        }
        $editForm['spu'] = $product['spu'];
        $editForm['sku'] = $product['sku'];
        $editForm['bdmin_user_id'] = $product['bdmin_user_id'];
        $order = Yii::$service->order->getByPrimaryKey($order_id);
        // 插件订单是否可以被评论
        
        if (!$order['order_id']) {
            return false;
        }
        if (!$editForm['customer_id'] || $order['customer_id'] != $editForm['customer_id']) {
            
            return false;
        }
        $orderItemModel = Yii::$service->order->item->getByPrimaryKey($item_id);
        if ($orderItemModel['order_id'] != $order['order_id']) {
            Yii::$service->helper->errors->add("product review error");
            
            return false;
        }
        if ($orderItemModel['product_id'] != $product_id) {
            Yii::$service->helper->errors->add("product review error");
            
            return false;
        }
        
        if (!$this->orderItemCanReview($order, $orderItemModel)) {
            
            Yii::$service->helper->errors->add("order item can not review");
            
            return false;
        } 
       
        // 增加评论
        if (!$this->_review->addOrderReview($editForm)) {
            return false;
        }
        
        // 更新订单评论状态
        if (!Yii::$service->order->updateOrderReviewStatus($order_id, $item_id)) {
            return false;
        }
        // echo 3;exit;
        return true;
    }
    
    
    
}
