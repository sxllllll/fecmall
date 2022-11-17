<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
?>
<?php
use fecshop\app\apphtml5\helper\Format;
?>
<div class="main-wrap" id="main-wrap">
    <header id="yoho-header" class="yoho-header boys">
        <a href="javascript:history.go(-1);" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title">
            <?= Yii::$service->page->translate->__('Order Review'); ?>
        </p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
    <div class="yoho-favorite-page yoho-page">
        <div class="fav-content" id="fav-content" style="touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
            <div class="fav-type show">
                <?php if(is_array($products) && !empty($products)):  ?>
                    
                <ul class="fav-product-list">
                    <?php foreach($products as $product): ?>
                    <li data-id="1315603" class="">
                        <a href="<?=  Yii::$service->url->getUrl($product['redirect_url']) ; ?>">
                            <div class="fav-img-box">
                                <img style="height:auto;" src="<?= Yii::$service->product->image->getResize($product['image'],[150,150],false) ?>" alt="">
                            </div>
                        </a>
                        <div class="fav-info-list">
                            <h2><?= $product['name'] ?></h2>
                            <span>
                                <?php if (is_array($product['spu_options']) && !empty($product['spu_options'])): ?>
                                    <?php foreach ($product['spu_options'] as $attr => $val): ?>
                                        <?= Yii::$service->page->translate->__($attr) ?>：<?= Yii::$service->page->translate->__($val) ?><br/>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </span>
                            <div class="fav-price">
                                <?= $currency_symbol ?><?= $product['price'] ?>
                            </div>
                            <div class="save-price">
                                <?php if (Yii::$service->product->review->hasReviewed($product['is_reviewed'])): ?>
                                    <input type="button" value="<?= Yii::$service->page->translate->__('Reviewed'); ?>" style="    font-size: 0.6rem;width: auto;float: right;margin-right: 0.6rem;"   />
                                <?php else: ?>
                                    <input type="button" value="<?= Yii::$service->page->translate->__('To Review'); ?>" style="    font-size: 0.6rem;width: auto;float: right;margin-right: 0.6rem;"  onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('catalog/reviewproduct/add', ['order_id' => $order_id,   'item_id' => $product['item_id'], 'product_id' => $product['product_id']]) ?>'" />
                                <?php endif; ?>
                            </div>
                        </div>
                        
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <div class="fav-load-more fav-load-background hide"></div>
            </div>
        </div>
    </div>
</div>

