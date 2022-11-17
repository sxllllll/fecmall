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
use fecshop\app\apphtml5\helper\Format;
use fec\helpers\CRequest;
?>
<div class="main-wrap" id="main-wrap">
    <header id="yoho-header" class="yoho-header boys">
        <a href="javascript:history.go(-1);" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title"><?= Yii::$service->page->translate->__('Cart') ?></p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="shopping-cart-page yoho-page ">
        <?php if(is_array($cart_info['products']) && (!empty($cart_info['products']))): ?>
            <div class="cart-box">    
                <div class="cart-nav clearfix more">
                    <div class="nav-item active" id="common-cart-nav" data-type="ordinary">
                        <span><?= Yii::$service->page->translate->__('Product Info') ?></span>
                    </div>
                </div>
                <div class="cart-content normal-good active">
                    <div class="normal-box">
                        <div class="cart-brand box good-pools-data">
                            <div class="good-list">
                            
                                <?php foreach ($cart_info['products'] as $bdminUserId => $cartProducts):   ?>
                                    <p class="left" style="width: 90%; float: none; padding: 0.4rem 0 0.4rem;  margin-left: 0.7rem;border-bottom: 1px solid #ebebeb;">
                                        <i style="position:static" bdminUser="<?= $bdminUserId ?>" class="cart_select_all cart_select_all_<?= $bdminUserId ?> iconfont item-select-check  chk select "></i>&nbsp;&nbsp;
                                        
                                        <?= Yii::$service->page->translate->__('店铺') ?>
                                        <?=  isset($cart_info['bdmin'][$bdminUserId])  ? $cart_info['bdmin'][$bdminUserId] : '' ?>
                                    </p>
                                    <?php if (is_array($cartProducts)): ?>
                                    <?php foreach ($cartProducts as $product_one): ?>
                                        <div class="good-item is-checked">
                                            <div class="opt"> 
                                                <i class="cart_select_item_<?= $bdminUserId ?> iconfont cart_select_item  item-select-check  chk select <?= ($product_one['active'] == Yii::$service->cart->quoteItem->activeStatus )  ? 'checked' : '' ?>" bdminUser="<?= $bdminUserId ?>"  rel="<?= $product_one['item_id']; ?>" ></i>
                                            </div>
                                            <div class="good-new-info">
                                                <a href="<?= $product_one['url'] ?>" class="img-a">
                                                    <div class="img">
                                                        <img  class="thumb lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getResize($product_one['image'],[120,160],false) ?>" style="display: block;">
                                                    </div>
                                                </a>
                                                <div class="info">
                                                    <div class="fixed-height">
                                                        <div class="intro intro-name">
                                                            <div class="name-row">
                                                                <div class="name">
                                                                    <a href="<?= $product_one['url'] ?>"><?= $product_one['name'] ?></a>
                                                                </div>
                                                            </div>
                                                            <div class="color-size-row">
                                                                <div class="price" style="float:right;">
                                                                    <span class="market-price" style="margin-right:0">
                                                                        <?=  $currency_info['symbol'];  ?><?= Format::price($product_one['product_price']); ?>
                                                                    </span>
                                                                </div>
                                                                 <?php  if(is_array($product_one['custom_option_info'])):  ?>
                                                                    <?php foreach($product_one['custom_option_info'] as $label => $val):  ?>
                                                                        <span ><?= Yii::$service->page->translate->__(ucwords($label).':') ?><?= Yii::$service->page->translate->__($val) ?></span><br/>
                                                                    <?php endforeach;  ?>
                                                                <?php endif;  ?>
                                                            </div>
                                                            <div style="margin-top:0.5rem;">
                                                                <span class="del-fav iconfont btn-remove" rel="<?= $product_one['item_id']; ?>" style="float:right;margin-top: 0.2rem;color:#777">&#xe621;</span>
                                                                <div class="edit-box">
                                                                    <div class="num-opt">
                                                                        <a href="javascript:;" class="btn btn-opt-minus" rel="<?= $product_one['item_id']; ?>">
                                                                            <span class="iconfont"></span>
                                                                        </a>
                                                                        <input type="text" class="good-num" disabled="true" value="<?= $product_one['qty']; ?>" >
                                                                        <a href="javascript:;" class="btn btn-opt-plus" rel="<?= $product_one['item_id']; ?>"> 
                                                                            <span class="iconfont"></span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="cart-footer">
                        <!--
                        <div class="check-all">
                            <i class="iconfont chk select item-selectall-check"></i>
                            <i class="iconfont chk edit"></i>
                            <p><?= Yii::$service->page->translate->__('All ') ?></p>
                        </div>
                        -->
                        
                        <div class="opts bill ">
                            <div class="total">
                                <p class="price">
                                    <?= Yii::$service->page->translate->__('Grand Total') ?>:<?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['grand_total']) ?>
                                    &nbsp;&nbsp;(<?= $cart_info['items_count']  ?><?= Yii::$service->page->translate->__('Piece') ?>)
                                </p>
                                <p class="intro"><?= Yii::$service->page->translate->__('No Shipping Cost') ?></p>
                            </div>
                            <button class="btn btn-red btn-balance"><?= Yii::$service->page->translate->__('Checkout') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="cart-box"><div class="cart-zero">
                <i class="iconfont">&#xe640;</i>
                <p><?= Yii::$service->page->translate->__('Your cart has no goods') ?></p>
                <a href="<?= Yii::$service->url->homeUrl(); ?>">
                    <?= Yii::$service->page->translate->__('Walk Around') ?>
                </a>
            </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
