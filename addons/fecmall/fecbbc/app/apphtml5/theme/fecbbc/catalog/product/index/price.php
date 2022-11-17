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
    <h2 class="current-price"><?= $price_info['special_price']['symbol']  ?><?= Yii::$service->helper->format->numberFormat($price_info['special_price']['value']) ?></h2>
    <h2 class="previous-price"><?= $price_info['price']['symbol']  ?><?= Yii::$service->helper->format->numberFormat($price_info['price']['value']) ?></h2>
<?php else:  ?>
    <h2 class="current-price"><?= $price_info['price']['symbol']  ?><?= Yii::$service->helper->format->numberFormat($price_info['price']['value']) ?></h2>
    <h2 class="previous-price"></h2>
<?php endif; ?>


    

