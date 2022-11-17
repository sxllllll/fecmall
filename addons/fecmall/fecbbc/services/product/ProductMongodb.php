<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\services\product;

use fecshop\models\mongodb\Product;
use fecshop\services\Service;
use Yii;

/**
 * Product ProductMysqldb Service
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductMongodb extends \fecshop\services\product\ProductMongodb
{
    public $numPerPage = 20;
    
    protected $_productModelName = '\fecbbc\models\mongodb\Product';
    
    
    // spu 中 `代表产品`的 is_deputy 的值
    public $goodsIsDeputy = 1;
    // spu 中，不是 `代表产品`的 is_deputy 的值
    public $goodsNotDeputy = 2;
    

    /**
     * @param $one|array , 产品数据数组
     * @param $originUrlKey|string , 产品的原来的url key ，也就是在前端，分类的自定义url。
     * 保存产品（插入和更新），以及保存产品的自定义url
     * 如果提交的数据中定义了自定义url，则按照自定义url保存到urlkey中，如果没有自定义urlkey，则会使用name进行生成。
     */
    public function save($one, $originUrlKey = 'catalog/product/index')
    {
        if (!$this->initSave($one)) {
            return false;
        }
        $one['min_sales_qty'] = (int)$one['min_sales_qty'];
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
       
        //验证sku 是否重复的where
        $whereOr = [];
        $whereOr[] =   [
            'sku' => $one['sku'],
        ];
        
        $whereOr[] =   [
            'spu' => $one['spu'],
            ['<>', 'bdmin_user_id', $one['bdmin_user_id'],]
        ]; 
        $where = [
            '$or' => $whereOr,
        ];
        if ($primaryVal) {
            $model = $this->_productModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('Product {primaryKey} is not exist', ['primaryKey'=>$this->getPrimaryKey()]);
                
                return false;
            }
            $product_one = $this->_productModel->find()->asArray()->where([
                '<>', $this->getPrimaryKey(), (new \MongoDB\BSON\ObjectId($primaryVal)),
            ])->andWhere($where)->one();
            
            if ($product_one['sku'] && $product_one['bdmin_user_id']) {
                if ($product_one['sku'] == $one['sku']) {
                    Yii::$service->helper->errors->add('Product Sku:{sku} is exist ，please use other sku', [ 'sku' => $one['sku'] ]);
                }
                if ($product_one['bdmin_user_id'] != $one['bdmin_user_id'] && $product_one['spu'] == $one['spu']) {
                    Yii::$service->helper->errors->add('Product spu:{spu} is exist in other bdmin，please use other spu', [ 'spu' => $one['spu'] ]);
                } 
                return false;
            }
        } else {
            $model = new $this->_productModelName();
            $model->created_at = time();
            $model->created_user_id = \fec\helpers\CUser::getCurrentUserId();
            $primaryVal = new \MongoDB\BSON\ObjectId();
            $model->{$this->getPrimaryKey()} = $primaryVal;
            //验证sku 是否重复
            $product_one = $this->_productModel->find()->asArray()->where($where)->one();
            if ($product_one['sku']) {
                if ($product_one['sku'] == $one['sku']) {
                    Yii::$service->helper->errors->add('Product Sku:{sku} is exist ，please use other sku', [ 'sku' => $one['sku'] ]);
                }
                if ($product_one['bdmin_user_id'] != $one['bdmin_user_id'] && $product_one['spu'] == $one['spu']) {
                    Yii::$service->helper->errors->add('Product spu:{spu} is exist in other bdmin，please use other spu', [ 'spu' => $one['spu'] ]);
                } 
                return false;
            }
        }
        $model->updated_at = time();
        // 计算出来产品的最终价格。
        $one['final_price'] = Yii::$service->product->price->getFinalPrice($one['price'], $one['special_price'], $one['special_from'], $one['special_to']);
        $one['score'] = (int) $one['score'];
        unset($one['_id']);
        /**
         * 保存产品
         */
        $saveStatus = Yii::$service->helper->ar->save($model, $one);
        /**
         * 如果 $one['custom_option'] 不为空，则计算出来库存总数，填写到qty
         */
        if (is_array($one['custom_option']) && !empty($one['custom_option'])) {
            $custom_option_qty = 0;
            foreach ($one['custom_option'] as $co_one) {
                $custom_option_qty += $co_one['qty'];
            }
            $model->qty = $custom_option_qty;
        }
        $saveStatus = Yii::$service->helper->ar->save($model, $one);
        // 自定义url部分
        if ($originUrlKey) {
            $originUrl = $originUrlKey.'?'.$this->getPrimaryKey() .'='. $primaryVal;
            $originUrlKey = isset($one['url_key']) ? $one['url_key'] : '';
            $defaultLangTitle = Yii::$service->fecshoplang->getDefaultLangAttrVal($one['name'], 'name');
            $urlKey = Yii::$service->url->saveRewriteUrlKeyByStr($defaultLangTitle, $originUrl, $originUrlKey);
            $model->url_key = $urlKey;
            $model->save();
        }
        $product_id = $model->{$this->getPrimaryKey()};
        /**
         * 更新产品库存。
         */
        Yii::$service->product->stock->saveProductStock($product_id, $one);
        /**
         * 更新产品信息到搜索表。
         */
        Yii::$service->search->syncProductInfo([$product_id]);

        return $model;
    }
    /**
     * @param productId string
     * @param $bdmin_user_id int  商户id
     * @return boolean
     */
    public function isBdminHasEditRole($productId, $bdmin_user_id)
    {
        $product = $this->getByPrimaryKey($productId);
        if (!isset($product['bdmin_user_id']) || !$product['bdmin_user_id']) {
            
            return false;
        }
        
        if ($product['bdmin_user_id'] != $bdmin_user_id) {
            
            return false;
        }
        
        return true;
    }
    /**
     * @param $spu | string
     * @return boolean
     * 查看该spu，是否存在代表产品
     */
    public function isProductSpuHasDeputy($spu)
    {
        if (!$spu) {
            
            return false;
        }
        $one = $this->_productModel->findOne([
                'spu' => $spu,
                'is_deputy' => $this->goodsIsDeputy,
            ]);
        if (!isset($one['spu']) || !$one['spu']) {
            
            return false;
        }
        
        return true;
    }
    /**
     * @param $spu | string, 产品spu
     * @return Object， product model
     * 得到spu对应的`代表产品`的产品信息，这个大致用于产品编辑。
     */
    public function getByEditSpu($spu)
    {
        if ($spu) {
            $one = $this->_productModel->findOne([
                'spu' => $spu,
                'is_deputy' => $this->goodsIsDeputy,
            ]);
            if (isset($one['attr_group']) && $one['attr_group']) {
                $this->addGroupAttrs($one['attr_group']);
            }
            
            $one = $this->_productModel->findOne([
                'spu' => $spu,
                'is_deputy' => $this->goodsIsDeputy,
            ]);
            
            return $one ;
        } else {
            
            return new $this->_productModelName();
        }
    }
    
    /**
      * @param $spu | string
      * @param  $spuAttrs ,spu对应的产品的规格属性数组，譬如：  array(2) { [0]=> string(5) "color" [1]=> string(4) "size" }
      * @param $spuImgAttr ，spu 显示图片的规格属性， 譬如：string(5) "color"
      * 得到当前spu对应的所有产品，里面的属性组的属性值。
      * @return $collArr | array, 规格属性数组，例子：array(2) { ["color"]=> array(2) { ["white"]=> string(5) "white" ["black"]=> string(5) "black" } ["size"]=> array(2) { ["M"]=> string(1) "M" ["L"]=> string(1) "L" } }
      * @return  $attrKeyArr | array , 用于初始化后台编辑，例子：array(4) { ["white_M_"]=> array(3) { ["sku_code"]=> string(5) "qqqqq" ["sku_price"]=> string(5) "11.00" ["sku_qty"]=> string(2) "11" } ["white_L_"]=> array(3) { ["sku_code"]=> string(4) "wwww" ["sku_price"]=> string(5) "11.00" ["sku_qty"]=> string(2) "11" } ["black_M_"]=> array(3) { ["sku_code"]=> string(5) "eeeee" ["sku_price"]=> string(5) "11.00" ["sku_qty"]=> string(2) "11" } ["black_L_"]=> array(3) { ["sku_code"]=> string(4) "rrrr" ["sku_price"]=> string(5) "11.00" ["sku_qty"]=> string(2) "11" } }
      */
    public function getSpuSelectSpuAttrVal($spu, $spuAttrs, $spuImgAttr)
    {
        $primaryKey = $this->getPrimaryKey();
        
        $collection = $this->_productModel->find()->getCollection();
        $condition = ['spu' => $spu];
        $fields = array_merge($spuAttrs, [$primaryKey,  'sku', 'price', 'image']);
        $options = [ ];
        $coll = [];
        $cursor = $collection->find($condition,  $fields, $options);
        foreach ($cursor as $one) {
            $coll[] = $one;
        }
        /*
        $filter = [
            'select' => [$primaryKey, 'attr_group_info', 'sku', 'price', 'image'],
            'asArray' => true,
            'fetchAll' => true,
            'where' => [
                ['spu' => $spu]
            ],
        ];
        $data = $this->coll($filter);
        $coll = $data['coll'];
        */
        $collArr = [];
        $attrKeyArr = [];
        $attrImgs = [];
        if (is_array($coll) && !empty($coll)) {
            $productIds = [];
            foreach ($coll as $one) {
                //var_dump($one);exit;
                $productIds[] = (string)$one[$primaryKey];
            } 
            $productQtys = Yii::$service->product->stock->getQtyByProductIds($productIds);
            foreach ($coll as $one) {
                
                $arr = [];
                $image = isset($one['image']['main']['image']) ? $one['image']['main']['image'] : '';
                $attrKey = '';
                foreach ($spuAttrs  as $spuAttr) {
                    if (isset($one[$spuAttr]) && $one[$spuAttr]) {
                        $spu_val = $one[$spuAttr];
                        $collArr[$spuAttr][$spu_val] = $spu_val;
                        $attrKey .= $spu_val . '_';
                        if ($spuAttr == $spuImgAttr) {
                            $attrImgs[$spuAttr][$spu_val] = $image;
                        }
                    }
                }
                
                $productId = (string)$one[$primaryKey];
                $productQty = 0;
                if (is_array($productQtys) && isset($productQtys[$productId]) && $productQtys[$productId]) {
                    $productQty = $productQtys[$productId];
                }
                
                $attrKeyArr[$attrKey] = [
                    'sku_code' => $one['sku'],
                    'sku_price' => $one['price'],
                    'sku_qty' => $productQty,
                ];
                
            }
        } else {
            $attrImgs[$spuImgAttr] = [];
        }
        
        return [$collArr, $attrKeyArr, $attrImgs];
    }
    
    
    /**
     * @param $editSpu, 编辑的spu
     * @param $one|array , 产品数据数组
     * @param $spuArr | array， spu数据数组
     * @param $originUrlKey|string , 产品的原来的url key ，也就是在前端，分类的自定义url。
     * 淘宝模式保存产品信息。
     * 保存产品（插入和更新），以及保存产品的自定义url
     * 如果提交的数据中定义了自定义url，则按照自定义url保存到urlkey中，如果没有自定义urlkey，则会使用name进行生成。
     */
    public function tbSave($editSpu, $one, $spuArr, $originUrlKey = 'catalog/product/index')
    {
        $primaryKey = $this->getPrimaryKey();
        if ($editSpu) {  // 产品编辑
            // 查询出来spu对应的产品，sku列表，不在
            $dbSpuColl = $this->_productModel->find()->asArray()
                    ->select([$primaryKey, 'spu', 'sku', 'is_deputy', 'url_key'])
                    ->where(['spu' => $editSpu])
                    ->all();
            // 编辑产品sku数组
            $editSkus = [];
            //var_dump($spuArr);exit;
            foreach ($spuArr  as $spuOne) {
                $editSkus[] = $spuOne['sku'];
            }
            // 数据库产品sku数组
            $dbSkus = [];
            $dbSkuProduct = [];
            $dbSkuProductUrlKey = [];   /////
            $deputy_sku = '';
            foreach ($dbSpuColl  as $dbSpuOne) {
                $sku = $dbSpuOne['sku'];
                //echo $sku;
                $is_deputy = $dbSpuOne['is_deputy'];
                if (($is_deputy == $this->goodsIsDeputy)  && in_array($sku, $editSkus)) {
                    $deputy_sku = $sku ;
                }
                $productId = (string)$dbSpuOne[$primaryKey];
                $dbSkus[] = $sku;
                /** Begin **/
                if (!empty($editSkus)) {
                    if (!in_array($sku, $editSkus)) {
                        // 删除产品
                        //echo $sku;exit;
                        $this->remove($productId);
                    }
                } else {
                    if ($sku == $one['sku']) {
                        $one[$primaryKey] = $productId ;
                    }
                }
                /** End **/
                $dbSkuProduct[$sku] = $productId;
                
                $dbSkuProductUrlKey[$sku] = $dbSpuOne['url_key'];  /////
            }
            $i = 0;
            
            
            if (is_array($spuArr) && !empty($spuArr)) {
                foreach ($spuArr  as $spuOne) {
                    $i++;
                    $insertOne = $one;
                    $sku = $spuOne['sku'];
                    
                    // 设置`代表产品`
                    if ($i == 1 && !$deputy_sku) {
                        $insertOne['is_deputy'] = $this->goodsIsDeputy;
                    } else if ($deputy_sku && ($deputy_sku != $sku)) {
                        $insertOne['is_deputy'] = $this->goodsNotDeputy;
                    } else if ($deputy_sku && $deputy_sku == $sku) {
                        $insertOne['is_deputy'] = $this->goodsIsDeputy;
                    }
                    if (in_array($sku, $dbSkus)) {
                        // 更新
                        $productId = $dbSkuProduct[$sku];
                        $url_key = $dbSkuProductUrlKey[$sku];   /////
                        $insertOne[$primaryKey] = $productId ;
                        $insertOne['url_key'] = $url_key ;      //////
                        //unset($insertOne['url_key']);
                    } else {
                        // 插入
                    }
                    /**  Begin **/
                    // 1.所有的图片加入gallery
                    $gallerys = [];
                    $gallerys = $one['image']['gallery'];
                    if (is_array($one['image']['main']) && !empty($one['image']['main'])) {
                        $gallerys[] = $one['image']['main'];
                    }
                    // 将spu属性写入。
                    foreach ($spuOne as $k=>$v) {
                        if ($k != 'main_image') {
                            $insertOne[$k] = $v;
                        } else {
                            $insertOne['image']['main']['image'] = $v;
                        }
                    }
                    // 2.设置主图，
                    // 3.然后查看gallery中是否存在main，如果存在，则清除,同时去重复
                    $galleryImgs = [];
                    foreach ($gallerys as $galleryK => $gallery) {
                        $galleryImg = $gallery['image'];
                        
                        // 如果存在，去重复
                        if (isset($galleryImgs[$galleryImg])) {
                            unset($gallerys[$galleryK]);
                        } else {
                            $galleryImgs[$galleryImg] = $galleryImg;
                        }
                        if ($galleryImg == $insertOne['image']['main']['image']) {
                            unset($gallerys[$galleryK]);
                        }
                    }
                    $insertOne['image']['gallery'] = $gallerys;
                    /**  End **/
                    $this->save($insertOne, 'catalog/product/index');
                }
            } else {
                $one['is_deputy'] = 1;
                if (isset($one['product_id']) && $one['product_id']) {
                    $one[$primaryKey] = $one['product_id'];
                }
                $this->save($one, 'catalog/product/index');
            }
            
        } else {  // 产品插入
            // $spu = $one['spu'];
            $i = 0;
            if (is_array($spuArr) && !empty($spuArr)) {
                foreach ($spuArr as $spuOne) {
                    $insertOne = $one;
                    foreach ($spuOne as $k=>$v) {
                        $insertOne[$k] = $v;
                    }
                    $i++;
                    if ($i == 1) {
                        $insertOne['is_deputy'] = $this->goodsIsDeputy;
                    } else {
                        $insertOne['is_deputy'] = $this->goodsNotDeputy;
                    }
                    // 将spu属性写入。
                    foreach ($spuOne as $k=>$v) {
                        if ($k != 'main_image') {
                            $insertOne[$k] = $v;
                        } else {
                            $insertOne['image']['main']['image'] = $v;
                        }
                    }
                    // 图片
                    if ($spuOne['main_image']) {
                        $insertOne['image']['main']['image'] = $spuOne['main_image'];
                    }
                    $this->save($insertOne, 'catalog/product/index');
                }
            } else {
                $one['is_deputy'] = 1;
                $this->save($one, 'catalog/product/index');
            }
        }
    }    
        
    
    
    /**
     * @param $one|array , 产品数据数组
     * @param $originUrlKey|string , 产品的原来的url key ，也就是在前端，分类的自定义url。
     * 保存产品（插入和更新），以及保存产品的自定义url
     * 如果提交的数据中定义了自定义url，则按照自定义url保存到urlkey中，如果没有自定义urlkey，则会使用name进行生成。
     */
    public function excelSave($one, $originUrlKey = 'catalog/product/index')
    {
        $sku = $one['sku'];
        if (isset($one['attr_group']) && $one['attr_group']) {
            $this->addGroupAttrs($one['attr_group']);
        }
        // 查询出来主键。
        $primaryKey = $this->getPrimaryKey();
        $productModel = $this->getBySku($sku);
        if (isset($productModel['sku']) && $productModel['sku']) {
            $one[$primaryKey] = $productModel[$primaryKey];
        }
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        
        // 得到group spu attr
        $attr_group = $one['attr_group'];
        $groupSpuAttrs = Yii::$service->product->getGroupSpuAttr($attr_group);
        $spuAttrArr = [];
        if (is_array($groupSpuAttrs)) {
            foreach ($groupSpuAttrs as $groupSpuOne) {
                $spuAttrArr[] = $groupSpuOne['name'];
            }
        }
        
        if ($primaryVal) {
            $model = $this->_productModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('Product {primaryKey} is not exist', ['primaryKey'=>$this->getPrimaryKey()]);

                return false;
            }

            //验证sku 是否重复
            $product_one = $this->_productModel->find()->asArray()->where([
                '<>', $this->getPrimaryKey(), (new \MongoDB\BSON\ObjectId($primaryVal)),
            ])->andWhere([
                'sku' => $one['sku'],
            ])->one();
            if ($product_one['sku']) {
                Yii::$service->helper->errors->add('Product Sku is exist，please use other sku');

                return false;
            }
            
            // spu 下面的各个sku的spu属性不能相同
            if (!empty($spuAttrArr)) {
                $product_mode = $this->_productModel->find()->asArray()->where([
                    '<>', $this->getPrimaryKey(), (new \MongoDB\BSON\ObjectId($primaryVal)),
                ])->andWhere([
                    'spu' => $one['spu'],
                ]);
                foreach ($spuAttrArr as $sar) {
                    $product_mode->andWhere([$sar => $one[$sar]]);
                }
                
                $product_one = $product_mode->one();
                if ($product_one['sku']) {
                    Yii::$service->helper->errors->add('product Spu of the same,  Spu attributes cannot be the same');

                    return false;
                }
            }
            
            // 多语言属性，如果您有其他的多语言属性，可以自行二开添加。
            $name =$model['name'];
            $meta_title = $model['meta_title'];
            $meta_keywords = $model['meta_keywords'];
            $meta_description = $model['meta_description'];
            $short_description = $model['short_description'];
            $description = $model['description'];
            if (is_array($one['name']) && !empty($one['name'])) {
                $one['name'] = array_merge((is_array($name) ? $name : []), $one['name']);
            }
            if (is_array($one['meta_title']) && !empty($one['meta_title'])) {
                $one['meta_title'] = array_merge((is_array($meta_title) ? $meta_title : []), $one['meta_title']);
            }
            if (is_array($one['meta_keywords']) && !empty($one['meta_keywords'])) {
                $one['meta_keywords'] = array_merge((is_array($meta_keywords) ? $meta_keywords : []), $one['meta_keywords']);
            }
            if (is_array($one['meta_description']) && !empty($one['meta_description'])) {
                $one['meta_description'] = array_merge((is_array($meta_description) ? $meta_description : []), $one['meta_description']);
            }
            if (is_array($one['short_description']) && !empty($one['short_description'])) {
                $one['short_description'] = array_merge((is_array($short_description) ? $short_description : []), $one['short_description']);
            }
            if (is_array($one['description']) && !empty($one['description'])) {
                $one['description'] = array_merge((is_array($description) ? $description : []), $one['description']);
            }
        } else {
            $model = new $this->_productModelName();
            $model->created_at = time();
            $model->created_user_id = \fec\helpers\CUser::getCurrentUserId();
            $primaryVal = new \MongoDB\BSON\ObjectId();
            $model->{$this->getPrimaryKey()} = $primaryVal;
            //验证sku 是否重复
            $product_one = $this->_productModel->find()->asArray()->where([
                'sku' => $one['sku'],
            ])->one();
            if ($product_one['sku']) {
                Yii::$service->helper->errors->add('Product Sku is exist，please use other sku');

                return false;
            }
            
            if (!empty($spuAttrArr)) {
                $product_mode = $this->_productModel->find()->asArray()->where([
                    'spu' => $one['spu'],
                ]);
                foreach ($spuAttrArr as $sar) {
                    $product_mode->andWhere([$sar => $one[$sar]]);
                }
                
                $product_one = $product_mode->one();
                if ($product_one['sku']) {
                    Yii::$service->helper->errors->add('product Spu of the same,  Spu attributes cannot be the same');

                    return false;
                }
            }
            
        }
        $model->updated_at = time();
        // 计算出来产品的最终价格。
        $one['final_price'] = Yii::$service->product->price->getFinalPrice($one['price'], $one['special_price'], $one['special_from'], $one['special_to']);
        $one['score'] = (int) $one['score'];
        unset($one['_id']);
        unset($one['custom_option']);
        /**
         * 如果 $one['custom_option'] 不为空，则计算出来库存总数，填写到qty
         */
        //if (is_array($one['custom_option']) && !empty($one['custom_option'])) {
        //    $custom_option_qty = 0;
        //    foreach ($one['custom_option'] as $co_one) {
        //        $custom_option_qty += $co_one['qty'];
        //    }
        //    $one['qty'] = $custom_option_qty;
        //}
        /**
         * 保存产品
         */
        //var_dump($one);exit;
        $saveStatus = Yii::$service->helper->ar->save($model, $one);
        
        // 查看spu对应的产品，是否有`代表产品`,如果没有，则设置
        $se_one = $this->_productModel->findOne([
            'spu' => $one['spu'],
            'is_deputy' => $this->goodsIsDeputy,
        ]);
        if (!isset($se_one['spu']) || !$se_one['spu']) {
            $model->is_deputy = $this->goodsIsDeputy;
        }
        
        // 自定义url部分
        if ($originUrlKey) {
            $originUrl = $originUrlKey.'?'.$this->getPrimaryKey() .'='. $primaryVal;
            $originUrlKey = isset($one['url_key']) ? $one['url_key'] : '';
            $defaultLangTitle = Yii::$service->fecshoplang->getDefaultLangAttrVal($one['name'], 'name');
            $urlKey = Yii::$service->url->saveRewriteUrlKeyByStr($defaultLangTitle, $originUrl, $originUrlKey);
            $model->url_key = $urlKey;
            $model->save();
        }
        
        $product_id = $model->{$this->getPrimaryKey()};
        /**
         * 更新产品库存。
         */
        Yii::$service->product->stock->saveProductStock($product_id, $one);
        /**
         * 更新产品信息到搜索表。
         */
        Yii::$service->search->syncProductInfo([$product_id]);

        return $model;
    }
    
    /**
     * @param $productModel | object
     *
     */
    public function initProductDeputy($productModel)
    {
        $spu = $productModel['spu'];
        //$productId = (string)$productModel['_id'];
        if (!$spu) {
            return ;
        }
        if (!$this->isProductSpuHasDeputy($spu)) {
            $productModel['is_deputy'] = $this->goodsIsDeputy;
            $productModel->save();
        }
    }
    
    /**
     * @param $ids | Array or String
     * 删除产品，如果ids是数组，则删除多个产品，如果是字符串，则删除一个产品
     * 在产品产品的同时，会在url rewrite表中删除对应的自定义url数据。
     * 首先查询出来该id对应的spu，然后将spu下的所有的sku删除
     */
    public function removeByProductIdsWithSameSpu($ids)
    {
        if (empty($ids)) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        // 查询对应ids的spu
        $primaryKey = $this->getPrimaryKey();
        $filter = [
            'where' => [
                ['in', $primaryKey, $ids]
            ],
            'asArray' => true,
            'fetchAll' => true,
        ];
        $data = $this->coll($filter);
        $coll = $data['coll'];
        if (!is_array($coll)) {
            
            return false;
        }
        $spus = [];
        // 得到spus数组
        foreach ($coll as $one) {
            $spus[$one['spu']] = $one['spu'];
        }
        if (empty($spus)) {
            
            return false;
        }
        // 根据spu数组，得到所有的产品
        $filter = [
            'where' => [
                ['in', 'spu', $spus]
            ],
            'fetchAll' => true,
            'asArray' => false,
        ];
        $data = $this->coll($filter);
        $coll = $data['coll'];
        if (!is_array($coll)) {
            
            return false;
        }
        $removeAll = 1;
        foreach ($coll as $model) {
            // 对产品进行删除操作。
            if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                $id = $model[$this->getPrimaryKey()];
                $url_key = $model['url_key'];
                // 删除在重写url里面的数据。
                Yii::$service->url->removeRewriteUrlKey($url_key);
                // 删除在搜索表（各个语言）里面的数据
                Yii::$service->search->removeByProductId($id);
                Yii::$service->product->stock->removeProductStock($id);
                $model->delete();
                $this->removeCategoryProductRelationByProductId($id);
            }
        }
        if (!$removeAll) {
            return false;
        }
        
        return true;
    }
    
    public function bulkenable($productIds)
    {
        if (!is_array($productIds) || empty($productIds)) {
            Yii::$service->helper->errors->add('product ids is not array or is empty');
            
            return false;
        }
        $updateArr['status'] = 1;
        $updateArr['updated_at'] = time();
        $primaryKey = $this->getPrimaryKey();
        $this->_productModel->updateAll(
                $updateArr,
                [
                    'in', $primaryKey, $productIds
                ]
            ); 
        
        return true;
    } 
    
    public function bulkdisable($productIds)
    {
        if (!is_array($productIds) || empty($productIds)) {
            Yii::$service->helper->errors->add('product ids is not array or is empty');
            
            return false;
        }
        $updateArr['status'] = 2;
        $updateArr['updated_at'] = time();
        $primaryKey = $this->getPrimaryKey();
        $this->_productModel->updateAll(
                $updateArr,
                [
                    'in', $primaryKey, $productIds
                ]
            ); 
        
        return true;
    } 
    
}
