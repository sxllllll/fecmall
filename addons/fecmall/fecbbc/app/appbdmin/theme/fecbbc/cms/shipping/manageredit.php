<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
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
            <?= $lang_attr ?>
            <fieldset id="fieldset_table_qbe">
                <legend style="color:#cc0000"><?= Yii::$service->page->translate->__('Edit Info') ?></legend>
                <div>
                    <?= $editBar; ?>
                </div>
            </fieldset>
		</div>
	
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li>
                    <div class="buttonActive"><div class="buttonContent"><button onclick="func('accept')"  value="accept" name="accept" type="submit"><?= Yii::$service->page->translate->__('Save') ?></button></div></div>
                </li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close"><?= Yii::$service->page->translate->__('Cancel') ?></button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>	

<script>

$(document).ready(function(){
    $("select[name='editFormData[type]']").change(function(){
        v = $(this).val();
        if (v == 'bdmin_shipping_cost') {
            $("input[name='editFormData[first_weight]']").val('');
            $("input[name='editFormData[first_cost]']").val('');
            $("input[name='editFormData[next_weight]']").val('');
            $("input[name='editFormData[next_cost]']").val('');
            
            $("input[name='editFormData[first_weight]']").parent().hide();
            $("input[name='editFormData[first_cost]']").parent().hide();
            $("input[name='editFormData[next_weight]']").parent().hide();
            $("input[name='editFormData[next_cost]']").parent().hide();
            
        } else {
            $("input[name='editFormData[first_weight]']").parent().show();
            $("input[name='editFormData[first_cost]']").parent().show();
            $("input[name='editFormData[next_weight]']").parent().show();
            $("input[name='editFormData[next_cost]']").parent().show();
        }
    });
   
});



</script>
