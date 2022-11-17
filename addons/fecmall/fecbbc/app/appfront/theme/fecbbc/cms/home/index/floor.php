<!--=======  slider with banner and sidebar container  =======-->
<div class="slider-banner-sidebar-container">
    <div class="row no-gutters">
        <div class="col-lg-3 col-xl-2 col-md-4">
            <!--=======  sidebar  =======-->
            <div class="slider-sidebar">
                <h3 class="slider-sidebar-title"><?= $parentThis['floor_title'] ?></h3>
                <div class="sidebar-list">
                    <ul>
                        <?php if (is_array($parentThis['floor_items']) && !empty($parentThis['floor_items'])): ?>
                            <?php foreach ($parentThis['floor_items'] as $item): ?>
                                <li><a href="<?= Yii::$service->url->getUrl($item['url']) ?>"><?= $item['name'] ?></a></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <!--=======  End of sidebar  =======-->
        </div>
        <div class="col-lg-9 col-xl-10 col-md-8">
            <!--=======  banner slider  =======-->

            <div class="fl-slider banner-slider">
                <!--=======  single product  =======-->
                <?php if (is_array($parentThis['floor_products']) && !empty($parentThis['floor_products'])): ?>
                <?php foreach ($parentThis['floor_products'] as $product ): ?>
                <div class="fl-product">
                    <div class="image sale-product">
                        <a href="<?= $product['url'] ?>">
                            <img src="<?= Yii::$service->product->image->getResize($product['image'],[234,234],false) ?>" class="img-fluid" alt="">
                            <img src="<?= Yii::$service->product->image->getResize($product['image'],[234,234],false) ?>" class="img-fluid" alt="">
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
                        
                        <?php
                            $config = [
                                'class' 		=> 'fecshop\app\appfront\modules\Catalog\block\category\Price',
                                'view'  		=> 'catalog/category/price.php',
                                'price' 		=> $product['price'],
                                'special_price' => $product['special_price'],
                                'special_from' => $product['special_from'],
                                'special_to' => $product['special_to'],
                            ];
                            echo Yii::$service->page->widget->renderContent('category_product_price',$config);
                            ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
                <!--=======  End of single product  =======-->
                
            </div>

            <!--=======  End of banner slider  =======-->

            <!--=======  slider banner  =======-->

            <div class="slider-banner home-one-banner banner-bg banner-bg-1">
                <div class="banner-text">
                    <?= $parentThis['floor_banner_text']  ?>
                </div>
            </div>

            <!--=======  End of slider banner  =======-->
        </div>
    </div>
</div>
<!--=======  End of slider with banner and sidebar container  =======-->
