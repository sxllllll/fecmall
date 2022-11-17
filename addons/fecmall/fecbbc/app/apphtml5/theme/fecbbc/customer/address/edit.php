<div class="main-wrap" id="main-wrap">
    <header id="yoho-header" class="yoho-header boys">
        <a href="<?= Yii::$service->url->getUrl('customer/address/index') ?>" class="iconfont nav-back">&#xe610;</a>
        <p class="nav-title"><?= Yii::$service->page->translate->__('Edit address') ?></p>
    </header>
    <div class="my-address-page yoho-page">
        <div class="tip"><?= Yii::$service->page->translate->__('please fill in the address as accurately as possible.') ?></div>
        <div class="my-edit-address-page page-wrap" style="min-height: 759px;">
            <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
            <form action="<?= Yii::$service->url->getUrl('customer/address/edit', ['address_id' =>$address['address_id'] ]); ?>" method="post" class="edit-address" id="save">
                <input type="hidden"  name="editForm[address_id]" value="<?=  $address['address_id']; ?>"   />
                <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
                <div class="username">
                    <?= Yii::$service->page->translate->__('Receiver Name') ?> :
                    <input name="editForm[first_name]" type="text" maxlength="21" value="<?=  $address['first_name']; ?>">
                </div>
                <div class="mobile">
                    <?= Yii::$service->page->translate->__('Telephone') ?>:
                    <input name="editForm[telephone]" type="tel" value="<?=  $address['telephone']; ?>">
                </div>
                <div class="area" style="padding-bottom: 0.2rem;">
                    <div class="distpicker" data-toggle="distpicker">
                        <select name="editForm[state]" class="address_state" data-province="<?=  $address['state'] ? $address['state'] : '---- '.Yii::$service->page->translate->__('Province').' ----'  ?>"></select>
                        <select name="editForm[city]" class="address_state"  data-city="<?=  $address['city'] ? $address['city'] : '---- '.Yii::$service->page->translate->__('City').' ----'  ?>"></select>
                        <select name="editForm[area]" class="address_state"  data-district="<?=  $address['area'] ? $address['area'] : '---- '.Yii::$service->page->translate->__('Area').' ----'  ?>"></select>
                    </div>
                </div>
                <div class="username">
                    <?= Yii::$service->page->translate->__('Zip') ?> :
                    <input type="text" name="editForm[zip]" maxlength="21" value="<?=  $address['zip']; ?>">
                </div>
                <div class="address">
                    <?= Yii::$service->page->translate->__('Address Detail') ?>:
                    <input type="text" name="editForm[street1]" maxlength="21" value="<?=  $address['street1']; ?>">
                </div>
                
                <div class="username">
                    <i class="is_default_address_show iconfont <?= $address['is_default'] == 1 ? 'icon-cb-radio' : 'icon-radio' ?>  " style="color:#333;float: left;position: relative;margin-right: 0.5rem;"></i>
                    <input class="is_default_address" type="checkbox" style="-webkit-appearance:checkbox;display:none;" id="check" <?= $address['is_default'] == 1 ? 'checked="checked"' : '' ?> value="1"   name="editForm[is_default]"/>
                    <span class="setDefaultAddress"><?= Yii::$service->page->translate->__('Set Default Address')   ?></span>
                </div>
            </form>
            <div class="submit">
                <?= Yii::$service->page->translate->__('Save And Use') ?>
            </div>
        </div>
        <div class="my-address-list-page page-wrap hide">
        </div>
    </div>
</div>

<style>
.address_state {
    margin:1rem 0;
    display: block;
    width: 90%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
</style>


<script>
<?php $this->beginBlock('cart_address') ?>   
function setDefaultAddress(){
    if ($(".is_default_address_show").hasClass("icon-cb-radio")) {
        $(".is_default_address_show").removeClass("icon-cb-radio");
        $(".is_default_address_show").addClass("icon-radio");
        $(".is_default_address").prop("checked", false );
    } else {
        $(".is_default_address_show").removeClass("icon-radio");
        $(".is_default_address_show").addClass("icon-cb-radio");
        $(".is_default_address").prop("checked",  true);
    }
}
$(document).ready(function(){  
    // set default address check
    $(".setDefaultAddress").click(function(){
        setDefaultAddress();
    });
    // set default address check
    $(".is_default_address_show").click(function(){
        setDefaultAddress();
    });
    $(".submit").click(function(){
        $(".edit-address").submit();
    });
});    
    
<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['cart_address'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>


