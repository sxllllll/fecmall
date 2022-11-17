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
$media_size = isset($parentThis['media_size']) ? $parentThis['media_size'] : null;
$image = $parentThis['image'];
$middle_img_width = isset($media_size['middle_img_width']) ? $media_size['middle_img_width'] : 400;
?>
<?php
	if(isset($image['gallery']) && is_array($image['gallery']) && !empty($image['gallery'])){
		$gallerys = $image['gallery'];
		$gallerys = \fec\helpers\CFunc::array_sort($gallerys,'sort_order',$dir='asc');
		if(is_array($image['main']) && !empty($image['main'])){
			$main_arr[] = $image['main'];
			$gallerys = array_merge($main_arr,$gallerys);
		}	
	}else if(is_array($image['main']) && !empty($image['main'])){
		$main_arr[] = $image['main'];
		$gallerys = $main_arr;
	}
?>
<?php if(is_array($gallerys) && !empty($gallerys)): ?>
    <div class="banner-swiper swiper-container swiper-container-horizontal">
        <ul class="swiper-wrapper">
            <?php foreach($gallerys as $gallery): ?>
                <?php $image = $gallery['image']; ?>
                <li class="swiper-slide swiper-slide-active" style="width: 263px; margin-right: 3px;">
                    <a href="javascript:;">
                        <img class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getResize($image,700,false)  ?>" alt="">
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
    <div class="swiper-pagination">
        <div class="pagination-inner swiper-pagination-clickable swiper-pagination-bullets"></div>
    </div>
    <div class="my-swiper-button-prev prev-grey swiper-button-disabled"></div>
    <div class="my-swiper-button-next next-grey"></div>

    <script>
    <?php $this->beginBlock('product_detail_image') ?>  
        $(document).ready(function(){
            var swiper = new Swiper('.swiper-container', {
              pagination: {
                el: '.pagination-inner',
                dynamicBullets: true,
              },
            });
        });
    <?php $this->endBlock(); ?>  
    </script>  
    <?php $this->registerJs($this->blocks['product_detail_image'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
<?php endif; ?>


 