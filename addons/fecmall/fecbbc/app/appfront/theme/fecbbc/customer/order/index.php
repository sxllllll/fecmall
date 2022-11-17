<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
?>

<div class="orders-me-page me-page yoho-page clearfix">
    <p class="home-path">
        <span class="path-icon"></span>
        <a href="<?= Yii::$service->url->homeUrl(); ?>"><?= Yii::$service->page->translate->__('Home') ?></a>
        &nbsp;&nbsp;&gt;&nbsp;&nbsp;
        <span><?= Yii::$service->page->translate->__('Order Info') ?></span>
    </p>    
    <div class="home-navigation block">
        <?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>
    </div>
    <div class="me-main">
        <div class="orders block">
            <h2 class="title">
                
            </h2>
            <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
            <div class="swindle-info">
                <span><?= Yii::$service->page->translate->__('Important Tips') ?> ：</span>
                <?= Yii::$service->page->translate->__('Recently, fake customer service fraud incidents have occurred frequently, and the goods will not ask you for account password or guide transfer for any reason. Please be vigilant and beware of fraud') ?>
                。
            </div>

            <ul class="tabs clearfix">
                <li class="active">
                    <a href=""><?= Yii::$service->page->translate->__('All Order') ?></a>
                </li>
                
            </ul>
            <?php  if(is_array($order_list) && !empty($order_list)):  ?>
            <div class="me-orders">
                <p class="order-table-header table-header clearfix">
                    <span class="info"><?= Yii::$service->page->translate->__('Goods Info') ?></span>
                    <span class="price"><?= Yii::$service->page->translate->__('Price') ?></span>
                    <span class="count"><?= Yii::$service->page->translate->__('Qty') ?></span>
                    <span class="pay"><?= Yii::$service->page->translate->__('Subtotal') ?></span>
                    <span class="order-status"><?= Yii::$service->page->translate->__('Order Status') ?></span>
                    <span class="operation"><?= Yii::$service->page->translate->__('Operate') ?></span>
                </p>
                <?php foreach($order_list as $order): ?>
                <?php 
                    $currencyCode = $order['order_currency_code'];
                    $symbol = Yii::$service->page->currency->getSymbol($currencyCode);
                ?>
                    <div class="order" >
                        <p class="order-title">
                            <?= Yii::$service->page->translate->__('Order No') ?>: <?= $order['increment_id'] ?>
                            <span class="order-time"><?= Yii::$service->page->translate->__('Order Date') ?>：<?= date('Y-m-d H:i:s',$order['created_at']) ?></span>
                            <?php if ($order['can_cancel']) :  ?>
                                <a class="right order-delete" href="<?= Yii::$service->url->getUrl('customer/order/cancel', ['order_increment_id' => $order['increment_id']]) ?>">
                                    <?= Yii::$service->page->translate->__('Cancel Order'); ?>
                                </a>
                            <?php endif; ?> 
                            
                            <?php if ($order['can_cancel_back']) :  ?>
                                <a class="right order-delete" style="color:#468fa2" href="<?= Yii::$service->url->getUrl('customer/order/cancelback', ['order_increment_id' => $order['increment_id']]) ?>">
                                    <?= Yii::$service->page->translate->__('Cancel Order Back'); ?>
                                </a>
                            <?php endif; ?>
                        </p>
                        
                        <div class="order-wrap">
                            <ul>
                                <?php  if(is_array($order['products']) && !empty($order['products'])):  ?>
                                <?php $product_count = count($order['products']); $i=0; ?>
                                <?php foreach($order['products'] as $product):  $i++;?>
                                <li>
                                    <div class="info clearfix">
                                        <a class="thumb-wrap" href="<?=  Yii::$service->url->getUrl($product['redirect_url']) ; ?>" target="_blank">
                                            <img class="thumb" src="<?= Yii::$service->product->image->getResize($product['image'],[150,150],false) ?>"  />
                                        </a>
                                        <div class="text-info">
                                            <a class="name" href="<?=  Yii::$service->url->getUrl($product['redirect_url']) ; ?>" target="_blank">
                                                <?= $product['name'] ?>
                                            </a>
                                            <?php if (is_array($product['spu_options']) && !empty($product['spu_options'])): ?>
                                                <span class="color-size">
                                                <?php foreach ($product['spu_options'] as $attr => $val): ?>
                                                    <b title="<?= Yii::$service->page->translate->__($val) ?>">
                                                        <?= Yii::$service->page->translate->__($attr) ?>：
                                                        <?= Yii::$service->page->translate->__($val) ?>&nbsp;&nbsp;
                                                    </b>
                                                <?php endforeach; ?>
                                                </span>
                                            <?php endif; ?>   
                                        </div>
                                    </div>
                                    <div class="price">
                                        <p><?= $product['price'] ?></p>
                                    </div>
                                    <div class="count"><?= $product['qty'] ?></div>
                                </li>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                            <div class="pay">
                                <?= $symbol ?><?= $order['grand_total'] ?>
                                <span class="pay-tip">(<?= Yii::$service->page->translate->__('With Shipping Cost') ?>:<?= $symbol ?><?= $order['shipping_total'] ?>)</span>
                            </div>
                            <div class="order-status">
                                <span class="cancel"><?= Yii::$service->page->translate->__($order['order_status']); ?></span>
                            </div>
                            <div class="operation">
                                <a style="margin:5px 0" class="op-item" href="<?= Yii::$service->url->getUrl('customer/order/view', ['order_id' => $order['order_id']])  ?>" target="_blank">
                                    <?= Yii::$service->page->translate->__('Order Detail');?>
                                </a>
                                <?php  $isLast = false; ?>
                                <?php if ($order['can_payment']) : ?>
                                    <a class="op-item" href="<?= Yii::$service->url->getUrl('checkout/onepage/payment', ['order_increment_id' => $order['increment_id']]) ?>" >
                                        <span class="rebuy" style="color:#000">
                                            <?= Yii::$service->page->translate->__('To Pay'); ?>
                                        </span>
                                    </a>
                                    <br>
                                    <?php $isLast = true; ?>
                                <?php endif; ?>
                                
                                <?php if ($order['can_received']) :?>
                                    <a class="op-item" href="<?= Yii::$service->url->getUrl('customer/order/receive', ['order_increment_id' => $order['increment_id']]) ?>" >
                                        <span class="rebuy" style="color:#000">
                                            <?= Yii::$service->page->translate->__('Confirm Receipt'); ?>
                                        </span>
                                    </a>
                                    <br> 
                                    <?php $isLast = true; ?>
                                <?php endif; ?>
                                
                                <?php if ($order['can_query_shipping']) : ?>
                                    <a class="op-item" href="<?= Yii::$service->url->getUrl('customer/order/shipping', ['order_increment_id' => $order['increment_id']]) ?>" >
                                        <span class="rebuy" style="color:#000">
                                        <?= Yii::$service->page->translate->__('View Shipping'); ?>
                                        </span>
                                    </a><br> 
                                    <?php $isLast = true; ?>
                                <?php endif; ?>
                                <?php if ($order['can_review']) :  ?>
                                    <a class="op-item" href="<?= Yii::$service->url->getUrl('customer/order/reviewproduct', ['order_id' => $order['order_id']]) ?>" >
                                        <span class="rebuy" style="color:#000">
                                            <?= Yii::$service->page->translate->__('Review'); ?>
                                        </span>
                                    </a><br> 
                                <?php endif; ?>
                                
                                <?php if ($order['can_after_sale']) :  ?>
                                    <a class="op-item" href="<?= Yii::$service->url->getUrl('customer/order/aftersale', ['order_id' => $order['order_id']]) ?>" >
                                        <span class="rebuy" style="color:#000">
                                            <?= Yii::$service->page->translate->__('After Sale'); ?>
                                        </span>
                                    </a><br> 
                                <?php endif; ?>
                                <?php if ($order['can_delay_receive']) :  ?>
                                    <a class="op-item" href="<?= Yii::$service->url->getUrl('customer/order/delayreceive', ['order_id' => $order['order_id']]) ?>" >
                                        <span class="rebuy" style="color:#000">
                                            <?= Yii::$service->page->translate->__('Delay Receive'); ?>
                                        </span>
                                    </a><br> 
                                <?php endif; ?>
                                
                                
                                <?php if ($order['can_reorder']) :  ?>
                                    <a class="op-item" href="<?= Yii::$service->url->getUrl('customer/order/reorder', ['order_id' => $order['order_id']]) ?>" >
                                        <span class="rebuy" style="color:#000">
                                        <?= Yii::$service->page->translate->__('Reorder'); ?>
                                        </span>
                                    </a><br> 
                                    <?php $isLast = true; ?>
                                <?php endif; ?>
                                
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>  
            
            
                
                
            <div class="me-pager clearfix">
                <?php if($pageToolBar): ?>
                    <div class="pageToolbar">
                        <label class=""></label>
                        <?= $pageToolBar ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>    

    
    
    

	