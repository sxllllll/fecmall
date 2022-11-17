<div class="main-wrap" id="main-wrap">
    <header id="yoho-header" class="yoho-header boys">
        <a href="<?= Yii::$service->url->getUrl('customer/account') ?>" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title">
            <?= Yii::$service->page->translate->__('My Order'); ?>
        </p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
    <div class="order-page yoho-page">
        <a class="fraud-tip" href="javascript:void">
            <span class="iconfont">&#xe614;</span>
            <span class="tip-title"><?= Yii::$service->page->translate->__('Important reminder about anti-fraud'); ?></span>
            <span class="tip-content"><?= Yii::$service->page->translate->__('We will not ask you to return a refund for any reason, please be vigilant.'); ?>
            </span>
        </a>
        <?php  $order_status = Yii::$app->request->get('order_status'); ?>
        <ul id="order-nav" class="order-nav clearfix">
            <li class="tap-hightlight <?= !$order_status ? 'active' : ''  ?>" >
                <a class="nav-tap" href="<?= Yii::$service->url->getUrl('customer/order') ?>">
                    <?= Yii::$service->page->translate->__('All'); ?>
                </a>
            </li>
            <li class="tap-hightlight  <?= $order_status == 'waiting_payment' ? 'active' : ''  ?> " >
                <a class="nav-tap" href="<?= Yii::$service->url->getUrl('customer/order', ['order_status' => 'waiting_payment']) ?>">
                    <?= Yii::$service->page->translate->__('Pending')   ?>
                </a>
            </li>
            <li class="tap-hightlight <?= $order_status == 'waiting_shipping' ? 'active' : ''  ?>" >
                <a class="nav-tap" href="<?= Yii::$service->url->getUrl('customer/order', ['order_status' => 'waiting_shipping']) ?>">
                    <?= Yii::$service->page->translate->__('Shipping')   ?>
                </a>
            </li>
            <li class="tap-hightlight  <?= $order_status == 'waiting_receive' ? 'active' : ''  ?>" >
                <a class="nav-tap" href="<?= Yii::$service->url->getUrl('customer/order', ['order_status' => 'waiting_receive']) ?>">
                    <?= Yii::$service->page->translate->__('Shipped')   ?>
                </a>
            </li>
        </ul>

        <div id="order-container" class="order-container">
            <?php  if(is_array($order_list) && !empty($order_list)):  ?>
                <div class="firstscreen-orders">
                    <?php foreach($order_list as $order): 
                        $currencyCode = $order['order_currency_code'];
                        $symbol = Yii::$service->page->currency->getSymbol($currencyCode);
                    ?>
                    <div class="order " >
                        <header class="header order-info" rel="<?=  Yii::$service->url->getUrl('customer/order/view',['order_id' => $order['order_id']]);?>">
                            <?= Yii::$service->page->translate->__('Order No'); ?>: <?= $order['increment_id'] ?>
                            <span class="order-status">
                                <?= Yii::$service->page->translate->__($order['order_status']); ?>
                            </span>
                        </header>
                        <section class="order-goods order-info"   rel="<?=  Yii::$service->url->getUrl('customer/order/view',['order_id' => $order['order_id']]);?>">
                            <?php  if(is_array($order['products']) && !empty($order['products'])):  ?>
                                <?php foreach($order['products'] as $product):  ?>
                                    <div class="order-good">
                                        <div class="thumb-wrap">
                                           <div class="pic-c">
                                                <img class="thumb" src="<?= Yii::$service->product->image->getResize($product['image'],[150,180],false) ?>">
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
                                                        <span >
                                                            <?= Yii::$service->page->translate->__($attr) ?>: <?= Yii::$service->page->translate->__($val) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </p>
                                            <p class="row price-wrap">
                                                <span class="price">
                                                    <?= $symbol ?><?= $product['price'] ?>
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
                        <footer class="footer">
                            <?= Yii::$service->page->translate->__('Total'); ?><?= $order['items_count'] ?>
                            <?= Yii::$service->page->translate->__(' items '); ?>  
                            <?= Yii::$service->page->translate->__('Pay '); ?>
                            <span class="sum-cost">
                                <?= $symbol ?><?= $order['grand_total'] ?>
                            </span>
                            (<?= Yii::$service->page->translate->__(' Shipping Cost'); ?><?= $symbol ?><?= $order['shipping_total'] ?>)
                        </footer>
                        <div class="order-opt">
                            
                            <?php if ($order['can_payment']) : ?>
                                <span class="btn" onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('checkout/onepage/payment', ['order_increment_id' => $order['increment_id']]) ?>'" >
                                    <?= Yii::$service->page->translate->__('To Pay'); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($order['can_received']) :?>
                                <span class="btn" onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/order/receive', ['order_increment_id' => $order['increment_id']]) ?>'" >
                                    <?= Yii::$service->page->translate->__('Confirm Receipt'); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($order['can_query_shipping']) : ?>
                                <span class="btn" onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/order/shipping', ['order_increment_id' => $order['increment_id']]) ?>'" >
                                    <?= Yii::$service->page->translate->__('View Shipping'); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($order['can_review']) :  ?>
                                <span class="btn" onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/order/reviewproduct', ['order_id' => $order['order_id']]) ?>'">
                                    <?= Yii::$service->page->translate->__('Review'); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($order['can_reorder']) :  ?>
                                <span class="btn rebuy" onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/order/reorder', ['order_id' => $order['order_id']]) ?>'">
                                    <?= Yii::$service->page->translate->__('Reorder'); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($order['can_cancel']) :  ?>
                                <span class="btn" onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/order/cancel', ['order_increment_id' => $order['increment_id']]) ?>'">
                                    <?= Yii::$service->page->translate->__('Cancel Order'); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ($order['can_cancel_back']) :  ?>
                                <span class="btn" onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/order/cancelback', ['order_increment_id' => $order['increment_id']]) ?>'">
                                    <?= Yii::$service->page->translate->__('Cancel Order Back'); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ($order['can_after_sale']) :  ?>
                                <span class="btn" onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/order/aftersale', ['order_id' => $order['order_id']]) ?>'">
                                    <?= Yii::$service->page->translate->__('After Sale'); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ($order['can_delay_receive']) :  ?>
                                <span class="btn" onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/order/delayreceive', ['order_id' => $order['order_id']]) ?>'">
                                    <?= Yii::$service->page->translate->__('Delay Receive'); ?>
                                </span>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                 <?php if($pageToolBar): ?>
                    <div class="pageToolbar customer_order">
                        <?= $pageToolBar ?>
                        <div class="clear"></div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-order">
                    <div class="icon"></div>
                    <span>
                        <?= Yii::$service->page->translate->__('You have no order yet'); ?>!
                    </span>
                    <a class="walk-way" href="<?= Yii::$service->url->homeUrl(); ?>">
                        <?= Yii::$service->page->translate->__('Walk Around'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>



<script>
<?php $this->beginBlock('customer_order') ?>  
$(document).ready(function(){
    $(".order-info").click(function(){
        url = $(this).attr('rel');
        window.location.href=url;
    });
});
<?php $this->endBlock(); ?>  
<?php $this->registerJs($this->blocks['customer_order'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 





