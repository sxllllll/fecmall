<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Catalogsearch\block\index;

use Yii;
use yii\base\InvalidValueException;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends \fecshop\app\appfront\modules\Catalogsearch\block\index\Index
{
    public function getLastData()
    {
        
        $this->getNumPerPage();
        //echo Yii::$service->page->translate->__('fecshop,{username}', ['username' => 'terry']);
        $this->initSearch();
        // change current layout File.
        //Yii::$service->page->theme->layoutFile = 'home.php';

        $productCollInfo = $this->getSearchProductColl();
        $products = $productCollInfo['coll'];
        $this->_productCount = $productCollInfo['count'];
        //echo $this->_productCount;
        return [
            'searchText'        => $this->_searchText,
            'title'             => $this->_title,
          //  'name'              => Yii::$service->store->getStoreAttrVal($this->_category['name'], 'name'),
          //  'image'             => $this->_category['image'] ? Yii::$service->category->image->getUrl($this->_category['image']) : '',
          //  'description'       => Yii::$service->store->getStoreAttrVal($this->_category['description'], 'description'),
            'products'          => $products,
            'query_item'        => $this->getQueryItem(),
            'product_page'      => $this->getProductPage(),
            'refine_by_info'    => $this->getRefineByInfo(),
            'filter_info'       => $this->getFilterInfo(),
            'filter_price'      => $this->getFilterPrice(),
           // 'filter_category'       => $this->getFilterCategory(),
            'traceSearchData'   => $this->getTraceSearchData(),
        ];
    }
    
     /**
     * 得到子分类，如果子分类不存在，则返回同级分类。
     */
    /*
    protected function getFilterCategory()
    {
        $category_id = '';
        $parent_id = $this->_category['parent_id'];
        $filter_category = Yii::$service->category->getFilterCategory($category_id, $parent_id);
        
        return $filter_category;
    }
    */
    
    protected function breadcrumbs()
    {
        Yii::$service->page->breadcrumbs->addItems(['name' => $this->_searchText]);
    }
}
