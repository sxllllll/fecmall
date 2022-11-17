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
use fecshop\app\appfront\helper\Format;
?>

<div class="order-ensure yoho-page ">
    <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
    <div class="order-ensure-title">
        <ul class="shopping-step">
            <li class="step first focus"><?= Yii::$service->page->translate->__('View Cart') ?></li>
            <li class="step focus"><?= Yii::$service->page->translate->__('Submit Order') ?></li>
            <li class="step last"><?= Yii::$service->page->translate->__('Pay, Complete Buy') ?></li>
        </ul>        
    </div>

        <div class="address-wrap">
            <div class="block-title">
                <?= Yii::$service->page->translate->__('Address Info') ?>
                <span id="new-address-btn" class="right modify-addr"><?= Yii::$service->page->translate->__('Add New Address') ?><i>+</i></span>
            </div>
            <div id="addr-list" class="addr-list clearfix more">
                <?php if (is_array($address_list) && !empty($address_list)):  ?>
                    <?php foreach ($address_list as $address_one):  $i++;?>
                    <?php $address_info = $address_one['address_info'];  ?>
                    <?php if ($address_info['is_default'] == 1) {
                        $add_info = $address_info['state'] . $address_info['city'] .  $address_info['area'] .  $address_info['street1'] ;
                        $add_person =  $address_info['first_name'];
                        $add_tel =  $address_info['telephone'];
                    } 
                    ?>
                        <div rel="<?= $address_info['address_id'] ?>" class="address_item  addr-item addr-default <?= $address_info['is_default'] == 1 ? 'addr-select' : ''  ?>" >
                            <p class="name">
                                <?= $address_info['first_name'] ?>
                                <span class="right"><?= $address_info['telephone'] ?></span>
                            </p>
                            <p class="area"><?= $address_info['state'] ?><?= $address_info['city'] ?><?= $address_info['area'] ?></p>
                            <p class="street fw300"><?= $address_info['street1'] ?></p>
                            <p class="option">
                                <?php if ($address_info['is_default'] == 1): ?>
                                    <label class="default-tip"><?= Yii::$service->page->translate->__('Default Address');?></label>
                                <?php endif; ?>
                                <span class="delete-addr">
                                    <a class="removeAddress" href="javascript:void(0)" rel="<?= Yii::$service->url->getUrl('checkout/onepage/addressremove', ['address_id' => $address_info['address_id']]) ?>">
                                        <?= Yii::$service->page->translate->__('Remove');?>
                                    </a>
                                </span>
                                <span class="modify-addr" rel="<?= $address_info['address_id'] ?>" ><?= Yii::$service->page->translate->__('Update');?></span>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <div id="new-address-block" class="new-addr">
                    <div class="plus-icon modify-addr"></div>
                    <p class="modify-addr" rel=""><?= Yii::$service->page->translate->__('Add Address');?></p>
                </div>
            </div>
            <?php if ($i >=4): ?>
            <p class="addr-opt">
                <span class="more-addr-btn act"><?= Yii::$service->page->translate->__('Show All Address');?></span>
                <span class="hide-more-btn"><?= Yii::$service->page->translate->__('Hide Address');?></span>
            </p>
            <?php endif; ?>
        </div>
        
        
        <div class="body-mask" style="height: 2330px; width: 1903px;display:none;"></div>
        <div class="yoho-dialog ope-address-dialog" style="margin-top: -258px; margin-left: -250px;display:none;">
            <span class="close"><i class="iconfont"></i></span>
            <div class="content">
                <div class="title"><?= Yii::$service->page->translate->__('Add New Address') ?></div>
                <p class="prompt"><?= Yii::$service->page->translate->__('please fill in the address as accurately as possible.') ?></p>
                
                <form class="addressedit" action="<?= Yii::$service->url->getUrl('checkout/onepage/addressedit', ['method' => 'edit']); ?>" id="form-validate" method="post">
                    <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
                    <input type="hidden"  class="ad_address_id" name="editForm[address_id]"  value="" />
                    <ul class="info-wrap">
                        <li>
                            <span class="left-rd"><i class="red">*</i><?= Yii::$service->page->translate->__('Receiver Person') ?>：</span>
                            <input class="ad_first_name" type="text" name="editForm[first_name]" value="" placeholder="<?= Yii::$service->page->translate->__('please enter your name') ?>"  required="required">
                    
                            <p class="caveat-tip"></p>
                        </li>
                        <li>
                            <span class="left-rd"><i class="red">*</i><?= Yii::$service->page->translate->__('Your Area') ?>：</span>
                            <div class="distpicker" id="distpicker3" style="display:inline-block;width: auto">
                                <select name="editForm[state]" class="ad_state address_state" ></select>
                                <select name="editForm[city]" class="ad_city address_state"  ></select>
                                <select name="editForm[area]" class="ad_area address_state"  ></select>
                            </div>
                            <p class="caveat-tip"></p>
                        </li>
                        <li>
                            <span class="left-rd"><i class="red">*</i><?= Yii::$service->page->translate->__('Address Detail') ?>：</span>
                            <input class="ad_street1" type="text" required="required" name="editForm[street1]" value="" placeholder="<?= Yii::$service->page->translate->__('Street name or community name') ?>">
                    
                            <p class="caveat-tip"></p>
                        </li>
                        <li>
                            <span class="left-rd"><i class="red">*</i><?= Yii::$service->page->translate->__('Telephone') ?>：</span>
                            <input class="ad_telephone" type="text" value="" name="editForm[telephone]" placeholder="<?= Yii::$service->page->translate->__('Please enter your mobile number (imported required)') ?>"  required="required">
                    
                            <p class="caveat-tip"></p>
                        </li>
                        
                        <li>
                            <span class="left-rd"><i class="red"></i><?= Yii::$service->page->translate->__('Zip');?>：</span>
                            <input type="text" class="ad_zip form-control required-entry l_num" id="zip" placeholder="<?= Yii::$service->page->translate->__('Zip');?>" maxlength="255" title="Zip" value="" name="editForm[zip]"  />
                            
                            <p class="caveat-tip"></p>
                        </li>
                        
                        <li>
                            <span class="left-rd"></span>
                            <label class="radio-btn setDefaultAddress <?= $address['is_default'] == 1 ? 'on' : '' ?>"><?= Yii::$service->page->translate->__('Set Default Address') ?></label>
                            <input name="editForm[is_default]" value="<?= $address['is_default'] == 1 ? '1' : '' ?>" title="Save in address book" id="address:is_default" class="address_is_default" type="hidden">
                          
                        </li>
                    </ul>
                </form>
            </div>
            <div class="btns">
                <span id="dialog-save" class="btn black  saveEditAddress"><?= Yii::$service->page->translate->__('Save') ?></span>
                <span id="dialog-cancel" class="btn btn-close cancelEditAddress"><?= Yii::$service->page->translate->__('Cancel') ?></span>
            </div>
        </div>
        
            
        <form action="<?= Yii::$service->url->getUrl('checkout/onepage'); ?>" method="post" id="onestepcheckout-form">
            <?= CRequest::getCsrfInputHtml(); ?>
            <input type="hidden" name="postType" class="postType" value="placeOrder"  />
            <?php if (is_array($cart_infos) && !empty($cart_infos)): ?>
                <div class="block-title" style="    margin-bottom: 10px;">
                    <?= Yii::$service->page->translate->__('Order Goods Info');?>
                    <a href="<?= Yii::$service->url->getUrl('checkout/cart') ?>" class="right">
                        <span id="go-cart-btn">
                            <?= Yii::$service->page->translate->__('Return Update Cart');?>
                        </span>
                    </a>
                </div>
                <?php foreach ($cart_infos as $bdmin_user_id => $cart_info): ?>
                    <div style="margin-bottom:35px;">
                        <div class="goods-wrap">
                            <?php # review order部分
                                $reviewOrderParam = [
                                    'cart_info' => $cart_info,
                                    'currency_info' => $currency_info,
                                ];
                            ?>
                            <?= Yii::$service->page->widget->render('order/view', $reviewOrderParam); ?>
                                
                            
                            <div >
                                <div class="block-title"><?= Yii::$service->page->translate->__('Coupon');?></div>
                                
                                <div class="coupon-opt-title">
                                    <ul class="coupon-tab clearfix">
                                        <li class="tab-item usable active"><?= Yii::$service->page->translate->__('Available');?><span>（<?= count($cart_info['coupon_available']) ?>）</span></li>
                                        <li class="tab-item unusable"><?= Yii::$service->page->translate->__('Unavailable');?>（<?= count($cart_info['coupon_unavailable']) ?>）<span></span></li>
                                    </ul>
                                </div>
                                
                                <div class="coupon-list-wrap">
                                    <div class="usable-wrap">
                                        <?php if (is_array($cart_info['coupon_available']) && !empty($cart_info['coupon_available'])): ?>
                                            <div class="list-content">
                                                <div class="list-100 type-content clearfix">
                                                    <?php  foreach ($cart_info['coupon_available'] as $active_coupon): ?>
                                                        <div class="coupon-item coupon-item-200 active" data-code="dpzusyqh4agogo">
                                                            <div class="worth">
                                                                <p>
                                                                    <span class="price">
                                                                        <?= $active_coupon['discount_cost'] ?>
                                                                    </span>
                                                                    <br><span class="conditions"><?= Yii::$service->page->translate->__('Full {condition} yuan available', ['condition' => $active_coupon['use_condition']]);?></span>
                                                                </p>
                                                            </div>
                                                            <div class="coupon-info">
                                                                <p class="name"><span class="type">[<?= Yii::$service->page->translate->__('Coupon');?>]</span><?= $active_coupon['name'] ?></p>
                                                                <p class="time"><?= Yii::$service->page->translate->__('Expire Date');?>：<?= date("Y-m-d H:i:s", $active_coupon['active_end_at']) ?></p>
                                                            </div>
                                                            
                                                            <input buid="<?= $bdmin_user_id ?>"   <?=  $cart_info['coupon_selected']['code'] == $active_coupon['code'] ? 'checked' : '' ?> type="radio"  class="iconfont check-icon coupon-check-btn"  value="<?= $active_coupon['code']; ?>" />
                                                            <?php  if ($cart_info['coupon_selected']['code'] == $active_coupon['code']): ?>
                                                                <input  type="hidden" class="cart_coupon cart_coupon_<?= $bdmin_user_id ?>" name="editForm[cart_coupon][<?= $bdmin_user_id ?>]" value="<?= $active_coupon['code'] ?>"  />
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="list-content">
                                                <p class="empty"><span><?= Yii::$service->page->translate->__('No coupons');?></span></p>
                                            </div>
                                        <?php endif; ?>
                                        
                                    </div>
                                    <div class="unusable-wrap hide">
                                        <?php  if (is_array($cart_info['coupon_unavailable']) && !empty($cart_info['coupon_unavailable'])): ?>
                                            <div class="list-content">
                                                <div class="list-100 type-content clearfix">
                                                    <?php  foreach ($cart_info['coupon_unavailable'] as $not_active_coupon): ?>
                                                        <div class="coupon-item coupon-item-100 active" data-code="dpzusyqh4agogo">
                                                            <div class="worth">
                                                                <p>
                                                                    <span class="price">
                                                                        <?= $not_active_coupon['discount_cost'] ?>
                                                                    </span>
                                                                    <br><span class="conditions"><?= Yii::$service->page->translate->__('Full {condition} yuan available', [ 'condition' => $not_active_coupon['use_condition'] ]);?></span>
                                                                </p>
                                                            </div>
                                                            <div class="coupon-info">
                                                                <p class="name"><span class="type">[<?= Yii::$service->page->translate->__('Coupon');?>]</span><?= $not_active_coupon['name'] ?></p>
                                                                <p class="time"><?= Yii::$service->page->translate->__('Expire Date');?>：<?= date("Y-m-d H:i:s", $not_active_coupon['active_end_at']) ?></p>
                                                            </div>
                                                            
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                        <div class="list-content">
                                            <p class="empty"><span><?= Yii::$service->page->translate->__('No coupons');?></span></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                    
                        </div>
                        
                        
                        
                        <div class="delivery-way-wrap" style="border-top: none;">
                            <div class="block-title" style="font-size: 14px; border: none; margin-bottom: 15px;">
                                <?= Yii::$service->page->translate->__('Shipping Method');?>
                            </div>
                            <input type="hidden" id="support-way2" value="1">
                                <?php if (is_array($cart_info['shipping_methods'])): ?>
                                    <?php foreach ($cart_info['shipping_methods'] as $shipping): ?>   
                                        <p >    
                                            <label rel="<?= $shipping['id'] ?>" buid="<?= $bdmin_user_id ?>"  class="shipping_method_select check-btn <?= $shipping['selected'] ? 'checked' : '' ?>"  title="">
                                                <?= $shipping['label'] ?>：<?= Yii::$service->page->translate->__('Shipping Cost');?> <?=  $currency_info['symbol'];  ?><?= $shipping['current_cost'] ?>
                                            </label>
                                        </p>
                                        <?php  if ($shipping['selected']): ?>
                                            <input  type="hidden" class="shipping_method shipping_method_<?= $bdmin_user_id ?>" name="editForm[shipping_method][<?= $bdmin_user_id ?>]" value="<?= $shipping['id'] ?>"  />
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <div style="clear:both"></div>    
                            <p class="default-line" style="    line-height: 23px; color: #888; font-size: 12px;">
                                <?= Yii::$service->page->translate->__('Note: The delivery will delay your delivery due to irresistible objective factors such as weather, traffic, etc. Please know.');?>
                            </p>
                        </div>
                        
                        
                        
                        <div style="clear:both"></div>
                    </div>
                    <div style="clear:both"></div>
                <?php endforeach; ?>
                
                <div style="clear:both;"></div>
                
                <div class="balance-wrap">
                    <ul id="balance-detail" class="balance-detail">
                        <li class="promotion-item">
                                <span class="total-num">商品总数：<i><?= $all_count  ?></i><?= Yii::$service->page->translate->__('items') ?></span>
                            <span class="promotion-name"><?= Yii::$service->page->translate->__('Goods Subtotal') ?>：</span>
                            <em class="promotion-val">+ <?=  $currency_info['symbol'];  ?><?= Format::price($all_product_total); ?></em>
                        </li>
                        <li class="promotion-item">
                            <span class="promotion-name"><?= Yii::$service->page->translate->__('Shipping Cost') ?>：</span>
                            <em class="promotion-val">+ <?=  $currency_info['symbol'];  ?><?= Format::price($all_shipping_cost); ?></em>
                        </li>
                        <li class="promotion-item">
                            <span class="promotion-name"><?= Yii::$service->page->translate->__('Discount') ?>：</span>
                            <em class="promotion-val">- <?=  $currency_info['symbol'];  ?><?= Format::price($all_discount); ?></em>
                        </li>
                    </ul>
                    <p id="delivery-detail" class="delivery-detail">
                        <?= Yii::$service->page->translate->__('Sent To') ?>：<?= $add_info ?><br>
                        <?= Yii::$service->page->translate->__('Receiver Person') ?>：<?= $add_person ?>&nbsp;&nbsp;&nbsp;&nbsp;<?= $add_tel ?></p>
                </div>
                
                <div class="sum-wrap">
                    <?= Yii::$service->page->translate->__('Grand Total') ?>：
                    <span id="order-price" class="price" data-price="9885">
                        <?=  $currency_info['symbol'];  ?><?= Format::price($all_total) ?>
                    </span>
                    <button id="order-submit"><?= Yii::$service->page->translate->__('Place Order') ?></button>
                </div>
            <?php endif; ?>
        </form>
