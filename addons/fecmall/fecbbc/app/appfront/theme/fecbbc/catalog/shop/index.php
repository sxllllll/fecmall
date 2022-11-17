<div class="home-page yoho-page boys" data-page="boys" data-mchannel="">
    <?= $home_top_banner ?>
    <div class="singlehot clearfix">
        <div class="floor-header clearfix" floorId="">
            <h2 class="floor-title"><?= $bdminName ?></h2>
        </div>
        <div class="goods-container clearfix home-goods">
            <?php if (is_array($bdmin_products) && !empty($bdmin_products)) : ?>
            <?php foreach ($bdmin_products as $product): ?>
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
        <div class="foot-pager clearfix" >
                <?= $product_page ?>
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



