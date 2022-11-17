<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main-wrap" id="main-wrap">
    <header id="yoho-header" class="yoho-header boys">
        <a href="javascript:history.go(-1);" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title"><?= Yii::$service->page->translate->__('Product List');  ?></p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="good-detail-page yoho-page">
        <input type="hidden" class="product_view_id" value="<?=  $_id ?>">
        <input type="hidden" class="sku" value="<?= $sku; ?>" />
        <div class="banner-container">
            <div class="tag-container">
            </div>
            <div class="banner-top banner-product-images">
                <?php # 图片部分。
                    $imageParam = [
                        'media_size' => $media_size,
                        'image' => $image_thumbnails,
                    ];
                ?>
                <?= Yii::$service->page->widget->render('product/image',$imageParam); ?>
            </div>
        </div>
        <div class="goods-name">
            <h1 class="name"><?= $name; ?></h1>
        </div>
        <div class="price-date">
            <div class="goods-price">
                <?= Yii::$service->page->widget->render('product/price', ['price_info' => $price_info]); ?>
            </div>
        </div>
        
        <?= Yii::$service->page->widget->render('product/coupon', ['productModel' => $productModel]); ?>
        <!--
        <div class="brand-coupon bdmin_store" rel="<?= Yii::$service->url->getUrl('catalog/shop', ['bdmin_user_id' => $bdminUserId]) ?>">
            <i class="iconfont font-right pull-right"></i>
            <i class="promotion-icon "  style="background-color:#ff5000">店</i><span> <?= $bdminName; ?></span>
        </div>
        -->
        
        <div class="chose-panel page-chose-panel product-options-panel" style="padding-left: 0.75rem; padding-right: 0.75rem;position: relative;background:#fff;">
            <?= Yii::$service->page->widget->render('product/options', ['options' => $options]); ?>
        </div>
        <div id="placeholder-promotion-yohocoin"></div>
        
        <div id="productDesc" class="product-desc ">
            <div class="goods-desc  page-block">
                <h2 class="title">
                    <?= Yii::$service->page->translate->__('Product Info');  ?>
                    <span class="en-title">PRODUCT INFO</span>
                </h2>
                
                <div class="detail table">
                    <?php if(is_array($groupAttrArr)): ?>
                        <?php foreach($groupAttrArr as $k => $v): ?>
                            <div class="column"><?= $k ?>：<?= $v ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="product-detail page-block">
                <h2 class="title">
                    <?= Yii::$service->page->translate->__('Product Detail');  ?>
                    <span class="en-title">DETAILS</span>
                </h2>
                <div class="pro-detail">
                    <?= $description; ?>
                    <?php   if(is_array($image_detail)):  ?>
                        <?php foreach($image_detail as $image_detail_one): ?>
                            <br/>
                            <img class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getUrl($image_detail_one['image']);  ?>"  />
                        <?php  endforeach;  ?>
                    <?php  endif;  ?>
                </div>
            </div>
                
            <div class="feedback-list page-block">
                <h2 class="title">
                    <?= Yii::$service->page->translate->__('Product Review');  ?>
                    <span class="en-title">REVIEWS</span>
                </h2>
                <div id="feedback-content">
                    <div class="comment-content content">
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
        <?= Yii::$service->page->widget->render('product/buy_also_buy', ['products' => $buy_also_buy]); ?>
        
        <div class="cart-bar">
            
            <a href="<?= Yii::$service->url->getUrl('checkout/cart') ?>" class="new-foot-ico" rel="nofollow">
                <div class="num-incart iconfont">
                    <span class="num-tag">0</span>
                    &#xe62c;
                </div>
                <div class="tip"><?= Yii::$service->page->translate->__('Cart');  ?></div>
            </a>
            
            <a href="<?= Yii::$service->url->getUrl('catalog/shop', ['bdmin_user_id' => $bdminUserId]) ?>" class="new-foot-ico">
                <div class="iconfont">&#xe705;</div>
                <div class="tip"><?= Yii::$service->page->translate->__('Shop');  ?></div>
            </a>
            
            <a href="javascript:void(0);" class="new-foot-ico favorite_product fav">
                <div id="likeBtn" class="favorite iconfont ">&#xe605;</div>
                <div class="tip opa"><?= Yii::$service->page->translate->__('Favorite');  ?></div>
            </a>
            <a href="javascript:;" class="addto-cart add-to-cart-url"><?= Yii::$service->page->translate->__('Add To Cart');  ?></a>
        </div>

        
        <div class="chose-panel chose-panel-add-cart" style="display:none;z-index:9999;">
            <div class="main">
                <div class="close iconfont">&#xe626;</div>
                <div class="infos ">
                    <div class="basic-info">
                        <div class="thumb-img">
                            <img class="thumb" src="<?= Yii::$service->product->image->getResize($main_img,[150, 160],false)  ?>">
                        </div>
                        <div class="text-info">
                            <div class="goods-price">
                                <?= Yii::$service->page->widget->render('product/price', ['price_info' => $price_info]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chose-items product-options-panel">
                        <?= Yii::$service->page->widget->render('product/options', ['options' => $options]); ?>
                    </div>
                    <div class="num">
                        <span class="name"><?= Yii::$service->page->translate->__('Qty ');  ?></span>
                        <div class="clearfix">
                            <a class="btn btn-minus " href="javascript:void(0);">
                                <span class="iconfont ">&#xe625;</span>
                            </a>
                            <input id="good-num" class="good-num disabled qty" type="text" value="1" disabled="true">
                            <a class="btn btn-plus" href="javascript:void(0);">
                                <span class="iconfont ">&#xe624;</span>
                            </a>
                        </div>
                        <span class="left-num"></span>
                        <input id="left-num" type="hidden" value="0">
                        <input id="limitNum" type="hidden" value="">
                    </div>
                </div>
                <div class="btn-wrap">
                    <button id="chose-btn-buynow" class="btn btn-sure-buynow"><?= Yii::$service->page->translate->__('Buy Now');  ?></button>
                    <button id="chose-btn-sure" class="btn btn-sure-addtocart"><?= Yii::$service->page->translate->__('Add To Cart');  ?></button>
                </div>
            </div>
        </div>
    </div>
   <?= Yii::$service->page->widget->render('base/footer_navigation',$this); ?>
</div>






<script>
	<?php $this->beginBlock('product_detail') ?>
    lazyload();
    $(document).ready(function(){
        var mySwiper = new Swiper('.swiper-container',{
            loop: true,
            speed:1000,
            autoplay: 2000,
            pagination: '.swiper-pagination',
        });
        
    });
    lazyload();
      
    // ajax加载产品收藏信息
    $(document).ready(function(){
        productAjaxUrl = "<?= Yii::$service->url->getUrl("customer/ajax/product"); ?>";
		product_id   = $(".product_view_id").val();
        var selfss = ".favorite_product";
		$.ajax({
			async: true,
			timeout: 6000,
			dataType: 'json',
			type: 'get',
			data: {
				"product_id": product_id
			},
			url: productAjaxUrl,
			success:function(data, textStatus){
				if(data.favorite){
                    $(selfss).removeClass("fav");
                    $(selfss).find(".tip").removeClass("opa");
				} else {
                    $(selfss).addClass("fav");
                    $(selfss).find(".tip").addClass("opa");
                }
				if(data.csrfName && data.csrfVal && data.product_id){
					$(".product_csrf").attr("name",data.csrfName);
					$(".product_csrf").val(data.csrfVal);
				}
                var cart_qty = data.cart_qty;
                if (cart_qty >=1) {
                    $(".num-tag").html(cart_qty);
                }
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
        
        $(".bdmin_store").click(function(){
            url = $(this).attr("rel");
            window.location.href = url;
        });
        // 收藏产品
        $(".favorite_product").click(function(){
            productFavoriteUrl = "<?= Yii::$service->url->getUrl("catalog/favoriteproduct/favo"); ?>";
            var self = this;
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
                        $(self).removeClass("fav");
                        $(self).find(".tip").removeClass("opa");
                    } else {
                        $(self).addClass("fav");
                        $(self).find(".tip").addClass("opa");
                    }
                },
                error:function (XMLHttpRequest, textStatus, errorThrown){}
            });
	   });
       // qty -1
       $(".num .btn-minus").click(function(){
           var qty = $("#good-num").val();
           qty = parseInt(qty);
           if (qty >=2) {
               qty = qty - 1;
               $("#good-num").val(qty);
           }
       });
       // qty +1
       $(".num .btn-plus").click(function(){
           var qty = $("#good-num").val();
           qty = parseInt(qty);
           qty = qty + 1;
           $("#good-num").val(qty);
       });
       // 打开addtocart panel
        $(".addto-cart").click(function(){
            $(".chose-panel-add-cart").show();
        });
        // close panel
        $(".chose-panel .close").click(function(){
            $(".chose-panel-add-cart").hide();
        });
        // 点击阴影，关闭panel
        $(".chose-panel-add-cart").click(function(event){
            if (event.target===event.currentTarget) {
                $(".chose-panel-add-cart").hide();   
            }
        });
        
        openPancel = <?= Yii::$app->request->get('openPancel') == 1 ? 1 : 0 ?>;
        if (openPancel == 1) {
            $(".chose-panel-add-cart").show();
        }
        
        
        $(".btn-sure-buynow").click(function(){
            buy_now = 'buy_now';
            AddToCart (buy_now);
        });
        $(".btn-sure-addtocart").click(function(){
            buy_now = '';
            AddToCart (buy_now);
        });
    
        function AddToCart (buy_now) {
            qty = $(".good-num").val();
            qty = qty ? qty : 1;
            $data = {};
            $data['product_id'] 	= $(".product_view_id").val();
            $data['qty'] 			= qty;
            $data['buy_now'] 			= buy_now;
            addToCartUrl = "<?= Yii::$service->url->getUrl('checkout/cart/add'); ?>";
            
            $.ajax({
                async:true,
                timeout: 6000,
                dataType: 'json', 
                type:'post',
                data: $data,
                url:addToCartUrl,
                success:function(data, textStatus){ 
                    if(data.status == 'success'){
                        items_count = data.items_count;
                        $(".num-tag").html(items_count);
                        // alert(items_count);
                        if (buy_now == "buy_now") {
                            window.location.href="<?= Yii::$service->url->getUrl("checkout/onepage") ?>";
                        } else {
                            //window.location.href="<?= Yii::$service->url->getUrl("checkout/cart") ?>";
                            addTips('加入成功');
                            $(".chose-panel-add-cart").hide();
                        }
                        
                    }else{
                        content = data.content;
                        $(".chose-panel").hide();
                        alert(content);
                    }
                    
                },
                error:function (XMLHttpRequest, textStatus, errorThrown){}
            });
		}
        
    });
	<?php $this->endBlock(); ?> 
	<?php $this->registerJs($this->blocks['product_detail'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 
<?= Yii::$service->page->trace->getTraceProductJsCode($this, $productM)  ?>
  
 <?php
    // 添加微信share
    $middle_img_width = isset($media_size['middle_img_width']) ? $media_size['middle_img_width'] : 400;
    $imgUrl = Yii::$service->product->image->getResize($image_thumbnails['main']['image'],$middle_img_width,false);
    if (substr($imgUrl,0,4) != 'http') {
        $imgUrl = 'http:'.$imgUrl;
    }
    $productPageTitle = $name;
    $productPageDesc = Yii::$service->wx->h5share->productPageDefaultDesc;
    $productPageImgUrl = $imgUrl;
    $wx_param = [ 'title' => $productPageTitle, 'imgUrl' => $productPageImgUrl,  'desc' => $productPageDesc];
    echo Yii::$service->page->widget->render('base/wx', ['wx_param' => $wx_param]); 
?>
 