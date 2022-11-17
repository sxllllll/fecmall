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
use fecshop\app\appfront\helper\Format;
use fec\helpers\CRequest;
?>

<div class="shop-cart yoho-page clearfix">
    <div class="order-edit order-cart" id="cart-page">
        <div class="order-title cart-page-title order-ensure-title">
            <ul class="shopping-step">
                <li class="step first focus"><?= Yii::$service->page->translate->__('View Cart') ?></li>
                <li class="step"><?= Yii::$service->page->translate->__('Submit Order') ?></li>
                <li class="step last"><?= Yii::$service->page->translate->__('Pay, Complete Buy') ?></li>
            </ul>
        </div>
        <div class="cartnew-tips">
            <div class="tipsbox" id="tipsbox">
                <a href="javascript:void(0);" class="btn-close" title="<?= Yii::$service->page->translate->__('Close') ?>"></a>
                <strong><?= Yii::$service->page->translate->__('Tips') ?>：</strong>
                1. <?= Yii::$service->page->translate->__('The items in cart do not retain the inventory, please settle in time.') ?> 
                2. <?= Yii::$service->page->translate->__('Price, Stock Qty are subject to the order submission.') ?>
            </div>
        </div>
        <?php if(isset($cart_info['products'] ) && is_array($cart_info['products'] )): ?>
        <div class="order-pay" id="Y_CartListWrap">
            <!-- 购物车商品列表 -->
            <div class="pay-wapper" data-role="order-cart">
                <div class="cart-title">
                    
                    <p style="width:35%"><?= Yii::$service->page->translate->__('Goods Info') ?></p>
                    <p style="width:14%"><?= Yii::$service->page->translate->__('Price') ?></p>
                    <p style="width:15%;"><?= Yii::$service->page->translate->__('Qty') ?></p>
                    <p style="width:16%;"><?= Yii::$service->page->translate->__('Subtotal') ?></p>
                    <p class="right" style="width:11.8%;"><?= Yii::$service->page->translate->__('Operate') ?></p>
                </div>
                <div data-role="ordinary">
                    <?php foreach ($cart_info['products'] as $bdminUserId => $cartProducts):   ?>
                        <?php if (is_array($cartProducts)): ?>
                            <p class="left" style="width: 100%; float: none; margin-bottom: 20px; margin-top: 20px;">
                                <i style="position:static" bdminUser="<?= $bdminUserId ?>" class="cart_select_all cart_select_all_<?= $bdminUserId ?> cart-item-check iconfont "></i>&nbsp;&nbsp;
                                <?= Yii::$service->page->translate->__('店铺') ?>
                                <?=  isset($cart_info['bdmin'][$bdminUserId])  ? $cart_info['bdmin'][$bdminUserId] : '' ?>
                            </p>
                            <div style="border: 1px solid #e0e0e0">
                                <?php foreach ($cartProducts as $product_one): ?>
                                    <div class="promotion-pool mt20" >
                                        <div class="cart-table" style="border:none;">
                                            <ul class="table table-group">
                                                <li class="pre-sell-box tr  active" >
                                                    <div class="pay-pro td " style="width: 368px;">
                                                        <i  bdminUser="<?= $bdminUserId ?>"  rel="<?= $product_one['item_id']; ?>" class="cart_select_item_<?= $bdminUserId ?> cart_select_item cart-item-check iconfont <?=  ($product_one['active'] == Yii::$service->cart->quoteItem->activeStatus ) ?  'cart-item-checked' : '' ?> "  checked=""></i>
                                                        <a class="pay-pro-icon"  href="<?= $product_one['url'] ?>" target="_blank">
                                                            <img  class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getResize($product_one['image'],[66,90],false) ?>">
                                                        </a>
                                                        <p class="pay-pro-info">
                                                            <a href="<?= $product_one['url'] ?>" target="_blank" data-role="item-title">
                                                                <?= $product_one['name'] ?>
                                                            </a>
                                                            <?php  if(is_array($product_one['custom_option_info'])):  ?>
                                                                <em class="pay-pro-detail">
                                                                <span>
                                                                    <?php foreach($product_one['custom_option_info'] as $label => $val):  ?>
                                                                        <?= Yii::$service->page->translate->__(ucwords($label).':') ?><?= Yii::$service->page->translate->__($val) ?>
                                                                    <?php endforeach;  ?>
                                                                </span>
                                                            </em>
                                                            <?php endif;  ?>
                                                        </p>
                                                    </div>
                                                    <div class="product-price td " style="width:148px;">
                                                        <p class="p-product-price">
                                                            <?=  $currency_info['symbol'];  ?><?= Format::price($product_one['product_price']); ?>
                                                        </p>
                                                    </div>
                                                    <div style="width:128px;" class="adjust-cart-num td">
                                                        <div class="cart-num-cont">
                                                            <span class="minus cart-num-btn  ">
                                                                <i class="iconfont icon-minus"></i>
                                                            </span>
                                                            <input  class="car_ipt" type="text" value="<?= $product_one['qty']; ?>" readonly="readonly"  rel="<?= $product_one['item_id']; ?>">
                                                            <span class="plus cart-num-btn ">
                                                                <i class="iconfont icon-plus"></i>
                                                            </span>
                                                        </div>
                                                        <p class="tip-message "></p>
                                                    </div>
                                                    <div style="width:160px;" class="sub-total red td">
                                                        <?=  $currency_info['symbol'];  ?><?= Format::price($product_one['product_row_price']); ?>
                                                    </div>
                                                    <div style="width:100px;" class="cart-operation td">
                                                        <span  class="cart-del-btn btn-remove" data-role="cart-del-btn" rel="<?= $product_one['item_id']; ?>">
                                                        <?= Yii::$service->page->translate->__('Remove') ?></span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php //var_dump($cart_info); ?>
                <div id="cart-fixed-submit"></div>
                <!-- 总价计算 -->
                <div class="cart-fixed-submit" >
                    <div class="cart-fixed-wrap">
                        <div class="center-content clearfix">
                            <p class="select-num">
                                <?= Yii::$service->page->translate->__('Selected Goods') ?>
                                <strong class="ins"><?= $cart_info['items_count'] ?></strong>
                                <?= Yii::$service->page->translate->__('item') ?>
                            </p>
                            <div class="price-sum">
                                <p class="sum"><?= Yii::$service->page->translate->__('Goods Subtotal') ?>：
                                    <strong>
                                        <kbd><?=  $currency_info['symbol'];  ?></kbd>
                                        <?= $cart_info['product_total'] ?>
                                    </strong>
                                </p>
                            </div>
                            <a href="<?= Yii::$service->url->getUrl('checkout/onepage/index') ?>" id="Y_SubmitBtn" class="btn-account right" >
                                <?= Yii::$service->page->translate->__('Checkout') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
            <div class="order-pay" id="Y_CartListWrap">
                <div class="pay-wapper">
                    <div class="shop-cart-empty">
                        <i class="iconfont"></i>
                        <p><?= Yii::$service->page->translate->__('The Cart is empty, Please choose your favorite product to add to the shopping cart') ?></p>
                        <a href="<?=  Yii::$service->url->homeUrl(); ?>"><?= Yii::$service->page->translate->__('Go Shopping') ?></a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>	
