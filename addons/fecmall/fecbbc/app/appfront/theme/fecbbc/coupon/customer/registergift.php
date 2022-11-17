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


<div class="contaniner fixed-contb" style="margin-top:11px;">
    <section class="detail">
        <div style="width: 1200px;  margin: 0 auto; position: relative;">
            <img  src="<?= Yii::$service->image->getImgUrl('addons/fectb/register_coupon_gift.png')  ?>" style="width:100%" />
        
            <div class="coupons-pic" >
                <img src="<?= Yii::$service->image->getImgUrl('addons/fectb/coupons-pic.jpg')  ?>" style="width:100%" />
                <div class="text">
                    <div class="number">
                        <?php if ($coupon['type'] == Yii::$service->coupon->coupon_type_direct): ?>
                            <text class="money"><?= $coupon['discount'] ?></text>
                            <text class="unit"><?= Yii::$service->page->translate->__('yuan'); ?></text>
                        <?php elseif ($coupon['type'] == Yii::$service->coupon->coupon_type_percent): ?>
                            <text class="money"><?= (100 - $coupon['discount']) / 10 ?></text>
                            <text class="unit"><?= Yii::$service->page->translate->__('Off'); ?></text>
                        <?php endif; ?>
                    </div>
                    <div class="condition" ><?= Yii::$service->page->translate->__('Over {coupon_condition} yuan to use', ['coupon_condition' => $coupon['condition']]); ?></div>
                    <div class="date" ><?= Yii::$service->page->translate->__('Get valid within {active_days} days', ['active_days' => $active_days]); ?></div>
                    <?php if ($is_received): ?>
                        <button ><?= Yii::$service->page->translate->__('Already Got'); ?></button>
                    <?php else: ?>
                        <button class="fetch_coupon" rel="<?= $coupon['coupon_id']; ?>"><?= Yii::$service->page->translate->__('To Get'); ?></button>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </section>
</div>


<script>
	// add to cart js	
<?php $this->beginBlock('fectch_coupon') ?>
csrfName = "<?= CRequest::getCsrfName() ?>";
csrfVal = "<?= CRequest::getCsrfValue() ?>";
$(document).ready(function(){
	$(".fetch_coupon").click(function(){
		$coupon_id = $(this).attr("rel");
		$data = {
			coupon_id:$coupon_id,
            type: "register_gift_coupon"
		};
		$data[csrfName] = csrfVal;
        fetchCouponUrl = "<?= Yii::$service->url->getUrl('coupon/fetch/customer'); ?>";
		$.ajax({
			async:true,
			timeout: 6000,
			dataType: 'json', 
			type: 'post',
			data: $data,
			url: fetchCouponUrl,
			success:function(data, textStatus){ 
                if(data.status == 'success'){
                    window.location.href="<?= Yii::$service->url->getUrl('customer/coupon') ?>";
                } else if(data.status == 'no_login'){
                    window.location.href="<?= Yii::$service->url->getUrl('customer/account/login') ?>";
                } else {
                    alert('新人礼包优惠券领取失败');
                }   
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
	});
    
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['fectch_coupon'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>

<style>

/* pages/freshman/freshman.wxss */
.page-bg{
	width: 750rpx;
	position: absolute;
	top: 0;
	left: 0;
}
.page-bg image{
	width: 100%;
}
.coupons-pic{
	position: absolute;
    width: 420px;
    text-align: center;
    top: 660px;
    left: 400px;
}
.coupons-pic image{
	width: 10rem;
	height: 10rem;
	box-shadow: 0 2rpx 5rpx #731807;
}
.coupons-pic .text{
	position: absolute;
    top: 200px;
    z-index: 2;
    width: 100%;
    text-align: center;
    font-size: 1rem;
    color: #dc3b0d;
    left: 0;
}
.coupons-pic .text .number{
	margin-bottom: 1rem;
}
.coupons-pic .text .number .money{
	font-size: 2rem;
    font-weight: bold;
    margin-right: .2rem;
}
.coupons-pic .text .number .unit{
	font-size: 1rem;
}
.coupons-pic .text .date{
	margin-bottom: 1rem;
	font-size: 1rem;
	color: #A18D65;
    margin-top: 0.4rem;
}
.coupons-pic .text .condition{
	font-size: 1.1rem;
}
.coupons-pic .text button{
	background: #dc3b0d;
    width: 7rem;
    text-align: center;
    height: 2rem;
    color: #fff;
    line-height: 2rem;
    border-radius: 1rem;
    font-size: 1rem;
}


</style>
