<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>

<div class="login-page passport-page yoho-page clearfix">
    <div style="font-size: 12px;text-align: center">
        <div style="display: inline-block;width: 990px; height: 25px; background-color: #f5f5f5; line-height: 25px; overflow: hidden; margin-top: 10px;">
            <i class="iconfont" style="font-size: 12px;"></i>
                &nbsp;&nbsp;
                <?= Yii::$service->page->translate->__('In order to protect the security and normal use of the account, please use the real email registration, thank you for your understanding and support of FECMALL') ?>！
        </div>
    </div>
    <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
    <div class="passport-cover">
        <div class="cover-content">
                <a href="" target="_bank">
                    <img class="cover-img" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/l_img.png')  ?>">
                </a>
        </div>
    </div>        
    <form action="<?= Yii::$service->url->getUrl('customer/account/forgotpassword'); ?>" method="post" id="form-validate">
        <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
        <div class="content">
            <ul class="login-ul">
                <div class="desktop-login">
                    <li class="relative clearfix">
                        <h2 class="title">
                            <?= Yii::$service->page->translate->__('Forgot Password');?>
                        </h2>
                    </li>
                    <li class="relative password-login ">
                        <input type="text" class="account input va validate-phone required-entry" id="phone" placeholder="<?= Yii::$service->page->translate->__('Phone');?>"  title="phone" value="<?= $phone ?>" name="editForm[phone]"  />   
                        <span class="err-tip phone hide">
                            <i></i>
                            <em><?= Yii::$service->page->translate->__('Please input the correct phone') ?></em>
                        </span>
                        <ul id="phone-autocomplete" class="phone-autocomplete hide"></ul>
                    </li>
    
                    <li class="relative password-login ">
                        <input id="captcha" style="width:100px" type="text" name="editForm[captcha]" value="" size=10 class="captcha input login-captcha-input required-entry "  placeholder="<?= Yii::$service->page->translate->__('Captcha') ?>">
                        <input type="button" class="obtain generate_code" value=" 获取验证码" style="margin-left:15px;width:auto;display: inline-block; padding: 0px 15px;height: 46px;"/>  
                                
                    </li>
                    <script>
                        <?php $this->beginBlock('forgot_password_captcha_onclick_refulsh') ?>  
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
                                    phoneCaptchaUrl = "<?= Yii::$service->url->getUrl('customer/account/forgotpasswordcaptchacode') ?>";
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
                    <?php $this->registerJs($this->blocks['forgot_password_captcha_onclick_refulsh'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
             
                    <li class="desktop-login">
                        <span id="login-btn" class="login-btn btn"><?= Yii::$service->page->translate->__('Next') ?></span>
                    </li>
                </div>
            </ul>
        </div>
    </form>
</div>


<?php 
$requiredValidate 			= 'This is a required field.';
$phoneFormatValidate 		= Yii::$service->page->translate->__('Please enter a valid phone. For example 18661854521.');
?>
<script>
<?php $this->beginBlock('forgot_password') ?>  
function isPhone(phone) {
    var myreg=/^[1][3,4,5,6,7,8,9][0-9]{9}$/;
    if (!myreg.test(phone)) {
        return false;
    } else {
        return true;
    }
}
$(document).ready(function(){
    $(".login-btn").click(function(){
        $i = 1;
        if (!$(".validate-phone").val()) {
            $(".err-tip.phone").removeClass("hide");
            $i =0;
        } else if(!isPhone($(".validate-phone").val())){
             $(".err-tip.phone").removeClass("hide");
        }
        if ($i) {
            $("#form-validate").submit();
        }
    });
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['forgot_password'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>


