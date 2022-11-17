<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main container two-columns-left">
	<div class="col-main account_center">
		<?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
        <div class="fur_container myaccount-content captcha-container">
            
			<form method="post" id="form-validate" autocomplete="off">
				<?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
                <table border="0" style="width:370px; font-size:14px; margin-top:30px;" cellspacing="0" cellpadding="0">
                  <tr height="50" valign="top">
                    <td width="55">&nbsp;</td>
                    <td>
                        <span class="fl" style="font-size:24px;"><?= Yii::$service->page->translate->__('Contact Information'); ?></span>
                    </td>
                  </tr>
                  <tr height="70">
                    <td><?= Yii::$service->page->translate->__('Name');?></td>
                    <td>
                        <input style="height: 30px;  padding: 0 10px; border: 1px solid #ddd;  width: 300px;"  name="editForm[name]" id="contacts_name" class="mb-0 l_user form-control input-text  review-input-text required-entry" title="First Name" maxlength="255" value="<?=  $name ?>" type="text">
                   
                    </td>
                  </tr>
                  <tr height="70">
                    <td><?= Yii::$service->page->translate->__('Email');?></td>
                    <td>
                        <input style="height: 30px;  padding: 0 10px; border: 1px solid #ddd;  width: 300px;" name="editForm[email]" id="contacts_email" class="mb-0 l_user form-control input-text  review-input-text required-entry" title="Last Name" maxlength="255" value="<?=  $email ?>" type="email">
                    
                    </td>
                  </tr>
                  <tr height="70">
                    <td><?= Yii::$service->page->translate->__('Telephone');?></td>
                    <td>
                        <input style="height: 30px;  padding: 0 10px; border: 1px solid #ddd;  width: 300px;" name="editForm[telephone]" id="contacts_telephone" class="mb-0 l_tel form-control input-text  review-input-text required-entry" title="Email Address" maxlength="255" value="<?=  $telephone ?>" type="telephone">
                    
                    
                    </td>
                  </tr>
                  <tr height="70">
                    <td><?= Yii::$service->page->translate->__('Comment');?></td>
                    <td>
                        <textarea style="width:313px;height:150px;border:1px solid #ccc" name="editForm[comment]" id="contacts_comment" class="form-control input-text  review-input-text required-entry"><?= $comment ?></textarea>
                    </td>
                  </tr>
                  <?php if($contactsCaptcha):  ?>
                        <tr height="70">
                            <td><?= Yii::$service->page->translate->__('Captcha');?></td>
                            <td>
                                <input style="width:120px;height:30px;border:1px solid #ccc;padding: 0 10px; border: 1px solid #ddd;  " type="text" name="sercrity_code" value="" size=10 class="login-captcha-input  form-control"> 
                                
                                <img style="margin-top: 0; display: inline-block; vertical-align: middle;" class="login-captcha-img"  title="<?= Yii::$service->page->translate->__('click refresh'); ?>" src="<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?<?php echo md5(time() . mt_rand(1,10000));?>" align="absbottom" onclick="this.src='<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?'+Math.random();"></img>
                                <i class="refresh-icon"></i>
                                <script>
                                <?php $this->beginBlock('login_captcha_onclick_refulsh') ?>  
                                $(document).ready(function(){
                                    $(".refresh-icon").click(function(){
                                        $(this).parent().find("img").click();
                                    });
                                });
                                <?php $this->endBlock(); ?>  
                            </script>  
                            <?php $this->registerJs($this->blocks['login_captcha_onclick_refulsh'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
                            </td>
                        </tr>
                    <?php endif;  ?>
                  
                  <tr height="60">
                    <td>&nbsp;</td>
                    <td>
                    <input type="submit" value="<?= Yii::$service->page->translate->__('Contact Us');?>" class="log_btn" /></td>
                  </tr>
                </table>
             <div class="clear"></div>  
            </form>
		</div>
	</div>
	<div class="clear"></div>
</div>

<style>

.log_btn{
        display: block;
    width: 150px;
    height: 35px;
    background-color: #ff1901;
    color: #fff;
    text-align: center;
    letter-spacing: 5px;
    font-size: 14px;
    line-height: 35px;
    cursor: pointer;
    border: none;
}
</style>
	