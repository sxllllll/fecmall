<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="main-wrap" id="main-wrap">
    <header id="yoho-header" class="yoho-header boys">
        <a href="javascript:history.go(-1);" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title"><?= Yii::$service->page->translate->__('Product List');  ?></p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="good-detail-page yoho-page">
        <div class="col-main">
            <div class="content-404 text-center">
                <img style="width: 50%;margin-top: 2rem;" class="image404" src="<?=  Yii::$service->image->getImgUrl('apphtml5/images/404.png') ?>" class="img-responsive" alt=""  />
                <h1><b><?= Yii::$service->page->translate->__('OPPS!'); ?></b> <?= Yii::$service->page->translate->__('We Couldn’t Find this Page'); ?></h1>
                <p><?= Yii::$service->page->translate->__('Please contact us if you think this is a server error, Thank you.'); ?></p>
                <h2><a href="<?= Yii::$service->url->homeUrl(); ?>"><?= Yii::$service->page->translate->__('Bring me back Home'); ?></a></h2>
            </div>
        </div>	
    </div>
</div>