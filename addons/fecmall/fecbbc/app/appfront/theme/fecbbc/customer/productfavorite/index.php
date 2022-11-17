<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
use fecshop\app\appfront\helper\Format;
?>

<div class="me-favorite-page me-page yoho-page clearfix">
    <p class="home-path">
        <span class="path-icon"></span>
        <a href="<?= Yii::$service->url->homeUrl(); ?>"><?= Yii::$service->page->translate->__('Home') ?></a>
        &nbsp;&nbsp;&gt;&nbsp;&nbsp;
        <span><?= Yii::$service->page->translate->__('Product Favorite') ?></span>
    </p>    
    <div class="home-navigation block">
        <?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>
    </div>
    <div class="me-main">
        <div class="favorite block fav-products">
            <h2 class="title"></h2>
            <ul class="tabs clearfix">
                <li class="active">
                    <a href="#"><?= Yii::$service->page->translate->__('Favorites') ?></a>
                </li>
            </ul>

            <div class="favorite-products">
                <p class="favorite-table-header table-header clearfix">
                    <span class="info"><?= Yii::$service->page->translate->__('Image') ?></span>
                    <span class="price"><?= Yii::$service->page->translate->__('Goods Info') ?></span>
                    <span class="options"><?= Yii::$service->page->translate->__('Operate') ?></span>
                </p>
                <?php $i=0;  if(is_array($coll) && !empty($coll)):  ?>
                    <ul>
                    <?php foreach($coll as $one):  $i++;?>
                        <li style="padding: 10px 0 20px;border-bottom: 1px solid #eaeaea;">
                            <?php $main_image = isset($one['image']['main']['image']) ? $one['image']['main']['image'] : '' ?>
                            <div class="product_image" style="display:inline-block;width:200px;padding-left:42px;padding-top: 15px;">
                                <a class="product_img" href="<?= Yii::$service->url->getUrl($one['url_key']);  ?>">
                                        <img src="<?= Yii::$service->product->image->getResize($main_image,[80,80],false) ?>" />
                                    </a>
                            </div>
                            
                            <div class="info" style="    display: inline-block;width: 390px;text-align: left;vertical-align: top;padding-top: 20px;">
                                <div>
                                    <?= Yii::$service->store->getStoreAttrVal($one['name'],'name')  ?>
                                </div>
                                <div class="category_product" style="padding:12px 0 12px;">
                                    <?= Yii::$service->page->widget->DiRender('category/price', ['productModel' => $one]); ?> 
                                </div>
                                <div>
                                <?= Yii::$service->page->translate->__('Favorite Date:');?><?= date('Y-m-d H:i:s',$one['updated_at']) ?>
                                </div>
                            </div>

                            <div class="favo_operate" style="display: inline-block;width: 136px; text-align: center; vertical-align: top; padding-top: 40px;">
                                <a href='javascript:doPost("<?= Yii::$service->url->getUrl('customer/productfavorite') ?>", {"type":"remove", "favorite_id":"<?= $one['favorite_id'] ?>", "<?= CRequest::getCsrfName() ?>": "<?= CRequest::getCsrfValue() ?>" })' >
                                        <?= Yii::$service->page->translate->__('Remove'); ?>
                                    </a>
                            </div> 
                        </li>                        

                    <?php endforeach; ?>
                    </p>
                <?php else: ?>
                
                    <p class="empty-tip"><?= Yii::$service->page->translate->__('There is no favorite product') ?>！</p>
                <?php endif; ?>
            </div>

            <?php if($pageToolBar): ?>
                <div class="pageToolbar">
                    <?= $pageToolBar ?>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>    

  
