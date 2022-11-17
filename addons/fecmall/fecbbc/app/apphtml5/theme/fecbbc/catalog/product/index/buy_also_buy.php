<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php if ($parentThis['products']): ?>
    <div class="recommend-for-you hide" style="display: block;">
        <p class="title">
            <span>
                <?= Yii::$service->page->translate->__('Recommend') ?>
            </span>
        </p>
        <div class="new-goods container clearfix">

    <?php foreach ($parentThis['products']  as $product): ?>
        
        <div class="good-info">
            <div class="tag-container clearfix" style="overflow: hidden;width: 100%;height: 0.7rem;position: static;">
            </div>
            <div class="good-detail-img">
                <a class="good-thumb" href="<?= $product['url'];  ?>">
                    <img class="lazy" src="<?= Yii::$service->product->image->getResize($product['image'],[291, 388],false) ?>">
                </a>
            </div>
            <div class="good-detail-text">
                <div class="name">
                    <a href="">
                        <?= $product['name'];  ?>
                    </a>
                </div>
                <?= Yii::$service->page->widget->DiRender('home/product_price', ['productModel' => $product]); ?> 
            </div>
        </div>
    <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
