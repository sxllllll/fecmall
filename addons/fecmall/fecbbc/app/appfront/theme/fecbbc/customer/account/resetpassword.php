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
        
    </div>
    <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
    <div class="passport-cover">
        <div class="cover-content">
                <a href="" target="_bank">
                    <img class="cover-img" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/l_img.png')  ?>">
                </a>
        </div>
    </div>   
    
    <form action="<?= Yii::$service->url->getUrl('customer/account/resetpassword',['resetToken'=>$resetToken]); ?>" method="post" id="form-validate">
        <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
        <input type="hidden"  name="editForm[resetToken]"  value="<?= $resetToken ?>" />
        <div class="content">
            <?php  if(!empty($identity)):  ?>
            <ul class="login-ul">
                <div class="desktop-login">
                    <li class="relative clearfix">
                        <h2 class="title">
                            <?= Yii::$service->page->translate->__('Forgot Password');?>
                        </h2>
                    </li>
                    
                    <li class="relative password-login ">
                        <input type="password" class="password input va  required-entry" id="password" placeholder="<?= Yii::$service->page->translate->__('Password');?>"  title="Password" value="" name="editForm[password]"  />
                   
                   
                   <span class="err-tip password hide">
                            <i></i>
                            <em><?= Yii::$service->page->translate->__('Please input the password'); ?></em>
                        </span>
                    </li>
                    
                    <li class="relative confirmation-login ">
                        <input type="password" class="confirmation input va  required-entry" id="confirmation" placeholder="<?= Yii::$service->page->translate->__('Confirm Password');?>"  title="Confirm Password" value="" name="editForm[confirmation]"  />
                   
                   
                        <span class="err-tip confirmation hide">
                            <i></i>
                            <em><?= Yii::$service->page->translate->__('Please input the confirm password'); ?></em>
                        </span>
                    </li>
                    
                    <li class="desktop-login">
                        <span id="register-btn" class="register-btn btn">
                            <?= Yii::$service->page->translate->__('Submit'); ?>
                        </span>
                    </li>

                </div>
            </ul>
            <?php  else:  ?>
                <div class="container">
                    <?php
                    $param = ['logUrlB' => '<a href="'.$forgotPasswordUrl.'">','logUrlE' => '</a> '];
                    ?>
                    <?= Yii::$service->page->translate->__('Your Reset Password Token is Expired, You can {logUrlB} click here {logUrlE} to retrieve it ',$param); ?>
                </div>
            <?php  endif; ?>

        </div>
    </form>
</div>

<script>
<?php $this->beginBlock('resetAccount') ?>
$(document).ready(function(){
    $(".register-btn").click(function(){
        $i = 1;
        
        if (!$("#password").val()) {
            $(".err-tip.password").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.password").addClass("hide");
        }
        if (!$("#confirmation").val()) {
            $(".err-tip.confirmation").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.confirmation").addClass("hide");
        }
        
        if ($i) {
            $("#form-validate").submit();
        }
    });
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['resetAccount'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>




