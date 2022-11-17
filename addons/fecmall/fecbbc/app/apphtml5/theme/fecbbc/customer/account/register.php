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
            <span class="page-title"><?= Yii::$service->page->translate->__('Register Account');?></span>
        </div>
        <?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
        <form  class="back-email-form" action="<?= Yii::$service->url->getUrl('customer/account/register'); ?>" method="post" id="register-form" >
            <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
            
            <div class="form-group phone">
                <label for="phone" class="iconfont">&#xe724;</label>
                <input type="text" name="editForm[phone]" id="phone" placeholder="<?= Yii::$service->page->translate->__('Phone');?>"  value="<?= $phone ?>" >
            </div>
            <div class="form-group verify-code">
                <label for="verifyCode" class="iconfont">&#xe723;</label>
                <input type="password" name="editForm[password]"  placeholder="<?= Yii::$service->page->translate->__('New Password');?>" class="verify-code-input">
            </div>
            <div class="form-group verify-code">
                <label for="verifyCode" class="iconfont">&#xe723;</label>
                <input type="password" name="editForm[confirmation]"  placeholder="<?= Yii::$service->page->translate->__('Confirm Password');?>" class="verify-code-input">
            </div>
            <div class="form-group verify-code">
                <label for="verifyCode" class="iconfont">&#xe71c;</label>
                <input style="width:4rem" type="text" name="editForm[captcha]"  placeholder="<?= Yii::$service->page->translate->__('captcha'); ?>" class="verify-code-input">
                <input type="button" class="obtain generate_code" value=" 获取验证码" style="display: inline-block; padding: 0px 15px;  height: 1rem; position: absolute;  width: 4rem;  border: 1px solid #ccc; right: 1.4rem;" />
            </div>
            <script>
					<?php $this->beginBlock('register_get_phone_captcha') ?>  
                        $(document).ready(function(){
                            $(".generate_code").click(function(){  
                                var disabled = $(".generate_code").attr("disabled");  
                                if(disabled){  
                                    return false;  
                                }  
                                if($("#phone").val() == "" || isNaN($("#phone").val()) || $("#phone").val().length != 11 ){  
                                    alert("请填写正确的手机号！");  
                                    return false;  
                                }  
                                phone = $("#phone").val();
                                phoneCaptchaUrl = "<?= Yii::$service->url->getUrl('customer/account/phoneregistercaptchacode') ?>";
                                $.ajax({
                                    async:true,
                                    timeout: 6000,
                                    dataType: 'json', 
                                    type: 'get',
                                    data: {
                                        "phone": phone
                                    },
                                    url: phoneCaptchaUrl,
                                    success: function(data, textStatus){ 
                                        if (data.status == 'success') {
                                            settime();
                                        } else {
                                            content = data.content;
                                            if (!content) {
                                                content = "发送验证码失败";
                                            }
                                            alert(content);
                                        }
                                    },
                                    error:function (XMLHttpRequest, textStatus, errorThrown){}
                                });
                            });
                            var countdown=60;  
                            var _generate_code = $(".generate_code");  
                            
                            function settime() {  
                              if (countdown == 0) {  
                                _generate_code.attr("disabled",false);  
                                _generate_code.val("获取验证码");  
                                countdown = 60;  
                                return false;  
                              } else {  
                                $(".generate_code").attr("disabled", true);  
                                _generate_code.val("重新发送(" + countdown + ")");  
                                countdown--;  
                              }  
                              setTimeout(function() {  
                                settime();  
                              },1000);  
                            } 
                        });
                        <?php $this->endBlock(); ?>  
                        </script>  
                    <?php $this->registerJs($this->blocks['register_get_phone_captcha'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
				
            <button id="backEmailResetBtn" class="back-email-reset-btn" type="button">
                <?= Yii::$service->page->translate->__('Confirm ');?>
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

