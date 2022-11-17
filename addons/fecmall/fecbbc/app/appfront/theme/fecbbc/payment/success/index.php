<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<style>
.paypal_success{line-height:24px;}
</style>
<div class="main container one-column">
	<div class="col-main">
		<div class="paypal_success">
			<div class="fur_container myaccount-content">
			<h3><?= Yii::$service->page->translate->__('Your order has been received,Thank you for your purchase!'); ?></h3>
			
			<p><?= Yii::$service->page->translate->__('Your order # is:'); ?> <?= $increment_ids ?>.</p>
			<p><?= Yii::$service->page->translate->__('You will receive an order confirmation email with details of your order and a link to track its progress.'); ?></p>

			<div class="buttons-set">
				<button type="button" class="button register-button" title="Continue Shopping" onclick="window.location='<?= Yii::$service->url->homeUrl();  ?>'"><span><span><?= Yii::$service->page->translate->__('Continue Shopping'); ?></span></span></button>
			</div>
            
			<div class="clear"></div>
		</div>
	</div>
</div>