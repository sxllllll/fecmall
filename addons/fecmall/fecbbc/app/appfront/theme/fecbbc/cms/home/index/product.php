<!--=======  tab product slider  =======-->
<?php if (is_array($parentThis['products']) && !empty($parentThis['products'])):  $i=0;?>
<div class="fl-slider tab-product-slider">
    <!--=======  single product  =======-->
    <?php foreach($parentThis['products'] as $product):  echo $i; $i++?>
    <div class="fl-product">
        <div class="image sale-product">
            <a href="<?= $product['url'] ?>">
                <img class=" img-fluid" src="<?= Yii::$service->product->image->getResize($product['image'],[224,224],false) ?>"   alt="">
                <img class=" img-fluid" src="<?= Yii::$service->product->image->getResize($product['image'],[224,224],false) ?>"   alt="">
            </a>
            
        </div>
        <div class="content">
            <h2 class="product-title"> <a href="<?= $product['url'] ?>"><?= $product['name'] ?></a></h2>
            <?php # review star
            $reviewStarView = [
                'view'	=> 'catalog/product/index/review_star.php'
            ];
            $parentParam = [
                'reviw_rate_star_average' => $product['reviw_rate_star_average'],
            ];
            ?>
            <?= Yii::$service->page->widget->render($reviewStarView,$parentParam); ?>
            
            <?= Yii::$service->page->widget->DiRender('category/price', ['productModel' => $product]); ?> 
        </div>
    </div>
    <?php endforeach; ?>
    <!--=======  End of single product  =======-->
    
    <!--=======  End of single product  =======-->
</div>
<?php endif; ?>
<!--=======  End of tab product slider  =======-->