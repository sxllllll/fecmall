<script>
var isWechat = /micromessenger/i.test(navigator.userAgent || '');
if (isWechat) {
    // 如果是微信，则进行跳转
    window.location.href="<?= Yii::$service->wx->h5login->getOauthAuthorizeUrl('customer/wx/loginbridge') ?>";
}
</script>
<div class="main-wrap" id="main-wrap" >
    <div class="sms-login-new-page">
        <div class="banner-box">
            <img src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/01cf2c685c5d7ddbb21b7c7b961da77454.jpg')  ?>">
            <div class="banner-info">
                <div class="top-operation-bar">
                    <button class="close iconfont" onclick="location.href='javascript:history.go(-1)'" type="button">
                        &#xe72e;
                    </button>
                    <a href="<?= Yii::$service->url->getUrl("customer/account/register") ?>" class="register">
                        <?= Yii::$service->page->translate->__('Register');?>
                    </a>
                </div>
            </div>
        </div>
        <?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
        <form action="<?= Yii::$service->url->getUrl("customer/account/login");  ?>" method="post" id="login-form" class="account-form sms-login-form">
            <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
            <div class="form-group mobile">
                <label for="mobile" class="iconfont">&#xe636;
                </label>
                <input id="phone" type="text" name="editForm[email]" placeholder="<?= Yii::$service->page->translate->__('Email'); ?>" class="verify-code-input" value="<?= $phone; ?>">
            </div>
            <div class="form-group verify-code">
                <label for="verifyCode" class="iconfont">&#xe723;</label>
                <input type="password" name="editForm[password]"  placeholder="<?= Yii::$service->page->translate->__('Password'); ?>" class="verify-code-input">
            </div>
            
            <button id="smsLoginBtn" class="sms-login-btn active" type="button">登录</button>
            <div class="other-info">
                <a id="getPswrdBtn" href="<?= Yii::$service->url->getUrl('customer/account/forgotpassword');  ?>">
                    <?= Yii::$service->page->translate->__('Forgot Your Password?'); ?>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
<?php $this->beginBlock('customer_login') ?>  
$(document).ready(function(){
    $("#smsLoginBtn").click(function(){
        $("#login-form").submit();
    });
});
<?php $this->endBlock(); ?>  
<?php $this->registerJs($this->blocks['customer_login'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 