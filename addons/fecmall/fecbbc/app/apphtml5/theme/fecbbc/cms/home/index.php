<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main-wrap" id="main-wrap" >
    <div class="mobile-container">
        <div class="mobile-wrap boys-wrap    yoho-page" data-channel="boys">
            <div id="home-header" class="home-header clearfix" >
                <div id="browser-header">
                    <span class="nav-btn iconfont top_category_nav" rel="<?= Yii::$service->url->getUrl('catalog/categorylist') ?>">
                        &#xe60b;
                    </span>
                     <img style="margin-top: .7rem;width: 5rem;" src="<?= Yii::$service->image->getH5LogoImgUrl() ?>"  />
                    <span class="search-btn iconfont">
                        <a href="<?= Yii::$service->url->getUrl('catalogsearch/text') ?>">&#xe60f;</a>
                    </span>
                </div>
                <div id="wechat-header" class="hide">
                    <span class="nav-btn iconfont">&#xe60b;</span>
                    <div class="search-input">
                        <a href="<?= Yii::$service->url->getUrl('catalogsearch/text') ?>">
                            <i class="search-icon iconfont">&#xe60f;</i>
                            <p><?= Yii::$service->page->translate->__('Search Product');?></p>
                        </a>
                    </div>
                </div>
            </div>
            <div class="resource-content">
                <div class="banner-top">
                    <div class="banner-swiper swiper-container">
                        <ul class="swiper-wrapper">
                            <?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('h5-home-big-img','apphtml5') ?>    
                        </ul>
                    </div>
                    <div class="swiper-pagination">
                        <div class="pagination-inner">
                        </div>
                    </div>
                </div>
                
                <div class="icons-wrapper warperstw"  style="" >
                    <ul class="icons-list clearfix">
                        <li class="icons-item item-4">
                            <a href="<?= Yii::$service->url->getUrl('customer/order') ?>" class="imagebar">
                                <img class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/qnav2.png') ?>" alt="">
                                
                            </a>
                            <a href="<?= Yii::$service->url->getUrl('customer/order') ?>" class="linkbar"><?= Yii::$service->page->translate->__('My Order');?></a>
                        </li>
                        <li class="icons-item item-4">
                            <a href="<?= Yii::$service->url->getUrl('coupon/fetch/lists') ?>" class="imagebar">
                                <img class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/qnav4.png') ?>" alt="">
                            </a>
                            <a href="<?= Yii::$service->url->getUrl('coupon/fetch/lists') ?>" class="linkbar">
                                <?= Yii::$service->page->translate->__('Get Coupon');?>
                            </a>
                        </li>
                        <li class="icons-item item-4">
                            <a href="<?= Yii::$service->url->getUrl('customer/productfavorite') ?>" class="imagebar">
                                <img class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/qnav3.png') ?>" alt="">
                            </a>
                            <a href="<?= Yii::$service->url->getUrl('customer/productfavorite') ?>" class="linkbar"><?= Yii::$service->page->translate->__('My Favorite');?></a>
                        </li>
                        <li class="icons-item item-4">
                            <a href="<?= Yii::$service->url->getUrl('catalog/categorylist') ?>" class="imagebar">
                                <img class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/h5/01b097e06ac9fc78bbcc3d3e0dfbe01fcc.png') ?>" alt="">
                            </a>
                            <a href="<?= Yii::$service->url->getUrl('catalog/categorylist') ?>" class="linkbar"><?= Yii::$service->page->translate->__('All Category');?></a>
                        </li>
                    </ul>
                </div>
               
                
                <div  class="divide-image"></div>
                <div class="banner-list">
                    <?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('h5-home-banner-1','apphtml5') ?>
                </div>
                
                <div class="hot-category">
                    <div class="floor-header-more">
                        <h2><?= Yii::$service->page->translate->__('Hot Category');?></h2>
                    </div>
                    <ul class="category-list clearfix">
                        <?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('h5-home-hot-category','apphtml5') ?>   
                    </ul>
                </div>
                <div  class="divide-image"></div>
                <div class="banner-list">
                    <?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('h5-home-banner-2','apphtml5') ?>
                </div>
                <ul class="hot-brands clearfix">
                    <div class="floor-header-more">
                        <h2><?= Yii::$service->page->translate->__('Hot Brand');?></h2>
                    </div>
                    <?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('h5-home-hot-brand','apphtml5') ?>
                    
                </ul>
            </div>

            <?php if (is_array($bestFeaturedProducts) && !empty($bestFeaturedProducts)): ?>
                <div class="maybe-like">
                    <p class="title">
                        <i class="icon"></i>
                        <span><?= Yii::$service->page->translate->__('You may like');?></span>
                    </p>
                    <div id="goods-list" class="goods-list clearfix">     
                    <?php
                            $parentThis['products'] = $bestFeaturedProducts;
                            $parentThis['name'] = 'featured';
                            echo Yii::$service->page->widget->render('cms/productlist', ['products' => $bestFeaturedProducts]);
                    ?>
                    </div>
                </div>
            <?php endif; ?>
            
            
            <div class="footer_bar">
                <div class="change-bar">
                    <div class="c_left"><?= Yii::$service->page->translate->__('Language'); ?>: </div>
                    <div class="c_right">
                        <select class="lang" rel="" style="padding: 0.2rem; width: 70%; border: 1px solid #ccc;">
                            <?php foreach($stores as $store=> $langName):   ?>
                                <?php  $selected = ""; ?>
                                <?php if($store == $currentStore){ $selected = 'selected = "selected"';  } ?>
                                <option <?= $selected ?> value="<?= '//'.$store ?>"><?= $langName ?></option>
                            <?php endforeach; ?>	
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="change-bar">
                    <div class="c_left"><?= Yii::$service->page->translate->__('Currency'); ?>: </div>
                    <div class="c_right">
                        <select class="currency" style="padding: 0.2rem; width: 70%; border: 1px solid #ccc;">
                            <?php foreach($currencys as $c):    ?>
                                <?php  $selected = ""; ?>
                                <?php if($c['code'] == $currency['code']){ $selected = 'selected = "selected"';  } ?>
                                <option <?= $selected ?> value="<?= $c['code'] ?>"><label><?= $c['symbol'] ?></label><?= $c['code'] ?></option>
                            <?php endforeach; ?>	
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            
            
            
            <div class="footer-tab">
                <a class="tab-item current" href="<?= Yii::$service->url->homeUrl(); ?>">
                    <p class="iconfont tab-icon">&#xe62a;</p>
                    <p class="tab-name"><?= Yii::$service->page->translate->__('Home');?></p>
                </a>
                <a class="tab-item " href="<?= Yii::$service->url->getUrl('catalog/categorylist') ?>">
                    <p class="iconfont tab-icon">&#xe62d;</p>
                    <p class="tab-name"><?= Yii::$service->page->translate->__('Category');?></p>
                </a>
                <a class="tab-item " href="<?= Yii::$service->url->getUrl('checkout/cart') ?>" rel="nofollow">
                    <p class="iconfont tab-icon">&#xe62c;</p>
                    <p class="tab-name"><?= Yii::$service->page->translate->__('Cart');?></p>
                </a>
                <a class="tab-item " href="<?= Yii::$service->url->getUrl('customer/account') ?>" rel="nofollow">
                    <p class="iconfont tab-icon">&#xe62b;</p>
                    <p class="tab-name"><?= Yii::$service->page->translate->__('My');?></p>
                </a>
            </div>

            <div class="overlay"></div>
        </div>
    </div>
    <?= Yii::$service->page->widget->render('base/footer_navigation',$this); ?>
