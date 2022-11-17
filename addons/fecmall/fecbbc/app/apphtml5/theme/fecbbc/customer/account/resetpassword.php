<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>

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
    <div class="back-email-new-page">
        <div class="top-operation-bar">
            <button class="back iconfont" onclick="javascript:history.go(-1);" type="button">&#xe72e;</button>
            <span class="page-title"><?= Yii::$service->page->translate->__('Reset Password');?></span>
        </div>
        <?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
        <form class="back-email-form" action="<?= Yii::$service->url->getUrl('customer/account/resetpassword',['resetToken'=>$resetToken]); ?>" method="post" id="form-validate">
            <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
            <input type="hidden"  name="editForm[resetToken]"  value="<?= $resetToken ?>" />
            
            <div class="form-group verify-code">
                <label for="verifyCode" class="iconfont">&#xe723;</label>
                <input type="password" name="editForm[password]"  placeholder="<?= Yii::$service->page->translate->__('New Password');?>" class="verify-code-input">
            </div>
            
            <div class="form-group verify-code">
                <label for="verifyCode" class="iconfont">&#xe723;</label>
                <input type="password" name="editForm[confirmation]"  placeholder="<?= Yii::$service->page->translate->__('Confirm Password');?>" class="verify-code-input">
            </div>
            
            <button id="backEmailResetBtn" class="back-email-reset-btn" type="button"><?= Yii::$service->page->translate->__('Reset Password');?></button>
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
