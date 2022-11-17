

<div class=" ope-address-dialog" style="margin:100px auto;padding: 20px;background:#f8f8f8;border:5px solid #5b5c5c">
    <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
    <div class="content">
        <div class="title"><?= Yii::$service->page->translate->__('Add New Address') ?></div>
        <p class="prompt"> &nbsp;</p>
        <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
		<form action="<?= Yii::$service->url->getUrl('checkout/onepage/addressedit',['method' => 'add']); ?>" method="post" class="add-address" id="save">
            <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
            <ul class="info-wrap">
                <li>
                    <span class="left-rd"><i class="red">*</i><?= Yii::$service->page->translate->__('Receiver Person') ?>：</span>
                    <input type="text" name="editForm[first_name]" value="<?=  $address['first_name']; ?>" placeholder="<?= Yii::$service->page->translate->__('please enter your name') ?>"  required="required">
                    
                    <p class="caveat-tip"></p>
                </li>
                <li>
                    <span class="left-rd"><i class="red">*</i><?= Yii::$service->page->translate->__('Your Area') ?>：</span>
                    
                    <div class="distpicker " data-toggle="distpicker"  style="display:inline-block;width: 340px;">
                        <select required="required" name="editForm[state]" class="address_state" data-province="<?=  $address['state'] ? $address['state'] : '---- '.Yii::$service->page->translate->__('Province').' ----'  ?>"></select>
                        <select required="required" name="editForm[city]" class="address_state"  data-city="<?=  $address['city'] ? $address['city'] : '---- '.Yii::$service->page->translate->__('City').' ----'  ?>"></select>
                        <select required="required" name="editForm[area]" class="address_state"  data-district="<?=  $address['area'] ? $address['area'] : '---- '.Yii::$service->page->translate->__('Area').' ----'  ?>"></select>
                    </div>
                    <p class="caveat-tip"></p>
                </li>
                <li>
                    <span class="left-rd"><i class="red">*</i><?= Yii::$service->page->translate->__('Address Detail') ?>：</span>
                    <input type="text" required="required" name="editForm[street1]" value="<?= $address['street1'] ?>" placeholder="<?= Yii::$service->page->translate->__('Street name or community name') ?>">
                    
                    <p class="caveat-tip"></p>
                </li>
                <li>
                    <span class="left-rd"><i class="red">*</i><?= Yii::$service->page->translate->__('Telephone') ?>：</span>
                    <input type="text" value="<?= $address['telephone'] ?>" name="editForm[telephone]" placeholder="<?= Yii::$service->page->translate->__('Please enter your mobile number (imported required)') ?>"  required="required">
                    
                    <p class="caveat-tip"></p>
                </li>
            </ul>
        </form>
    </div>
    <div class="btns">
        <span id="dialog-save" class="btn black save_address"><?= Yii::$service->page->translate->__('Next') ?></span>
    </div>
</div>

<script>
<?php $this->beginBlock('add_address') ?>   
$(document).ready(function(){    
    $(".save_address").click(function(){
        $(".add-address").submit();
	});
});    
<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['add_address'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>
