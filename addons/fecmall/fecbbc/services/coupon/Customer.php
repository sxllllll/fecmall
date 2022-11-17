<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\services\coupon;

use Yii;
use fecshop\services\Service;

/**
 * Order services.
 *
 * @property \fecshop\services\order\Item $item
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class  Customer extends Service
{
    
    public $numPerPage = 10;
    
     // 优惠券已使用
    public $coupon_is_used = 1;
    // 优惠券未使用
    public $coupon_is_not_used = 2;
    
    protected $_modelName = '\fecbbc\models\mysqldb\coupon\Customer';

    protected $_model;
    
    /**
     *  language attribute.
     */
    protected $_lang_attr = [
        'condition_product_skus',
        'condition_product_category_ids',
    ];
    
    public function init()
    {
        parent::init();
        list($this->_modelName, $this->_model) = \Yii::mapGet($this->_modelName);
    }
    
    
    
    /**
     * 得到order 表的id字段。
     */
    public function getPrimaryKey()
    {
        return 'id';
    }

    /**
     * @param $primaryKey | Int
     * @return Object($this->_orderModel)
     * 通过主键值，返回Order Model对象
     */
    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            $one = $this->_model->findOne($primaryKey);
            foreach ($this->_lang_attr as $attrName) {
                if (isset($one[$attrName])) {
                    $one[$attrName] = unserialize($one[$attrName]);
                }
            }

            return $one;
        } else {
            return new $this->_modelName();
        }
    }
    
    /**
     * @param $filter|array
     * @return Array;
     *              通过过滤条件，得到coupon的集合。
     *              example filter:
     *              [
     *                  'numPerPage' 	=> 20,
     *                  'pageNum'		=> 1,
     *                  'orderBy'	    => ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     *                  'where'			=> [
     *                      ['>','price',1],
     *                      ['<=','price',10]
     * 			            ['sku' => 'uk10001'],
     * 		            ],
     * 	                'asArray' => true,
     *              ]
     * 根据$filter 搜索参数数组，返回满足条件的订单数据。
     */
    public function coll($filter = '')
    {
        $query = $this->_model->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        $coll = $query->all();
        if (!empty($coll)) {
            foreach ($coll as $k => $one) {
                foreach ($this->_lang_attr as $attr) {
                    $one[$attr] = $one[$attr] ? unserialize($one[$attr]) : '';
                }
                $coll[$k] = $one;
            }
        }
        //var_dump($one);
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }
    
    
    /**
     * @param $one|array
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    public function save($one)
    {
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        
        if ($primaryVal) {
            $model = $this->_model->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('coupon {primaryKey} is not exist', ['primaryKey' => $this->getPrimaryKey()]);

                return;
            }
        } else {
            $model = new $this->_modelName();
            $model->created_at = time();
        }
        $model->updated_at = time();
        foreach ($this->_lang_attr as $attrName) {
            if (is_array($one[$attrName]) && !empty($one[$attrName])) {
                $one[$attrName] = serialize($one[$attrName]);
            }
        }
        $primaryKey = $this->getPrimaryKey();
        $model      = Yii::$service->helper->ar->save($model, $one);
        $primaryVal = $model[$primaryKey];

        return true;
    }
    
    
    public function remove($ids)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $model = $this->_model->findOne($id);
                $model->delete();
            }
        } else {
            $id = $ids;
            $model = $this->_model->findOne($id);
            $model->delete();
        }

        return true;
    }
    
    /**
     * @param $couponCode | string ， 优惠券
     * 通过优惠券得到Model
     */
    public function getByCouponCode($couponCode)
    {
        
        $one = $this->_model->findOne(['code' => $couponCode]);
        foreach ($this->_lang_attr as $attrName) {
            if (isset($one[$attrName])) {
                $one[$attrName] = unserialize($one[$attrName]);
            }
        }
        
        return $one;
    }
    
    /**
     * @param $customerModel | object, 用户model
     * @param $couponId | string， 优惠券卷码id
     * 用户领取coupon
     */
    public function fetchCoupon($customerModel, $couponArr)
    {
        $couponId = '';
        if (isset($couponArr['coupon_id']) && $couponArr['coupon_id']) {
            $couponId = $couponArr['coupon_id'];
            $customerCoupon = $this->_model->findOne(['coupon_id' => $couponId]);
        } else if (isset($couponArr['coupon_code']) && $couponArr['coupon_code']) {
            $coupon_code = $couponArr['coupon_code'];
            $customerCoupon = $this->_model->findOne(['code' => $coupon_code]);
        }
        
        //1.查看该优惠券，用户是否已经领取,
        if ($customerCoupon && $customerCoupon['coupon_id']) {
            if ($customerCoupon['is_used'] == $this->coupon_is_not_used) {
                Yii::$service->helper->errors->add('Coupon has been claimed');
            
                return false;
            } else {
                Yii::$service->helper->errors->add('Coupon has been used');
            
                return false;
            }
        }
        // coupon表查询
        if ($couponId) {
            $couponModel = Yii::$service->coupon->getByPrimaryKey($couponId);
        } else {
            $couponModel = Yii::$service->coupon->getByCouponCode($coupon_code);
        }
        $couponId = $couponModel['id'];   
        $couponCode = $couponModel['code'];
        if (!$couponId || !$couponCode ) {
            
            return false;
        }
        //2.查看该优惠券是否存在，而且可以发放
        if (!Yii::$service->coupon->checkFetchCoupon($couponCode)) {
            Yii::$service->helper->errors->add('this coupon code can not fetch');
            
            return false;
        }
       
        //3.进行优惠券领取, 将用户表中插入数据，将领取优惠券扣除1
        $couponModel = Yii::$service->coupon->getByCouponCode($couponCode);
        //$active_days = $couponModel['active_days'];
        $active_begin_at = $couponModel['assign_begin_at'];
        $active_end_at = $couponModel['assign_end_at'];
        
        $customerCouponModel = new $this->_modelName();
        $customerCouponModel->created_at = time();
        $customerCouponModel->updated_at = time();
        $customerCouponModel->customer_id = $customerModel['id'];
        $customerCouponModel->coupon_id = $couponModel['id'];
        $customerCouponModel->name = $couponModel['name'];
        $customerCouponModel->code = $couponModel['code'];
        $customerCouponModel->bdmin_user_id = $couponModel['bdmin_user_id'];
        $customerCouponModel->discount_cost = $couponModel['discount_cost'];
        $customerCouponModel->use_condition = $couponModel['use_condition'];
        $customerCouponModel->condition_product_type = $couponModel['condition_product_type'];
        $customerCouponModel->condition_product_skus = $couponModel['condition_product_skus'];
        $customerCouponModel->condition_product_category_ids = $couponModel['condition_product_category_ids'];
        $customerCouponModel->active_begin_at = $active_begin_at;
        $customerCouponModel->active_end_at = $active_end_at;
        $customerCouponModel->is_used = $this->coupon_is_not_used;
        $customerCouponModel->remark = $couponModel['remark'];
        if (!$customerCouponModel->save()) {
            
            return false;
        }
        
        // 将领取优惠券的发放个数+1
        $totalCount = $couponModel['total_count'];
        if (!Yii::$service->coupon->updateAssignCount($couponCode, $totalCount)) {
            
            return false;
        }
        
        return true;
    }
   
   
    
    
    
    /**
     * @param $couponModel | Object,  优惠券Model
     * @param $customerId | int,  用户id
     * @param $bdminUserId | int， 经销商id
     * @param $baseTotal | float，订单金额。
     * 检查优惠券是否可用
     */
    public function checkCommonCoupon($couponModel, $customerId, $bdminUserId, $baseTotal)
    {
        // 检查是否是sku优惠券
        if ($couponModel['condition_product_type'] != Yii::$service->coupon->condition_product_type_all) {
            Yii::$service->helper->errors->add('condition_product_type is common type');
            
            return false;
        } 
        
        // 优惠券已经被使用	
        if ($couponModel['is_used'] == $this->coupon_is_used) {
            Yii::$service->helper->errors->add('coupon is used');
            
            return false;
        } 
       
        // 当前用户和优惠券用户不一致
        if ($couponModel['customer_id'] != $customerId) {
            Yii::$service->helper->errors->add('customer do not have role');
            
            return false;
        }
       
        // 经销商和优惠券经销商不一致
        if ($couponModel['bdmin_user_id'] != $bdminUserId) {
            Yii::$service->helper->errors->add('bdmin User Id is error');
            
            return false;
        } 
        // 优惠券已经失效
        if ($couponModel['active_begin_at'] > time() || $couponModel['active_end_at'] < time()) {
            Yii::$service->helper->errors->add('coupon is unavailable');
            
            return false;
        }
        
        // 不满足优惠券使用条件
        if ((float)$couponModel['use_condition'] > (float)$baseTotal) {
            Yii::$service->helper->errors->add('coupon use cost must gt {use_condition}', ['use_condition' => $couponModel['use_condition']]);
            
            return false;
        }
        
        return true;
    }
    
    
    
    /**
     * @param $couponModel | Object,  优惠券Model
     * @param $customerId | int,  用户id
     * @param $bdminUserId | int， 经销商id
     * @param $skuItems | Array，订单产品金额。格格式 ['sku1' => 11.12, 'sku2' => 21.12, 'sku3' => 9.12,]
     *  sku优惠券检查是否可用，只有允许的sku才允许使用
     */
    public function checkSkuCoupon($couponModel, $customerId, $bdminUserId, $skuItems)
    {
        // 检查是否是sku优惠券
        if ($couponModel['condition_product_type'] != Yii::$service->coupon->condition_product_type_sku) {
            Yii::$service->helper->errors->add('condition_product_type is not sku type');
            
            return false;
        } 
        
        // 优惠券已经被使用	
        if ($couponModel['is_used'] == $this->coupon_is_used) {
            Yii::$service->helper->errors->add('coupon is used');
            
            return false;
        } 
        // 当前用户和优惠券用户不一致
        if ($couponModel['customer_id'] != $customerId) {
            Yii::$service->helper->errors->add('customer do not have role');
            
            return false;
        }
        // 经销商和优惠券经销商不一致
        if ($couponModel['bdmin_user_id'] != $bdminUserId) {
            Yii::$service->helper->errors->add('bdmin User Id is error');
            
            return false;
        }
        // 优惠券已经失效
        if ($couponModel['active_begin_at'] > time() || $couponModel['active_end_at'] < time()) {
            Yii::$service->helper->errors->add('coupon is unavailable');
            
            return false;
        }
        
        $condition_product_skus = $couponModel['condition_product_skus'];
        if (!is_array($skuItems) || empty($skuItems)) {
            
            return false;
        }
        
        if (!is_array($condition_product_skus) || empty($condition_product_skus)) {
            
            return false;
        }
        
        $baseTotal = 0;
        foreach ($skuItems as $sku =>$cost) {
            if (in_array($sku, $condition_product_skus)) {
                $baseTotal += $cost;
            }
        }
        //var_dump($couponModel['use_condition'] , $baseTotal);
        // 不满足优惠券使用条件
        if ((float)$couponModel['use_condition'] > (float)$baseTotal) {
            Yii::$service->helper->errors->add('coupon use cost must gt {use_condition}', ['use_condition' => $couponModel['use_condition']]);
            //var_dump($couponModel['use_condition'] , $baseTotal);
            return false;
        }
        
        return true;
    }
    
    
    /**
     * @param $couponModel | Object,  优惠券Model
     * @param $customerId | int,  用户id
     * @param $bdminUserId | int， 经销商id
     * @param $productIdItems | Array，订单产品金额。格式 ['productId1' => 11.12, 'productId2' => 21.12, 'productId3' => 9.12,]
     * @param $productCategoryIdItems | Array，订单产品金额。格式 ['productId1' => ['categoryId1','categoryId2'], 'productId2' =>  ['categoryId1','categoryId2'], 'productId3' =>  ['categoryId1','categoryId2'],]
     *  分类优惠券检查是否可用，只有允许的产品分类才允许使用
     */
    public function checkCategoryCoupon($couponModel, $customerId, $bdminUserId, $productIdItems, $productCategoryIdItems)
    {
        // 检查是否是sku优惠券
        if ($couponModel['condition_product_type'] != Yii::$service->coupon->condition_product_type_category_id) {
            Yii::$service->helper->errors->add('condition_product_type is not category type');
            
            return false;
        } 
        
        // 优惠券已经被使用	
        if ($couponModel['is_used'] == $this->coupon_is_used) {
            Yii::$service->helper->errors->add('coupon is used');
            
            return false;
        } 
        // 当前用户和优惠券用户不一致
        if ($couponModel['customer_id'] != $customerId) {
            Yii::$service->helper->errors->add('customer do not have role');
            
            return false;
        }
        // 经销商和优惠券经销商不一致
        if ($couponModel['bdmin_user_id'] != $bdminUserId) {
            Yii::$service->helper->errors->add('bdmin User Id is error');
            
            return false;
        }
        // 优惠券已经失效
        if ($couponModel['active_begin_at'] > time() || $couponModel['active_end_at'] < time()) {
            Yii::$service->helper->errors->add('coupon is unavailable');
            
            return false;
        }
        $condition_product_category_ids = $couponModel['condition_product_category_ids'];
        if (!is_array($productCategoryIdItems) || empty($productCategoryIdItems)) {
            
            return false;
        }
        if (!is_array($productIdItems) || empty($productIdItems)) {
            
            return false;
        }
        if (!is_array($condition_product_category_ids) || empty($condition_product_category_ids)) {
            
            return false;
        }
        $baseTotal = 0;
        // 对产品分类Ids数据进行遍历
        foreach ($productCategoryIdItems as $productId => $categoryIds) {
            // 产品对应的分类数据  与  优惠券的分类数组  是否存在交集，如果存在交集，则说明优惠券可用
            $result = array_intersect($categoryIds, $condition_product_category_ids);
            $cost = isset($productIdItems[$productId]) ? $productIdItems[$productId] : 0;
            if (!empty($result)) {
                $baseTotal += $cost;
            }
            
        }
        // 不满足优惠券使用条件
        if ((float)$couponModel['use_condition'] > (float)$baseTotal) {
            Yii::$service->helper->errors->add('coupon use cost must gt {use_condition}', ['use_condition' => $couponModel['use_condition']]);
            
            return false;
        }
        
        return true;
    }
    
    /**
     * @param $customerId | int, 用户的id
     * @param $bdminUserIds | array， 经销商的id
     * 得到用户id和经销商id数组，得到未过期并且没有用过的 优惠券列表
     */
    public function getCustomerActiveCouponList($customerId, $bdminUserIds)
    {
        $filter = [
            'asArray' => true,
            'fetchAll' => true,
            'where' => [
                ['customer_id' => $customerId],
                ['in', 'bdmin_user_id', $bdminUserIds],
                ['<', 'active_begin_at', time()],
                ['>', 'active_end_at', time()],
                ['is_used' => $this->coupon_is_not_used]
            ],
        ];
        $data = $this->coll($filter);
        $coll = $data['coll'];
        if (!is_array($coll) || empty($coll)) {
            
            return [];
        }
        
        return $coll;
    }
    
    
    /**
     * @param $customerId | int, 用户的id
     * @param $coupon_ids | array， 优惠券id
     * 
     */
    public function getByCustomerIdAndCouponIds($customerId, $coupon_ids)
    {
        $filter = [
            'asArray' => true,
            'fetchAll' => true,
            'where' => [
                ['customer_id' => $customerId],
                ['in', 'coupon_id', $coupon_ids],
                //['<', 'active_begin_at', time()],
                //['>', 'active_end_at', time()],
               // ['is_used' => $this->coupon_is_not_used]
            ],
        ];
        $data = $this->coll($filter);
        $coll = $data['coll'];
        if (!is_array($coll) || empty($coll)) {
            
            return [];
        }
        
        return $coll;
    }
    
    
    /**
     * @param $customerId | int， 用户id
     * @param $cartItems | array ， 购物车产品信息，格式为:
     *    [
     *        'bdmin_user_id1' => [ 
                    ['sku' => 'sku1', 'product_id' => 'product_id1',  'base_row_total'  => 43.23],
                    ['sku' => 'sku2', 'product_id' => 'product_id2',  'base_row_total'  => 13.23],
                ],
     *        'bdmin_user_id2' => [ 
                    ['sku' => 'sku3', 'product_id' => 'product_id3',  'base_row_total'  => 41.23],
                    ['sku' => 'sku4', 'product_id' => 'product_id4',  'base_row_total'  => 12.23],
                ],
     *    ]
     * @param $postCartCoupon  | array， 购物车当前的优惠券，格式为：
     *   [
     *       'bdmin_user_id1' => 'coupon_code1',
     *       'bdmin_user_id2' => 'coupon_code2',
     *   ]
     * 得到购物车可用的优惠券列表
     */
    public function getCartCouponList($customerId, $cartItems, $postCartCoupon)
    {
        if (!is_array($cartItems) || empty($cartItems)) {
            
            return null;
        }
        $bdminUserIds = [];
        $cartTotal = [];
        $skuBdminItems = [];
        $productIdBdminItems = [];
        foreach ($cartItems as $bdminUserId=>$cartItem) {
            $bdminUserIds[] = $bdminUserId;
            if (!is_array($cartItem) || empty($cartItem)) {
                
                return null;
            }
            
            foreach ($cartItem as $one) {
                $baseRowTotal = $one['base_row_total'];
                $sku = $one['sku'];
                $product_id = $one['product_id'];
                if (!isset($cartTotal[$bdminUserId])) {
                    $cartTotal[$bdminUserId] = 0;
                }
                $cartTotal[$bdminUserId] += $baseRowTotal;
               
                $skuBdminItems[$bdminUserId][$sku] = $baseRowTotal;
                 //var_dump([$sku => $baseRowTotal ]);exit;
                $productIdBdminItems[$bdminUserId][$product_id] = $baseRowTotal;
            }
        }
        // 得到用户可用的优惠券列表。
        $customerActiveCoupons = $this->getCustomerActiveCouponList($customerId, $bdminUserIds);
        if (!is_array($customerActiveCoupons) || empty($customerActiveCoupons)) {
            
            return null;
        }
        
        // 对优惠券进行检查
        $availableCoupons = [];
        $unavailableCoupons = [];
        $maxCostCoupon = [];
        $postSelectedCoupon = [];
        foreach ($customerActiveCoupons as $customerCoupon) {
            $customerCoupon['current_discount_cost'] = (float)Yii::$service->page->currency->getCurrentCurrencyPrice($customerCoupon['discount_cost']);
            $customerCoupon['current_use_condition'] = (float)Yii::$service->page->currency->getCurrentCurrencyPrice($customerCoupon['use_condition']);
            
            
            $couponType =  $customerCoupon['condition_product_type'];
            $bdminUserId =  $customerCoupon['bdmin_user_id'];
            $isAvailableCoupon = false;
            if ($couponType == Yii::$service->coupon->condition_product_type_all) {  // 全店铺优惠券
                $baseTotal = isset($cartTotal[$bdminUserId]) ? $cartTotal[$bdminUserId] : 0;
                //echo $baseTotal.'##';
                
                if ($this->checkCommonCoupon($customerCoupon, $customerId, $bdminUserId, $baseTotal)) {
                   
                    $isAvailableCoupon = true;
                }
            } else if  ($couponType == Yii::$service->coupon->condition_product_type_sku) {  // 特定产品优惠券
                $skuItems = isset($skuBdminItems[$bdminUserId]) ? $skuBdminItems[$bdminUserId] : [] ;
                if ($this->checkSkuCoupon($customerCoupon, $customerId, $bdminUserId, $skuItems)) {
                    $isAvailableCoupon = true;
                }
            } else  if($couponType == Yii::$service->coupon->condition_product_type_category_id) {  // 特定分类优惠券
                $productIdItems = isset($productIdBdminItems[$bdminUserId]) ? $productIdBdminItems[$bdminUserId] : [] ;
                $productCategoryIdItems = $this->getCategoryIdItemsByProductIdItems($bdminUserId, $productIdItems);
                if ($this->checkCategoryCoupon($customerCoupon, $customerId, $bdminUserId, $productIdItems, $productCategoryIdItems)) {
                    $isAvailableCoupon = true;
                }
            }
            if ($isAvailableCoupon) {
                $availableCoupons[$bdminUserId][] = $customerCoupon;
                
                // 用户选择的优惠券
                $postSelectedCouponCode = isset($postCartCoupon[$bdminUserId]) ? $postCartCoupon[$bdminUserId] : '';
                if ($postSelectedCouponCode && $postSelectedCouponCode == $customerCoupon['code']) {
                    $postSelectedCoupon[$bdminUserId] = $customerCoupon;
                }
            
                // 设置默认的coupon
                if (!isset($maxCostCoupon[$bdminUserId])) {
                    $maxCostCoupon[$bdminUserId] = $customerCoupon;
                } else if ($maxCostCoupon[$bdminUserId]['discount_cost'] < $customerCoupon['discount_cost']) {
                    $maxCostCoupon[$bdminUserId] = $customerCoupon;
                }
            } else {
                $unavailableCoupons[$bdminUserId][] = $customerCoupon;
            }
        }
        foreach ($postSelectedCoupon as $bdminUserId=>$couponM) {
            $maxCostCoupon[$bdminUserId] = $couponM;
        }
        //var_dump($unavailableCoupons);
        return [
            // 可用优惠券
            'availableCoupons' => $availableCoupons,
            // 不可用优惠券
            'unavailableCoupons' => $unavailableCoupons,
            // 币值最大的可用优惠券
            'selectedCoupon' => $maxCostCoupon,
        ];
    }
    
    
     /**
     * @param $couponCode | string,  优惠券
     * 优惠券使用后，将优惠券的的使用状态改成已使用
     */
    public function useCoupon($customerId, $couponCode)
    {
        if (!$couponCode || !$customerId) {
            
            return ;
        }
        $updateColumns = $this->_model->updateAll(
            ['is_used' => $this->coupon_is_used],
            [
                'code' => $couponCode, 
                'customer_id' => $customerId,
                'is_used' =>$this->coupon_is_not_used,
            ]
        );
        if (empty($updateColumns)) {// 上面更新sql返回的更新行数如果为0，则说明更新失败，产品不存在，或者产品库存不够
            Yii::$service->helper->errors->add('coupon change to used status fail');
            
            return false;
        }
        
        return true;
    }
    /**
     * @param $couponCode | string,  优惠券
     * 订单取消，将订单使用的优惠券的使用状态还原，将优惠券的的使用状态改成未使用
     */
    public function revertCoupon($customerId, $couponCode)
    {
        if ($customerId && $couponCode) {
            $updateColumns = $this->_model->updateAll(
                ['is_used' => $this->coupon_is_not_used],
                [
                    'code' => $couponCode, 
                    'customer_id' => $customerId,
                    'is_used' =>$this->coupon_is_used,
                ]
            );
            if (empty($updateColumns)) {// 上面更新sql返回的更新行数如果为0，则说明更新失败，产品不存在，或者产品库存不够
                Yii::$service->helper->errors->add('coupon revert used status fail');
                
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * @param $customerId | int， 用户id
     * @param $cartItems | array ， 购物车产品信息，格式为:
     *    [
     *        'bdmin_user_id1' => [ 
                    ['sku' => 'sku1', 'product_id' => 'product_id1',  'base_row_total'  => 43.23],
                    ['sku' => 'sku2', 'product_id' => 'product_id2',  'base_row_total'  => 13.23],
                ],
     *        'bdmin_user_id2' => [ 
                    ['sku' => 'sku3', 'product_id' => 'product_id3',  'base_row_total'  => 41.23],
                    ['sku' => 'sku4', 'product_id' => 'product_id4',  'base_row_total'  => 12.23],
                ],
     *    ]
     * @param $couponCode | string, 优惠券字符串
     * 得到当前优惠券对应的优惠金额。
     */
    public function getCouponCostByCode($customerId, $cartItems, $couponCode)
    {
        if (!is_array($cartItems) || empty($cartItems)) {
            
            return 0;
        }
        $bdminUserIds = [];
        $cartTotal = [];
        $skuBdminItems = [];
        $productIdBdminItems = [];
        foreach ($cartItems as $bdminUserId=>$cartItem) {
            $bdminUserIds[] = $bdminUserId;
            if (!is_array($cartItem) || empty($cartItem)) {
                
                return 0;
            }
            foreach ($cartItem as $one) {
                $baseRowTotal = $one['base_row_total'];
                $sku = $one['sku'];
                $product_id = $one['product_id'];
                if (!isset($cartTotal[$bdminUserId])) {
                    $cartTotal[$bdminUserId] = 0;
                }
                $cartTotal[$bdminUserId] += $baseRowTotal;
                $skuBdminItems[$bdminUserIds][$sku] = $baseRowTotal;
                $productIdBdminItems[$bdminUserIds][$product_id] = $baseRowTotal;
            }
        }
        // 通过优惠券码，得到优惠券model
        $customerCoupon = $this->getByCouponCode($couponCode);
        if (!$customerCoupon) {
            
            return 0;
        }
        $couponType =  $customerCoupon['condition_product_type'];
        $bdminUserId =  $customerCoupon['bdmin_user_id'];
        // 对优惠券的可用性进行检查。
        $isAvailableCoupon = false;
        if ($couponType == Yii::$service->coupon->condition_product_type_all) {  // 全店铺优惠券
            $baseTotal = isset($cartTotal[$bdminUserId]) ? $cartTotal[$bdminUserId] : 0;
            if ($this->checkCommonCoupon($customerCoupon, $customerId, $bdminUserId, $baseTotal)) {
                $isAvailableCoupon = true;
            }
        } else if  ($couponType == Yii::$service->coupon->condition_product_type_sku) {  // 特定产品优惠券
            $skuItems = isset($skuBdminItems[$bdminUserId]) ? $skuBdminItems[$bdminUserId] : [] ;
            if ($this->checkSkuCoupon($customerCoupon, $customerId, $bdminUserId, $skuItems)) {
                $isAvailableCoupon = true;
            }
        } else  if($couponType == Yii::$service->coupon->condition_product_type_category_id) {  // 特定分类优惠券
            $productIdItems = isset($productIdBdminItems[$bdminUserId]) ? $productIdBdminItems[$bdminUserId] : [] ;
            $productCategoryIdItems = $this->getCategoryIdItemsByProductIdItems($productIdItems);
            if ($this->checkCategoryCoupon($customerCoupon, $customerId, $bdminUserId, $productIdItems, $productCategoryIdItems)) {
                $isAvailableCoupon = true;
            }
        }
        if ($isAvailableCoupon) {
            $discount_cost = $customerCoupon['discount_cost'];
            
            return $discount_cost ;
        } 
        
        return 0;
    }
    
    
    
    
    /**
     * @param $bdminUserId | int, 经销商id
     * @param $productIdItems | array ， 购物车产品信息，格式为:  ['productId1' => 11, 'productId2' => 22]
     * 通过sku数组，得到categoryIds数组 ['categoryId1' => 11.21, 'categoryId2' => 43.23]
     */
    public function getCategoryIdItemsByProductIdItems($productIdItems)
    {
        $productIds = [];
        if (is_array($productIdItems)) {
            foreach ($productIdItems as $productId=>$cost) {
                $productIds[] = $productId;
            }
        }
        if (empty($productIds)) {
            
            return null;
        }
        
        $productCategoryIds = Yii::$service->product->getCategorysByProductIds($productIds);
        
        return $productCategoryIds;
    }
    
    
    
    
    
}
