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
<div class="main-wrap" id="main-wrap">
    <header id="yoho-header" class="yoho-header boys">
        <a href="<?= Yii::$service->url->getUrl('customer/account') ?>" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title">
            <?= Yii::$service->page->translate->__('Goods Return'); ?>
        </p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="order-detail-page yoho-page">
        
        <div class="info-table">
            <div class="table-item">
                <?= Yii::$service->page->translate->__('Order No'); ?>：<?= $orderInfo['increment_id'] ?>
            </div>
            <div class="table-item">
                <?= Yii::$service->page->translate->__('Order Date'); ?>：<?=  date('Y-m-d H:i:s',$orderInfo['created_at']); ?>
            </div>
            <a href="javascript:void" target="_blank" class="iconfont">&#xe63c;</a>
        </div>
                
        <div id="order-detail" data-id="57684909141">
            
            <section class="goods block">
                <?php if(is_array($orderInfo['products']) && !empty($orderInfo['products'])):  ?>
                    <?php foreach($orderInfo['products'] as $product): ?>
                    <div class="order-good" >
                        <div class="thumb-wrap">
                            <div class="pic-c">
                                <a href="<?=  Yii::$service->url->getUrl($product['redirect_url']) ; ?>">
                                    <img class="thumb" src="<?= Yii::$service->product->image->getResize($product['image'],[150,180],false) ?>">
                                </a>
                            </div>
                           <p class="tag"></p>
                        </div>
                        <div class="deps">
                            <p class="name row">
                                <?= $product['name'] ?>
                            </p>
                            <p class="row">
                                <?php if (is_array($product['spu_options']) && !empty($product['spu_options'])): ?>
                                    <?php foreach ($product['spu_options'] as $attr => $val): ?>
                                        <span class="">
                                        <?= Yii::$service->page->translate->__($attr) ?>：<?= Yii::$service->page->translate->__($val) ?>
                                        </span>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </p>
                            <p class="row price-wrap">
                                <span class="price">
                                    <?= $orderInfo['currency_symbol'] ?>
                                    <?= $product['price'] ?>
                                </span>
                                <span class="count">
                                    ×<?= $product['qty'] ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>

            <form action="<?= Yii::$service->url->getUrl('customer/order/returnsubmit'); ?>" method="post" id="returnsubmit-form">
                <?= CRequest::getCsrfInputHtml(); ?>
                
                <div class="info-table">
                    <div class="table-item">
                        <?= Yii::$service->page->translate->__('Return Qty'); ?>：
                        <input name="editForm[return_qty]" class="return_qty" type="text" style="width:100px;padding-left:0.2rem"  value="<?= $product['qty'] ?>" />
                    </div>
                    <input type="hidden" name="editForm[item_id]" value="<?= $product['item_id'] ?>"   />
                    <div class="table-item" style="margin-top:1rem">
                        <?= Yii::$service->page->translate->__('Return Submit'); ?>：
                        <a rel="<?= $product['item_id'] ?>" class="return_submit" href="javascript:void(0)" style="background: #3a3a3a; color: #eee;padding: 5px 10px;cursor: pointer;">
                            <?= Yii::$service->page->translate->__('Return Submit'); ?>
                        </a>
                    </div>
                </div>
            </form>  
        </div>
    </div>
</div>


<script>	
<?php $this->beginBlock('customer_return_submit') ?>

$(document).ready(function(){
	submitReturnUrl = "<?= Yii::$service->url->getUrl('customer/order/returnsubmit') ?>";
	$(".return_submit").click(function(){
		return_qty = $(".return_qty").val();
        if (return_qty) {
            $("#returnsubmit-form").submit();
        }
	});
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['customer_return_submit'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>


