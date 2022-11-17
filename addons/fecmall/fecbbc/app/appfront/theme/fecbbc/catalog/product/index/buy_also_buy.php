<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>

<?php  if(is_array($parentThis['products']) && !empty($parentThis['products'])): ?>
    <div class="bottom-tab bottom-recommend-tab">
        <p>
            <span class="bottom-title shop bottom-cur"><?= Yii::$service->page->translate->__('Recommended Goods') ?></span>
        </p>
    </div>
    <div class="individual-comment info-block info-bottom">
        <div id="recommend-shop" class="shop">
            <div class="recommend-content clearfix">
                <div class="recommend-slider">
                    <ul class=" img-list" id="recommend-content">
                    
                    <?php foreach($parentThis['products'] as $product): ?>
                        <li class="img-item">
                            <span class="hide goods-id"></span>
                            <div class="good">
                                <a href="<?= $product['url'] ?>" target="_blank">
                                    <img  class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getResize($product['image'],[190,240],false) ?>"/>
                                </a>
                                <a class="name" href="<?= $product['url'] ?>" target="_blank"><?= $product['name'] ?></a>
                                 <?= Yii::$service->page->widget->DiRender('category/price', ['productModel' => $product]); ?> 
                            </div>
                        </li>
                    <?php  endforeach;  ?>         
                    </ul>
                    <div class="img-brand-switch">
                        <a class="prev iconfont" href="javascript:;">&#xe609;</a>
                        <a class="next iconfont" href="javascript:;">&#xe608;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php  endif;  ?>


