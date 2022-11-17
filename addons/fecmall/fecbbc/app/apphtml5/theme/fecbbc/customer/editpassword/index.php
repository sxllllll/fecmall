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
    <header id="yoho-header" class="yoho-header boys">
        <a href="javascript:history.go(-1);" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title">
            <?= Yii::$service->page->translate->__('Update Password')   ?>
        </p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="back-email-new-page">
        <?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
        <form class="back-email-form" method="post" id="form-validate" autocomplete="off" action="<?=  $actionUrl ?>">
            <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
            <?php if ($wx_password_is_set == 1): ?>
            <div class="form-group verify-code">
                <label for="verifyCode" class="iconfont">&#xe723;</label>
                <input type="password"  name="editForm[current_password]"  placeholder="<?= Yii::$service->page->translate->__('Enter the original password')   ?>" class="verify-code-input">
            </div>
            <?php endif; ?>
            <div class="form-group verify-code">
                <label for="verifyCode" class="iconfont">&#xe723;</label>
                <input type="password" name="editForm[password]"   placeholder="<?= Yii::$service->page->translate->__('Enter the new password')   ?>" class="verify-code-input">
            </div>
            
            <div class="form-group verify-code">
                <label for="verifyCode" class="iconfont">&#xe723;</label>
                <input type="password" name="editForm[confirmation]"  placeholder="<?= Yii::$service->page->translate->__('Enter the confirm password')   ?>" class="verify-code-input">
            </div>
            
            <button id="backEmailResetBtn" class="back-email-reset-btn" type="button">
                <?= Yii::$service->page->translate->__('Save')   ?>
            </button>
         </form>
    </div>
</div>
<script>
<?php $this->beginBlock('customer_forgot_password') ?>  
$(document).ready(function(){
    $("#backEmailResetBtn").click(function(){
        $(".back-email-form").submit();
    });
});
<?php $this->endBlock(); ?>  
<?php $this->registerJs($this->blocks['customer_forgot_password'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 



