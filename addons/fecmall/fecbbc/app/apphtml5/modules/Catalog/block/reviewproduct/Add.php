<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Catalog\block\reviewproduct;

//use fecshop\app\apphtml5\modules\Catalog\helpers\Review as ReviewHelper;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Add  extends \yii\base\BaseObject
{
    protected $_add_captcha;
    /**
     * 为了可以使用rewriteMap，use 引入的文件统一采用下面的方式，通过Yii::mapGet()得到className和Object
     */
    protected $_reviewHelperName = '\fecshop\app\apphtml5\modules\Catalog\helpers\Review';
    protected $_reviewHelper;
    
    public function init()
    {
        parent::init();
        /**
         * 通过Yii::mapGet() 得到重写后的class类名以及对象。Yii::mapGet是在文件@fecshop\yii\Yii.php中
         */
        list($this->_reviewHelperName,$this->_reviewHelper) = Yii::mapGet($this->_reviewHelperName);  
        $reviewHelper = $this->_reviewHelper;
        $reviewHelper::initReviewConfig();
    }
    
    /**
     * @return boolean , review页面是否开启验证码验证。
     */
    public function getAddCaptcha()
    {
        if (!$this->_add_captcha) {
            $appName = Yii::$service->helper->getAppName();
            $addCaptcha = Yii::$app->store->get($appName.'_catalog','review_add_captcha');
            // $reviewParam = Yii::$app->getModule('catalog')->params['review'];
            $this->_add_captcha = ($addCaptcha == Yii::$app->store->enable) ? true : false;
        }

        return $this->_add_captcha;
    }

    public function getLastData($editForm)
    {
        if (!is_array($editForm)) {
            $editForm = [];
        }
        $_id = Yii::$app->request->get('product_id');
        $order_id = Yii::$app->request->get('order_id');
        $item_id = Yii::$app->request->get('item_id');
        // request 传递参数验证
        $customerId = Yii::$app->user->identity->id;
        $orderModel = Yii::$service->order->getByPrimaryKey($order_id);
        if ($orderModel['customer_id'] != $customerId) {
            return [];
        }
        $orderItemModel = Yii::$service->order->item->getByPrimaryKey($item_id);
        if ($orderItemModel['order_id'] != $order_id) {
            return [];
        }
        if ($orderItemModel['product_id'] != $_id) {
            return [];
        }
        if (!$_id) {
            Yii::$service->page->message->addError('product _id  is empty');
            return [];
        }
        $product = Yii::$service->product->getByPrimaryKey($_id);
        if (!$product['spu']) {
            Yii::$service->page->message->addError('product _id:'.$_id.'  is not exist in product collection');
            return [];
        }
        
        $spu = $product['spu'];
        $image = $product['image'];
        $main_img = isset($image['main']['image']) ? $image['main']['image'] : '';
        $url_key = $product['url_key'];
        $product_name = Yii::$service->store->getStoreAttrVal($product['name'], 'name');
        $customer_name = '';
        if (!Yii::$app->user->isGuest) {
            $identity = Yii::$app->user->identity;
            $customer_name = $identity['firstname'].' '.$identity['lastname'];
        }

        return [
            'customer_name'    => $customer_name,
            'product_id'    => $_id,
            'product_name'    => $product_name,
            'spu'            => $spu,
            'main_img'        => $main_img,
            'editForm'        => $editForm,
            'add_captcha'    => $this->getAddCaptcha(),
            'url'        => Yii::$service->url->getUrl($url_key),
        ];
    }
    /**
     * @param $editForm | Array
     * @return boolean ，保存评论信息
     */
    public function saveOrderReview($editForm)
    {
        $editForm['customer_id'] = Yii::$app->user->identity->id;
        $editForm['customer_name'] = Yii::$app->user->identity->firstname;
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!Yii::$service->product->review->addOrderReview($editForm)) {
                $innerTransaction->rollBack();
                return false;
            }
            $innerTransaction->commit();
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            return false;
        }
        
        return true;
    }
    /**
     * @param $product | String Or Object
     * 得到产品的价格信息
     */
    protected function getProductPriceInfo($product)
    {
        $price = $product['price'];
        $special_price = $product['special_price'];
        $special_from = $product['special_from'];
        $special_to = $product['special_to'];

        return Yii::$service->product->price->getCurrentCurrencyProductPriceInfo($price, $special_price, $special_from, $special_to);
    }
    // 废弃
    protected function getSpuData()
    {
        $spu = $this->_product['spu'];
        $filter = [
            'select'    => ['size'],
            'where'            => [
                ['spu' => $spu],
            ],
            'asArray' => true,
        ];
        $coll = Yii::$service->product->coll($filter);
        if (is_array($coll['coll']) && !empty($coll['coll'])) {
            foreach ($coll['coll'] as $one) {
                $spu = $one['spu'];
            }
        }
    }
}
