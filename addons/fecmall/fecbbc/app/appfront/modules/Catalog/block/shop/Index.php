<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Catalog\block\shop;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
{
    // url的参数，排序方向
    protected $_direction = 'dir';
    // url的参数，排序字段
    protected $_sort = 'sort';
    // url的参数，页数
    protected $_page = 'p';
    protected $_numPerPage = 20;
    protected $_productCount;
    
    public function getConfigArr($str)
    {
        if (!$str) {
            return null;
        }
        $arr = explode(',', $str);
        
        return $arr;
    }
    
    public function getLastData()
    {
        $bdmin_user_id = Yii::$app->request->get('bdmin_user_id');
        $bdminUser = Yii::$service->bdminUser->bdminUser->getByPrimaryKey($bdmin_user_id);
        $this->initHead($bdmin_user_id);
        $bdminName = $bdminUser['person'];
        
        
        // $hot_product_skus = Yii::$app->store->get('fecbbc_info', 'hot_product_skus'); 
        // $hot_product_sku_arr = $this->getConfigArr($hot_product_skus);
        
        $home_top_banner = Yii::$app->store->bdminGet($bdmin_user_id, 'bdmin_config', 'home_top_banner');
        
        return [
            'bdmin_products' => $this->getBdminProducts($bdmin_user_id),
            'bdminName' => $bdminName,
            'home_top_banner' => $home_top_banner,
            'product_page'          => $this->getProductPage(),
        ];
        
    }
    
    
    public function getBdminProducts($bdmin_user_id)
    {
        if ($bdmin_user_id) {
            $productPrimaryKey = Yii::$service->product->getPrimaryKey();
            $select = [
                $productPrimaryKey, 'sku', 'spu', 'name', 'image',
                'price', 'special_price',
                'special_from', 'special_to',
                'url_key', 'score', 'reviw_rate_star_average', 'review_count'
            ];
            $filter['select'] = $select;
            $filter['pageNum'] = $this->getPageNum();
            $filter['numPerPage'] = $this->getNumPerPage();
            $filter['orderBy'] = $this->getOrderBy();
            $filter['where'] = ['bdmin_user_id' => (int)$bdmin_user_id, 'status' => Yii::$service->product->getEnableStatus()];
           //var_dump( $filter);exit;
            $productCollInfo = Yii::$service->category->product->getFrontList($filter);
            $products = $productCollInfo['coll'];
            $this->_productCount = $productCollInfo['count'];
            
            return $products;
        }
    }
    
    /**
     * 得到当前第几页
     */
    protected function getPageNum()
    {
        $numPerPage = Yii::$app->request->get($this->_page);

        return $numPerPage ? (int) $numPerPage : 1;
    }
    
     /**
     * 分类页面的产品，每页显示的产品个数。
     * 对于前端传递的个数参数，在后台验证一下是否是合法的个数（配置里面有一个分类产品个数列表）
     * 如果不合法，则报异常
     * 这个功能是为了防止分页攻击，伪造大量的不同个数的url，绕过缓存。
     */
    protected function getNumPerPage()
    {
        return $this->_numPerPage;
    }
    
    
    /**
     * 用于搜索条件的排序部分
     */
    protected function getOrderBy()
    {
        $storageName = Yii::$service->product->serviceStorageName();
        
        return [
            'created_at' => $storageName == 'mongodb' ? -1 :  SORT_DESC,
        ];
    }
    
    /**
     * 得到产品页面的toolbar部分
     * 也就是分类页面的分页工具条部分。
     */
    protected function getProductPage()
    {
        $productNumPerPage = $this->getNumPerPage();
        $productCount = $this->_productCount;
        $pageNum = $this->getPageNum();
        $config = [
            'class'        => 'fecshop\app\appfront\widgets\Page',
            'view'        => 'widgets/page.php',
            'pageNum'        => $pageNum,
            'numPerPage'    => $productNumPerPage,
            'countTotal'    => $productCount,
            'page'            => $this->_page,
        ];

        return Yii::$service->page->widget->renderContent('bdmin_product_page', $config);
    }
    
    public function initHead($bdmin_user_id)
    {
        $home_meta_title = Yii::$app->store->bdminGet($bdmin_user_id, 'bdmin_config', 'home_meta_title');
        $title = Yii::$service->store->GetStoreAttrVal($home_meta_title, 'home_meta_title');
        
        $home_meta_keywords = Yii::$app->store->bdminGet($bdmin_user_id, 'bdmin_config', 'home_meta_keywords');
        $home_meta_keywords = Yii::$service->store->GetStoreAttrVal($home_meta_keywords, 'home_meta_keywords');
        
        $home_meta_description = Yii::$app->store->bdminGet($bdmin_user_id, 'bdmin_config', 'home_meta_description');
        $home_meta_description = Yii::$service->store->GetStoreAttrVal($home_meta_description, 'home_meta_description');
        
        Yii::$app->view->title = $title;
        
        Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => $home_meta_keywords,
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => $home_meta_description,
        ]);
        
    }
}