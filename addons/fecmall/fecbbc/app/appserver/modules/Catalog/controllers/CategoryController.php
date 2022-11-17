<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appserver\modules\Catalog\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CategoryController extends \fecshop\app\appserver\modules\Catalog\controllers\CategoryController
{
    
    // 微信分类部分数据
    public function actionWxindex(){
        
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        // 每页显示的产品个数，进行安全验证，如果个数不在预先设置的值内，则会报错。
        // 这样是为了防止恶意攻击，也就是发送很多不同的页面个数的链接，绕开缓存。
        $this->getNumPerPage();
        //echo Yii::$service->page->translate->__('fecshop,{username}', ['username' => 'terry']);
        if(!$this->initCategory()){
            $code = Yii::$service->helper->appserver->category_not_exist;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        // change current layout File.
        //Yii::$service->page->theme->layoutFile = 'home.php';

        $productCollInfo = $this->getWxCategoryProductColl();
        $products = $productCollInfo['coll'];
        $this->_productCount = $productCollInfo['count'];
        $p = Yii::$app->request->get('p');
        $p = (int)$p;
        $query_item = $this->getQueryItem();
        $page_count = $this->getProductPageCount();
        $this->category_name = Yii::$service->store->getStoreAttrVal($this->_category['name'], 'name');
        $microshare = $this->getMicroShareInfo();
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'name'              => $this->category_name ,
            'name_default_lang' => Yii::$service->fecshoplang->getDefaultLangAttrVal($this->_category['name'], 'name'),
            'title'             => $this->_title,
            'image'             => $this->_category['image'] ? Yii::$service->category->image->getUrl($this->_category['image']) : '',
            'products'          => $products,
            'query_item'        => $query_item,
            'refine_by_info'    => $this->getRefineByInfo(),
            'filter_info'       => $this->getFilterInfo(),
            'filter_price'      => $this->getFilterPrice(),
            'filter_category'   => $this->getFilterCategory(),
            'page_count'        => $page_count,
            'microshare' => $microshare,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    /**
     *  微信分享的信息
     */
    public function getMicroShareInfo()
    {
        $pageImgUrl = Yii::$service->wx->micro->categoryPageImgUrl;
        
        return [
            'isDistribute' => false,
            'pageTitle' => $this->category_name,
            'pageImgUrl' => $pageImgUrl,
            'distributeCode' => '',
        ];
    }
    
    
}