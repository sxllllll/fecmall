<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>


<?php $filter_category = $parentThis['filter_category']  ?>
<?php if (is_array($filter_category) && !empty($filter_category)): ?>
    <ul>
        <li>
            <h2>
                <a href="/list/gd1.html" title="<?= Yii::$service->page->translate->__('All Category') ?>">
                    <?= Yii::$service->page->translate->__('All Category') ?>
                </a>
            </h2>
        </li>
        <?php foreach ($filter_category as $one): ?>
            <li class="product-list-nav <?=  $one['current'] ? 'active' : ''  ?>">
                <h3 title="<?= Yii::$service->store->getStoreAttrVal($one['name'], 'name'); ?>">
                    <span class="icon-triangle"></span>
                    <?= Yii::$service->store->getStoreAttrVal($one['name'], 'name'); ?>
                </h3>
                <?php if (isset($one['child']) && is_array($one['child']) && !empty($one['child'])): ?>
                <ul class="sort-child-list">
                    <?php foreach ($one['child'] as $childOne): ?>
                        <li class="<?=  $childOne['current'] ? 'active' : ''  ?>">
                            <a href="<?= Yii::$service->url->getUrl($childOne['url_key']); ?>" title="<?= Yii::$service->store->getStoreAttrVal($childOne['name'], 'name'); ?>">
                                <?= Yii::$service->store->getStoreAttrVal($childOne['name'], 'name'); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
