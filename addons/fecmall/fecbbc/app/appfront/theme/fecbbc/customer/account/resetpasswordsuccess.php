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
    <div style="font-size: 12px;text-align: center"></div>
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
            <!-- My Account Tab Content Start -->
            <div class="">
                <div class="myaccount-content">
                    <div class="main container one-column">
                        <?php
                        $param = ['logUrlB' => '<a href="'.$loginUrl.'">','logUrlE' => '</a> '];
                        ?>
                        <?= Yii::$service->page->translate->__('reset you account success, you can {logUrlB} click here {logUrlE} to login .',$param); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

