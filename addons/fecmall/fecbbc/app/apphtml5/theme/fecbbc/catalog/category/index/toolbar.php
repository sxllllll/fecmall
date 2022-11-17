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
	$frontSort = $query_item['frontSort'];
?>
<?php if(is_array($frontSort) && !empty($frontSort)): ?>
<ul class="drop-list hide"> 
    <?php foreach($frontSort as $np):  ?>
        <?php $active = $np['selected'] ? 'active' : ''; ?>
        <li class=" <?= $active ?>" rel="<?= $np['url']  ?>">
            <span>
                <?= Yii::$service->page->translate->__($np['label']); ?>
            </span>
            <span class="chose"></span>
        </li>
    <?php endforeach; ?>
    
    
</ul>
<?php endif; ?>

<script>
    <?php $this->beginBlock('category_product_sort') ?>  
    $(document).ready(function(){
        $(".drop-list li").click(function(){
            url = $(this).attr('rel');
            window.location.href = url;
        });
    });
    
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['category_product_sort'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
