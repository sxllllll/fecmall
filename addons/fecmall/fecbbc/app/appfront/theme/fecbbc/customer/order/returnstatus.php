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

<div class="order-detail-page me-page yoho-page clearfix">
    <p class="home-path">
        <span class="path-icon"></span>
        <a href="<?= Yii::$service->url->homeUrl(); ?>"><?= Yii::$service->page->translate->__('Home') ?></a>
        &nbsp;&nbsp;&gt;&nbsp;&nbsp;
        <span><?= Yii::$service->page->translate->__('Order Detail') ?></span>
    </p>    
    <div class="home-navigation block">
        <?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>
    </div>
    <div class="me-main">
        <div class="order-detail block" >
            <h2 class="title"></h2>
            <div class="detail-info ">
                
                <div class="good-list">
                    <p class="sub-title">
                        <span class="icon"></span>
                        <?= Yii::$service->page->translate->__('Goods Return List') ?>
                    </p>
                    <div class="content">
                        <table>
                            <thead>
                                <tr>
                                    <th class="product-info"><?= Yii::$service->page->translate->__('Goods Info') ?></th>
                                    <th class="good-price"><?= Yii::$service->page->translate->__('Price') ?></th>
                                    <th class="num"><?= Yii::$service->page->translate->__('Qty') ?></th>
                                    <th class="num"><?= Yii::$service->page->translate->__('Return Status') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(is_array($after_sale) && !empty($after_sale)):  ?>
                                    <tr>
                                        <td>
                                            <a class="thumb-link" href="#" >
                                                <img class="thumb" src="<?= Yii::$service->product->image->getResize($after_sale['image'],[150,150],false) ?>">
                                            </a>
                                            <p class="name-color-size">
                                                <a class="name" href="#" >
                                                    Sku: <?= $after_sale['sku'] ?>
                                                </a>
                                            </p>
                                        </td>
                                        <td>
                                            <p> <?= $after_sale['currency_symbol'] ?><?= $after_sale['price'] ?></p>
                                        </td>
                                        <td><?= $after_sale['qty'] ?></td>
                                        <td>
                                            <?= $after_sale['status'] ?>
                                            <?php if ($after_sale['can_cancel']): ?>
                                                <br/>
                                                <a href="<?= Yii::$service->url->getUrl('customer/order/returncancel', ['as_id' => $after_sale['id']]) ?>" style="color:#468fa2;margin-top:10px;display: block;">
                                                    <?= Yii::$service->page->translate->__('Cancel Return') ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                            
                            <?php if ($after_sale['show_shipping']): ?>
                            <form action="<?= Yii::$service->url->getUrl('customer/order/returndispatch'); ?>" method="post" id="returnsubmit-form">
                                <?= CRequest::getCsrfInputHtml(); ?>
                                <div class="order-balance-container">
                                    <div class="order-balance">
                                        <input type="hidden" name="editForm[as_id]" value="<?= $after_sale['id'] ?>"   />
                                        <p>
                                            物流公司：
                                            <select name="editForm[tracking_company]" class="tracking_company" style="width:204px;">
                                                <?= $companySelectOptions ?>
                                            </select>
                                        </p>
                                        <p style="margin-top:10px;">
                                            物流追踪号：<input name="editForm[tracking_number]" class="tracking_number" type="text" style="width:200px;"  value="<?= $after_sale['tracking_number'] ?>" />
                                        </p>
                                        <?php if ($after_sale['show_dispatch']): ?>
                                            <p style="text-align:left;margin-top:20px">
                                                退款产品发货提交：
                                                <span style="width:204px;display:inline-block;">
                                                    <a rel="<?= $product['item_id'] ?>" class="return_submit" href="javascript:void(0)" style="background: #3a3a3a; color: #eee;padding: 5px 10px;cursor: pointer;">
                                                        发货
                                                    </a>
                                                </span>
                                            </p> 
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                            <?php endif; ?>
                    </div>
                </div>
                <div class="order-operation clearfix">
                </div>
            </div>
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
