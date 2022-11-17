<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\services;

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
class  Coupon extends Service
{
    
    public $numPerPage = 10;
    
    protected $_modelName = '\fecbbc\models\mysqldb\Coupon';

    protected $_model;
    
    public $condition_product_type_all = 'all';
    public $condition_product_type_sku = 'sku';
    public $condition_product_type_category_id = 'category_id';
    
    
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
     * @param $primaryKey | Int
     * 得到分类对应的分类
     */
    public function getCategoryIdsByCouponId($primaryKey)
    {
        if ($primaryKey) {
            $one = $this->_model->findOne($primaryKey);
            foreach ($this->_lang_attr as $attrName) {
                if (isset($one[$attrName])) {
                    $one[$attrName] = unserialize($one[$attrName]);
                }
            }

            $categoryIds = $one['condition_product_category_ids'];
            if (is_array($categoryIds) && $categoryIds) {
                
                return $categoryIds;
            }
        }
        
        return [];
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
     * @param $param | array'， 经销商提交的优惠券信息。
     * 保存经销商提交的coupon
     */
    public function saveBdminCoupon($param)
    {
        
    }
    
     /**
     * @param $couponCode | string ， 优惠券
     * 通过优惠券得到Model
     */
    public function getByCouponCode($couponCode)
    {
        
        return $this->_model->findOne(['code' => $couponCode]);
    }
    
    
    
    /**
     * @param $couponCode | string， 优惠券卷码
     * 检查优惠券是否可以领取
     */
    public function checkFetchCoupon($couponCode)
    {
       
        $coupon = $this->getByCouponCode($couponCode);
        if (empty($coupon)) {
            Yii::$service->helper->errors->add('coupon has been claimed');
            echo $couponCode;exit;
            return false;
        }
        
        // 优惠券已经领光
        if ($coupon['total_count'] <=  $coupon['assign_count'] ) {
            Yii::$service->helper->errors->add('coupons are out of stock');
            echo 67;exit;
            return false;
        }
        // 领取时间
        if ($coupon['assign_begin_at'] > time() || $coupon['assign_end_at'] < time()) {
            Yii::$service->helper->errors->add('coupon issue time is illegal');
            echo 68;exit;
            return false;
        }
        
        return true;
    }
    
    /**
     * 得到所有经销商的可以领取的优惠券列表
     *
     */
    public function getAllAssignCoupons()
    {
        $filter = [
            'asArray' => true,
            'fetchAll' => true,
            'where' => [
                ['<', 'assign_begin_at', time()],
                ['>', 'assign_end_at', time()],
            ],
        ];
        $data = $this->coll($filter);
        
        return $data;
    }
    
    
   
    /**
     * @param $productModel | Object, 产品model
     * 得到产品的可领取的优惠券
     */
    public function getProductActiveCouponList($productModel)
    {
        $bdminUserId = $productModel['bdmin_user_id'];
        $productSku = $productModel['sku'];
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        $productId = $productModel[$productPrimaryKey];
        $bdminCoupons = $this->getCouponProductPageActiveListByBdminId($bdminUserId);
        
        $arr = [];
        foreach ($bdminCoupons as $bdminCoupon) {
            $condition_product_type = $bdminCoupon['condition_product_type'];
            if ($condition_product_type == $this->condition_product_type_all) {
                $arr[] = $bdminCoupon;
            } else if ($condition_product_type == $this->condition_product_type_sku) {
                $condition_product_skus = $bdminCoupon['condition_product_skus'];
                if (in_array($productSku, $condition_product_skus)) {
                    
                    $arr[] = $bdminCoupon;
                }
            } else if ($condition_product_type == $this->condition_product_type_category_id) {
                $condition_product_category_ids = $bdminCoupon['condition_product_category_ids'];
                $productCategoryIds = Yii::$service->product->getCategoryIdsByProductId($productId);
                if (!is_array($productCategoryIds) || !is_array($condition_product_category_ids)) {
                    continue;
                }
                $intersectResult = array_intersect($condition_product_category_ids, $productCategoryIds);
                if (!empty($intersectResult)) {
                    $arr[] = $bdminCoupon;
                }
            }
        }
        $symbol = Yii::$service->page->currency->getCurrentSymbol();
        // 货币转化
        foreach ($arr as $k=>$one) {
            $arr[$k]['symbol'] = $symbol;
            $arr[$k]['current_discount_cost'] = Yii::$service->page->currency->GetCurrentCurrencyPrice($one['discount_cost']);
            $arr[$k]['current_use_condition'] = Yii::$service->page->currency->GetCurrentCurrencyPrice($one['use_condition']);
        }
        
        return $arr;
    }
    
    /**
     * @param $bdminUserId | int, 经销商id
     * 通过经销商id，找到这个经销商，所有可用的， 在产品页面显示的，优惠券
     */
    public function getCouponProductPageActiveListByBdminId($bdminUserId)
    {
        $filter = [
            'asArray' => true,
            'fetchAll' => true,
            'where' => [
                ['bdmin_user_id' => $bdminUserId],
                ['>', 'assign_end_at', time()],
                ['<', 'assign_begin_at', time()],
                ['is_show_in_product_page' => 1],
            ],
            'orderBy' => [
                'discount_cost' => SORT_DESC
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
     * @param $bdminUserId | int, 经销商id
     * 通过经销商id，找到这个经销商的所有可用的优惠券
     */
    public function getCouponActiveListByBdminId($bdminUserId)
    {
        $filter = [
            'asArray' => true,
            'fetchAll' => true,
            'where' => [
                ['bdmin_user_id' => $bdminUserId],
                ['>', 'assign_end_at', time()],
                ['<', 'assign_begin_at', time()],
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
     * @param $couponCode | string，优惠卷码
     * @param $totalCount | int，优惠券最大的发放个数
     * 优惠券发放后，将优惠券的发放个数+1
     * 通过updateAll，条件中加入个数判断，防止高并发超发优惠券
     */
    public function updateAssignCount($couponCode, $totalCount)
    {
        $updateColumns = $this->_model->updateAllCounters(
            ['assign_count' => 1],
            [
                'and', 
                ['code' => $couponCode], 
                ['<', 'assign_count', $totalCount],
                ['<=', 'assign_begin_at', time()],
                ['>', 'assign_end_at', time()],
            ]
        );
        if (empty($updateColumns)) {// 上面更新sql返回的更新行数如果为0，则说明更新失败，产品不存在，或者产品库存不够
            Yii::$service->helper->errors->add('coupon assign count + 1 fail');
            echo 1;exit;
            return false;
        }
        
        return true;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
