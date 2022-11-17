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
use fec\helpers\CUrl;
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
<script>


function getCategoryData(coupon_id,i){												
	$.ajax({
		url:'<?= CUrl::getUrl("sales/coupon/getproductcategory",['coupon_id'=>$coupon_id]); ?>',
		async:false,
		timeout: 80000,
		dataType: 'json', 
		type:'get',
		data:{
			'coupon_id':coupon_id,
		},
		success:function(data, textStatus){
			if(data.return_status == "success"){
				jQuery(".category_tree").html(data.menu);
				// $.fn.zTree.init($(".category_tree"), subMenuSetting, json);
				if(i){
					$("ul.tree", ".dialog").jTree();
				}
			}
		},
		error:function(){
			alert("<?=  Yii::$service->page->translate->__('load category info error') ?>");
		}
	});
}



function thissubmit(thiss){
	
	cate_str = "";
	jQuery(".category_tree div.ckbox.checked").each(function(){
		cate_id = jQuery(this).find("input").val();
		cate_str += cate_id+",";
	});
	
	jQuery(".category_tree div.ckbox.indeterminate").each(function(){
		cate_id = jQuery(this).find("input").val();
		cate_str += cate_id+",";
	});
	
	jQuery(".inputcategory").val(cate_str);
	
	return validateCallback(thiss, dialogAjaxDoneCloseAndReflush);
}

$(document).ready(function(){
    var select_condition_product_type = $(".select_condition_product_type").val();
    if (select_condition_product_type == "<?= Yii::$service->coupon->condition_product_type_all  ?>") {
        $(".condition_product_skus").hide();
        $(".condition_product_category_ids").hide();
    } else if  (select_condition_product_type == "<?= Yii::$service->coupon->condition_product_type_sku  ?>") {
        $(".condition_product_skus").show();
        $(".condition_product_category_ids").hide();
    } else if  (select_condition_product_type == "<?= Yii::$service->coupon->condition_product_type_category_id  ?>") {
        $(".condition_product_skus").hide();
        $(".condition_product_category_ids").show();
    }

    $(".select_condition_product_type").change(function(){
        var select_condition_product_type = $(this).val();
        if (select_condition_product_type == "<?= Yii::$service->coupon->condition_product_type_all  ?>") {
            $(".condition_product_skus").hide();
            $(".condition_product_category_ids").hide();
        } else if  (select_condition_product_type == "<?= Yii::$service->coupon->condition_product_type_sku  ?>") {
            $(".condition_product_skus").show();
            $(".condition_product_category_ids").hide();
        } else if  (select_condition_product_type == "<?= Yii::$service->coupon->condition_product_type_category_id  ?>") {
            $(".condition_product_skus").hide();
            $(".condition_product_category_ids").show();
        }
        
    });
    
});





</script>
<div class="pageContent"> 
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return thissubmit(this, dialogAjaxDoneCloseAndReflush);">
		<?php echo CRequest::getCsrfInputHtml();  ?>	
		<div layouth="56" class="pageFormContent" style="height: 240px; overflow: auto;">
			
				<input type="hidden"  value="<?=  $product_id; ?>" size="30" name="product_id" class="textInput ">
				
				<fieldset id="fieldset_table_qbe">
					<legend style="color:#cc0000"><?= Yii::$service->page->translate->__('Edit Info'); ?></legend>
					<div>
						<?= $editBar; ?>
					</div>
				</fieldset>
				<?= $lang_attr ?>
				<?= $textareas ?>
                
                
                <fieldset id="fieldset_table_qbe">
					<legend style="color:#cc0000">优惠券约束信息</legend>
					<div>
                        <p class="edit_p">
							<label>使用约束：</label>
							<select class="select_condition_product_type combox required" name="editFormData[condition_product_type]" >
                                <?= $typeOptions ?>
                            </select>
                            <span class="remark-text"></span>
						</p>
                        <div style="clear:both"></div>
                        
                        <p class="edit_p condition_product_skus " style="width:700px;display:none;">
							<label>Sku列表：</label>
							<input style="margin-left:0;" type="text" value="<?=  $condition_product_skus  ?>" size="30" name="condition_product_skus" class="textInput  valid">
                            <span class="remark-text" style="line-height: 30px;margin-left: 12px;">多个sku用英文逗号隔开</span>
                        </p>
                        <div style="clear:both"></div>
                        
                        
                        <div class="condition_product_category_ids" style="display:none;">
                            <script> 
                                $(document).ready(function(){
                                    id = '<?= $coupon_id; ?>' ;
                                    
                                    getCategoryData(id,0);  
                                });
                            </script>
                            <input type="hidden" value="" name="condition_product_category_ids"  class="inputcategory"/>
                            <ul class="category_tree tree treeFolder treeCheck expand" >
                            </ul>
                        </div>
                    </div>
				</fieldset>
                
                
		</div>
	
		<div class="formBar">
			<ul>
				<li>
                    <div class="buttonActive"><div class="buttonContent"><button onclick="func('accept')"  value="accept" name="accept" type="submit"><?= Yii::$service->page->translate->__('Save'); ?></button></div></div>
                </li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close"><?= Yii::$service->page->translate->__('Cancel'); ?></button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>	

