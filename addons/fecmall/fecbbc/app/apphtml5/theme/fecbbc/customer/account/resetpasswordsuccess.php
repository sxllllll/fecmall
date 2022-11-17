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
            <span class="page-title"><?= Yii::$service->page->translate->__('Reset Password Success');?></span>
        </div>
        <?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
        <div class="success-info">
            <?php
                $param = ['logUrlB' => '<a external href="'.$loginUrl.'">','logUrlE' => '</a> '];
            ?>
            <div class="iconfont">&#xe72b;</div>
            <p class="info"><?= Yii::$service->page->translate->__('Reset you account success, you can {logUrlB} click here {logUrlE} to login .',$param); ?></p>
        </div>
        <div class="form-group back-email-success-btn">
            <button id="backEmailSuccessBtn" >
                <?= Yii::$service->page->translate->__('Complete ');?>
            </button>
        </div>
    </div>
</div>

<script>
<?php $this->beginBlock('customer_forgot_password_success') ?>  
$(document).ready(function(){
    $("#backEmailSuccessBtn").click(function(){
        window.location.href = "<?= Yii::$service->url->getUrl("customer/account/login") ?>";
    });
});
<?php $this->endBlock(); ?>  
<?php $this->registerJs($this->blocks['customer_forgot_password_success'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 