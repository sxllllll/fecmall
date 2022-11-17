<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
use fecshop\app\appfront\helper\Format;
?>

<div class="me-coupons-page me-page yoho-page clearfix">
    <p class="home-path">
        <span class="path-icon"></span>
        <a href="<?= Yii::$service->url->homeUrl(); ?>"><?= Yii::$service->page->translate->__('Home') ?></a>
        &nbsp;&nbsp;&gt;&nbsp;&nbsp;
        <span><?= Yii::$service->page->translate->__('Coupon') ?></span>
    </p>    
    <div class="home-navigation block">
        <?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>
    </div>
    <div class="me-main">
        <div class="coupons  block">
            <h2 class="title"><?= Yii::$service->page->translate->__('Coupon') ?></h2>
            <div class="coupon-tabs">
                <div class="tab-wrap">
                    
                    <ul class="tabs clearfix">
                            <li class="<?=  $activeStatus == 1 ? 'active' : ''  ?>">
                                <a href="<?= Yii::$service->url->getUrl('customer/coupon')  ?>">可用（<?= $coupon_available_count ?>）</a>
                            </li>
                            <li class="<?=  $activeStatus == 2 ? 'active' : ''  ?>">
                                <a href="<?= Yii::$service->url->getUrl('customer/coupon', ['type'=> 'used'])  ?>">已使用（<?= $coupon_used_count ?>）</a>
                            </li>
                            <li class="<?=  $activeStatus == 3 ? 'active' : ''  ?>">
                                <a href="<?= Yii::$service->url->getUrl('customer/coupon', ['type' => 'overtime'])  ?>">已失效（<?= $coupon_unavailable_count ?>）</a>
                            </li>
                    </ul>

                    <button class="code-sure-btn right exchange_coupon">
                        <?= Yii::$service->page->translate->__('Exchange'); ?>
                    </button>
                    <input type="text" class="code-input right input coupon_code" placeholder="<?= Yii::$service->page->translate->__('Please fill in the coupon code'); ?>">
                </div>
            </div>
            <div class="coupons-wrap clearfix">
                <?php if (is_array($coupons) && !empty($coupons)): ?>
                    <?php foreach ($coupons as $coupon): ?> 
                        <?php if ($activeStatus == 3): ?>
                            <div class="coupon-item coupon-item-200 over-time">
                                <div class="worth">
                                        <p><span class="price"><?= floor($coupon['discount_cost']) ?></span>
                                            <br><span class="conditions"><?= Yii::$service->page->translate->__('Over {coupon_condition} yuan to use',  ['coupon_condition' => $coupon['use_condition']]); ?></span>
                                        </p>
                                </div>
                                <div class="coupon-info">
                                    <p class="name"><span class="type type-200">[优惠券]</span> <?= $coupon['name']; ?></p>
                                    <p class="time"><?= date('Y.m.d', $coupon['active_begin_at']); ?>-<?= date('Y.m.d', $coupon['active_end_at']); ?></p>
                                        <label class="explain">
                                            限购 [ <?= $coupon['bdminUser']; ?> ] 店铺
                                            <?php  if ($coupon['condition_product_type'] == Yii::$service->coupon->condition_product_type_all): ?>
                                                全部
                                            <?php else: ?>
                                                部分
                                            <?php endif; ?>
                                        商品
                                        </label>
                                </div>
                            </div>
                        <?php elseif ($activeStatus == 2): ?>    
                            
                            <div class="coupon-item coupon-item-200 used">
                                <div class="worth">
                                        <p><span class="price"><?= floor($coupon['discount_cost']) ?></span>
                                            <br><span class="conditions"><?= Yii::$service->page->translate->__('Over {coupon_condition} yuan to use',  ['coupon_condition' => $coupon['use_condition']]); ?></span>
                                        </p>
                                </div>
                                <div class="coupon-info">
                                    <p class="name"><span class="type type-200">[优惠券]</span> <?= $coupon['name']; ?></p>
                                    <p class="time"><?= date('Y.m.d', $coupon['active_begin_at']); ?>-<?= date('Y.m.d', $coupon['active_end_at']); ?></p>
                                        <label class="explain">
                                            限购 [ <?= $coupon['bdminUser']; ?> ] 店铺
                                            <?php  if ($coupon['condition_product_type'] == Yii::$service->coupon->condition_product_type_all): ?>
                                                全部
                                            <?php else: ?>
                                                部分
                                            <?php endif; ?>
                                        商品
                                        </label>
                                </div>
                            </div>
                            
                            
                        <?php else: ?>
                        
                            <div class="coupon-item coupon-item-100">
                                <div class="worth">
                                    <p>
                                        <span class="price">
                                            <?= floor($coupon['discount_cost']) ?>
                                        </span>
                                        <br>
                                        <span class="conditions">
                                            <?= Yii::$service->page->translate->__('Over {coupon_condition} yuan to use',  ['coupon_condition' => $coupon['use_condition']]); ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="coupon-info">
                                    <p class="name"><span class="type type-100">[<?= Yii::$service->page->translate->__('Coupon') ?>]</span>
                                        <?= $coupon['name']; ?>
                                    </p>
                                    <p class="time">
                                        <?php if ($coupon['active_end_at'] > time()): ?>
                                            <?= Yii::$service->page->translate->__('Expire Date'); ?>：<?= date('Y-m-d', $coupon['active_end_at']); ?>
                                        <?php else: ?>
                                            <?= Yii::$service->page->translate->__('Expired'); ?>
                                        <?php endif; ?>
                                    </p>
                                    <p class="time">
                                        限购 [ <?= $coupon['bdminUser']; ?> ] 店铺
                                            <?php  if ($coupon['condition_product_type'] == Yii::$service->coupon->condition_product_type_all): ?>
                                                全部
                                            <?php else: ?>
                                                部分
                                            <?php endif; ?>
                                        商品
                                    
                                    </p>
                                </div>
                                    <?php if ($coupon['is_used'] != Yii::$service->coupon->customer->coupon_is_used): ?>
                                        <a style="top:10px;" class="use-now-btn"  href="<?= Yii::$service->url->getUrl('catalog/shop', ['bdmin_user_id' => $coupon['bdmin_user_id']]) ?>">
                                            立即使用 
                                        </a>
                                    <?php else: ?>
                                        <a style="top:10px;"  href="javascript:void(0)" class="use-now-btn" >
                                            <?= Yii::$service->page->translate->__('Used'); ?>
                                        </a>
                                    <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>    
                    <p class="empty-tip">暂无优惠券</p>
                <?php endif; ?>
                
                <?php if($pageToolBar): ?>
                    <div class="pageToolbar">
                        <?= $pageToolBar ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>    

