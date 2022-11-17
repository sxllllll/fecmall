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
        <a href="<?= Yii::$service->url->getUrl('customer/order') ?>" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title">
            <?= Yii::$service->page->translate->__('Goods Return'); ?>
        </p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="order-detail-page yoho-page">  
        <div class="info-table">
            <div class="table-item">
                退货状态：<?= $after_sale['status'] ?>
            </div>
            <div class="table-item">
                <a href="<?= Yii::$service->url->getUrl('customer/order/returncancel', ['as_id' => $after_sale['id']]) ?>" style="color:#666777;margin-top:10px;display: block;">
                    <?= Yii::$service->page->translate->__('Cancel Return') ?>
                </a>
            </div>
            <a href="javascript:void" target="_blank" class="iconfont">&#xe63c;</a>
        </div>
        
        <div id="order-detail">
            <section class="goods block">
                <div class="order-good" >
                    <div class="thumb-wrap">
                        <div class="pic-c">
                            <a href="#">
                                <img class="thumb" src="<?= Yii::$service->product->image->getResize($after_sale['image'],[150,150],false) ?>">
                            </a>
                        </div>
                       <p class="tag"></p>
                    </div>
                    <div class="deps">
                        <p class="name row">
                            <?= $after_sale['sku'] ?>
                        </p>
                        
                        <p class="row price-wrap">
                            <span class="price">
                                <?= $after_sale['currency_symbol'] ?><?= $after_sale['price'] ?>
                            </span>
                            <span class="count">
                                ×<?= $after_sale['qty'] ?>
                            </span>
                        </p>
                    </div>
                </div>
                    
            </section>

            
            
            <?php if ($after_sale['show_shipping']): ?>
                <form action="<?= Yii::$service->url->getUrl('customer/order/returndispatch'); ?>" method="post" id="returnsubmit-form">
                    <?= CRequest::getCsrfInputHtml(); ?>
                    <input type="hidden" name="editForm[as_id]" value="<?= $after_sale['id'] ?>"   />
                    <div class="info-table">
                        <div class="table-item">
                            <span style="width:4rem;display:inline-block;">物流公司</span>：
                                <select name="editForm[tracking_company]" class="tracking_company" style="width: 8rem;">
                                    <?= $companySelectOptions ?>
                                </select>
                        </div>
                        <div class="table-item" style="margin:0.5rem 0;">
                           <span style="width:4rem;display:inline-block;">物流追踪号</span>：
                           <input name="editForm[tracking_number]" class="tracking_number" type="text" style="width:8rem;margin-left: 0rem;padding-left:0.2rem"  value="<?= $after_sale['tracking_number'] ?>" />
                            
                        </div>
                        <?php if ($after_sale['show_dispatch']): ?>
                        <div class="table-item">
                            <span style="width:4rem;display:inline-block;">产品发货</span>：
                            <span style="width:204px;display:inline-block;">
                                <a rel="<?= $product['item_id'] ?>" class="return_submit" href="javascript:void(0)" style="background: #3a3a3a; color: #eee;padding: 5px 10px;cursor: pointer;">
                                    发货
                                </a>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
        
                </form>
            <?php endif; ?>
            
            
        </div>
    </div>
</div>

<script>	
<?php $this->beginBlock('customer_return_dispatch') ?>

$(document).ready(function(){
    
	$(".return_submit").click(function(){
		tracking_company = $(".tracking_company").val();
        tracking_number = $(".tracking_number").val();
        
        if (tracking_company && tracking_number) {
            $("#returnsubmit-form").submit();
        }
        
	});
    
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['customer_return_dispatch'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>
