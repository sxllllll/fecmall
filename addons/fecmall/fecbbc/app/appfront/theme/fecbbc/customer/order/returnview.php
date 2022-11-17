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
                <div class="status">
                    <p>
                        <?= Yii::$service->page->translate->__('Order No') ?>：
                        <em><?=  $order['increment_id'] ?></em>
                    </p>
                    <p>
                        <?= Yii::$service->page->translate->__('Order Date') ?>：
                        <em><?=  date('Y-m-d H:i:s', $order['created_at']) ?></em>
                    </p>
                </div>
                <div class="good-list">
                    <p class="sub-title">
                        <span class="icon"></span>
                        <?= Yii::$service->page->translate->__('Goods List') ?>
                    </p>
                    <div class="content">
                        <table>
                            <thead>
                                <tr>
                                    <th class="product-info"><?= Yii::$service->page->translate->__('Goods Info') ?></th>
                                    <th class="good-price"><?= Yii::$service->page->translate->__('Price') ?></th>
                                    <th class="num"><?= Yii::$service->page->translate->__('Qty') ?></th>
                                    <th class="num"><?= Yii::$service->page->translate->__('Subtotal') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(is_array($order['products']) && !empty($order['products'])):  ?>
                                    <?php foreach($order['products'] as $product): ?>
                                        <tr>
                                            <td>
                                                <a class="thumb-link" href="<?=  Yii::$service->url->getUrl($product['redirect_url']) ; ?>" target="_blank">
                                                    <img class="thumb" src="<?= Yii::$service->product->image->getResize($product['image'],[150,150],false) ?>">
                                                </a>
                                                <p class="name-color-size">
                                                    <a class="name" href="<?=  Yii::$service->url->getUrl($product['redirect_url']) ; ?>" target="_blank">
                                                        <?= $product['name'] ?>
                                                    </a>
                                                    
                                                    <?php if (is_array($product['spu_options']) && !empty($product['spu_options'])): ?>
                                                        <?php foreach ($product['spu_options'] as $attr => $val): ?>
                                                            <b title="<?= Yii::$service->page->translate->__($val) ?>">
                                                                <?= Yii::$service->page->translate->__($attr) ?>：<?= Yii::$service->page->translate->__($val) ?>
                                                            </b>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </p>
                                            </td>
                                            <td>
                                                <p> <?= $order['currency_symbol'] ?><?= $product['price'] ?></p>
                                            </td>
                                            <td><?= $product['qty'] ?></td>
                                            <td><?= $order['currency_symbol'] ?><?= $product['row_total'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <form action="<?= Yii::$service->url->getUrl('customer/order/returnsubmit'); ?>" method="post" id="returnsubmit-form">
                            <?= CRequest::getCsrfInputHtml(); ?>
                            <div class="order-balance-container">
                                <div class="order-balance">
                                    <p>
                                        退货个数：<input name="editForm[return_qty]" class="return_qty" type="text" style="width:100px;"  value="<?= $product['qty'] ?>" />
                                    </p>
                                    <input type="hidden" name="editForm[item_id]" value="<?= $product['item_id'] ?>"   />
                                    <p style="text-align:left;margin-top:20px">
                                        退货提交：
                                        <a rel="<?= $product['item_id'] ?>" class="return_submit" href="javascript:void(0)" style="background: #3a3a3a; color: #eee;padding: 5px 10px;cursor: pointer;">
                                            退货提交
                                        </a>
                                    </p> 
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="order-operation clearfix">
                </div>
            </div>
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
