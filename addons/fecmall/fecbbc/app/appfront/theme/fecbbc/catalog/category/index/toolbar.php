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
$query_item 	= $parentThis['query_item'];
$product_page 	= $parentThis['product_page'];
?>

<div class="sort-pager">
    <?php  $frontSort = $query_item['frontSort']; ?>
        <?php if(is_array($frontSort) && !empty($frontSort)): ?>
            <?php 
                $priceLowToHigh = '';
                $priceHighToLow = '';
            ?>
            <?php foreach($frontSort as $np):   ?>        
                <?php $selected = $np['selected'] ? 'selected="selected"' : ''; ?>
                <?php $url 		= $np['url'];  ?>
                <?php $val = $np['value']; ?>
                <?php $label = Yii::$service->page->translate->__($np['label']); ?>
                   
                    <a class="sort-type <?=  $selected ? 'active' : '' ?>" href="<?=  $url  ?>" rel="nofollow">
                        <?= $label ?>
                        <?php if ($val == 'low-to-high') : ?>
                            <span class="iconfont">&#xe615;</span>
                        <?php else: ?>
                            <span class="iconfont">&#xe610;</span>
                        <?php endif; ?>
                    </a>
            <?php endforeach; ?>
        <?php endif; ?>
</div>