<?php $this->beginBlock('changeCartInfo') ?>
csrfName = "<?= CRequest::getCsrfName() ?>";
csrfVal = "<?= CRequest::getCsrfValue() ?>";
$(document).ready(function(){
    lazyload();
    
    bdmin_select_all = {};
    item_select_all = 1;
    console.log(111);
    $(".cart_select_item").each(function(){
        bdminUser = $(this).attr("bdminUser");
        if (!bdmin_select_all.hasOwnProperty(bdminUser)) {
            bdmin_select_all[bdminUser] = 1;
        }
        // bdmin_select_all[bdminUser]
        checked = $(this).hasClass('checked')
        console.log(checked);
        if (checked == false) {
            bdmin_select_all[bdminUser] = 0;
        }
    });
    console.log(bdmin_select_all);
    for (bdminUser in bdmin_select_all) {
        v = bdmin_select_all[bdminUser];
        if (v == 1) {
            s = ".cart_select_all_"+ bdminUser
            $(s).addClass("checked");
        }
    }
    
    
    
	currentUrl = "<?= Yii::$service->url->getUrl('checkout/cart') ?>";
	updateCartInfoUrl = "<?= Yii::$service->url->getUrl('checkout/cart/updateinfo') ?>";
    selectOneProductUrl = "<?= Yii::$service->url->getUrl('checkout/cart/selectone') ?>";
    selectAllProductUrl = "<?= Yii::$service->url->getUrl('checkout/cart/selectall') ?>";
    // btn-opt-minus good-num btn-opt-plus
	$(".btn-opt-minus").click(function(){
		$item_id = $(this).attr("rel");
		num = $(this).parent().find(".good-num").val();
		if(num > 1){
			$data = {
				item_id:$item_id,
				up_type:"less_one"
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
					} else {
                        alert(data.content);
                    }
				},
				error:function (XMLHttpRequest, textStatus, errorThrown){}
			});
		}
	});
	
	$(".btn-opt-plus").click(function(){
		$item_id = $(this).attr("rel");
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
				} else {
                    alert(data.content);
                }
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
	});
	
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
	
    $(".good-item .item-select-check").click(function(){
		$item_id = $(this).attr("rel");
		checked = $(this).hasClass("checked");
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
    
    $(".cart_select_all").click(function(){
		checked = $(this).hasClass('checked');
        checked = checked ? 0 : 1;
		$data = {
			checked:checked
		};
        $data[csrfName] = csrfVal;
        bdminUser = $(this).attr("bdminUser");
        console.log(bdminUser);
        s = ".cart_select_item_"+bdminUser;
        var items = [];
        $(s).each(function(){
            
            item_id = $(this).attr("rel");
            items.push(item_id);
        });
        
        $data["items"] = items;
        console.log(items);
        
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
    
    $(".btn-balance").click(function(){
        window.location.href = "<?= Yii::$service->url->getUrl('checkout/onepage') ?>";
    });
	
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['changeCartInfo'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>