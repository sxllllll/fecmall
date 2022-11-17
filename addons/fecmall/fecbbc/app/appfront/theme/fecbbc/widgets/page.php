<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>

<div class="pager">
    <?php  if($prevPage):  ?>
        <a href="<?= $prevPage['url']['url'] ?>" title="<?= Yii::$service->page->translate->__('Previous Page'); ?>">
            <span class="iconfont">&#xe60e;</span>
            <?= Yii::$service->page->translate->__('Previous Page'); ?>
        </a>
    <?php else:  ?>
        <a href="javascript:void(0)" title="<?= Yii::$service->page->translate->__('Previous Page'); ?>">
            <span class="iconfont">&#xe60e;</span>
            <?= Yii::$service->page->translate->__('Previous Page'); ?>
        </a>
    <?php endif;  ?>
    <?php if($firstSpaceShow):  ?>
        <a href="<?= $firstSpaceShow['url']['url'] ?>"><?= $firstSpaceShow[$pageParam] ?></a>
    <?php endif;  ?>
    <?= $hiddenFrontStr ?>
    <?php  if(!empty($frontPage )): ?>
        <?php foreach($frontPage as $page): ?>
            <a href="<?= $page['url']['url'] ?>"><?= $page[$pageParam] ?></a>
        <?php endforeach;  ?>
    <?php endif;  ?>

    <?php if($currentPage): ?>
        <a class="cur" href="javascript:void(0)"><?= $currentPage[$pageParam] ?></a>
    <?php endif;  ?>

    <?php if(!empty($behindPage )): ?>
        <?php foreach($behindPage as $page): ?>
            <a href="<?= $page['url']['url'] ?>"><?= $page[$pageParam] ?></a>
        <?php endforeach;  ?>
    <?php endif;  ?>

    <?= $hiddenBehindStr ?>
    <?php if($lastSpaceShow): ?>
        <a href="<?= $lastSpaceShow['url']['url'] ?>"><?= $lastSpaceShow[$pageParam] ?></a>
    <?php endif;  ?>
    <?php if($nextPage):  ?>
        <a href="<?= $nextPage['url']['url'] ?>" class="p_pre"><?= Yii::$service->page->translate->__('Next Page'); ?><span class="iconfont">&#xe60c;</span></a>
    <?php else:  ?>
        <a href="javascript:void(0)" class="p_pre"><?= Yii::$service->page->translate->__('Next Page'); ?><span class="iconfont">&#xe60c;</span></a>
    <?php endif;  ?>
</div>


            