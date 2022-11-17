<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
?>
<?php
use fecshop\app\apphtml5\helper\Format;
?><div class="main-wrap" id="main-wrap" >
    <header id="yoho-header" class="yoho-header boys">
        <a href="<?= Yii::$service->url->getUrl('checkout/cart') ?>" class="iconfont nav-back">&#xe610;</a>
        <p class="nav-title"><?= Yii::$service->page->translate->__('Confirm Order');?></p>
    </header>
    <div class="order-ensure-page yoho-page">
            <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
            <div class="address block address-wrap  boys" >
                <div class="info">
                    <span class="info-name"><?=  $default_address['first_name']; ?> <?=  $default_address['last_name']; ?></span>
                    <span class="info-phone"><?=  $default_address['telephone']; ?></span>
                    <a href="javascript:void(0)">
                        <span class="info-address">
                            <?= Yii::$service->helper->country->getStateByContryCode('CN', $default_address['state']); ?>
                            <?=  $default_address['city']; ?><?=  $default_address['area']; ?>
                            <?=  $default_address['street1']; ?> <?=  $default_address['street2']; ?><?=  $default_address['zip']; ?>
                        </span>
                    </a>
                    <i class="iconfont">&#xe637;</i>
                </div>
                <a class="rest" href="<?=  Yii::$service->url->getUrl('checkout/onepage/address') ?>"><?= Yii::$service->page->translate->__('Other Address');?>
                    <span class="iconfont">&#xe614;</span>
                </a>
            </div>
        <form action="<?= Yii::$service->url->getUrl('checkout/onepage'); ?>" method="post" id="onestepcheckout-form">
            <?= CRequest::getCsrfInputHtml(); ?>
            <input type="hidden" name="postType" class="postType" value="placeOrder"  />
            <?php if (is_array($cart_infos) && !empty($cart_infos)): ?>
                <?php foreach ($cart_infos as $bdmin_user_id => $cart_info): ?>
                    <section class="block goods-bottom payment_shipping dispatch" style="margin:1rem 0 ">
                        <?php # review order部分
                            $reviewOrderParam = [
                                'cart_info' => $cart_info,
                                'currency_info' => $currency_info,
                            ];
                        ?>
                        <?= Yii::$service->page->widget->render('order/view', $reviewOrderParam); ?>
                        <?php if (is_array($cart_info['shipping_methods'])): ?>
                            <div class="sub-block delivery-id">
                                <?php foreach ($cart_info['shipping_methods'] as $shipping): ?>
                                    <?php if ($shipping['selected']): ?>
                                        <h3>
                                            <p><?= Yii::$service->page->translate->__('Shipping Method');?></p>
                                            
                                            <input  type="hidden" class="shipping_method shipping_method_<?= $bdmin_user_id ?>" name="editForm[shipping_method][<?= $bdmin_user_id ?>]" value="<?= $shipping['id'] ?>"  />
                                            
                                            <i class="iconfont down" style="float: right; margin-right: 0.3rem;">&#xe616;</i>
                                            <i class="iconfont hide up"  style="float: right; margin-right: 0.3rem;">&#xe615;</i>
                                            
                                            <span style="width: auto;  float: right; margin-right: 0.3rem;">
                                                <?= $shipping['label']; ?>
                                                ：<?=  $currency_info['symbol'];  ?>
                                                <?= $shipping['current_cost']; ?>
                                            </span>
                                        </h3>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <ul class="dispatch-mode">
                                    <?php foreach ($cart_info['shipping_methods'] as $shipping):  ?>
                                        <li buid="<?= $bdmin_user_id ?>" class=" shipping_method <?= $shipping['selected'] ? 'chosed' : '' ?>"  rel="<?= $shipping['id']; ?>">
                                            <span class="tip">
                                                <?= $shipping['label']; ?>：
                                                <?= Yii::$service->page->translate->__('Shipping Cost');?>
                                                <?=  $currency_info['symbol'];  ?>
                                                <?= $shipping['current_cost']; ?>
                                            </span>
                                            <i class="right iconfont <?= $shipping['selected'] ? 'icon-cb-radio' : 'icon-radio' ?> " ></i>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <div class="sub-block delivery-id changeCouponMethod" rel="<?= $bdmin_user_id ?>">
                            <h3>
                                <p>
                                    <?= Yii::$service->page->translate->__('Coupon') ?>
                                </p>
                                <p class="coupon_available_count" style="height: 1rem; line-height: 1rem;vertical-align: 0.55rem;">
                                        <?= count($cart_info['coupon_available']) ?>
                                        <?= Yii::$service->page->translate->__(' available ');?>
                                </p>
                                <i class="iconfont down" style="float: right; margin-right: 0.3rem;">&#xe616;</i>
                                <i class="iconfont hide up" style="float: right; margin-right: 0.3rem;">&#xe615;</i>
                                
                                <span style="width: auto;  float: right; margin-right: 0.3rem;">
                                    -<?=  $currency_info['symbol'];  ?><?= $cart_info['coupon_cost'] ?>
                                </span>
                                
                            </h3>
                        </div>
                        <div class="mutil-coupon-shadow mutil-coupon-shadow-<?= $bdmin_user_id ?>" ></div>
                        <div class="mutil-coupon mutil-coupon-<?= $bdmin_user_id ?>" >
                            <img class="close_coupon_window" style="" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/u_close.png')  ?>"/>
                            <div class="coupon_title">
                                <div class="coupon_tab coupon_tab_ab" style="display:inline-block">
                                    <span class="active"><?= Yii::$service->page->translate->__('Available coupons') ?>(<?= count($cart_info['coupon_available']) ?>)</span>
                                </div>
                                <div class="coupon_tab coupon_tab_noab" style="display:inline-block">
                                    <span><?= Yii::$service->page->translate->__('Unavailable coupon') ?>(<?= count($cart_info['coupon_unavailable']) ?>)</span>
                                </div>
                            </div>
                            <div class="coupon_lists">
                                <div class="coupon_active_lists">
                                    <?php if (is_array($cart_info['coupon_available'] )): ?>
                                        <ul class="coupon_methods">
                                        <?php  foreach ($cart_info['coupon_available'] as $active_coupon): ?>
                                            <li class="<?= $cart_info['coupon_selected']['code'] == $active_coupon['code'] ?  'checked' : '' ?>">
                                                <div class="clear"></div>
                                                <div class="check_rado_img" >
                                                    <label for="coupon_<?= $active_coupon['name'] ?>" class="<?= $cart_info['coupon_selected']['code'] == $active_coupon['code']  ? 'checked' : '' ; ?>" ></label>
                                                </div>
                                                
                                                <input  buid="<?= $bdmin_user_id ?>"  id="coupon_<?= $active_coupon['code'] ?>"  name="editForm[cart_coupon][<?= $bdmin_user_id ?>]"   style="display:none;"  class="coupon_method_radio  coupon_method_radio_<?= $bdmin_user_id ?>" type="radio" <?= $cart_info['coupon_selected']['code'] == $active_coupon['code']  ? 'checked="checked"' : '' ; ?> value="<?= $active_coupon['code']; ?>"    />
                                                
                                                <label for="coupon_<?= $active_coupon['code'] ?>" class="coupon_method_label">
                                                    <i class="iconfont item-select-check  chk select <?= $cart_info['coupon_selected']['code'] == $active_coupon['code'] ?  'checked' : '' ?>   "  rel="91" ></i>
                                                    <?= $active_coupon['name'] ?>
                                                    <span style="float:right">-<?= $active_coupon['discount_cost'] ?></span>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="coupon_not_active_lists" style="display:none;">
                                    <?php  if (is_array($cart_info['coupon_unavailable']) && !empty($cart_info['coupon_unavailable'])): ?>
                                        <ul class="coupon_methods">
                                        <?php  foreach ($cart_info['coupon_unavailable'] as $not_active_coupon): ?>
                                            <li >
                                                <div class="clear"></div>
                                                <label for="coupon_<?= $not_active_coupon['code'] ?>" class="coupon_method_label">
                                                    <?= $not_active_coupon['name'] ?>
                                                    <span style="float:right;color:#999;"><?= Yii::$service->page->translate->__('Over {symbol}{coupon_condition} to use', ['symbol' => '', 'coupon_condition' => $not_active_coupon['use_condition']]) ?></span>
                                                </label>
                                                
                                            </li>
                                        <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="goods-num">
                            <?= Yii::$service->page->translate->__('total');?><?= $cart_info['product_qty_total'] ?>
                            <?= Yii::$service->page->translate->__(' items ');?>   
                            <?= Yii::$service->page->translate->__('Subtotal');?>
                            <span><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['product_total']); ?></span>
                        </div>
                    </section>
                <?php endforeach; ?>
            <?php endif; ?>

            <section class="price-cal block">
                <ul class="total">
                    <li>
                        <p><?= Yii::$service->page->translate->__('Subtotal ');?></p>
                        <span><?=  $currency_info['symbol'];  ?><?= Format::price($all_product_total); ?></span>
                    </li>
                    <li>
                        <p><?= Yii::$service->page->translate->__('Shipping Cost');?></p>
                        <span>+<?=  $currency_info['symbol'];  ?><?= Format::price($all_shipping_cost); ?></span>
                    </li>
                    <li>
                        <p><?= Yii::$service->page->translate->__('Discount');?></p>
                        <span>-<?=  $currency_info['symbol'];  ?><?= Format::price($all_discount); ?></span>
                    </li>
                </ul>
                <div class="price-cost">
                    <?= Yii::$service->page->translate->__('Grand Total ');?>
                    <span>
                        <?=  $currency_info['symbol'];  ?><?= Format::price($all_total) ?>
                    </span>
                </div>
            </section>
            <div class="bill">
                <?= Yii::$service->page->translate->__('Need Pay');?>：
                <span>
                    <?=  $currency_info['symbol'];  ?><?= Format::price($all_total) ?>
                </span>
                <a href="javascript:;" id="place-order"><?= Yii::$service->page->translate->__('Place Order');?></a>
            </div>
        </form>
    </div>
