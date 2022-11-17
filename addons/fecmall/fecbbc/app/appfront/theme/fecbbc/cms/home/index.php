<div class="home-page yoho-page boys" data-page="boys" data-mchannel="">
    <div class="slide-container slide-thumb-container">
        <div class="home_big_slide">
            <?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('pc-home-big-img','appfront') ?>
        </div>
    </div>

    <div class="slide-container-placeholder slide-thumb-container-placeholder"></div>
    <div class="singlehot clearfix">
        <div class="floor-header clearfix" floorId="">
            <h2 class="floor-title"><?= Yii::$service->page->translate->__('Hot Product') ?></h2>
        </div>
        <ul class="g-list imgopacity home-goods">
            <?php if (is_array($hot_products) && !empty($hot_products)) : ?>
            <?php foreach ($hot_products as $product): ?>
                <li>
                    <a class="impo0" href="<?= $product['url'] ?>" target= "_blank" title="">
                        <img style="width:180px;height:auto;" class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getResize($product['image'],[180,200],false) ?>" alt=""/>
                    </a>
                    <?= Yii::$service->page->widget->DiRender('category/price', ['productModel' => $product]); ?> 
                </li>
            <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
    <div class="floor-header clearfix" floorId="" style="margin-top:40px;">
        <h2 class="floor-title"><?= Yii::$service->page->translate->__('Preferred Brand') ?></h2>
    </div>
    <div class="preference-brand">
        <?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('pc-home-brand','appfront') ?>
    </div>
    <div class="tpl-recommend clearfix">
        <div class="floor-header clearfix" floorId="">
            <h2 class="floor-title"><?= Yii::$service->page->translate->__('Trend tops') ?></h2>
        </div>
        <div class="tpl-body clearfix">
            <?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('pc-home-hot-top','appfront') ?>
            <div class="tpl-types imgopacity clearfix">
                <ul class="home-goods">
                    <?php if (is_array($chaoliu_s1_products) && !empty($chaoliu_s1_products)) : ?>
                    <?php foreach ($chaoliu_s1_products as $product): ?>
                        <li style="height: 248px;">
                            <a class="impo0" href="<?= $product['url'] ?>" target= "_blank" title="" style="height:200px;">
                                <img style="width:180px;height:auto;" class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getResize($product['image'],[180,200],false) ?>" alt=""/>
                            </a>
                            <?= Yii::$service->page->widget->DiRender('category/price', ['productModel' => $product]); ?> 
                        </li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="tpl-products imgopacity clearfix" style="margin-top: 20px;">
            <ul class="home-goods">
                <?php if (is_array($chaoliu_s2_products) && !empty($chaoliu_s2_products)) : ?>
                <?php foreach ($chaoliu_s2_products as $product): ?>
                    <li>
                        <a style="height:210px;" class="impo0" href="<?= $product['url'] ?>" target= "_blank" title="">
                            <img style="width:192px;height:auto;" class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getResize($product['image'],[192,210],false) ?>" alt=""/>
                        </a>
                        <?= Yii::$service->page->widget->DiRender('category/price', ['productModel' => $product]); ?> 
                    </li>
                <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    <div class="tpl-recommend clearfix">
        <div class="floor-header clearfix" floorId="">
            <h2 class="floor-title"><?= Yii::$service->page->translate->__('Trend pants') ?></h2>
        </div>
        <div class="tpl-body clearfix">
            <?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('pc-home-hot-bottom','appfront') ?>
            <div class="tpl-types imgopacity clearfix">
                <ul class="home-goods">
                    <?php if (is_array($chaoliu_x1_products) && !empty($chaoliu_x1_products)) : ?>
                    <?php foreach ($chaoliu_x1_products as $product): ?>
                        <li style="height: 248px;">
                            <a class="impo0" href="<?= $product['url'] ?>" target= "_blank" title="" style="height:200px;">
                                <img style="width:180px;height:auto;" class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getResize($product['image'],[180,200],false) ?>" alt=""/>
                            </a>
                            <?= Yii::$service->page->widget->DiRender('category/price', ['productModel' => $product]); ?> 
                        </li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="tpl-products imgopacity clearfix" style="margin-top: 20px;">
           <ul class="home-goods">
                <?php if (is_array($chaoliu_x2_products) && !empty($chaoliu_x2_products)) : ?>
                <?php foreach ($chaoliu_x2_products as $product): ?>
                    <li>
                        <a style="height:210px;" class="impo0" href="<?= $product['url'] ?>" target= "_blank" title="">
                            <img style="width:192px;height:auto;" class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getResize($product['image'],[192,210],false) ?>" alt=""/>
                        </a>
                        <?= Yii::$service->page->widget->DiRender('category/price', ['productModel' => $product]); ?> 
                    </li>
                <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="commodity clearfix" id="newarrivals">
        <div class="floor-header clearfix" >
            <h2 class="floor-title"><?= Yii::$service->page->translate->__('New Product') ?></h2>
            <ul class="header-navs">
                <li data-classify="">
                
                </li>
            </ul>
        </div>

        <div class="goods-container clearfix home-goods">
            <?php if (is_array($new_products) && !empty($new_products)) : ?>
            <?php foreach ($new_products as $product): ?>
                <div class="good-info imgopacity" data-skn="">
                    <div class="tag-container clearfix">
                    </div>
                    <div class="good-detail-img">
                        <a class="good-thumb" href="<?= $product['url'] ?>" target= "_blank">
                            <img class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getResize($product['image'],[192,210],false) ?>">
                        </a>
                    </div>
                    <div class="good-detail-text">
                        <a href="<?= $product['url'] ?>" target= "_blank"><?= $product['name'] ?></a>
                        <?= Yii::$service->page->widget->DiRender('category/price', ['productModel' => $product]); ?> 
                    </div>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    <?php $this->beginBlock('home_page_big_slider') ?>
    lazyload();
    $(document).ready(function(){
		$('.bxslider').bxSlider({
			mode: 'horizontal',
			moveSlides: 1,
			slideMargin: 40,
			infiniteLoop: true,
			slideWidth: 1150,
            hideControlOnEnd:true,
			minSlides: 1,
			maxSlides: 1,
			speed: 800,
		});
	});
    <?php $this->endBlock(); ?>
    <?php $this->registerJs($this->blocks['home_page_big_slider'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 



