<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>

<?php if(isset($special_price) && !empty($special_price)): ?>
    <span class="new-price">
        <?= $special_price['symbol'].Yii::$service->helper->format->numberFormat($special_price['value']) ?>
    </span>
    <span class="fav-price price-underline">
        <?= $price['symbol'].Yii::$service->helper->format->numberFormat($price['value']) ?>
    </span>
<?php else: ?>
    <span class="fav-price ">
        <?= $price['symbol'].Yii::$service->helper->format->numberFormat($price['value']) ?>
    </span>
<?php endif; ?>
