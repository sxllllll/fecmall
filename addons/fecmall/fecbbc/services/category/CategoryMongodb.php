<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\services\category;

use fecshop\services\Service;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CategoryMongodb extends \fecshop\services\category\CategoryMongodb
{
    
    /**
     * @param $category_id|string  当前的分类_id
     * @param $parent_id|string  当前的分类上级id parent_id
     * 这个功能是点击分类后，在产品分类页面侧栏的子分类菜单导航，详细的逻辑如下：
     * 1.如果level为一级，那么title部分为当前的分类，子分类为一级分类下的二级分类
     * 2.如果level为二级，那么将所有的二级分类列出，当前的二级分类，会列出来当前二级分类对应的子分类
     * 3.如果level为三级，那么将所有的二级分类列出。当前三级分类的所有姊妹分类（同一个父类）列出，当前三级分类如果有子分类，则列出
     * 4.依次递归。
     * 具体的显示效果，请查看appfront 对应的分类页面。
     */
    public function getFilterCategory($category_id, $parent_id)
    {
        // 得到父分类
        $data = $this->_categoryModel->find()->asArray()->where(['parent_id' => '0'])->all();
        $arr = [];
       
        if (is_array($data) && !empty($data)) {
            foreach ($data as $k => $one) {
                $current = false;
                //$one =$this->unserializeData($one) ;
                $categoryId = (string)$one['_id'];
                $childCategory = $this->getChildCate($categoryId);
                $childArr = [];
                foreach ($childCategory as $currentId => $childOne) {
                    //echo $currentId;
                    $childCurrent = false;
                    if ($category_id == $currentId ) {
                        $childCurrent = true;
                        $current = true;
                    }
                    $childArr[] = [
                        'url_key' => $childOne['url_key'],
                        'name' => $childOne['name'],
                        'id' => $currentId,
                        'current' => $childCurrent,
                    ];
                }
                if ($category_id == $categoryId ) {
                    $current = true;
                }
                $arr[] = [
                    'url_key' => $one['url_key'],
                    'name' => $one['name'],
                    'id' => $one['id'],
                    'current' => $current,
                    'child' => $childArr,
                ]; 
            }
        }
        return $arr;
    }
    
}