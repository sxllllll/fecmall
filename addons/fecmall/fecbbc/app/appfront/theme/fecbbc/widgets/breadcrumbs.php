<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php $items = Yii::$service->page->breadcrumbs->getItems();    ?>
<?php if(is_array($items) && !empty($items)):    ?>
        <div class="path-nav" listType="" brandId="">
        <?php  $count = count($items); $i=0;  ?>
        <?php  foreach($items as $item):  $i++; ?>
            <?php  ($i == $count) ? ($str = '') : ($str = '<span class="iconfont">&#xe60c;</span>')  ?>
            <?php $name = isset($item['name']) ? $item['name'] : ''; ?>
            <?php $name = Yii::$service->page->translate->__($name); ?>
            <?php $url  = isset($item['url']) ? $item['url'] : ''; ?>
            <?php  if($name): ?>
                <?php  if($url): ?>
                    <a href="<?= $url ?>" title="<?= $name ?>"><?= $name ?></a>
                    <span class="iconfont">&#xe60c;</span>
                <?php  else:   ?>
                    <a href="<?= $url ?>" title="<?= $name ?>"><?= $name ?></a>
                <?php  endif;   ?>
            <?php  endif;   ?>
        <?php  endforeach;   ?>
        </div>
<?php endif; ?>