</div>
  
<style>
.footer_bar .change-bar {
    background: #f3f3f3 none repeat scroll 0 0;
    margin: 0;
    padding: 0.4rem 0.4rem;
}
.footer_bar .change-bar .c_left {
    float: left;
    width: 30%;
    color: #888;
    line-height: 1rem;
}
.footer_bar .change-bar .c_right {
    float: left;
    width: 68%;
}
</style>
  
<script>
<?php $this->beginBlock('f-e_c-m_a-l_l_home') ?>
lazyload();
$(document).ready(function(){
	currentBaseUrl = "<?=  $currentBaseUrl; ?>";
	$(".footer_bar .change-bar .lang").change(function(){
		redirectUrl = $(this).val();
		location.href=redirectUrl;
	});
	
	$(".footer_bar .change-bar .currency").change(function(){
		currency = $(this).val();
		htmlobj=$.ajax({url:currentBaseUrl+"/cms/home/changecurrency?currency="+currency,async:false});
		//alert(htmlobj.responseText);
		location.reload() ;
	});
    $(".top_category_nav").click(function(){
        url = $(this).attr("rel");
        location.href=url;
    });
    // top big img slider
    var swiper = new Swiper('.swiper-container', {
      pagination: {
        el: '.pagination-inner',
        dynamicBullets: true,
      },
    });
});



<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['f-e_c-m_a-l_l_home'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
<?php
    // 添加微信share
    $homePageTitle = Yii::$service->wx->h5share->homePageTitle;
    $homePageDesc = Yii::$service->wx->h5share->homePageDesc;
    $homePageImgUrl = Yii::$service->wx->h5share->homePageImgUrl;
    $wx_param = [ 'title' => $homePageTitle, 'imgUrl' => $homePageImgUrl,  'desc' => $homePageDesc];
    echo Yii::$service->page->widget->render('base/wx', ['wx_param' => $wx_param]); 
?>
