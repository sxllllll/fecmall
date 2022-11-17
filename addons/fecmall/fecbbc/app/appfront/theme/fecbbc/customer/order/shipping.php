<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
use fecshop\app\appfront\helper\Format;
?>

<div class="order-detail-page me-page yoho-page clearfix">
    <p class="home-path">
        <span class="path-icon"></span>
        <a href="<?= Yii::$service->url->homeUrl(); ?>"><?= Yii::$service->page->translate->__('Home') ?></a>
        &nbsp;&nbsp;&gt;&nbsp;&nbsp;
        <span><?= Yii::$service->page->translate->__('Order Shipping') ?></span>
    </p>    
    <div class="home-navigation block">
        <?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>
    </div>
    <div class="me-main">
        <div class="myaccount-content" style="margin:20px;">
            <div class="order-shop">
                <ul>
                    <li>
                        <p>
                            <?= Yii::$service->page->translate->__('Order Date'); ?>：<?= date('Y-m-d H:i:s', $order['created_at']) ?>
                            <br /><?= Yii::$service->page->translate->__('Order No'); ?>：<?= $order['increment_id'] ?>
                            <br /><?= Yii::$service->page->translate->__('Shipping Company'); ?>：<?=  $shippingInfo['info']['shipping_company']; ?>
                            <br /><?= Yii::$service->page->translate->__('Tracking No'); ?>：<?=  $traceNo; ?>
                        </p>
                    </li>
                </ul>
            </div>
            <?php $i = 0; ?>
            <div class="wuliu clearfloat">
                <?php if (is_array($shippingInfo['info']['trace']) && !empty($shippingInfo['info']['trace'])):  ?>
                    <?php foreach ($shippingInfo['info']['trace'] as $timeYmd => $info): ?>
                        <?php $i++ ?>
                        <div class="list clearfloat">
                            <div class="left">
                                <?= $timeYmd ?>
                            </div>					
                            <div class="right clearfloat">
                                <ul>
                                <?php if (is_array($info) && !empty($info)):  ?>
                                    <?php foreach ($info as $one): ?>
                                        <?php $i++ ?>
                                        <li class="clearfloat <?= $i == 2 ?  'active' : ''  ?>">
                                            <span class="dian"></span>
                                            <div class="list-line clearfloat">
                                                <span class="zuo">
                                                    <?= $one['time']; ?>
                                                </span>
                                                <span class="you">
                                                    <?= $one['info']; ?>
                                                </span>
                                            </div>									
                                        </li>
                                            
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>    

 
<style>

.wuliu{width: 100%; padding: 4% 5%; margin-top: 3%; box-shadow: none; border-top: 1px solid #c8c8c8;  background-color: #fff; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -o-box-sizing: border-box;}
.wuliu .list{width: 100%; margin-bottom: 3%;}
.wuliu .list .left{width: 100%; margin-bottom: 10px; font-weight: bold; text-align: left; overflow: hidden; text-overflow: ellipsis; -webkit-text-overflow: ellipsis; -moz-text-overflow: ellipsis; -o-text-overflow: ellipsis; white-space: nowrap; float: left; font-size: font-size: 1.35em; color: #333;}
.wuliu .list:first-child .left{color: #555;}
.wuliu .right{width: 100%; float: right;}
.wuliu .right ul li{width: 100%; float: right; position: relative; margin-bottom: 5px; padding: 8px 0; border-left: 2px solid #ddd;}
.wuliu .right ul li .dian{position: absolute; border-radius: 50%; -webkit-border-radius: 50%; -moz-border-radius: 50%; left: -4px; top: 1.5em; width: 6px; height: 6px; background-color: #ddd;}
.wuliu .right ul li.active .dian{background-color: #555;}
.wuliu .right ul li.active{border-left: 2px solid #555;}
.wuliu .right ul li:last-child{margin-bottom: 0;}
.wuliu .right .zuo{width: 25%; float: left; color: #333; font-size: 1em; display: inline-block; text-align: center;}
.wuliu .right .you{color: #333; float: right; display: inline-block; width: 75%; font-size: 1em; padding-right: 3%; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -o-box-sizing: border-box;}
.wuliu .right ul li .zuo,
.wuliu .right ul li .you{line-height: 2em;}
.wuliu .right ul li.active .zuo,
.wuliu .right ul li.active .you{color: #fff; line-height: 2em;}
.fixed-cont1{margin-bottom: 0;}
.wuliu .right ul li .list-line{width: 90%; padding: 3% 0; float: right; background-color: #ddd; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;}
.wuliu .right ul li.active .list-line{background-color: #555;}
.order-shop ul li{line-height:22px; font-size:12px;}
</style>