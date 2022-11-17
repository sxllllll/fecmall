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
<?php
use fecshop\app\apphtml5\helper\Format;
?>
<div class="main-wrap" id="main-wrap" >
    <header id="yoho-header" class="yoho-header boys">
        <a href="<?= Yii::$service->url->getUrl('customer/order') ?>" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title"><?= Yii::$service->page->translate->__('Payment Center');?></p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="pay-page yoho-page">
        <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
        <form action="<?= Yii::$service->url->getUrl('checkout/onepage/payment'); ?>" method="post" id="onestepcheckout-form">
            <?= CRequest::getCsrfInputHtml(); ?>
            <input type="hidden"  name="editForm[order_increment_id]"  value="<?= $param_increment_id; ?>"  />
            <div class="payapp-list" style="visibility: visible;">
            <input class="current_payment_method"  type="hidden" value=""   name="editForm[payment_method]"/>
               
                <?php   if(is_array($payments) && !empty($payments)):   ?>
                    <?php foreach($payments as $payment_key => $payment): ?>
                        <?php 
                            
                            if ($payment_key == 'alipay_standard') {
                                $style="";
                            } else if (strstr($payment_key,'wxpay_')){
                                $style="background-position-y: -1.2rem;";
                            } else {
                                $style="background:none";
                            }
                            
                        ?>
                        <div class="box set_payment payment_<?= $payment_key ?>"  rel="<?= $payment_key ?>">
                           <div class="icon" >
                                <div style="<?= $style ?>"></div>
                           </div>
                           <div class="app"><?= $payment['label'] ?></div>
                           <div class="iconfont">&#xe604;</div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="loading-toast hide"></div>
        </form>
    </div>
</div>


<script>
	// add to cart js	
<?php $this->beginBlock('cart_address') ?>   

$(document).ready(function(){    
    $(".set_payment").click(function(){
        var payment_method = $(this).attr("rel");
        $(".current_payment_method").val(payment_method);
        $(".loading-toast").removeClass("hide");
        $("#onestepcheckout-form").submit();
	});
    var isWechat = /micromessenger/i.test(navigator.userAgent || '');
    if (isWechat) {
        $(".payment_wxpay_h5").hide();
    } else {
        $(".payment_wxpay_jsapi").hide();
    }
});    
    
    
<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['cart_address'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>

