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
        <p class="nav-title"><?= Yii::$service->page->translate->__('Get Coupon');?></p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="contaniner fixed-cont container">
      <div class="coupons-bg" >
        <div class="coupons-input">
          <input class="input coupon_code" type="coupons" placeholder="<?= Yii::$service->page->translate->__('Please fill in the coupon code'); ?>"  />
          <div class="primary exchange_coupon" ><?= Yii::$service->page->translate->__('Exchange '); ?></div>
        </div>
      </div>
        <div class="coupons-list">
            <?php if (is_array($coupons)): ?>
                <?php foreach ($coupons as $coupon): ?>
                    <div class="coupons-item" >
                      <div class="coupons-item-box">
                        <div class="money-left">
                            <?= $coupon['baseSymbol'] ?> <?= $coupon['discount_cost'] ?>
                        </div>
                        <div class="money-right">
                          <div class="money-name"><?= $coupon['name']; ?></div>
                          <div class="money-hold"><?= Yii::$service->page->translate->__('Over {coupon_condition} yuan to use', ['coupon_condition' => $coupon['baseSymbol'].' '.$coupon['use_condition'] ]); ?></div>
                        </div>
                        
                        <?php if ($coupon['fetched']): ?>
                            <div class="money-home">
                                <a style="color:#e65e5f" href="<?= Yii::$service->url->getUrl('catalog/shop', ['bdmin_user_id' => $coupon['bdmin_user_id']]) ?>">
                                        立即使用 
                                    </a>
                            </div>
                        <?php else: ?>
                            <div class="money-home fetch_coupon" rel="<?= $coupon['id']; ?>"><?= Yii::$service->page->translate->__('To Get'); ?></div>
                        <?php endif; ?>
                        
                        <div style="clear:both"></div>
                      </div>
                        
                        <div class="money-line"></div>
                        <div class="money-fooder" >
                            
                            
                            <div style="    float: right;  margin-right: 20px; font-size: 0.4rem;  line-height: 0.7rem;">限购 
                                <a style="color:#fff;font-weight:700" href="<?= Yii::$service->url->getUrl('catalog/shop', ['bdmin_user_id' => $coupon['bdmin_user_id']]) ?>">
                                    [ <?= $coupon['bdminUser']; ?> ] 
                                </a>
                                店铺
                                    <?php  if ($coupon['condition_product_type'] == Yii::$service->coupon->condition_product_type_all): ?>
                                        全部
                                    <?php else: ?>
                                        部分
                                    <?php endif; ?>
                                商品
                            </div>
                            <div style="clear:both;"></div>
                        </div>
                        
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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
	$(".fetch_coupon").click(function(){
		$coupon_id = $(this).attr("rel");
		$data = {
			coupon_id:$coupon_id,
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
					window.location.href=currentUrl;
                } else if(data.status == 'no_login'){
					window.location.href="<?= Yii::$service->url->getUrl('customer/account/login') ?>";
				} else {
                    alert(data.content);
                }
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
	});
    
    $(".exchange_coupon").click(function(){
		coupon_code = $(".coupon_code").val();
        if (!coupon_code) {
            alert('请填写兑换码');
        } else {
            $data = {
                coupon_code: coupon_code
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
                        alert('<?= Yii::$service->page->translate->__('Coupon redemption failed'); ?>');
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
}
.money-left{
  width: 5rem;
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