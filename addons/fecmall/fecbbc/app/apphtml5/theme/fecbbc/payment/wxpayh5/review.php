<?php

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
        <div style="padding:1.6rem">
            <?php if (!$wainting): ?>
                <div align="center">
                    错误： <?= $errors ?>
                </div>
            <?php endif; ?>
            <div align="center" style="margin-top:3.6rem">
                <?php if ($wainting): ?>
                    如果您已完成付款，请点击已完成按钮。
                <?php endif; ?>
            </div>

        </div>
        
        <button class="repay1" type="button" onclick="callpay()" >重新支付</button>
        <button style="float:right;" class="repay2" type="button" onclick="ispayed()" >已完成支付</button>
    </div>
    
</div>

<style>
.repay1{
    background-color: #fff;
    width: 48%;
    float:left;
    height: 1.75rem;
    border-radius: .1rem;
    background-color: #b0b0b0;
    font-size: .8rem;
    color: #333;
}

.repay2{
    background-color: #444 !important;
    width: 48%;
    float:left;
    height: 1.75rem;
    border-radius: .1rem;
    background-color: #b0b0b0;
    font-size: .8rem;
    color: #fff;
}
</style>

<script>
function callpay()
	{
		window.location.href="<?=  Yii::$service->url->getUrl('payment/wxpayh5/start'); ?>";
	}
function ispayed()
{
    window.location.href="<?=  Yii::$service->url->getUrl('payment/wxpayh5/review', ['isPayed' => 'isPayed']); ?>";
}
</script>