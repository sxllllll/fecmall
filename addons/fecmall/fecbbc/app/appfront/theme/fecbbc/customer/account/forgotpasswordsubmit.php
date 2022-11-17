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
    
        <div class="content">
            <div class="">
                <div class="myaccount-content">
                    <h3 style="font-size:24px;font-weight:200;"><?= Yii::$service->page->translate->__('Forgot Password');?></h3>
                    <div style="    font-size: 14px;margin-top: 20px;">
                        <?php if(!empty($identity)):  ?>
                            <div>
                                <?= Yii::$service->page->translate->__('We\'ve sent a message to the email address'); ?> <?=  $identity['email'] ?>
                                <?= Yii::$service->page->translate->__('Please follow the instructions provided in the message to reset your password.'); ?>
                            </div>
                            <div>
                                <p><?= Yii::$service->page->translate->__('Didn\'t receive the mail from us?'); ?> <a href="<?= $forgotPasswordUrl ?>"><?= Yii::$service->page->translate->__('click here to retry'); ?></a></p>

                                <p><?= Yii::$service->page->translate->__('Check your bulk or junk email folder.'); ?></p>
                                <?php
                                $param = ['logUrlB' => '<a href="'. $contactUrl.' ">','logUrlE' => '</a> '];
                                ?>
                                <p><?= Yii::$service->page->translate->__('Confirm your identity to reset password ,If you still can\'t find it, click {logUrlB} support center {logUrlE} for help',$param); ?></p>
                            </div>
                        <?php else:  ?>
                        <div>
                            <?php
                            $param = ['logUrlB' => '<a href="'. $forgotPasswordUrl.' ">','logUrlE' => '</a> '];
                            ?>
                            <?= Yii::$service->page->translate->__('Email address do not exist, please {logUrlB} click here {logUrlE} to re-enter!',$param); ?>
                        </div>
                        <div>
                            <?php  endif; ?>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </form>
</div>


<style>

.login-page .content {
    float: left;
    margin-top: 147px;
    padding-left: 35px;
    width: 268px;
    line-height: 26px;
}
</style>


