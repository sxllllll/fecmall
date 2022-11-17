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
        <p class="nav-title"><?= Yii::$service->page->translate->__('Register Gift');?></p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="contaniner fixed-contb" >
        <section class="detail">
            <img  src="<?= Yii::$service->image->getImgUrl('addons/fectb/register_coupon_gift.png')  ?>" style="width:100%" />
        
            <div class="coupons-pic" >
                <img src="<?= Yii::$service->image->getImgUrl('addons/fectb/coupons-pic.jpg')  ?>" style="width:80%" />
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
        </section>
    </div>
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
    width: 100%;
    text-align: center;
    top: 16rem;
}
.coupons-pic image{
	width: 10rem;
	height: 10rem;
	box-shadow: 0 2rpx 5rpx #731807;
}
.coupons-pic .text{
	position: absolute;
    top: 5.5rem;
    z-index: 2;
    width: 100%;
    text-align: center;
    font-size: .6rem;
    color: #dc3b0d;
    left: 0;
}
.coupons-pic .text .number{
	margin-bottom: 0.5rem;
}
.coupons-pic .text .number .money{
	font-size: 1rem;
    font-weight: bold;
    margin-right: .2rem;
}
.coupons-pic .text .number .unit{
	font-size: 1rem;
}
.coupons-pic .text .date{
	margin-bottom: 0.5rem;
font-size: 0.6rem;
color:
#A18D65;
margin-top: 0.2rem;
}
.coupons-pic .text .condition{
	font-size: 0.7rem;
}
.coupons-pic .text button{
	background:
    #dc3b0d;
    width: 5rem;
    text-align: center;
    height: 1.2rem;
    color:
    #fff;
    line-height: 1.2rem;
    border-radius: 0.5rem;
    font-size: 0.7rem;
}


</style>
