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
<div class="row">
    <div class="col-lg-16 col-md-16 col-sm-12 d-flex flex-column flex-sm-row justify-content-between align-items-left align-items-sm-center">

        <?php  $frontSort = $query_item['frontSort']; ?>
        <?php if(is_array($frontSort) && !empty($frontSort)): ?>
            <div class="sort-by-dropdown d-flex align-items-center mb-xs-10">
                <p class="mr-10 mb-0"><?=  Yii::$service->page->translate->__('Sort By'); ?>: </p>
                <select name="sort-by" id="sort-by" class="nice-select product_sort">
                    <?php foreach($frontSort as $np):   ?>
                        <?php $selected = $np['selected'] ? 'selected="selected"' : ''; ?>
                        <?php $url 		= $np['url'];  ?>
                        <option <?= $selected; ?> url="<?= $url; ?>" value="<?= $np['value']; ?>"><?= Yii::$service->page->translate->__($np['label']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
        <?php  $frontNumPerPage = $query_item['frontNumPerPage']; ?>
        <?php if(is_array($frontNumPerPage) && !empty($frontNumPerPage)): ?>
            <select class="product_num_per_page nice-select">
                <?php foreach($frontNumPerPage as $np):   ?>
                    <?php $selected = $np['selected'] ? 'selected="selected"' : ''; ?>
                    <?php $url 		= $np['url'];  ?>
                    <option <?= $selected; ?> url="<?= $url; ?>" value="<?= $np['value']; ?>"><?= $np['value']; ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <?= $product_page ?>
        <div class="clear"></div>
    </div>
</div>


