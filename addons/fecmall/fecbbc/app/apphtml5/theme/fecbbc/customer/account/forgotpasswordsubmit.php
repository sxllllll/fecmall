<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main-wrap" id="main-wrap" >
    <div class="back-email-success-new-page">
        <div class="top-operation-bar">
            <button class="back iconfont" onclick="javascript:history.go(-1);">&#xe72e;</button>
            <span class="page-title"><?= Yii::$service->page->translate->__('Email Recovery Password'); ?></span>
        </div>
        <?php if(!empty($identity)):  ?>
        <div class="success-info">
            <div class="iconfont">&#xe72b;</div>
            <p class="info"><?= Yii::$service->page->translate->__('verification email has been sent to your mailbox'); ?></p>
            <p class="info"><?= Yii::$service->page->translate->__('Please set a new password within 24 hours via the link in the email.'); ?></p>
        </div>
        <?php else:  ?>
            <div class="success-info">
                <?php
                    $param = ['logUrlB' => '<a external href="'. $forgotPasswordUrl.' ">','logUrlE' => '</a> '];
                ?>
                <?= Yii::$service->page->translate->__('Email address do not exist, please {logUrlB} click here {logUrlE} to re-enter!',$param); ?> 
            </div>
            <div>
        <?php  endif; ?>
        <div class="form-group back-email-success-btn">
            <button id="backEmailSuccessBtn" ><?= Yii::$service->page->translate->__('Complete '); ?></button>
        </div>
        <input type="hidden" name="resendUrl" value="">
        <div class="form-group resend-email-btn">
            <button id="resendEmailBtn"><?= Yii::$service->page->translate->__('Resend Mail'); ?></button>
        </div>
    </div>
</div>

<script>
<?php $this->beginBlock('customer_forgot_password_complete') ?>  
$(document).ready(function(){
    $("#resendEmailBtn").click(function(){
        window.location.href = "<?=  Yii::$service->url->getUrl("customer/account/forgotpassword") ?>";
    });
    $("#backEmailSuccessBtn").click(function(){
        window.location.href = "<?= Yii::$service->url->getUrl("customer/account/login") ?>";
    });
});
<?php $this->endBlock(); ?>  
<?php $this->registerJs($this->blocks['customer_forgot_password_complete'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 





