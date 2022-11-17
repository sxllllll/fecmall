<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="user-me-page me-page yoho-page clearfix">
    <p class="home-path">
        <span class="path-icon"></span>
        <a href="<?= Yii::$service->url->homeUrl(); ?>"><?= Yii::$service->page->translate->__('Home') ?></a>
        &nbsp;&nbsp;&gt;&nbsp;&nbsp;
        <span><?= Yii::$service->page->translate->__('Account Edit') ?></span>
    </p>    
    <div class="home-navigation block">
        <?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>
    </div>
    <div class="me-main">
        <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
        <div class="userinfo-edit block">
            <h2 class="title"></h2>
            <div class="edit-box">
               <div class="user-personal-info">
                    <form method="post" id="form-validates"  action="<?=  $actionUrl ?>" class="first">
                        <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
                        <div class="box">
                            <div class="user-info">
                                <div class="form-group">
                                    <label for="nickname"><?= Yii::$service->page->translate->__('Phone');?>：</label>
                                    <input style="color:#ccc;" readonly="true" id="customer_phone" name="editForm[phone]" value="<?= $phone ?>" title="phone" maxlength="255" class="input-1 " type="text">        
                                </div>
                                <?php if ($wx_password_is_set == 1): ?>
                                <div class="form-group is_change_password"  >
                                    <label for="username">
                                        <?= Yii::$service->page->translate->__('Current Password');?>
                                    ：</label>
                                    <input title="Current Password" class="input-1"  name="editForm[current_password]" id="current_password" placeholder="Current Password" type="password">
                                </div>
                                <?php endif; ?>
                                <div class="form-group is_change_password">
                                    <label for="username">
                                        <?= Yii::$service->page->translate->__('New Password');?>
                                    ：</label>
                                    <input title="New Password" class="input-1" name="editForm[password]" id="password" placeholder="New Password" type="password">
                                </div>
                                <div class="form-group is_change_password">
                                    <label for="username">
                                        <?= Yii::$service->page->translate->__('Confirm Password');?>
                                    ：</label>
                                    <input  title="Confirm New Password" class="input-1" name="editForm[confirmation]" id="confirmation" placeholder="Confirm Password" type="password">
                                </div>
                                <input type="button" id="base-info" class="btn-b1" value="<?= Yii::$service->page->translate->__('Save') ?>">
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>  
<script>
    <?php $this->beginBlock('customer_account_info_update') ?>
    
    $(document).ready(function(){
        $(".btn-b1").click(function(){
            $("#form-validates").submit();
            
        });
        
    });
    
    <?php $this->endBlock(); ?>
</script>
<?php $this->registerJs($this->blocks['customer_account_info_update'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

	