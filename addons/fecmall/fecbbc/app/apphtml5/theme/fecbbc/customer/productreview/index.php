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
        <a href="<?= Yii::$service->url->getUrl('customer/account') ?>" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title">
            <?= Yii::$service->page->translate->__('Product Review'); ?>
        </p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
    <div class="yoho-favorite-page yoho-review-page yoho-page">
        <div class="fav-content" id="fav-content" style="touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
            <div class="fav-type show">
                <?php if(is_array($coll) && !empty($coll)):  ?>
                    <ul class="fav-product-list review-product">
                        <?php foreach($coll as $one): ?>
                        <?php  $main_img = $one['image']['main']['image'];   ?>
                        <li data-id="1315603" class="">
                            <a href="<?= Yii::$service->url->getUrl($one['url_key']) ?>">
                                <div class="fav-img-box">
                                    <img src="<?= Yii::$service->product->image->getResize($main_img,[160,200],false) ?>" alt="">
                                </div>
                            </a>
                            <dl>
                                <dt>
                                    <span style="float:left;color#777;font-size:0.7rem;">
                                        <?= $one['review_date'] ? date('Y-m-d',$one['review_date']) : '' ?>
                                    </span>
                                    <div class="star">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <?php if($i <= $one['rate_star'] ): ?>
                                                <span><img src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn01.png')  ?>"/></span>
                                            <?php else: ?>
                                                <span><img src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn001.png')  ?>"/></span>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </dt>
                                <dd>
                                    <div style="font-size: 0.8rem;padding: 1rem 0;margin-top: 1.2rem;">
                                        <?= $one['review_content'] ?>
                                    </div>
                                </dd>
                                <small>
                                    <?php if($one['status'] == $noActiveStatus): ?>  
                                        <div class="review_moderation">
                                            <?= Yii::$service->page->translate->__('Your Review is awaiting moderation...');?>
                                        </div>
                                        <?php elseif($one['status'] == $refuseStatus): ?>
                                        <div class="review_refuse">
                                            <?= Yii::$service->page->translate->__('Your Review is refused.');?>
                                        </div>
                                        <?php elseif($one['status'] == $activeStatus): ?>
                                        
                                    <?php endif; ?>
                                </small>
                            </dl>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="fav-content-loading hide"></div>
                    <div class="fav-box ">
                        <span class="fav-null">
                            <?= Yii::$service->page->translate->__('You have no product review yet'); ?>
                        </span>
                        <a class="go-shopping" href="<?= Yii::$service->url->homeUrl();  ?>">
                            <?= Yii::$service->page->translate->__('Walk Around'); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="fav-load-more fav-load-background hide"></div>
            </div>
        </div>
    </div>
</div>

<style>
.review-product li dl {
    float: left;
    width: 74%;
    padding-left: 6%;
}
.review-product li dl dt {
    width: 100%;
    overflow: hidden;
    float: right;
    margin-top: 0.2rem;
}
.review-product li dl dt p {
    color: 
    #666;
    font-size: 1.169em;
}
.review-product li dl dt .star {
    float: right;
    width: 4rem;
    display: table;
}
.review-product li dl dt .star span {
    display: table-cell;
    width: 20%;
}
.review-product li dl dt .star span img {
    width: 100%;
}
.review-product li dl dd {
    font-size: 1.35em;
    margin-top: 3%;
    color: 
    #333;
}
.review-product  li dl small {
    font-size: 1.169em;
    margin-top: 3%;
    color: 
    #999;
}
.yoho-favorite-page .fav-content .fav-product-list li {
    height: auto;
    overflow: hidden;
    margin-top: .5rem;
    border-bottom: 1px solid 
    #ddd;
    padding: 0.2rem 0;
}

</style>
