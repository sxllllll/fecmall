<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<!--Begin Header Begin-->
<div class="soubg">
    <input type="hidden" class="currentBaseUrl" value="<?= $currentBaseUrl ?>" />
    <input type="hidden" class="logoutUrl" value="<?= $logoutUrl ?>" />
    <input type="hidden" class="logoutStr" value="<?= Yii::$service->page->translate->__('Logout'); ?>" />
    <input type="hidden" class="welcome_str" value="<?= Yii::$service->page->translate->__('Welcome!'); ?>" />
	<div class="sou">
        <!--End 所在收货地区 End-->
        <span class="fr ">
        	<span class="fl login-text-top">
                <?= Yii::$service->page->translate->__('Hello'); ?>，<?= Yii::$service->page->translate->__('Please'); ?><a href="<?= Yii::$service->url->getUrl('customer/account/login') ?>"><?= Yii::$service->page->translate->__('Login'); ?></a>
            &nbsp; <a href="<?= Yii::$service->url->getUrl('customer/account/register') ?>" style="color:#ff4e00;"><?= Yii::$service->page->translate->__('Free Join'); ?></a>
            &nbsp;
            </span>
            |&nbsp;
            <span class="fl">
            <a href="#"><?= Yii::$service->page->translate->__('My Order'); ?></a>&nbsp;</span>
            <span class="fl">|&nbsp;<?= Yii::$service->page->translate->__('Follow us'); ?>：</span>
            <span class="s_sh"><a href="#" class="sh1"><?= Yii::$service->page->translate->__('XinLang'); ?></a><a href="#" class="sh2"><?= Yii::$service->page->translate->__('WeiXin'); ?></a></span>
            <span class="fr">|&nbsp;
                <a href="#"><?= Yii::$service->page->translate->__('Mobile'); ?>&nbsp;
                    <img src="<?= Yii::$service->image->getImgUrl('addons/fectb/front/s_tel.png')  ?>" align="absmiddle" />
                </a>
            </span>
        </span>
    </div>
</div>
</div>
<div class="top">
    <div class="logo" style="width:237px;">
        <a href="<?= Yii::$service->url->homeUrl() ?>">
            <img style="width:100%" src="<?= Yii::$service->image->getImgUrl('appfront/custom/logo.png') ?>" />
        </a>
    </div>
</div>