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
?>

<script type="text/javascript">
//调用微信JS api 支付
function jsApiCall()
{
    WeixinJSBridge.invoke(
        'getBrandWCPayRequest',
        <?php echo $jsApiParameters; ?>,
        function(res){
            WeixinJSBridge.log(res.err_msg);
            //alert("WeixinJSBridge22:" + res.err_code+ "##"+res.err_desc+ "##"+res.err_msg);
            if (res.err_msg == "get_brand_wcpay_request:ok") {
                window.location.href="<?= $success_url; ?>";
            } else if (res.err_msg == "get_brand_wcpay_request:cancel") {
                
            } else if (res.err_msg == "get_brand_wcpay_request:fail") {
                
            }
        }
    );
}

function callpay()
{
    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
            document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
        }
    }else{
        jsApiCall();
    }
}
</script>
<script type="text/javascript">
//获取共享地址
function editAddress()
{
    WeixinJSBridge.invoke(
        'editAddress',
        <?php echo $editAddress; ?>,
        function(res){
            var value1 = res.proviceFirstStageName;
            var value2 = res.addressCitySecondStageName;
            var value3 = res.addressCountiesThirdStageName;
            var value4 = res.addressDetailInfo;
            var tel = res.telNumber;
            
            //alert("editAddress:" + value1 + value2 + value3 + value4 + ":" + tel);
        }
    );
}

window.onload = function(){
    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', editAddress, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', editAddress); 
            document.attachEvent('onWeixinJSBridgeReady', editAddress);
        }
    }else{
        editAddress();
    }
    callpay();
};

</script>
<div class="main-wrap" id="main-wrap" >
    <header id="yoho-header" class="yoho-header boys">
        <a href="<?= Yii::$service->url->getUrl('customer/order') ?>" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title"><?= Yii::$service->page->translate->__('微信支付');?></p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="pay-page yoho-page">   
        <div style="margin: 1rem 1rem 2rem;">
            <font >
                <b style="display:inline-block;width:3rem">交易号:</b>
                <span style=""><?= $out_trade_no ?></span>
            </font>
            <br/>
            <br/>
            <br/>
            <font >
                <b style="display:inline-block;width:3rem">支付金额：</b>
                <b><span style=""><?= $total_amount ?></span></b>
            </font>
            <br/>
            <br/>
        </div>
        <button class="repay" type="button" onclick="callpay()" >立即支付</button>
    </div>
</div>    

<style>
.repay{
    background-color: #444 !important;
    width: 13.5rem;
    height: 1.75rem;
    border-radius: .1rem;
    background-color: #b0b0b0;
    font-size: .8rem;
    color: #fff;
    margin: 1rem 1rem 2rem;
}
</style>
