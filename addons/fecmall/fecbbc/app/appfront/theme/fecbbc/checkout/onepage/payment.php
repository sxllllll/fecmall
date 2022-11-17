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


<div class="shopping-pay-page yoho-page clearfix">
    <div class="pay-page">
        <div class="pay-title">
            <div class="step4"></div>
            <ul>
                <li><span><?= Yii::$service->page->translate->__('View Cart');?></span></li>
                <li><span><?= Yii::$service->page->translate->__('Fill Order');?></span></li>
                <li class="end"><span><?= Yii::$service->page->translate->__('Pay, Complate Shopping');?></span></li>
            </ul>
        </div>
            <div class="cart-pay">
                <h2><?= Yii::$service->page->translate->__('Your order has been successful, go pay now~');?></h2>
                <h3>
                    <?= Yii::$service->page->translate->__('Your Order No');?>：
                    <strong class="order-num"><?= $trade_no; ?></strong>
                    <?= Yii::$service->page->translate->__('Pay Amount');?> ：
                    <strong><?=  $currency_symbol;  ?><?= Format::price($order_all_total) ?></strong> &nbsp; &nbsp;
                </h3>
                <h4 class="js-time" data-time="3600">
                    <?= Yii::$service->page->translate->__('If you are unable to complete the payment within {datetime} minutes, your order will be cancelled.', ['datetime' =>  '<strong class="js-timer" id="js-timer" style="color: #e8044f;">'.$l_minute.Yii::$service->page->translate->__('minute').$l_second.Yii::$service->page->translate->__('second').'</strong>']);?>
                </h4>
            </div>
        <div class="wrapper">
            <div class="pay-way">
                <span class="word"><?= Yii::$service->page->translate->__('Use');?>：</span>
                <span class="selected_payment"><?= Yii::$service->page->translate->__('Alipay');?></span>
            </div>
            
            <div id="tab-box">
                <ul class="tabs" id="tabs">
                        <li class="active"><?= Yii::$service->page->translate->__('Pay Platform');?></li>
                </ul>
                <ul class="tab-conbox">
                    <?php   if(is_array($payments) && !empty($payments)):   ?>
                    <li class="tab-con">
                        <?php if (isset($payments['alipay_standard']) ): ?>
                            <div class="mode active" rel="alipay_standard" data-name="<?= Yii::$service->page->translate->__('Alipay');?>">
                                <span class="choosed-tag"></span>
                                <img  src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/font/zhifubao.png')  ?>" alt="<?= Yii::$service->page->translate->__('Alipay');?>">
                            </div>
                        <?php endif; ?>
                        <?php foreach($payments as $payment_key => $payment): ?>
                            <?php if ($payment_key == 'alipay_standard'):  continue;?>
                            <?php elseif ($payment_key == 'wxpay_standard'): ?>
                                <div class="mode " rel="<?= $payment_key ?>" data-name="<?= Yii::$service->page->translate->__('Wxpay');?>">
                                    <span class="choosed-tag"></span>
                                    <img  src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/font/weixinzhifu.png')  ?>" alt="<?= Yii::$service->page->translate->__('Wxpay');?>">
                                </div>
                            <?php elseif ($payment_key == 'paypal_standard'): ?>
                                <div class="mode " rel="<?= $payment_key ?>" data-name="Paypal">
                                    <span class="choosed-tag"></span>
                                    <img style="height:42px;"  src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/font/paypal.png')  ?>" alt="Paypal">
                                </div>
                            <?php else: ?>
                                <div class="mode " rel="<?= $payment_key ?>" data-name="<?= $payment['label'] ?>">
                                    <span class="choosed-tag"></span>
                                    <?= $payment['label'] ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div>
                <form action="<?= Yii::$service->url->getUrl('checkout/onepage/payment'); ?>" method="post" id="onestepcheckout-form">
                    <?= CRequest::getCsrfInputHtml(); ?> 
                    <input type="hidden"  name="editForm[order_increment_id]"  value="<?= $param_increment_id; ?>"  />
                        
                    <input type="hidden" class="payment_method"  value=""   name="editForm[payment_method]" />
                    
                    <input type="button" class="btnby "  value="<?= Yii::$service->page->translate->__('Pay By Alipay');?>">
                </form>
            </div>
        </div>

        <div class="light-box">
            <div class="opacity" id="fade"></div>
            <div class="content" id="light">
                <h2>请您在新打开的页面上完成付款</h2>
                <div class="notice">
                    <p>付款完成前请不要关闭此窗口</p>
                    <p>完成付款后请根据您的情况点击下面的按钮</p>
                </div>
                <div class="btns">
                    <a href="javascript:void(0);" data-url="" class="over">已完成付款</a>
                    <a href="#" class="change">更换支付方式</a>
                </div>
                <a href="#" class="close">x</a>
            </div>
        </div>
    </div>
</div>  
    
<style>
.shopping-pay-page {
    margin: 25px auto;
}
</style> 
    
<script>
	// add to cart js	
<?php $this->beginBlock('cart_address') ?>   
function TimeDown(id, endDateStr) {
    //结束时间
    var endDate = new Date(endDateStr);
    //当前时间
    var nowDate = new Date();
    if (endDate - nowDate < 0) {
        document.getElementById(id).innerHTML =  "0<?= Yii::$service->page->translate->__('minute') ?>0<?= Yii::$service->page->translate->__('second') ?>";
    } else {
        //相差的总秒数
        var totalSeconds = parseInt((endDate - nowDate) / 1000);
        //天数
        var days = Math.floor(totalSeconds / (60 * 60 * 24));
        //取模（余数）
        var modulo = totalSeconds % (60 * 60 * 24);
        //小时数
        var hours = Math.floor(modulo / (60 * 60));
        modulo = modulo % (60 * 60);
        //分钟
        var minutes = Math.floor(modulo / 60);
        //秒
        var seconds = modulo % 60;
        //输出到页面
        document.getElementById(id).innerHTML =  minutes + "<?= Yii::$service->page->translate->__('minute') ?>" + seconds + "<?= Yii::$service->page->translate->__('second') ?>";
        //延迟一秒执行自己
        setTimeout(function () {
            TimeDown(id, endDateStr);
        }, 1000)
    }
    
}

var endDateStr = "<?= $endDataTime ?>";
TimeDown("js-timer", endDateStr);


$(document).ready(function(){    

    $("#tab-box ul li  .mode").click(function(){
        $("#tab-box ul li  .mode").removeClass("active");
        $(this).addClass("active");
        $label = $(this).attr("data-name");
        $(".selected_payment").html($label);
        
        $(".btnby").val("<?= Yii::$service->page->translate->__('Pay By ');?>"+$label);
    });

    $(".btnby").click(function(){
        payment_method = $("#tab-box ul li  .mode.active").attr("rel");
        if (payment_method) {
            $(".payment_method").val(payment_method);
            $("#onestepcheckout-form").submit();
        }
        
    });
    
    
});    
    
    
<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['cart_address'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>

