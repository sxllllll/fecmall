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
        <p class="nav-title"><?= Yii::$service->page->translate->__('Contact Us') ?></p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="back-email-new-page">
        
        <?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
        <form method="post" id="form-validate" class="back-email-form" >
        
            <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
            
            <div class="form-group email">
                <label for="email" class="iconfont">&#xe62b;
                </label>
                <input type="email" name="editForm[name]" id="name" placeholder="<?= Yii::$service->page->translate->__('Your Name');?>"  value="<?= $name ?>" >
                
            </div>
            
            <div class="form-group email">
                <label for="email" class="iconfont">&#xe724;</label>
                <input type="email" name="editForm[email]" id="email_address" placeholder="<?= Yii::$service->page->translate->__('Your Email Address');?>"  value="<?= $email ?>" >
                
            </div>
            
            <div class="form-group mobile">
                <label for="mobile" class="iconfont">&#xe727;</label>
                <input type="tel" name="editForm[telephone]" id="telephone" placeholder="<?= Yii::$service->page->translate->__('Your Telephone');?>"  value="<?= $telephone ?>" >
                
            </div>
            
            <div class="form-group comment" style="height:5rem;">
                <label for="comment" class="iconfont" style="vertical-align:top">&#xe738;</label>
                <textarea style="height:4.5rem;border:none;width:11rem;padding-top:0.1rem;color:#555;" placeholder="<?= Yii::$service->page->translate->__('Your Comment'); ?>" name="editForm[comment]" id="contacts_comment"  style="display: inline-block; vertical-align: middle; width: 67%;  height: 6rem; border: 1px solid #ccc;padding: 2%;"><?= $comment ?></textarea>
                    
            </div>
            
            <?php if($contactsCaptcha):  ?>
                <div class="form-group verify-code">
                    <label for="verifyCode" class="iconfont">&#xe71c;</label>
                    <input style="width:4rem" type="text" required="required" name="sercrity_code"  placeholder="<?= Yii::$service->page->translate->__('captcha'); ?>" class="verify-code-input">
                    <img style="float: right;" class="login-captcha-img"  title="<?= Yii::$service->page->translate->__('click refresh'); ?>" src="<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?<?php echo md5(time() . mt_rand(1,10000));?>" align="absbottom" onclick="this.src='<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?'+Math.random();"></img>
                </div>
                 <script>
                    <?php $this->beginBlock('forgot_password_captcha_onclick_refulsh') ?>  
                    $(document).ready(function(){
                        $(".icon-refresh").click(function(){
                            $(this).parent().find("img").click();
                        });
                    });
                    <?php $this->endBlock(); ?>  
                </script>  
                <?php $this->registerJs($this->blocks['forgot_password_captcha_onclick_refulsh'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
            <?php endif;  ?>	
            
            <button id="backEmailResetBtn" class="back-email-reset-btn" type="button">
                <?= Yii::$service->page->translate->__('Contact Us'); ?>
            </button>
         </form>
         
          <div class="mailtous" style="padding:4%">
                    <span><?= Yii::$service->page->translate->__('Our Email Address'); ?></span>: 
                    <a href="mailto:<?= $contactsEmail ?>"><?= $contactsEmail ?>
                </div>
                
    </div>
</div>
<script>
<?php $this->beginBlock('customer_contacts') ?>  
$(document).ready(function(){
    $("#backEmailResetBtn").click(function(){
        $(".back-email-form").submit();
    });
});
<?php $this->endBlock(); ?>  
<?php $this->registerJs($this->blocks['customer_contacts'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 

	