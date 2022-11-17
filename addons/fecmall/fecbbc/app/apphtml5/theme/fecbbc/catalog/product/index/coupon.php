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
    <div class="brand-coupon fetchCoupon">
        <i class="iconfont font-right pull-right"></i>
        <i class="promotion-icon">券</i><span>领取优惠券</span>
    </div>
    <div class="coupon-drawer close">
        <div class="coupon-drawer-dialog">
            <div class="title">领取优惠券</div>
            <div class="body">
                <ul class="coupon-list">
                    <?php foreach ($coupons as $coupon):?>
                        <li class="coupon" data-coupon="642761">
                            <div class="pull-right">
                                <button type="button" class="coupon-btn coupon-btn-valid fetch_coupon" rel="<?= $coupon['id'] ?>">立刻领取</button>
                            </div>
                            <div class="coupon-intro">
                                <div class="coupon-price"><?= $coupon['symbol'] ?>&nbsp;<?= (int)$coupon['current_discount_cost'] ?></div>
                                <div class="coupon-desc">【<?= $coupon['name'] ?>】满<?= (int)$coupon['current_use_condition'] ?>减<?= (int)$coupon['current_discount_cost'] ?></div>
                                <div class="coupon-time">使用期限: <?= date('Y.m.d', $coupon['assign_begin_at']) ?>-<?= date('Y.m.d', $coupon['assign_end_at']) ?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    
                </ul>
            </div>
        </div>
        <div class="coupon-drawer-mask"></div>
    </div>
    
    <div id="yoho-tip" class="yoho-tip"   style="display: none;"></div>
    
    <script>
        <?php $this->beginBlock('product_coupon') ?>
        $(document).ready(function(){   // onclick="ShowDiv_1('MyDiv1','fade1')"
            $(".coupon-drawer-mask").click(function(){
                $(".coupon-drawer").removeClass("open");
                $(".coupon-drawer").addClass("close");
            });
            
            $(".fetchCoupon").click(function(){
                $(".coupon-drawer").removeClass("close");
                $(".coupon-drawer").addClass("open");
                  
            });
            
            csrfName = "<?= CRequest::getCsrfName() ?>";
            csrfVal = "<?= CRequest::getCsrfValue() ?>";
            $(".fetch_coupon").click(function(){
                if (!$(this).hasClass("coupon-btn-valid")) {
                    return ;
                }
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
                            self.removeClass("coupon-btn-valid");
                            self.html("已领取");
                        } else if(data.status == 'no_login'){
                            // window.location.href="<?= Yii::$service->url->getUrl('customer/account/login') ?>";
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