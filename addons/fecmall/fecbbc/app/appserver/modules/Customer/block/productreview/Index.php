<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appserver\modules\Customer\block\productreview;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
{
    public $pageNum;
    public $numPerPage = 20;
    public $_page = 'p';

    public function getLastData()
    {
        $this->pageNum = Yii::$app->request->get($this->_page);
        $this->pageNum = $this->pageNum ? $this->pageNum : 1;
        $identity = Yii::$app->user->identity;
        $user_id = $identity['id'];
        $filter = [
            'numPerPage'    => $this->numPerPage,
            'pageNum'        => $this->pageNum,
            'orderBy'    => ['review_date' => SORT_DESC],
            'where'            => [
                ['user_id'=> $user_id],
            ],
            'asArray' => true,
        ];
        $data = Yii::$service->product->review->getReviewsByUserId($filter);
        $coll = $data['coll'];
        $productIds = [];
        if (is_array($coll) && !empty($coll)) {
            foreach ($coll as $k=>$one) {
                $product_id = $one['product_id'];
                $productIds[] = $product_id;
            }
        }
        $productColl = [];
        if (is_array($productIds) && !empty($productIds)) {
            $productParymaryKey = Yii::$service->product->getPrimaryKey();
            $filter = [
                'select' => [$productParymaryKey, 'image', 'name', 'url_key'],
                'where'            => [
                    ['in', $productParymaryKey, $productIds],
                ],
                'asArray' => true,
                'fetchAll' => true,
            ];
            $productData = Yii::$service->product->coll($filter);
            if (is_array($productData['coll']) && !empty($productData['coll'])) {
                foreach ($productData['coll'] as $one) {
                    $mainImg = isset($one['image']['main']['image']) ? $one['image']['main']['image'] : '';
                    $name = $one['name'];
                    $product_id = $one[$productParymaryKey];
                    $productColl[$product_id] = [
                        'product_id' => $product_id,
                        'url_key' => $one['url_key'],
                        'name' => Yii::$service->store->getStoreAttrVal($name, 'name'),
                        'image' => Yii::$service->product->image->getUrl($mainImg),
                    ];
                }
            }
        }
        if (is_array($coll) && !empty($coll)) {
            foreach ($coll as $k=>$one) {
                $product_id = $one['product_id'];
                $productM = $productColl[$product_id];
                $coll[$k]['name'] = $productM['name'];
                $coll[$k]['image'] = $productM['image'];
                $coll[$k]['url_key'] = $productM['url_key'];
                $coll[$k]['review_date'] = date('Y-m-d', $one['review_date']);
                $rate_star = $one['rate_star'];
                if ($rate_star <= 1) {
                    $coll[$k]['rate_star_str'] = '差评';
                } else if ($rate_star <= 3) {
                    $coll[$k]['rate_star_str'] = '中评';
                } else if ($rate_star >= 4) {
                    $coll[$k]['rate_star_str'] = '好评';
                }
            }
        }
        $data = [
            'reviewList'            => $coll,
            'noActiveStatus'=> Yii::$service->product->review->noActiveStatus(),
            'refuseStatus'  => Yii::$service->product->review->refuseStatus(),
            'activeStatus'  => Yii::$service->product->review->activeStatus(),
        ];
        $code = Yii::$service->helper->appserver->status_success;
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }

    protected function getProductPage($countTotal)
    {
        if ($countTotal <= $this->numPerPage) {
            return '';
        }
        $config = [
            'class'        => 'fecshop\app\apphtml5\widgets\Page',
            'view'        => 'widgets/page.php',
            'pageNum'        => $this->pageNum,
            'numPerPage'    => $this->numPerPage,
            'countTotal'    => $countTotal,
            'page'            => $this->_page,
        ];

        return Yii::$service->page->widget->renderContent('category_product_page', $config);
    }
}
