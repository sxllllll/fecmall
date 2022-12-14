<div class="product-page yoho-page product-list-page static-list-page">
    <div class="center-content clearfix">
        <?= Yii::$service->page->widget->render('base/breadcrumbs',$this); ?>
    <div class="list-left pull-left">
        <div class="sort-container">
            <?php
                # Category Left Filter subCategory
                $parentThis = [
                    'filter_category' => $filter_category,
                    'current_category'=> $name,
                ];
                echo Yii::$service->page->widget->render('category/filter_subcategory', $parentThis);
            ?>
        </div>
    </div>
    <div class="list-right pull-right">
        <div class="shop-entry-area"></div>
            <div class="filter-box">
                <?php
                    # Refind By
                    $parentThis = [
                        'refine_by_info' => $refine_by_info,
                    ];
                    echo Yii::$service->page->widget->render('category/filter_refineby', $parentThis);
                ?>
                <?php
                    # Category Left Filter Product Attributes
                    $parentThis = [
                        'filters' => $filter_info,
                    ];
                    echo Yii::$service->page->widget->render('category/filter_attr', $parentThis);
                ?>
                <?php
                    # Category Left Filter Product Price
                    $parentThis = [
                        'filter_price' => $filter_price,
                    ];
                    echo Yii::$service->page->widget->render('category/filter_price', $parentThis);
                ?>
            </div>

            <?php
                $parentThis = [
                    'query_item' => $query_item,
                ];
                echo $toolbar = Yii::$service->page->widget->render('category/toolbar', $parentThis);
            ?>
            


            <div class="goods-container clearfix">
                
                <?php  if(is_array($products) && !empty($products)): ?>
                    <?php foreach($products as $product): ?>
                    <div class="good-info" >
                        <div class="tag-container clearfix">
                        </div>
                        <div class="good-detail-img">
                            <a class="good-thumb" href="<?= $product['url'] ?>" title="" target="_blank">
                                <img class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getResize($product['image'],[235,315],false) ?>"  />
                            </a>
                        </div>
                        <div class="good-detail-text ">
                            <a href="<?= $product['url'] ?>" target="_blank" title="" >
                                <?= $product['name'] ?>
                            </a>
                            <?php
                                //$diConfig = [
                                //    'price' 		=> $product['price'],
                                //    'special_price' => $product['special_price'],
                                //    'special_from' => $product['special_from'],
                                //    'special_to' => $product['special_to'],
                                //];
                                //echo Yii::$service->page->widget->DiRender('category/price', $diConfig);
                            ?> 
                            <?= Yii::$service->page->widget->DiRender('category/price', ['productModel' => $product]); ?> 
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="good-item-wrapper">
                    <div class="good-info-main"></div>
                    <div class="good-select-color"></div>
                </div>
            </div>
            <div class="foot-pager clearfix" >
                <?= $product_page ?>
            </div>
        </div>
    </div>
</div>

<script>
    <?php $this->beginBlock('category_list') ?>
    lazyload();
    $(document).ready(function(){
		$(".product-list-nav").click(function(){
            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
            } else {
                 $(this).addClass("active");
            }
        });
	});
    <?php $this->endBlock(); ?>
    <?php $this->registerJs($this->blocks['category_list'],\yii\web\View::POS_END);//????????????js??????????????????????????? ?>
</script> 
<?= Yii::$service->page->trace->getTraceCategoryJsCode($this, $categoryM, $products)  ?>