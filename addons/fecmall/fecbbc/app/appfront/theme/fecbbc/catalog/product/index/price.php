<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php  $price_info = $parentThis['price_info'];   ?>
<?php if(isset($price_info['special_price']['value'])):  ?>
    <p class="market-price">
        <span class="price-row">
            <span class="title"><?= Yii::$service->page->translate->__('Goods Price') ?>：</span>
            <span class="price has-other-price">
                <?= $price_info['price']['symbol']  ?><?= Yii::$service->helper->format->numberFormat($price_info['price']['value']) ?>
            </span>
        </span>
        <br>
        <span class="promotion-price">
            <span class="title"><?= Yii::$service->page->translate->__('Special Price') ?>：</span>
            <span class="price">
                <?= $price_info['special_price']['symbol']  ?><span><?= Yii::$service->helper->format->numberFormat($price_info['special_price']['value']) ?>
            </span>
        </span>
        <span class="desc">
                
        </span>
    </p>
<?php else:  ?>
    <p class="market-price">
        <span class="promotion-price">
            <span class="title"><?= Yii::$service->page->translate->__('Special Price') ?>：</span>
            <span class="price">
                <?= $price_info['price']['symbol']  ?>
                <?= Yii::$service->helper->format->numberFormat($price_info['price']['value']) ?>
            </span>
        </span>
        <br>
        <span class="desc">
        </span>
    </p>
<?php endif; ?>
