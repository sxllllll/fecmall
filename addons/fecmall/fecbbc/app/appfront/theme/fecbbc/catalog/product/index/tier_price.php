<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php  $tier_price = $parentThis['tier_price'];   ?>
<?php  if(is_array($tier_price) && !empty($tier_price) ): ?>
<div class="row activity-wrapper clearfix">
    <span class="title pull-left"><?= Yii::$service->page->translate->__('Tier Price') ?>： </span>
    <div class="pull-left">
        <ul class="activity">
            <?php $i = 1;  ?>
            <?php  foreach($tier_price as $one):  ?>
            <?php if($i != 1):  ?>
                <li class="promotion-item ">
                    <span class="ac-type">
                        <?php $end_qty = $one['qty'] - 1; ?>
                        <?php if ($end_qty > $pre_qty):  ?>
                            <?php echo $pre_qty.'-'.$end_qty; ?>
                        <?php else: ?>
                            <?php echo $pre_qty ?>
                        <?php endif; ?>
                    </span>
                    <span class="ac-des ">
                        <?= Yii::$service->page->translate->__('item') ?>：<a target="_blank" href="" rel="nofollow"><?= Yii::$service->product->price->formatSamplePrice($tier_price_item); ?></a>
                    </span>
                </li>
            <?php endif; ?>
                <?php
                $i++;
                $pre_qty = $one['qty'];
                $tier_price_item = $one['price']
                ?>
            <?php   endforeach;  ?>
            <li class="promotion-item ">
                <span class="ac-type"><?= '>='.$pre_qty;  ?></span>
                <span class="ac-des ">
                    <?= Yii::$service->page->translate->__('item') ?>：<a target="_blank" href="" rel="nofollow"><?= Yii::$service->product->price->formatSamplePrice($tier_price_item); ?></a>
                </span>
            </li>
        </ul>
    </div>
</div>
<?php  endif;  ?>
