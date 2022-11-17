<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="price">
	<?php if(isset($special_price) && !empty($special_price)):  ?>
        <?php  $special_price['value'] = Yii::$service->helper->format->numberFormat($special_price['value']);  ?>
		<span class="sale-price "><?= $special_price['symbol'] ?><?= $special_price['value'] ?></span>
        <span class="market-price"><?= $price['symbol'] ?><?= $price['value'] ?></span>   
	<?php else: ?>
        <span class="sale-price no-price">
            <?= $price['symbol'] ?><?= $price['value'] ?>
        </span>
	<?php endif; ?>
</div>