<script>
	// add to cart js	
<?php $this->beginBlock('fectch_coupon') ?>
csrfName = "<?= CRequest::getCsrfName() ?>";
csrfVal = "<?= CRequest::getCsrfValue() ?>";
currentUrl = "<?= Yii::$service->url->getUrl('coupon/fetch/lists') ?>";
$(document).ready(function(){
    $(".exchange_coupon").click(function(){
		coupon_code = $(".coupon_code").val();
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
    
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['fectch_coupon'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>
  
  
<style>
.container{
    padding:3%;
    width: auto;
}
.coupons-bg,.coupons-image{
  width:100%;
  height:400rpx;
}
.coupons-input{
  background-color:#fff;
  padding:20px 20px 8px 20px;
  margin:2% 0 5%;
  top:160px;
  left:5%;
  border-radius:5px;
  border:1px solid #f8b551;
}
.input{
  border:1px solid #f8b551;
  border-radius:3px;
  padding:5px 10px;
  margin-bottom:10px;
  font-size:30rpx;
  width:70%;
  display:inline-block;
}
.primary {
  background:linear-gradient(to right,#eb7073,#f4948a);
  font-size:28rpx;
  border-radius:4px;
  color:#fff;
  text-align:center;
  width:20%;
  display:inline-block;
  float:right;
  height:33px;
  line-height:33px;
}

.coupons-list{
  width:100%;
  margin-top:120rpx;
}
.coupons-item{
  color:#fff;
  font-size:30rpx;
  background:linear-gradient(to right,#eb7073,#f4948a);
  margin-bottom:10px;
  padding-top:10px;
  border-radius:5px;
  position:relative;
}
.coupons-item-box{
  width:100%;
  height:80px;
}
.money-left{
  width: 6rem;
  font-size:60rpx;
  float:left;
  text-align:center;
  height:70px;
  line-height:70px;
}
.money-left text{
  font-size:28rpx;
  padding-left:6px;
}
.money-right{
  float:left;
  margin-top:15px;
}
.money-name{
  font-size:30rpx;
  padding-bottom:5px;
}
.money-hold{
  font-size:24rpx;
  color:rgba(255,255,255,.8);
}
.money-home{
  display:inline-block;
  float:right;
  margin:27px 0px;
  background-color:rgba(255,255,255,.8);
  color:#e65e5f;
  font-size:25rpx;
  padding:3px 10px;
  border-radius:3px;
  margin-right:10px;
}

.money-fooder{
  background-color:rgba(0,0,0,.08);
  font-size:26rpx;
  padding:8px 10px;
  color: rgba(255,255,255,.8);
  border-bottom-right-radius:5px;
  border-bottom-left-radius:5px;
}
  </style>