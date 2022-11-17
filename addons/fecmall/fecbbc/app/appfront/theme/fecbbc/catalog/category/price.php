<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<p class="price ">
<?php if(isset($special_price) && !empty($special_price)): ?>
    <span class="market-price">
        <?= $price['symbol'] ?> <?= Yii::$service->helper->format->numberFormat($price['value']) ?>
    </span>
    <span class="sale-price prime-cost">
        <?= $special_price['symbol'] ?> <?= Yii::$service->helper->format->numberFormat($special_price['value']) ?>
    </span>
<?php else: ?>
    <span class="sale-price prime-cost">
        <?= $price['symbol'] ?> <?= Yii::$service->helper->format->numberFormat($price['value']) ?>
    </span>
<?php endif; ?>
</p>
