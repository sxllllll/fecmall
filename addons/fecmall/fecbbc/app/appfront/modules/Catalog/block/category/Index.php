<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Catalog\block\category;

use Yii;
use yii\base\InvalidValueException;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends \fecshop\app\appfront\modules\Catalog\block\category\Index
{
     /**
     * 得到子分类，如果子分类不存在，则返回同级分类。
     */
    protected function getFilterCategory()
    {
        $category_id = $this->_primaryVal;
        $parent_id = $this->_category['parent_id'];
        $filter_category = Yii::$service->category->getFilterCategory($category_id, $parent_id);
        
        return $filter_category;
    }
    
    
    public function getLastData()
    {
        // 每页显示的产品个数，进行安全验证，如果个数不在预先设置的值内，则会报错。
        // 这样是为了防止恶意攻击，也就是发送很多不同的页面个数的链接，绕开缓存。
        $this->getNumPerPage();
        //echo Yii::$service->page->translate->__('fecshop,{username}', ['username' => 'terry']);
        if(!$this->initCategory()){
            Yii::$service->url->redirect404();
            return;
        }
        
        // change current layout File.
        //Yii::$service->page->theme->layoutFile = 'home.php';

        $productCollInfo = $this->getCategoryProductColl();
        $products = $productCollInfo['coll'];
        $this->_productCount = $productCollInfo['count'];
        //echo $this->_productCount;
        return [
            'title'                 => $this->_title,
            'name'                  => Yii::$service->store->getStoreAttrVal($this->_category['name'], 'name'),
            'name_default_lang'     => Yii::$service->fecshoplang->getDefaultLangAttrVal($this->_category['name'], 'name'),
            'image'                 => $this->_category['image'] ? Yii::$service->category->image->getUrl($this->_category['image']) : '',
            'description'           => Yii::$service->store->getStoreAttrVal($this->_category['description'], 'description'),
            'products'              => $products,
            'query_item'            => $this->getQueryItem(),
            'product_page'          => $this->getProductPage(),
            'refine_by_info'        => $this->getRefineByInfo(),
            'filter_info'           => Yii::$service->category->getFilterInfo($this->_category, $this->_where),  //$this->getFilterInfo(),
            'filter_price'          => $this->getFilterPrice(),
            'filter_category'       => $this->getFilterCategory(),
            'categoryM' => $this->_category,
            //'content' => Yii::$service->store->getStoreAttrVal($this->_category['content'],'content'),
            //'created_at' => $this->_category['created_at'],
        ];
    }
}
