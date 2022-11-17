<div class="main-wrap" id="main-wrap">
    <header id="yoho-header" class="yoho-header boys">
        <a href="<?= Yii::$service->url->getUrl('customer/account') ?>" class="iconfont nav-back">&#xe610;</a>
        <p class="nav-title"><?= Yii::$service->page->translate->__('Select Address') ?></p>
    </header>
    <div class="my-address-page select-address-page yoho-page">
        <div class="page-wrap clearfix" style="min-height: 629px;">
            <?php   if(is_array($addresses) && !empty($addresses)):   ?>
            <?php foreach($addresses as $address): ?>
                <div class="address-item" rel="<?=  $address['address_id'] ?>" >
                    <span class="name"><?= $address['first_name'].' '.$address['last_name'] ?></span>
                    <span class="tel"><?= $address['telephone']; ?></span>
                    <p class="address-info" >
                        <?= Yii::$service->helper->country->getStateByContryCode('CN',$address['state']); ?>
                        <?= $address['city'] ?> <?= $address['area'] ?> 
                        <?= $address['street1'].' '.$address['street2'] ?>
                        <?= $address['zip'] ?>
                    </p>
                    <?php if ($address['is_default'] == 1): ?>
                        <small class="default_address"><?= Yii::$service->page->translate->__('Default') ?></small>
                    <?php endif; ?>
                    <div class="action iconfont ">
                        <span class="edit" rel="<?= Yii::$service->url->getUrl('customer/address/edit', ['address_id' => $address['address_id']]) ?>">&#xe61e;</span>
                        <span class="del" rel="<?= Yii::$service->url->getUrl('customer/address/remove', ['address_id' => $address['address_id']]) ?>">&#xe621;</span>
                    </div>
                    
                </div>
            <?php 		endforeach; ?>
            <?php 	endif; ?>
            <a class="add-address"  href="<?= Yii::$service->url->getUrl('customer/address/edit') ?>">
               <?= Yii::$service->page->translate->__('Add New Address') ?>
            </a>
            <div class="confim-mask hide">
                <div class="confim-box">
                    <div class="content">
                        <?= Yii::$service->page->translate->__('Are you sure delete address?') ?>
                    </div>
                    <div class="action">
                        <span class="cancel">
                            <?= Yii::$service->page->translate->__('Cancel') ?>
                        </span>
                        <span class="confim">
                            <?= Yii::$service->page->translate->__('Confirm') ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= Yii::$service->page->widget->render('base/footer_navigation',$this); ?>
</div>

<script>
	// add to cart js	
<?php $this->beginBlock('cart_address') ?>   

$(document).ready(function(){   
    // set default address 
    //$(".address-item").click(function(event){
    //    addressId = $(this).attr('rel');
    //    setDefaultAddressUrl = "<?= Yii::$service->url->getUrl('checkout/onepage/address') ?>?defaultaddressid=" + addressId;
    //    window.location.href = setDefaultAddressUrl;
	//});
    // edit address
    $(".address-item .edit").click(function(event){
        event.stopPropagation();
        editUrl = $(this).attr('rel');
        window.location.href = editUrl;
    });
    
    $(".address-item .del").click(function(event){
        event.stopPropagation();
        removeUrl = $(this).attr('rel');
        $(".confim-mask").removeClass("hide");
        $(".confim-mask").attr("rel", removeUrl);
        //window.location.href = removeUrl;
    });
    
    $(".cancel").click(function(){
        $(".confim-mask").addClass("hide");
    });
    
    $(".confim").click(function(){
        $(".confim-mask").addClass("hide");
        url = $(".confim-mask").attr("rel");
        if (url) {
            window.location.href = url;
        }
        
    });
    
    
});    
    
   
        
        
        
    
<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['cart_address'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>







