<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Catalog\block\categorylist;

use Yii;
use yii\base\InvalidValueException;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index  extends \yii\base\BaseObject
{
    
    public function getLastData()
    {
        $top_category_id = Yii::$app->request->get('top_cate_id');
        $top_category_id = $top_category_id ? $top_category_id : 0;
        $oneLevelCategorys = Yii::$service->category->getChildCategory('0');
        $childCategorys = [];
        $activeTopCategoryId = '';
        $i = 0;
        $categoryImage = '';
        $categoryUrl = '';
        $categoryName = '';
        foreach ($oneLevelCategorys as $category_id => $one) {
            $i++;
            
            $oneLevelCategorys[$category_id]['name'] = Yii::$service->store->getStoreAttrVal($oneLevelCategorys[$category_id]['name'], 'name');
            // 初始化，默认选择第一个
            if ($i == 1) {
                $activeTopCategoryId = $category_id;
                $categoryImage = $one['image'];
                $categoryUrl = $one['url_key'];
                $categoryName = $oneLevelCategorys[$category_id]['name'];
            }
            // 如果存在当前的category，那么进行赋值
            if ($top_category_id == $category_id) {
                $activeTopCategoryId = $category_id;
                $categoryImage = $one['image'];
                $categoryUrl = $one['url_key'];
                $categoryName = $oneLevelCategorys[$category_id]['name'];
            }
            
        }
        if ($activeTopCategoryId) {
            $childCategorys = Yii::$service->category->getChildCategory($activeTopCategoryId);
            foreach ($childCategorys as $categoryId => $one) {
                $childCategorys[$categoryId]['name'] = Yii::$service->store->getStoreAttrVal($childCategorys[$categoryId]['name'], 'name');
                $thumbnail_image = $childCategorys[$categoryId]['thumbnail_image'];
                if (!$thumbnail_image) {
                    
                }
                if (!$thumbnail_image) {
                    $childCategorys[$categoryId]['thumbnail_image']  = Yii::$service->image->getImgUrl('addons/fectb/classify-ph02.png');
                } else {
                    $childCategorys[$categoryId]['thumbnail_image']  = Yii::$service->category->image->getUrl($thumbnail_image);
                }
                
            }
        }
        if (!$categoryImage) {
            $categoryImage = Yii::$service->image->getImgUrl('addons/fectb/classify-ph01.png');
        } else {
            $categoryImage = Yii::$service->category->image->getUrl($categoryImage);
        }
        return [
            'top_categorys' => $oneLevelCategorys,
            'active_category_id' => $activeTopCategoryId,
            'child_categorys' => $childCategorys,
            'category_image' => $categoryImage,
            'category_url' => $categoryUrl,
            
            'category_name' => $categoryName,
        ];
    }
    
}
