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
use fecshop\app\appfront\helper\Format;
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

<div class="pageContent" style="background:#fff;"> 
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDoneCloseAndReflush);">
		<?php echo CRequest::getCsrfInputHtml();  ?>	
		<div layouth="56" class="pageFormContent" style="height: 240px; overflow: auto;">
            <input type="hidden"  value="<?=  $order['order_id']; ?>" size="30" name="editForm[order_id]" class="textInput ">
            <fieldset id="fieldset_table_qbe">
                <legend style="color:#009688"><?= Yii::$service->page->translate->__('Order Shipping')  ?></legend>
                <div>
                    <p class="edit_p" style="width:100%">
                        <label style="width:150px;"><?= Yii::$service->page->translate->__('Increment Id')  ?>：</label>
                        <span><?= $order['increment_id'] ?></span>
                    </p>
                    <p class="edit_p" style="width:100%">
                        <label style="width:150px;"><?= Yii::$service->page->translate->__('Order Tracking Company')  ?>：</label>
                        <span>
                            <span>
                                <select name="editForm[tracking_company]">
                                    <?= $order['tracking_company_options'] ?>
                                </select>
                            </span>
                        </span>
                    </p>
                    <p class="edit_p" style="width:100%">
                        <label style="width:150px;"><?= Yii::$service->page->translate->__('Order Tracking Number')  ?>：</label>
                        <span>
                            <input type="text" name="editForm[tracking_number]" value="<?= $order['tracking_number'] ?>" />
                        </span>
                    </p>
                </div>
            </fieldset>
		</div>
	
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button onclick="func('accept')"  value="accept" name="accept" type="submit"><?= Yii::$service->page->translate->__('Order Ship')  ?></button></div></div></li>
                <li>
					<div class="button"><div class="buttonContent"><button type="button" class="close"><?= Yii::$service->page->translate->__('Cancel')  ?></button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>	

