<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php
use fecshop\app\apphtml5\helper\Format;
use fec\helpers\CRequest;
?>
<div class="main-wrap" id="main-wrap">
    <header id="yoho-header" class="yoho-header boys">
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title"><?= Yii::$service->page->translate->__('Payment Success') ?></p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="shopping-cart-page yoho-page " style="padding:6% 3%">
        <div style="margin: 1rem 1rem 2rem;">
            <h2 style="font-size: 0.6rem; line-height: 1rem;margin-bottom: 0.5rem;" class="sub-title">
                <?= Yii::$service->page->translate->__('Your order has been received,Thank you for your purchase!'); ?>
            </h2>
            
            <p style="font-size: 0.6rem; line-height: 1rem;margin-bottom: 0.2rem;"><?= Yii::$service->page->translate->__('Your order # is:'); ?> <?= $increment_ids ?>.</p>
            
            <br/>
            <br/>
            
            <p style="font-size: 0.6rem; line-height: 1rem;margin-bottom: 1.2rem;"><?= Yii::$service->page->translate->__('You will receive an order confirmation email with details of your order and a link to track its progress.'); ?></p>
            <br/>
            <br/>
            
            <div style="margin-top:2rem;" class="submit" onclick="window.location='<?= Yii::$service->url->homeUrl();  ?>'" >
                <?= Yii::$service->page->translate->__('Continue Shopping'); ?>
            </div>
        </div>
        
    </div>
    
</div>
<style>
.submit {
    margin: .75rem auto 0;
    width: 100%;
    height: 2rem;
    color: #fff;
    background: #444;
    text-align: center;
    font-size: .8rem;
    line-height: 2.2rem;
}
</style>    