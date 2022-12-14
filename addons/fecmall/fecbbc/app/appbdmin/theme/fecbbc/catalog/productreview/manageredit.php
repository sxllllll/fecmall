<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
use yii\helpers\Html;
use fec\helpers\CRequest;
use fecadmin\models\AdminRole;
/** 
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
?>
<style>
.checker{float:left;}
.dialog .pageContent {background:none;}
.dialog .pageContent .pageFormContent{background:none;}
</style>

<div class="pageContent"> 
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDoneCloseAndReflush);">
		<?php echo CRequest::getCsrfInputHtml();  ?>	
		<div layouth="56" class="pageFormContent" style="height: 240px; overflow: auto;">
			
				<input type="hidden"  value="<?=  $product_id; ?>" size="30" name="product_id" class="textInput ">
				
				<fieldset id="fieldset_table_qbe">
					<legend style="color:#cc0000"><?= Yii::$service->page->translate->__('Edit Info') ?></legend>
					<div>
						
						<p class="edit_p">
							<label><?= Yii::$service->page->translate->__('Spu') ?></label>
							<input disabled value="<?= $review['product_spu'] ?>" size="30" name="" class="textInput" type="text">
						</p>
						
						<p class="edit_p">
							<label><?= Yii::$service->page->translate->__('Product Id') ?></label>
							<input disabled value="<?= $review['product_id'] ?>" size="30" name="" class="textInput" type="text">
						</p>
						
						<?= $editBar; ?>
						
						<p class="edit_p">
							<label><?= Yii::$service->page->translate->__('Store') ?></label>
							<input disabled value="<?= $review['store'] ?>" size="30" name="" class="textInput" type="text">
						</p>
						
						<p class="edit_p">
							<label><?= Yii::$service->page->translate->__('Lang Code') ?></label>
							<input disabled value="<?= $review['lang_code'] ?>" size="30" name="" class="textInput" type="text">
						</p>
						
						<p class="edit_p">
							<label><?= Yii::$service->page->translate->__('Review Date') ?></label>
							<input disabled value="<?= date('Y-m-d H:i:s',$review['review_date']); ?>" size="30" name="" class="textInput" type="text">
						</p>
						
					</div>
				</fieldset>
				<?= $lang_attr ?>
				<?= $textareas ?>
		</div>
	
		
	</form>
</div>	