</div>
  
<script>
<?php $this->beginBlock('checkout') ?>
$(document).ready(function(){
    // 切换shipping method
    $(".shipping_method").click(function(){
        shipping_method = $(this).attr('rel');
        buid = $(this).attr('buid');
        if (!$(this).hasClass("chosed")) {
            $(this).parent().find(".shipping_method").removeClass("chosed");
            $(this).addClass("chosed");
            str = ".shipping_method_" + buid;
            $(str).val(shipping_method);
            $(".postType").val("selectShippingMethod");
            $("#onestepcheckout-form").submit();
        }
    });
    
    // shipping and  payment  下拉框
    $(".payment_shipping .sub-block .down").click(function(){
        $(this).addClass("hide");
        $(this).parent().find(".up").removeClass("hide");
        $(this).parent().parent().find("ul").show();
    });
    $(".payment_shipping .sub-block .up").click(function(){
        $(this).addClass("hide");
        $(this).parent().find(".down").removeClass("hide");
        $(this).parent().parent().find("ul").hide();
    });
    
	currentUrl = "<?= Yii::$service->url->getUrl('checkout/onepage') ?>";
    changeShippingMethodUrl =  "<?= Yii::$service->url->getUrl('checkout/onepage/changeshippingmethod') ?>";
    changeCouponUrl =  "<?= Yii::$service->url->getUrl('checkout/onepage/changecoupon') ?>";
    // coupon window
    $(".changeCouponMethod").click(function(){
        bdminUserId = $(this).attr("rel");
        str1 = ".mutil-coupon-" + bdminUserId;
        str2 = ".mutil-coupon-shadow-" + bdminUserId;
        $(str1).show();
        $(str2).show();
    });
    
    $(".close_coupon_window").click(function(){
        $(".mutil-coupon").hide();
        $(".mutil-coupon-shadow").hide();
    });
    $(".mutil-coupon-shadow").click(function(){
        $(".mutil-coupon").hide();
        $(".mutil-coupon-shadow").hide();
    });
    // coupon 
    $(".coupon_tab_ab").click(function(){
        $(".coupon_active_lists").show();
        $(".coupon_not_active_lists").hide();
        $(".coupon_tab_ab span").addClass("active");
        $(".coupon_tab_noab span").removeClass("active");
    });
    
    $(".coupon_tab_noab").click(function(){
        $(".coupon_active_lists").hide();
        $(".coupon_not_active_lists").show();
        $(".coupon_tab_ab span").removeClass("active");
        $(".coupon_tab_noab span").addClass("active");
    });
    
    // 选择优惠券
    $(".coupon_method_radio").click(function(){
        $(".postType").val("selectCoupon");
        $("#onestepcheckout-form").submit();
    });
    
    clickCount = 0;
    $("#place-order").click(function(e){
        e.preventDefault();
        if (clickCount == 0) {
            clickCount = 1;
            $("#onestepcheckout-form").submit();
        }
    });
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['checkout'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>

<style>
.coupon_available_count{
    background-color: #ff575c;
    margin-left: .3rem;
    padding: .1rem .375rem;
    color: #fff;
    font-size: .55rem;
    font-weight: 300;
}

</style>

