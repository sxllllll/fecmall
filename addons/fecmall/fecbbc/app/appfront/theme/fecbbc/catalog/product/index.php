<div class="product-detail-page yoho-page">
    <div class="center-content">
        <?= Yii::$service->page->widget->render('base/breadcrumbs',$this); ?>
        <input type="hidden" class="product_view_id" value="<?=  $_id ?>">
        <input type="hidden" class="sku" value="<?= $sku; ?>" />
        <input type="hidden" class="favorited" value="<?= Yii::$service->page->translate->__('Favorited') ?>" />
        
        <div class="main clearfix product_images" >
            <?php # 图片部分。
                $imageParam = [
                    'media_size' => $media_size,
                    'image' => $image_thumbnails,
                    'productImgMagnifier' => $productImgMagnifier,
                ];
            ?>
            <?= Yii::$service->page->widget->render('product/image',$imageParam); ?>
            <div class="pull-right infos">
                <h1 class="name">
                    <?= $name; ?>
                </h1>
                
                <div class="line"></div>
                <div class="price_info">
                    <?= Yii::$service->page->widget->render('product/price', ['price_info' => $price_info]); ?>
                </div>
                <div class="coupon_info">
                    <?= Yii::$service->page->widget->render('product/coupon', ['productModel' => $productModel]); ?>
                </div>
                <div class="tier_price_info">
                    <?= Yii::$service->page->widget->render('product/tier_price', ['tier_price' => $tier_price]); ?>
                </div>
            <div class="line"></div>

            <div class="trade-content">
                <div id="type-chose" class="type-chose">
                    <div class="product_options">
                        <?= Yii::$service->page->widget->render('product/options', ['options' => $options]); ?>
                    </div>
                    <div class="chose-count row clearfix">
                        <span class="title pull-left"><?= Yii::$service->page->translate->__('Qty: ') ?></span>
                        <div class="num-wraper pull-left clearfix">
                            <span class="minus-plus pull-left">
                                <i id="minus-num" class="minus dis iconfont">&#xe63c;</i>
                            </span>
                            <span id="num" class="num pull-left">1</span>
                            <span class="minus-plus pull-left">
                                <i id="plus-num" class="plus iconfont">&#xe644;</i>
                            </span>
                        </div>
                    </div>

                    <div class="line"></div>
                        <span id="add-to-cart" class="buy-btn item-buy add-to-cart">
                            <i class="iconfont">&#xe600;</i> 
                            <?= Yii::$service->page->translate->__('Add To Cart') ?>
                        </span>
                        <span id="collect-product" class="collect-product">
                             <i class="iconfont">&#xe641;</i>  
                             <em><?= Yii::$service->page->translate->__('Favorite Goods') ?></em> 
                        </span>
                        <div class="support-service">
                            <span class="title"> <?= Yii::$service->page->translate->__('Service Info') ?>： </span>
                            <span class="item" >
                                <i class="iconfont icon-active">&#xe78a;</i>
                                <span class="active" >
                                    <?= Yii::$service->page->translate->__('Brand Protection') ?>
                                </span>
                            </span>
                            <span class="item" >
                                <i class="iconfont icon-active">&#xe78a;</i>
                                <span class="active" title=""><?= Yii::$service->page->translate->__('Support 7 Days Return') ?></span>
                            </span>
                            <span class="more js-more"><?= Yii::$service->page->translate->__('More') ?></span>
                        </div>
                        
                        <div class="support-service">
                            <a href="<?= Yii::$service->url->getUrl('catalog/shop', ['bdmin_user_id' => $bdminUserId]) ?>">
                                <span class="title"><img src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/dian.png');   ?>"   /> ： </span>
                                <span class="item" >
                                    <span class="active" >
                                        <?= $bdminName; ?>
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                    
                    <div id="balance" class="balance">
                        <p class="success-tip"><?= Yii::$service->page->translate->__('This item has been successfully added to the shopping cart.') ?></p>
                        <p class="cart-total"><?= Yii::$service->page->translate->__('Shopping cart has {qty} item', ['qty' => '<span id="cart-num">0</span>' ]) ?></p>
                        <p class="balance-btns">
                            <a id="go-to-cart-url" class="go-cart buy-btn" href="<?= Yii::$service->url->getUrl('checkout/cart'); ?>">
                                <?= Yii::$service->page->translate->__('Go To Cart') ?>
                                <i class="iconfont">&#xe60c;</i>
                            </a>

                            <span id="keep-shopping" class="keep-shopping"> <?= Yii::$service->page->translate->__('Continue Shopping') ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div id="package" class="package-box clearfix hide"></div>
        <div class="total-content">
            <div class="other-infos">
                <div class="description-material info-block head getnav" id="goodsMessage" data-id="0">
                    <div class="J-dc-tit-new dc-tit-new" id="J-proSize-scroll">
                        <p class="dc-title"><?= Yii::$service->page->translate->__('Goods Info') ?></p>
                    </div>
                    <div class="description-content">
                        <?php if(is_array($groupAttrArr)): $i=0;?>
                        <ul class="basic clearfix">
                            <?php foreach($groupAttrArr as $k => $v): ?> <?php $i++;  ?>
                                        
                                <li>
                                    <em class="justpostion">
                                            <span class="keySpace" ><?= $k ?>: </span>
                                            <span class="value-space"><?= $v ?></span>
                                    </em>
                                </li>        
                            <?php endforeach; ?> 
                        </ul>
                        <?php endif; ?>    
                    </div>
                </div>
                <div class="details info-block getnav" id="goodsInside" data-id="2">
                    <div class="J-dc-tit-new dc-tit-new" id="J-proSize-scroll">
                        <p class="dc-title"><?= Yii::$service->page->translate->__('Goods Detail') ?></p>
                    </div>

                    <div id="details-html" class="details-html">
                        <?= $description; ?>
                        <div class="img-section">
                            <?php   if(is_array($image_detail)):  ?>
                                <?php foreach($image_detail as $image_detail_one): ?>
                                    <br/>
                                    <img class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getUrl($image_detail_one['image']);  ?>"   />

                                <?php  endforeach;  ?>
                            <?php  endif;  ?>
                        </div>
                    </div>
                </div>
                            
                            
                            
                            
                <div class="consult-comment info-block getnav" id="judge" data-id="3">

                    <div class="J-dc-tit-new dc-tit-new" id="J-proSize-scroll">
                        <p class="dc-title"><?= Yii::$service->page->translate->__('Goods Review') ?></p>
                    </div>

                    <div class="comments cc-content">
                        <div class="judge-content">
                            <?php # review部分。
                                $reviewParam = [
                                    'product_id' 	=> $_id,
                                    'spu'			=> $spu,
                                ];
                                $reviewParam['reviw_rate_star_info'] = $reviw_rate_star_info;
                                $reviewParam['review_count'] = $review_count;
                                $reviewParam['reviw_rate_star_average'] = $reviw_rate_star_average;
                                // var_dump($reviewParam);exit;
                            ?>
                            <?= Yii::$service->page->widget->DiRender('product/review', $reviewParam); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="after-service">
            <div id="saleReturn" class="support-salereturned-service"></div>
        </div>
        <?= Yii::$service->page->widget->render('product/buy_also_buy', ['products' => $buy_also_buy]); ?>
    </div>
