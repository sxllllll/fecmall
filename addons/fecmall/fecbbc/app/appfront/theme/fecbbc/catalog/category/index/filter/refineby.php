<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php if(isset($parentThis['refine_by_info']) && is_array($parentThis['refine_by_info']) && !empty($parentThis['refine_by_info'])):   ?> 
<div class="checked-conditions section">
    <span class="title"><?= Yii::$service->page->translate->__('Selected') ?>：</span>
    <div class="attr-content clearfix">
    <?php foreach($parentThis['refine_by_info'] as $one):  ?>
        <?php $name = \Yii::$service->helper->htmlEncode($one['name']);  ?>
        <?php $url  = \Yii::$service->helper->htmlEncode($one['url']); ?>
       
        <a class="tag" href="<?= $url;?>" name="BOYS" itemType="gender">
            <?= $one['attrLabel'] ?  Yii::$service->page->translate->__($one['attrLabel']) . ': ' : '' ?>
            <?= Yii::$service->page->translate->__($name); ?>
            <i class="close iconfont">&#xe60d;</i>
        </a>
        
    <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

