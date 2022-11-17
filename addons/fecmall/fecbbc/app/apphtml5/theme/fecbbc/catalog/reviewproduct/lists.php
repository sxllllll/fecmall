<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main-wrap" id="main-wrap">
    <header id="yoho-header" class="yoho-header boys">
        <a href="javascript:history.go(-1);" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title">
            <?= Yii::$service->page->translate->__('Product List'); ?>
        </p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>

    <div class="good-detail-page yoho-page">
    <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
        <section class="list">
            <div class="std">
                <div class="review_lists">
                    <div class="review_list_product" style="width:100%">
                        <section class="assess">
                            <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
                            <form class="review_form" method="post" action="">
                                <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
                                <input type="hidden" name="editForm[rate_star]"  class="rate_star" value="5" />
                                <p>
                                    <a external class="product_name" href="<?= $url ?>">
                                        <img src="<?= Yii::$service->product->image->getResize($main_img,[159,159],false) ?>" />
                                    </a>
                                    <span style="float: right;text-align: justify;width: 60%;border: none;font-size: 1.35em; color: #666;">
                                        <a external class="product_name" href="<?= $url ?>">
                                            <?= $name ?>
                                        </a>
                                        <br/>
                                    </span>
                                </p>	
                                <div class="price-date" style="float:right;">
                                    <div class="goods-price">
                                         <?= Yii::$service->page->widget->render('product/price',['price_info' => $price_info]); ?>   
                                    </div>
                                </div>
                                <ul>
                                    <li>
                                        <?= Yii::$service->page->translate->__('Review Star') ?>
                                    </li>
                                    <li class="assess-right" style="margin: 0.2rem 0 0 0.3rem">
                                        <?php  
                                            $star = round($reviw_rate_star_average);      
                                            $star = $star? $star : 1;
                                            for ($i=1;$i<=5;$i++):
                                        ?>
                                            <?php if ($i <= $star): ?>
                                                <img  src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn01.png')  ?>"/>
                                            <?php else: ?>
                                                <img  src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn001.png')  ?>"/>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </li>
                                </ul>
                            </form>
                        </section>
                        <div class="clear"></div>
                    </div>
                    <div class="product-reviews pro_commit"> 
                        <div class="clear"></div>
                        <div class="lbBox writeRiviewTitle" style="padding:3% 5%">
                            <ul class="lineBlock proportionStars">
                                <li class="lbBox">
                                    <span class="lineBlock fz_blue"><?= Yii::$service->page->translate->__('5 stars'); ?></span>
                                    <div class="lineBlock proportionBox">
                                        <div style="width: <?=  $reviw_rate_star_info['star_5'] ?>%"> </div>
                                    </div>
                                    <span class="lineBlock"><?=  $reviw_rate_star_info['star_5'] ?>%</span>
                                </li>
                                <li class="lbBox">
                                    <span class="lineBlock fz_blue"><?= Yii::$service->page->translate->__('4 stars'); ?></span>
                                    <div class="lineBlock proportionBox">
                                        <div style="width: <?=  $reviw_rate_star_info['star_4'] ?>%"> </div>
                                    </div>
                                    <span class="lineBlock"><?=  $reviw_rate_star_info['star_4'] ?>%</span>
                                </li>
                                <li class="lbBox">
                                    <span class="lineBlock fz_blue"><?= Yii::$service->page->translate->__('3 stars'); ?></span>
                                    <div class="lineBlock proportionBox">
                                        <div style="width: <?=  $reviw_rate_star_info['star_3'] ?>%"> </div>
                                    </div>
                                    <span class="lineBlock"><?=  $reviw_rate_star_info['star_3'] ?>%</span>				
                                </li>
                                <li class="lbBox">
                                    <span class="lineBlock fz_blue"><?= Yii::$service->page->translate->__('2 stars'); ?></span>
                                    <div class="lineBlock proportionBox">
                                        <div style="width: <?=  $reviw_rate_star_info['star_2'] ?>%"> </div>
                                    </div>
                                    <span class="lineBlock"><?=  $reviw_rate_star_info['star_2'] ?>%</span>
                                </li>
                                <li class="lbBox">
                                    <span class="lineBlock fz_blue"><?= Yii::$service->page->translate->__('1 stars'); ?></span>
                                    <div class="lineBlock proportionBox">
                                        <div style="width: <?=  $reviw_rate_star_info['star_1'] ?>%"> </div>
                                    </div>
                                    <span class="lineBlock"><?=  $reviw_rate_star_info['star_1'] ?>%</span>
                                </li>
                            </ul>
                        </div>
                        <?php  if(is_array($coll) && !empty($coll)):  ?>
                            <?php foreach($coll as $one):  ?>
                                <div class="card">
                                    <div class="fec-card-header">
                                        <?= $one['summary'] ?>
                                    </div>
                                    <div class="fec-card-content">
                                        <div class="fec-card-content-inner">
                                            <div class="review-content">
                                                <?= $one['review_content'] ?>
                                            </div>
                                                
                                            <div class="moderation">
                                            <?php if($one['status'] == $noActiveStatus): ?>  
                                                <?= Yii::$service->page->translate->__('Your Review is awaiting moderation...');?>
                                            <?php elseif($one['status'] == $refuseStatus): ?>
                                                <?= Yii::$service->page->translate->__('Your Review is refused.');?>
                                            <?php endif; ?>
                                            </div>
                                            <div class="review_list_remark">
                                                <p><?= Yii::$service->page->translate->__('By');?> <?= $one['name'] ?></p>
                                                <span><?= $one['review_date'] ? date('Y-m-d H:i:s',$one['review_date']) : '' ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fec-card-footer">
                                        <a href="#" class="review_star review_star_<?= $one['rate_star'] ?>" onclick="javascript:return false;"></a>
                                    </div>
                                </div>
                            <?php  endforeach; ?>
                        <?php endif; ?>
                        <?php if($pageToolBar): ?>
                        <div class="pageToolbar">
                            <?= $pageToolBar ?>
                             <div class="clear"></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
 </div>