</div>    

<script>
    <?php $this->beginBlock('add_to_cart') ?>
    lazyload();
    $(document).ready(function(){   // onclick="ShowDiv_1('MyDiv1','fade1')"
        $(".add-to-cart").click(function(){
            product_qty = $("#num").html();
            product_qty = product_qty ? product_qty : 1;
            // ajax 提交数据
            addToCartUrl = "<?= Yii::$service->url->getUrl('checkout/cart/add'); ?>";
            $data = {};
            $data['product_id'] 	= $(".product_view_id").val();
            $data['qty'] 			= product_qty;
            jQuery.ajax({
                async:true,
                timeout: 6000,
                dataType: 'json',
                type:'post',
                data: $data,
                url:addToCartUrl,
                success:function(data, textStatus){
                    if(data.status == 'success'){
                        items_count = data.items_count;
                        $("#cart-num").html(items_count);
                        $("#type-chose").hide();
                        $("#balance").show();
                    }else{
                        content = data.content;
                        alert(content);
                    }
                },
                error:function (XMLHttpRequest, textStatus, errorThrown){}
            });
        });
        $(".keep-shopping").click(function(){
            
            $("#type-chose").show();
            $("#balance").hide();
            
        });
        // 收藏产品
        $(".collect-product").click(function(){
            productFavoriteUrl = "<?= Yii::$service->url->getUrl("catalog/favoriteproduct/favo"); ?>";
            product_id = $(".product_view_id").val();
			$.ajax({
                async: true,
                timeout: 6000,
                dataType: 'json',
                type: 'get',
                data: {
                    "product_id": product_id
                },
                url: productFavoriteUrl,
                success:function(data, textStatus){
                    loginStatus = data.loginStatus;
                    if (!loginStatus) {
                        window.location.href = "<?=  Yii::$service->url->getUrl('customer/account/login') ?>";
                    }
                    if (data.favoriteStatus){
                        $(".collect-product").addClass('coled');
                        $(".collect-product em").html('<?= Yii::$service->page->translate->__('Favorited') ?>');
                    } else {
                        $(".collect-product").removeClass('coled');
                        $(".collect-product em").html('<?= Yii::$service->page->translate->__('Favorite Goods') ?>');
                    }
                },
                error:function (XMLHttpRequest, textStatus, errorThrown){}
            });
	   });
       
       $(".collect-product").hover(function(){
           if ($(this).hasClass('coled')) {
               $(".collect-product em").html('<?= Yii::$service->page->translate->__('Cancel Favorite') ?>');
           }
       },function(){
           if ($(this).hasClass('coled')) {
               $(".collect-product em").html('<?= Yii::$service->page->translate->__('Favorited') ?>');
           }
       });
       // qty 个数 -1
       $("#minus-num").click(function(){
            var val = $("#num").html();
            val = parseInt(val);
            if (val >= 2) {
                $("#num").html(val-1);
            }
            nval = $("#num").html();
            $i = $("#minus-num");
            if (nval == 1) {
                
                if ($i.hasClass("dis")) {
                    
                } else {
                    $i.addClass("dis");
                }
            } else {
                $i.removeClass("dis");
            }
            
       });
       // qty 个数 +1
       $("#plus-num").click(function(){
            var val = $("#num").html();
            val = parseInt(val);
            $("#num").html(val+1);
            
            nval = $("#num").html();
            $i = $("#minus-num");
            if (nval >= 1) {
                $i.removeClass("dis");
            }
       });
       
    });
    <?php $this->endBlock(); ?>
    <?php $this->registerJs($this->blocks['add_to_cart'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>
<?= Yii::$service->page->trace->getTraceProductJsCode($this, $productM)  ?>


  