</div>


<div class="body-mask confirm-delete-address" style="height: 2330px; width: 1903px;display:none;" ></div>
<div class="yoho-dialog confirm-dialog confirm-delete-address" style="margin-top: -114px; margin-left: -200px;display:none;">
    <span class="close">
        <i class="iconfont"></i>
    </span>
    <div class="content">
        <p class="main"><?= Yii::$service->page->translate->__('Delete Address') ?></p>
        <p class="sub"><?= Yii::$service->page->translate->__('Are you sure delete Address?') ?></p>
    </div>
    <div class="btns">
        <span id="dialog-confirm-sure" class="btn confirm-sure  confirm-delete-address-sure"><?= Yii::$service->page->translate->__('Confirm') ?></span>
        <span id="dialog-confirm-cancel" class="btn confirm-cancel"><?= Yii::$service->page->translate->__('Cancel') ?></span>
    </div>
</div>

<style>
.order-ensure .goods-wrap{
    width: 700px;
    float: right;
}

.order-ensure .delivery-way-wrap{
    width: 200px;
    float: left;
}
.order-ensure .goods-wrap{
    border-top:none;
}
.order-ensure .goods-table .name{
    width: 200px;
}
</style>


<script>
	// add to cart js	
<?php $this->beginBlock('changeCartInfo') ?>

