<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php if (is_array($parentThis['products']) && !empty($parentThis['products'])): ?>
    <?php foreach ($parentThis['products'] as $product): ?>
        <div class="good-info " >
            <div class="tag-container clearfix">
            </div>
            <div class="good-detail-img">
                <a class="good-thumb" href="<?= $product['url'] ?>">
                    <img  class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getResize($product['image'],[352, 385],false) ?>">
                </a>    
            </div>
            <div class="good-detail-text">
                <div class="name">
                    <a href="<?= $product['url'] ?>"><?= $product['name'] ?></a>
                </div>
                <?= Yii::$service->page->widget->DiRender('category/price', ['productModel' => $product]); ?> 
            </div>
        </div>
        <?php endforeach; ?>
<?php endif; ?>





