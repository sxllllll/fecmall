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
    <form id="register-form" action="<?= Yii::$service->url->getUrl('customer/account/phoneregister', [ 'openid' => $openid]); ?>" method="post" >
        <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
        <input type="hidden" name="editForm[openid]" value="<?= $openid  ?>"  />
        <div class="content">
            <ul class="login-ul">
                <div class="desktop-login">
                    <li class="relative clearfix">
                        <h2 class="title"><?= Yii::$service->page->translate->__('Account Register') ?></h2>
                    </li>
                    
                    <li class="relative password-login ">
                        <input id="phone" name="editForm[phone]" class="phone input va " type="text" placeholder="<?= Yii::$service->page->translate->__('User Phone') ?>">
                        
                        <span class="err-tip phone hide">
                            <i></i>
                            <em><?= Yii::$service->page->translate->__('Please input the phone') ?></em>
                        </span>
                        <ul id="phone-autocomplete" class="phone-autocomplete hide"></ul>
                    </li>

                    <?php if($registerPageCaptcha):  ?>      
                        <li class="relative password-login ">
                            <input id="captcha" style="width:100px" type="text" name="editForm[captcha]" value="" size=10 class="captcha input login-captcha-input required-entry "  placeholder="<?= Yii::$service->page->translate->__('Captcha') ?>">
                            <input type="button" class="obtain generate_code" value=" 获取验证码" style="display: inline-block; padding: 0px 15px;height: 46px;margin-left:10px;"/>  
                                
                        </li>
                        
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
                        
                    <?php endif;  ?>
            
                    <li class="desktop-login">
                        <span id="register-btn" class="register-btn btn"><?= Yii::$service->page->translate->__('Register') ?></span>
                    </li>

                </div>
            </ul>
        </div>
    </form>
</div>

<script>
<?php $this->beginBlock('registerAccount') ?>
function isPhone(phone) {
    var myreg=/^[1][3,4,5,6,7,8,9][0-9]{9}$/;
    if (!myreg.test(phone)) {
        return false;
    } else {
        return true;
    }
}
$(document).ready(function(){
    $(".register-btn").click(function(){
        $i = 1;
        
        if (!$("#phone").val()) {
            $(".err-tip.phone").removeClass("hide");
            $i =0;
        }else if (!isPhone($("#phone").val())){
            $(".err-tip.phone").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.phone").addClass("hide");
        }
        if (!$("#captcha").val()) {
            $(".err-tip.captcha").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.captcha").addClass("hide");
        }
        
        if ($i) {
            $("#register-form").submit();
        }
    });
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['registerAccount'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>