$(document).ready(function(){
    
    var removeAddressUrl = '';
	currentUrl = "<?= Yii::$service->url->getUrl('checkout/onepage') ?>";
    changeShippingMethodUrl =  "<?= Yii::$service->url->getUrl('checkout/onepage/changeshippingmethod') ?>";
    changeCouponUrl =  "<?= Yii::$service->url->getUrl('checkout/onepage/changecoupon') ?>";
    getAddressUrl =  "<?= Yii::$service->url->getUrl('checkout/onepage/addressone') ?>";
    setDefaultAddressUrl =  "<?= Yii::$service->url->getUrl('checkout/onepage/setdefaultaddress') ?>";
    
    // edit address
    $(".modify-addr").click(function(event){
        event.stopPropagation();
        var address_id = $(this).attr('rel');
        if (address_id) {
            $.ajax({
                async:true,
                timeout: 6000,
                dataType: 'json', 
                type:'get',
                data: {
                    "address_id": address_id
                },
                url: getAddressUrl ,
                success: function(data, textStatus){ 
                    if(data.status == 'success'){
                        var address = data.address;
                        $(".ad_first_name").val(address.first_name);
                        $(".ad_street1").val(address.street1);
                        $(".ad_zip").val(address.zip);
                        $(".ad_telephone").val(address.telephone);
                        $(".ad_address_id").val(address.address_id);
                        
                        if (address.is_default == 1) {
                            //$(".address_is_default").attr('checked',true);
                            $(".setDefaultAddress").addClass("on");
                            $(".address_is_default").val("1");
                            
                        } else {
                            //$(".address_is_default").attr('checked',false);
                            $(".setDefaultAddress").removeClass("on");
                            $(".address_is_default").val("");
                        }
                        $("#distpicker3").distpicker('destroy');
                        $("#distpicker3").distpicker({
                            province: address.state,
                            city: address.city,
                            district: address.area
                        });
                        
                        $(".body-mask").show();
                        $(".ope-address-dialog").show();
                    } else {
                        alert(data.content);
                    }
                },
                error:function (XMLHttpRequest, textStatus, errorThrown){}
            });
        } else {
            $("#distpicker3").distpicker('destroy');
            $("#distpicker3").distpicker({
                province: "<?=  $address['state'] ? $address['state'] : '---- '.Yii::$service->page->translate->__('Province').' ----'  ?>",
                city: "<?=  $address['city'] ? $address['city'] : '---- '.Yii::$service->page->translate->__('City').' ----'  ?>",
                district: "<?=  $address['area'] ? $address['area'] : '---- '.Yii::$service->page->translate->__('Area').' ----'  ?>"
            });
            $(".ad_first_name").val("");
            $(".ad_street1").val("");
            $(".ad_zip").val("");
            $(".ad_telephone").val("");
            $(".ad_address_id").val("");
            $(".body-mask").show();
            $(".ope-address-dialog").show();
            $(".setDefaultAddress").removeClass("on");
            $(".address_is_default").val("");
        }
    });
    // save address
    $(".saveEditAddress").click(function(){
        $(".addressedit").submit();
    });
    // 地址弹出
    $(".more-addr-btn").click(function(){
        $(this).removeClass('act');
        $(".hide-more-btn").addClass('act');
        $(".address_area").css("height","auto");
    });
    // 地址收缩
    $(".hide-more-btn").click(function(){
        $(this).removeClass('act');
        $(".more-addr-btn").addClass('act');
        $(".address_area").css("height","210px");
    });
    // 默认地址选择
    $(".address_item").click(function(){
        if (!$(this).hasClass("addr-select")) {
            var address_id = $(this).attr("rel");
            $.ajax({
                async:true,
                timeout: 6000,
                dataType: 'json', 
                type:'get',
                data: {
                    "address_id": address_id
                },
                url: setDefaultAddressUrl ,
                success: function(data, textStatus){ 
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
    // 地址弹框关闭
    $(".ope-address-dialog .close").click(function(){
        $(".body-mask").hide();
        $(".ope-address-dialog").hide();
    });
    // 地址删除-弹出确认框
    $(".removeAddress").click(function(event){
        event.stopPropagation();
        removeAddressUrl = $(this).attr('rel');
        if (removeAddressUrl) {
            $(".confirm-delete-address").show();
        }
    });
    // 确认后，进行地址删除
    $(".confirm-delete-address-sure").click(function(){
        if (removeAddressUrl) {
            window.location.href=removeAddressUrl;
        }
    });
    $(".confirm-dialog .close").click(function(){
        $(".confirm-delete-address").hide();
    });
    // 
    $(".confirm-cancel").click(function(){
        $(".body-mask").hide();
        $(".ope-address-dialog").hide();
        $(".confirm-delete-address").hide();
    });
    
   // 地址弹框关闭
   $(".body-mask").click(function(){
        $(".body-mask").hide();
        $(".ope-address-dialog").hide();
        $(".confirm-delete-address").hide();
    });
    // 地址弹框关闭
    $(".cancelEditAddress").click(function(){
        $(".body-mask").hide();
        $(".ope-address-dialog").hide();
    });
    
    
    // coupon部分，可用和非可用切换
    $(".coupon-opt-title .usable").click(function(){
        $(this).addClass("active");
        $(this).parent().find(".unusable").removeClass("active")
        $(this).parent().parent().parent().find(".usable-wrap").removeClass("hide");
        $(this).parent().parent().parent().find(".unusable-wrap").addClass("hide");
    });
    
    $(".coupon-opt-title .unusable").click(function(){
        
        $(this).addClass("active");
        $(this).parent().find(".usable").removeClass("active");
        $(this).parent().parent().parent().find(".usable-wrap").addClass("hide");
        $(this).parent().parent().parent().find(".unusable-wrap").removeClass("hide");
    });
    
    // 切换shipping method
    $(".shipping_method_select").click(function(){
        shipping_method = $(this).attr('rel');
        buid = $(this).attr('buid');
        
        if (!$(this).hasClass("checked")) {
            $(this).parent().parent().find(".shipping_method_select").removeClass("checked");
            $(this).addClass("checked");
            str = ".shipping_method_" + buid;
            $(str).val(shipping_method);
            $(".postType").val("selectShippingMethod");
            $("#onestepcheckout-form").submit();
        }
    });
     
    // 选择优惠券
    $(".coupon-check-btn").click(function(){
        coupon_code = $(this).val();
        
        buid = $(this).attr('buid');
        
        if (!$(this).hasClass("checked")) {
            $(this).parent().find(".coupon-check-btn").removeClass("checked");
            $(this).addClass("checked");
            str = ".cart_coupon_" + buid;
            $(str).val(coupon_code);
            $(".postType").val("selectCoupon");
            $("#onestepcheckout-form").submit();
        }
        
    });
    // 防止表单重复提交
    clickCount = 0;
    $("#order-submit").click(function(e){
        e.preventDefault();
        if (clickCount == 0) {
            clickCount = 1;
            $(".postType").val("placeOrder");
            $("#onestepcheckout-form").submit();
        }
        
    });
    
    // set default address
    $(".setDefaultAddress").click(function(){
        if ($(this).hasClass("on")) {
            $(this).removeClass("on");
            $(".address_is_default").val("");
        } else {
            $(this).addClass("on");
            $(".address_is_default").val("1");
        }
    });  
    
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['changeCartInfo'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>
<style>
.order-ensure .coupon-list-wrap .list-content{min-height:140px;}
</style>