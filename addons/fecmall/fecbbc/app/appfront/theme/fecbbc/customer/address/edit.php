<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
?>

<div class="user-me-page me-page yoho-page clearfix">
    <p class="home-path">
        <span class="path-icon"></span>
        <a href="<?= Yii::$service->url->homeUrl(); ?>"><?= Yii::$service->page->translate->__('Home') ?></a>
        &nbsp;&nbsp;&gt;&nbsp;&nbsp;
        <span><?= Yii::$service->page->translate->__('Address Manager') ?></span>
    </p>    
    <div class="home-navigation block">
        <?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>
    </div>
    <div class="me-main">
        
        
        
        <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
        <div class="address block">
            <div class="title">
                <h2><?= Yii::$service->page->translate->__('Address Manager') ?></h2>
            </div>
            <div class="edit-box">
               <div class="user-personal-info">
                    <form class="addressedit" action="<?= Yii::$service->url->getUrl('customer/address/edit', ['address_id' =>$address['address_id']]); ?>" id="form-validate" method="post">
                        <?php echo CRequest::getCsrfInputHtml();  ?>
                        <input type="hidden"  name="editForm[address_id]" value="<?=  $address['address_id']; ?>"   />
                        <div class="box">
                            <div class="user-info">
                            
                            
                                <div class="form-group">
                                    <label >
                                        <?= Yii::$service->page->translate->__('User Name'); ?>：
                                    </label>
                                    <input type="text" class="input-1 " id="firstname" placeholder="" maxlength="255" title="first name" value="<?=  $address['first_name']; ?>" name="editForm[first_name]"  />
                        
                                </div>

                                <div class="form-group">
                                    <label >
                                        <?= Yii::$service->page->translate->__('Telephone'); ?>：
                                    </label>
                                    <input type="text" class="input-1 " id="telephone" placeholder="<?= Yii::$service->page->translate->__('Telephone');?>" maxlength="255" title="telephone" value="<?= $address['telephone'] ?>" name="editForm[telephone]"  />
                       
                                </div>
                                
                                <div class="form-group">
                                    <label >
                                        <?= Yii::$service->page->translate->__('State Info'); ?>：
                                    </label>
                                    <div class="distpicker" data-toggle="distpicker">
                                        <select name="editForm[state]" class="address_state" data-province="<?=  $address['state'] ? $address['state'] : '---- '.Yii::$service->page->translate->__('Province').' ----'  ?>"></select>
                                        <select name="editForm[city]" class="address_state"  data-city="<?=  $address['city'] ? $address['city'] : '---- '.Yii::$service->page->translate->__('City').' ----'  ?>"></select>
                                        <select name="editForm[area]" class="address_state"  data-district="<?=  $address['area'] ? $address['area'] : '---- '.Yii::$service->page->translate->__('Area').' ----'  ?>"></select>
                                    </div>
                       
                                </div>
                                
                                <div class="form-group">
                                    <label >
                                        <?= Yii::$service->page->translate->__('Address Detail'); ?>：
                                    </label>
                                    
                                    <input type="text" class="input-1" id="street1" placeholder="<?= Yii::$service->page->translate->__('Street1');?>" maxlength="255" title="Street1" value="<?= $address['street1'] ?>" name="editForm[street1]"  />
                        
                        
                                </div>
                                
                                <div class="form-group">
                                    <label >
                                        <?= Yii::$service->page->translate->__('Zip'); ?>：
                                    </label>
                                    <input type="text" class="input-1" id="zip" placeholder="<?= Yii::$service->page->translate->__('Zip');?>" maxlength="255" title="Zip" value="<?= $address['zip'] ?>" name="editForm[zip]"  />
                        
                                </div>
                                
                                <div class="form-group">
                                    <label >
                                        <?= Yii::$service->page->translate->__('Default'); ?>：
                                    </label>
                                    <input name="editForm[is_default]" value="1" title="Save in address book" id="address:is_default" class="input-1" <?= $address['is_default'] == 1 ? 'checked="checked"' : '' ?> type="checkbox">
                                </div>
                                <input type="button" id="base-info" class="btn-b1" value="<?= Yii::$service->page->translate->__('Save') ?>">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>    
<style>

.distpicker select{
        height: 24px;
    border: 1px solid #c9c9c9;
    width: 153px;
    margin-right: 10px;
}
.user-me-page .user-personal-info .user-info{
    width:700px;
}
</style>

<script>
	<?php $this->beginBlock('editCustomerAddress') ?>
	$(document).ready(function(){
		$("#base-info").click(function(){
			$(".addressedit").submit();
		});
	});

	<?php $this->endBlock(); ?>
	<?php $this->registerJs($this->blocks['editCustomerAddress'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

</script>




