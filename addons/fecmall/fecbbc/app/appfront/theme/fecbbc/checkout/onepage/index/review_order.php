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
use fecshop\app\appfront\helper\Format;
?>

<?php  $cart_info = $parentThis['cart_info'];   ?>
<?php  $currency_info = $parentThis['currency_info'];   ?>
<?php  if(is_array( $cart_info) && !empty( $cart_info)): ?>
<?php  $products = $cart_info['products'];  ?>

<table class="goods-table">
    <thead>
        <tr>
            <th width="3%"></th>
            <th class="aline-left" width="46%"><?= Yii::$service->page->translate->__('Goods Info');?></th>
            <th><?= Yii::$service->page->translate->__('Unit Price');?></th>
            <th width="18%"><?= Yii::$service->page->translate->__('Qty');?></th>
            <th width="6%"><?= Yii::$service->page->translate->__('Subtotal');?></th>
            <th width="3%"></th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($products)): ?>
            <?php foreach($products as $product):  ?>
            <tr class="goods-item" data-skn="52196024" data-sku="4368260" data-price="750.0" data-num="1">
                <td class="border-top"></td>
                <td class="border-top aline-left">
                    <a class="image" href="<?= $product['url'] ?>">
                        <img src="<?= $product['imgUrl'] ?>" class="thumb">
                        <p class="name">
                            <?= $product['name'] ?><br/>
                            <span style="line-height:37px;font-size:12px;background:#fff;color:#000;height:auto;">
                                <?php  if(is_array($product['custom_option_info'])):  ?>
                                    <?php foreach($product['custom_option_info'] as $label => $val):  ?>
                                        <?= Yii::$service->page->translate->__(ucwords($label).':') ?><?= Yii::$service->page->translate->__($val) ?> 
                                        
                                    <?php endforeach;  ?>
                                <?php endif;  ?>
                            </span>
                        </p>
                    </a>
                    <div class="special-tip"></div>
                    
                </td>
                <td class="border-top color-size">
                    <?=  $currency_info['symbol'];  ?><?= Format::price($product['product_price']); ?>
                </td>
                <td class="border-top price">
                    <p class="red"><?= $product['qty']; ?></p>
                </td>
                <td class="border-top">
                    <?=  $currency_info['symbol'];  ?><?= Format::price($product['product_row_price']); ?>
                </td>
                <td class="border-top"></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<?php endif; ?>                
  