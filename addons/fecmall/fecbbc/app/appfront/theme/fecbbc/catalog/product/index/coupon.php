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
<?php  
    $productModel = $parentThis['productModel'];
    $coupons = Yii::$service->coupon->getProductActiveCouponList($productModel);
    $i = 0;
?>
<?php if (is_array($coupons) && !empty($coupons)): ?>
<div class="small-coupon-list">
    <ul>
        <li><a class="left title">领&nbsp;&nbsp;券：</a></li>
        <?php foreach ($coupons as $coupon):  $i++;?>
            <li class="more-coupon ">
                <div class="small coupon-item" coupon_code="<?= $coupon['code'] ?>" data-name="" data-amount="30" data-status="1" data-rule="满<?= $coupon['symbol'] ?><?= $coupon['current_use_condition'] ?>减<?= $coupon['symbol'] ?><?= $coupon['current_discount_cost'] ?>">

                    <a class="more-coupon small-desc">满<?= $coupon['symbol'] ?><?= $coupon['current_use_condition'] ?>减<?= $coupon['symbol'] ?><?= $coupon['current_discount_cost'] ?></a>
                </div>
            </li>
            <?php if ($i >=3) break; ?>
        <?php endforeach; ?>
        
        
    </ul>
</div>

<div class="body-mask" style="height: 5885px; width: 1903px;display:none;"></div>
<div class="yoho-dialog coupon-big coupon-dialog" style="display:none;margin-top: -165.5px; margin-left: -215px;">
    <span class="close"><i class="iconfont"></i></span>
    <div class="content">    
        <div class="coupon-big">
            <div class="header">优惠券</div>
            <div class="coupon-big-list">
                <div class="coupon-list">
                    <div class="header">
                        <div class="divide-line"></div>
                        <div class="title">可领取的券</div>
                    </div>
            
                    <div>
                        <?php foreach ($coupons as $coupon):?>
                            <div class="item-bg">
                                <div class="pre">优惠券</div>
                            
                                <div class="desc">
                                    <div class="amount"><?= $coupon['symbol'] ?>&nbsp;<?= (int)$coupon['current_discount_cost'] ?></div>
                                    <div class="rule">满<?= $coupon['symbol'] ?><?= (int)$coupon['current_use_condition'] ?>减<?= $coupon['symbol'] ?><?= (int)$coupon['current_discount_cost'] ?></div>
                                </div>
                            
                                <div class="status fetch_coupon" rel="<?= $coupon['id'] ?>">
                                        <div class="coupon-status">点击  </div>
                                        <div class="coupon-go">领取</div>
                                </div>
                                
                                <div style="display:none;" class="status fetched_coupon" rel="<?= $coupon['id'] ?>">
                                        <div class="coupon-status">已领取  </div>
                                        <div class="coupon-go"><a class="coupon-btn" href="<?= Yii::$service->url->getUrl('catalog/shop', ['bdmin_user_id' => $coupon['bdmin_user_id']]) ?>">去使用</a></div>
                                </div>
                            </div>
                        
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="btns"></div>
</div>


<script>
    <?php $this->beginBlock('product_coupon') ?>
    $(document).ready(function(){   // onclick="ShowDiv_1('MyDiv1','fade1')"
        $(".coupon_info").on("click", ".coupon-big .close", function(){
            $(".coupon-dialog").hide();
            $(".body-mask").hide(); 
        });
        
        $(".coupon_info").on("click", ".body-mask", function(){
            $(".coupon-dialog").hide();
            $(".body-mask").hide(); 
        });
        
        $(".coupon_info").on("click", ".more-coupon.small-desc",  function(){
        //$(".more-coupon.small-desc").click(function(){
            $(".coupon-dialog").show();
            $(".body-mask").show();
              
        });
        
        csrfName = "<?= CRequest::getCsrfName() ?>";
        csrfVal = "<?= CRequest::getCsrfValue() ?>";
        $(".coupon_info").on("click", ".fetch_coupon", function(){
        //$(".fetch_coupon").click(function(){
            self = $(this);
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
                    if(data.status == 'success' ||  data.content == '您已经领取了该优惠券' ||  data.content == 'Coupon has been claimed'){
                        self.parent().find(".fetched_coupon").show();
                        self.hide();
                    } else if(data.status == 'no_login'){
                        window.location.href="<?= Yii::$service->url->getUrl('customer/account/login', ['rl' => base64_encode(Yii::$service->url->getCurrentUrl())]) ?>";
                    } else {
                        alert(data.content);
                    }
                },
                error:function (XMLHttpRequest, textStatus, errorThrown){}
            });
        });
    
        
    });
    <?php $this->endBlock(); ?>
    <?php $this->registerJs($this->blocks['product_coupon'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>
<?php endif; ?>
