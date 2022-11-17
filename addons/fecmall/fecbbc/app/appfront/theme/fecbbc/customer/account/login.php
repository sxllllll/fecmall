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
                <?= Yii::$service->page->translate->__('In order to protect the security and normal use of the account, please use the real email registration, thank you for your understanding and support of FECMALL') ?>
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
    <form id="loginForm" action="<?= Yii::$service->url->getUrl("customer/account/login");  ?>" method="post" >
        <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
        <div class="content" style="position:relative">
            <img class="switchForm" style="position:absolute; right:0; top:0;cursor:pointer;z-index:999" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/logIcon_1.png'); ?>"  accountSrc="<?= Yii::$service->image->getImgUrl('addons/fecbbc/logIcon_2.png'); ?>" rel="1"  qrSrc="<?= Yii::$service->image->getImgUrl('addons/fecbbc/logIcon_1.png'); ?>" />
            <ul class="login-ul login_account_form">
                <div class="desktop-login">
                    <li class="relative clearfix">
                        <h2 class="title"><?= Yii::$service->page->translate->__('Account Login') ?></h2>
                    </li>

                    <li class="relative password-login ">
                        <input id="account" name="editForm[phone]" class="account input va " type="text" placeholder="<?= Yii::$service->page->translate->__('Phone') ?>">
                        
                        <span class="err-tip phone hide">
                            <i></i>
                            <em><?= Yii::$service->page->translate->__('Please input the phone') ?></em>
                        </span>
                    <ul id="phone-autocomplete" class="phone-autocomplete hide"></ul></li>

                    <li class="relative password-login ">
                        <input id="password" name="editForm[password]" class="password input va " type="password" placeholder="<?= Yii::$service->page->translate->__('Password') ?>">
                        
                        <span class="err-tip password hide">
                            <i></i>
                            <em><?= Yii::$service->page->translate->__('Please input the password') ?></em>
                        </span>
                    </li>

                    <li class="desktop-login">
                        <span id="login-btn" class="login-btn btn"><?= Yii::$service->page->translate->__('Login') ?></span>
                    </li>

                    <li class="other-opts">
                        <span class="remember-me checked">
                            <i class="iconfont"></i>
                            <?= Yii::$service->page->translate->__('Remember') ?>
                        </span>
                        <span class="right">
                            <a class="forget-password" href="<?= Yii::$service->url->getUrl('customer/account/forgotpassword');  ?>">
                                <?= Yii::$service->page->translate->__('Forgot Password') ?>?
                            </a>
                            |
                            <a class="fast-reg" href="<?= Yii::$service->url->getUrl('customer/account/register');  ?>"><?= Yii::$service->page->translate->__('Register Now') ?></a>
                        </span>
                    </li>
                </div>
            </ul>
            <div class="login_qrcode" style="display:none;">
                <div class="tit">
                    <strong>微信扫码登录</strong>
                    <p>用微信扫码关注公众号直接登录</p>
                </div>
                <input class="scene_id" type="hidden"  value=""   />
                <img class="qrcodeimg" style="width:180px;" src=""    />
                <div class="qrcodenotice" style="display:none;font-size:12px;color:#cc0000;">二维码已经过期，请点击刷新页面</div>
            </div>
        </div>
    </form>
</div>




<script>
	// add to cart js	
<?php $this->beginBlock('loginAccount') ?>
$(document).ready(function(){
        $(".switchForm").click(function(){
            rel = $(this).attr('rel');
            accountSrc = $(this).attr('accountSrc');
            qrSrc = $(this).attr('qrSrc');
            if (rel == 1) {
                $(this).attr('src', accountSrc);
                $(this).attr('rel', 2);
                $(".login_qrcode").show();
                $(".login_account_form").hide();
                generateQrCode();
            } else {
                $(this).attr('src', qrSrc);
                $(this).attr('rel', 1);
                $(".login_qrcode").hide();
                $(".login_account_form").show();
            }
        });
        $(".qrcodenotice").click(function(){
            $(".qrcodenotice").hide();
            generateQrCode();
        });
        $(".qrcodeimg").click(function(){
            $(".qrcodenotice").hide();
            generateQrCode();
        });
    });
    
    var t2 ='';
    function generateQrCode(){
        //生成二维码
        $url = "<?= Yii::$service->url->getUrl('customer/wx/qrcode'); ?>";
        $.ajax({
			async:true,
			timeout: 60000,
			dataType: 'json', 
			type:'get',
			data: {},
			url: $url,
			success:function(data, textStatus){ 
				status = data.status;
                if (status == 'success') {
                    src = data.src;
                    scene_id = data.scene_id;
                    $(".qrcodeimg").attr("src", src);
                    $(".scene_id").val(scene_id);
                }
            },
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
        t2 = window.setInterval("login()", <?= Yii::$service->wx->qrcode->pcQrCodeReflush ?>);// 后台设置时间
        //window.clearTimeout(t1);//去掉定时器 
        setTimeout("end()", <?= Yii::$service->wx->qrcode->pcQrCodeTimeout ?>); // 后台设置时间。
    }
    
    function end(){  //如果超过一定时间不扫码，停止轮询，提示过期
        //$('.qrcodeimg').hide();   //隐藏二维码
        window.clearTimeout(t2);    //停止轮询
        $('.qrcodenotice').show();  //提示二维码过期 
    }
    function login(){   //不停发送请求，获取扫码人的信息，如果扫码了获取信息停止轮询，
        var code =$('.scene_id').val();
        $url = "<?= Yii::$service->url->getUrl('customer/wx/qrcodequery'); ?>?eventKey=" + code;
        $.ajax({
			async:true,
			timeout: 60000,
			dataType: 'json', 
			type:'get',
			data: {},
			url: $url,
			success:function(data, textStatus){ 
				status = data.status;
                code = data.code;
                openid = data.openid;
                if (status == 'success') {
                    if (code == 1) {
                        window.location.href="<?= Yii::$service->url->getUrl('customer/account') ?>";
                        window.clearTimeout(t2);
                    } else if (code == 2) {
                        window.location.href="<?= Yii::$service->url->getUrl('customer/account/phoneregister') ?>?openid="+openid;
                        window.clearTimeout(t2);
                    }
                    
                }
            },
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
        
        
        
        //$.post("https://xxxxxxx/mobile/KF/pass",{str:code},function(result){
        //    console.log(result);
        //    var obj = JSON.parse(result);
//
         //   var user_name = obj.user_name;
        //    var pass = obj.pass;
        //    if(pass){
         //        window.clearTimeout(t2);
         //        window.location.href="https://www.baidu.com"; //跳转到登录后的页面
        //    }
        //});
    }
    
    
$(document).ready(function(){
    $(".login-btn").click(function(){
        $i = 1;
        if (!$("#account").val()) {
            $(".err-tip.phone").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.phone").addClass("hide");
        }
        if (!$("#password").val()) {
            $(".err-tip.password").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.password").addClass("hide");
        }
        if ($i) {
            $("#loginForm").submit();
        }
    });
    
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['loginAccount'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>


