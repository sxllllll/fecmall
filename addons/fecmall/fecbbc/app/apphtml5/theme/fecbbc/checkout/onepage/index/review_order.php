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
use fecshop\app\apphtml5\helper\Format;
?>
<?php  $cart_info = $parentThis['cart_info'];   ?>
<?php  $currency_info = $parentThis['currency_info'];   ?>
<?php  if(is_array( $cart_info) && !empty( $cart_info)): ?>
<?php  $products = $cart_info['products'];  ?>
<?php if (is_array($products)): ?>
<?php foreach($products as $product):  ?>
    <div class="order-good" >
        <div class="thumb-wrap">
            <a href="<?= $product['url'] ?>">
                <img class="thumb lazy"  src="<?= $product['imgUrl'] ?>">
            </a>
        </div>
        <div class="deps">
            <p class="name row">
                <a href="<?= $product['url'] ?>">
                    <?= $product['name'] ?>
                </a>
            </p>
             <?php  if(is_array($product['custom_option_info'])):  ?>
                <p class="row">
                    <?php foreach($product['custom_option_info'] as $label => $val):  ?>
                        <span class="color">
                           <?= Yii::$service->page->translate->__(ucwords($label).':') ?><?= Yii::$service->page->translate->__($val) ?>
                        </span>
                    <?php endforeach;  ?>
                </p>
            <?php endif; ?>
            <p class="row price-wrap">
                <span class="price">
                    <?=  $currency_info['symbol'];  ?><?= Format::price($product['product_price']); ?>
                </span>
                <span class="count">
                    ×<?= $product['qty']; ?>
                </span>
            </p>
        </div>
    </div>	
<?php  endforeach; ?>
<?php endif; ?>			                
<?php  endif; ?>	               