<?php $this->beginBlock('changeCartInfo') ?>
csrfName = "<?= CRequest::getCsrfName() ?>";
csrfVal = "<?= CRequest::getCsrfValue() ?>";
lazyload();
$(document).ready(function(){
    bdmin_select_all = {};
    item_select_all = 1;
    $(".cart_select_item").each(function(){
        bdminUser = $(this).attr("bdminUser");
        if (!bdmin_select_all.hasOwnProperty(bdminUser)) {
            bdmin_select_all[bdminUser] = 1;
        }
        // bdmin_select_all[bdminUser]
        checked = $(this).hasClass('cart-item-checked')
        if (checked == false) {
            bdmin_select_all[bdminUser] = 0;
        }
    });
    
    for (bdminUser in bdmin_select_all) {
        v = bdmin_select_all[bdminUser];
        if (v == 1) {
            s = ".cart_select_all_"+ bdminUser
            $(s).addClass("cart-item-checked");
        }
    }
    
	currentUrl = "<?= Yii::$service->url->getUrl('checkout/cart') ?>";
	updateCartInfoUrl = "<?= Yii::$service->url->getUrl('checkout/cart/updateinfo') ?>";
    selectOneProductUrl = "<?= Yii::$service->url->getUrl('checkout/cart/selectone') ?>";
    selectAllProductUrl = "<?= Yii::$service->url->getUrl('checkout/cart/selectall') ?>";
	$(".cart-num-btn.minus").click(function(){
		$item_id = $(this).parent().find(".car_ipt").attr("rel");
        //alert($item_id);
		num = $(this).parent().find(".car_ipt").val();
		if(num > 1){
			$data = {
				item_id:$item_id,
				up_type:"less_one"
			};
            $data[csrfName] = csrfVal;
			jQuery.ajax({
				async:true,
				timeout: 6000,
				dataType: 'json', 
				type:'post',
				data: $data,
				url:updateCartInfoUrl,
				success:function(data, textStatus){ 
					if(data.status == 'success'){
						window.location.href=currentUrl;
					}
				},
				error:function (XMLHttpRequest, textStatus, errorThrown){}
			});
		}
	});
	
	$(".cart-num-btn.plus").click(function(){
		$item_id = $(this).parent().find(".car_ipt").attr("rel");
		$data = {
			item_id:$item_id,
			up_type:"add_one"
		};
        $data[csrfName] = csrfVal;
		$.ajax({
			async:true,
			timeout: 6000,
			dataType: 'json', 
			type:'post',
			data: $data,
			url:updateCartInfoUrl,
			success:function(data, textStatus){ 
				if(data.status == 'success'){
					window.location.href=currentUrl;
				}
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
	});
    
	$(".cart_select_item").click(function(){
		$item_id = $(this).attr("rel");
		checked = $(this).hasClass('cart-item-checked');
        checked = checked ? 0 : 1;
		$data = {
			item_id:$item_id,
			checked:checked
		};
        $data[csrfName] = csrfVal;
        
		$.ajax({
			async:true,
			timeout: 6000,
			dataType: 'json', 
			type:'post',
			data: $data,
			url:selectOneProductUrl,
			success:function(data, textStatus){ 
				if(data.status == 'success'){
					window.location.href = currentUrl;
				}
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
	});
    //main-container
    $(".btn-remove").click(function(){
		$item_id = $(this).attr("rel");
		$data = {
			item_id:$item_id,
			up_type:"remove"
		};
        $data[csrfName] = csrfVal;
		$.ajax({
			async:true,
			timeout: 6000,
			dataType: 'json', 
			type:'post',
			data: $data,
			url:updateCartInfoUrl,
			success:function(data, textStatus){ 
				if(data.status == 'success'){
					window.location.href=currentUrl;
				}
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
	});
    
    $(".cart_select_all").click(function(){
		checked = $(this).hasClass('cart-item-checked');
        checked = checked ? 0 : 1;
		$data = {
			checked:checked
		};
        $data[csrfName] = csrfVal;
        bdminUser = $(this).attr("bdminUser");
        s = ".cart_select_item_"+bdminUser;
        var items = [];
        $(s).each(function(){
            
            item_id = $(this).attr("rel");
            items.push(item_id);
        });
        
        $data["items"] = items;
        
        
        selectCurrentUrl = currentUrl + '?selectall=' + checked + '&bdminUser=' + bdminUser;
		$.ajax({
			async:true,
			timeout: 6000,
			dataType: 'json', 
			type:'post',
			data: $data,
			url:selectAllProductUrl,
			success:function(data, textStatus){ 
				if(data.status == 'success'){
					window.location.href = selectCurrentUrl;
				}
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
	});
     window.onload = function(){
        var screenw = document.documentElement.clientWidth || document.body.clientWidth;
        var screenh = document.documentElement.clientHeight || document.body.clientHeight;
        var screenb = $(document).height(); 
        window.onscroll = function(){
            var scrolltop = document.documentElement.scrollTop || document.body.scrollTop;
            var bTop = scrolltop + screenh;
            var dTop = document.getElementById("cart-fixed-submit").offsetTop
            console.log("scrolltop" + scrolltop + ',' + bTop + ',' + dTop);
            if(bTop < dTop){
                $(".order-pay").addClass('fixed');
            } else {
                $(".order-pay").removeClass('fixed');
            }
        } 
    }  
    $(".btn-close").click(function(){
        $(".cartnew-tips").hide();
    });
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['changeCartInfo'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>
<?php  // Yii::$service->page->trace->getTraceCartJsCode($trace_cart_info) // 这个改成服务端发送加入购物车数据，而不是js传递的方式 ?>


