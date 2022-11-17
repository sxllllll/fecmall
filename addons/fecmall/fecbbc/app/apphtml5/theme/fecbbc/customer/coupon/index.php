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
<div class="main-wrap" id="main-wrap" >
    <header id="yoho-header" class="yoho-header boys">
        <a href="javascript:history.go(-1);" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title">
            <?= Yii::$service->page->translate->__('My Coupon'); ?>
        </p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="coupon-new-page cpage-padding284">
        
        <div class="filter-box">
            <span class="filter-btn-box customer_coupon_t">
                <span rel="<?= Yii::$service->url->getUrl('customer/coupon')  ?>" class="filter-btn no-used <?=  $activeStatus == 1 ? 'active' : ''  ?>" >可用（<?= $coupon_available_count ?>）</span>
            </span>
            <span class="filter-btn-box customer_coupon_t">
                <span rel="<?= Yii::$service->url->getUrl('customer/coupon', ['type'=> 'used'])  ?>" class="filter-btn used <?=  $activeStatus == 2 ? 'active' : ''  ?>" >已使用（<?= $coupon_used_count ?>）</span>
            </span>
            <span class="filter-btn-box customer_coupon_t">
                <span rel="<?= Yii::$service->url->getUrl('customer/coupon', ['type' => 'overtime'])  ?>" class="filter-btn invalid <?=  $activeStatus == 3 ? 'active' : ''  ?>" >已失效（<?= $coupon_unavailable_count ?>）</span>
            </span>
        </div>
        
        <div class="exchange-box">
            <input type="text" name="couponCodeInput" class="couponCodeInput" placeholder="请输入优惠券码">
            <button id="exchangeCouponBtn" class="exchange-coupon-btn">兑换</button>
        </div>
        
        <?php if (is_array($coupons) && !empty($coupons)): ?>
            <div class="coupon-list" id="couponList">
                <?php foreach ($coupons as $coupon): ?> 
                    <?php if ($activeStatus == 3): ?>
                        <section class="coupon-section" data-code="yq7cg4t74a21vn" data-id="642371">
                            <div class="coupon coupon-overtime">
                                <div class="coupon-left">
                                    <p class="value"><span><?= floor($coupon['discount_cost']) ?></span></p>
                                    <p class="threshold">满<?= floor($coupon['use_condition']) ?>元可用</p>
                                </div>
                                <div class="coupon-right">
                                    <p class="title">
                                        <span class="type-activity">[优惠券]</span> <?= $coupon['name']; ?>
                                    </p>
                                    <p class="time"><?= date('Y.m.d', $coupon['active_begin_at']); ?> - <?= date('Y.m.d', $coupon['active_end_at']); ?></p>
                                    
                                    <span class="tip"></span>
                                </div>
                                    <div class="stamp overtime-stamp"></div>
                            </div>
                            
                        </section>
                    <?php elseif ($activeStatus == 2): ?>
                        
                        <section class="coupon-section" data-code="yq7u2d874a9p48" data-id="644489">
                            <div class="coupon coupon-used">
                                <div class="coupon-left">
                                    <p class="value"><span><?= floor($coupon['discount_cost']) ?></span></p>
                                    <p class="threshold">满<?= floor($coupon['use_condition']) ?>元可用</p>
                                </div>
                                <div class="coupon-right">
                                    <p class="title">
                                        <span class="type-activity">[优惠券]</span> <?= $coupon['name']; ?>
                                    </p>
                                    <p class="time"><?= date('Y.m.d', $coupon['active_begin_at']); ?> - <?= date('Y.m.d', $coupon['active_end_at']); ?></p>
                                    
                                    <span class="tip"></span>
                                </div>
                                        <div class="stamp used-stamp"></div>
                            </div>
                            
                        </section>
                    
                    
                    <?php else: ?>
                        <section class="coupon-section" >
                            <div class="coupon">
                                <div class="coupon-left coupon-left-shop">
                                    <p class="value"><span><?= floor($coupon['discount_cost']) ?></span></p>
                                    <p class="threshold">满<?= floor($coupon['use_condition']) ?>元可用</p>
                                </div>
                                <div class="coupon-right">
                                    <p class="title">
                                        <span class="type-shop">[优惠券]</span> <?= $coupon['name']; ?>
                                    </p>
                                    <p class="time">
                                        <?= date('Y.m.d', $coupon['active_begin_at']); ?> - <?= date('Y.m.d', $coupon['active_end_at']); ?>
                                    </p>
                                    
                                    <p class="time" style="top:80%">
                                        限购 [ <?= $coupon['bdminUser']; ?> ] 店铺
                                            <?php  if ($coupon['condition_product_type'] == Yii::$service->coupon->condition_product_type_all): ?>
                                                全部
                                            <?php else: ?>
                                                部分
                                            <?php endif; ?>
                                        商品
                                    </p>
                                </div>
                                <a href="<?= Yii::$service->url->getUrl('catalog/shop', ['bdmin_user_id' => $coupon['bdmin_user_id']]) ?>" class="use-now" style="top:1rem;">立即使用</a>
                            </div>
                        </section>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
         <?php else: ?>   
            <div class="no-conpon-now">
                <div class="icon-not"></div>
                <p>暂无优惠券</p>
            </div>

        <?php endif; ?>
        
        
    </div>
</div>  


<script>
	// add to cart js	
<?php $this->beginBlock('fectch_coupon') ?>
csrfName = "<?= CRequest::getCsrfName() ?>";
csrfVal = "<?= CRequest::getCsrfValue() ?>";
currentUrl = "<?= Yii::$service->url->getUrl('coupon/fetch/lists') ?>";
$(document).ready(function(){
    $("#exchangeCouponBtn").click(function(){
		coupon_code = $(".couponCodeInput").val();
        if (!coupon_code) {
            alert('请填写兑换码');
        } else {
            $data = {
                coupon_code: coupon_code,
            };
            $data[csrfName] = csrfVal;
            exchangeCouponUrl = "<?= Yii::$service->url->getUrl('coupon/fetch/customerexchange'); ?>";
            $.ajax({
                async:true,
                timeout: 6000,
                dataType: 'json', 
                type: 'post',
                data: $data,
                url: exchangeCouponUrl,
                success:function(data, textStatus){ 
                    if(data.status == 'success'){
                        window.location.href="<?= Yii::$service->url->getUrl('customer/coupon') ?>";
                    } else if(data.status == 'no_login'){
                        window.location.href="<?= Yii::$service->url->getUrl('customer/account/login') ?>";
                    } else {
                        alert('优惠卷兑换失败');
                    }
                },
                error:function (XMLHttpRequest, textStatus, errorThrown){}
            });
        }
		
	});
    $(".customer_coupon_t .filter-btn").click(function(){
        url = $(this).attr("rel");
        window.location.href = url;
    });
    
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['fectch_coupon'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>
  
  
<style>
.threshold{font-size:0.5rem;}
</style>