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
?>
<div class="main-wrap" id="main-wrap">
    <header id="yoho-header" class="yoho-header boys">
        <a href="<?= Yii::$service->url->getUrl('customer/account') ?>" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title">
            <?= Yii::$service->page->translate->__('My Order'); ?>
        </p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="order-detail-page yoho-page">
        <a class="fraud-tip" href="">
            <span class="iconfont">&#xe614;</span>
            <span class="tip-title"><?= Yii::$service->page->translate->__('Important reminder about anti-fraud'); ?></span>
            <span class="tip-content"><?= Yii::$service->page->translate->__('We will not ask you to return a refund for any reason, please be vigilant.'); ?>
            </span>
        </a>
        <a class="base-status clearfix">
            <div class="status-c">
                <div class="status-title"><?= Yii::$service->page->translate->__($orderInfo['order_status']); ?></div>
                <div class="status-time"><?=  date('Y-m-d H:i:s',$orderInfo['updated_at']); ?></div>
            </div>
        </a>
        <div id="order-detail" data-id="57684909141">
            <section class="owner-info block">
                <span class="iconfont">&#xe637;</span>
                <div class="beside-icon">
                    <p class="name-phone">
                        <?=  $orderInfo['customer_firstname'] ?> <?=  $orderInfo['customer_lastname'] ?>
                        <span><?=  $orderInfo['customer_telephone'] ?></span>
                    </p>
                    <p class="address">
                        <?=  $orderInfo['customer_address_state_name'] ?> <?=  $orderInfo['customer_address_city'] ?> <?=  $orderInfo['customer_address_area'] ?> <?=  $orderInfo['customer_address_street1'] ?>  <?=  $orderInfo['zip'] ?>
                    </p>
                </div>
            </section>

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

            <ul class="cost block" style="margin-bottom: .5rem; margin-top: .5rem;">
                <?php if ($orderInfo['payment_method']): ?>
                <li style="font-size:0.6rem"> <?= Yii::$service->page->translate->__('Payment Method'); ?>:
                    <span>
                        <?php
                            $paymentMethod = Yii::$service->payment->getPaymentLabelByMethod($orderInfo['payment_method']);
                            echo Yii::$service->page->translate->__($paymentMethod);
                        ?>
                    </span>
                </li>
                <?php endif; ?>
                <li style="font-size:0.6rem"><?= Yii::$service->page->translate->__('Shipping Method'); ?>:
                    <span>
                        <?= $shippingLabel ?>
                    </span>
                </li>
                
                <?php if($orderInfo['tracking_company']): ?>
                <li style="font-size:0.6rem"><?= Yii::$service->page->translate->__('Shipping Company'); ?>:
                    <span>
                        <?= Yii::$service->delivery->kdiniao->getDeliveryShipperName($orderInfo['tracking_company']) ?>
                    </span>
                </li>
                <?php endif; ?>
                
                <?php if($orderInfo['tracking_number']): ?>
                <li style="font-size:0.6rem"><?= Yii::$service->page->translate->__('Tracking No'); ?>:
                    <span>
                        <?= $orderInfo['tracking_number'] ?>
                    </span>
                </li>
                <?php endif; ?>
                
            </ul>    
            <ul class="cost block">   
                <li> <?= Yii::$service->page->translate->__('Subtotal '); ?>:
                    <span><?= $orderInfo['currency_symbol'] ?><?=  Format::price($orderInfo['subtotal']); ?></span>
                </li>
                <li> <?= Yii::$service->page->translate->__('Shipping Cost'); ?>:
                    <span><?= $orderInfo['currency_symbol'] ?><?=  Format::price($orderInfo['shipping_total']); ?></span>
                </li>
                <li> <?= Yii::$service->page->translate->__('Coupon'); ?>:
                    <span>-<?= $orderInfo['currency_symbol'] ?><?=  Format::price($orderInfo['subtotal_with_discount']); ?></span>
                </li>
            </ul>
            <ul class="real-amount">
                <li>
                    <?= Yii::$service->page->translate->__('Grand Total '); ?>:
                    <span><?= $orderInfo['currency_symbol'] ?><?=  Format::price($orderInfo['grand_total']); ?></span>
                </li>
            </ul>
            <div class="order-opt" style="touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                <?php if ($bInfo['can_payment']) : ?>
                    <span class="btn" onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('checkout/onepage/payment', ['order_increment_id' => $orderInfo['increment_id']]) ?>'" >
                        <?= Yii::$service->page->translate->__('To Pay'); ?>
                    </span>
                <?php endif; ?>
                <?php if ($bInfo['can_received']) :?>
                    <span class="btn" onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/order/receive', ['order_increment_id' => $orderInfo['increment_id']]) ?>'" >
                        <?= Yii::$service->page->translate->__('Confirm Receipt'); ?>
                    </span>
                <?php endif; ?>
                <?php if ($bInfo['can_query_shipping']) : ?>
                    <span class="btn" onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/order/shipping', ['order_increment_id' => $orderInfo['increment_id']]) ?>'" >
                        <?= Yii::$service->page->translate->__('View Shipping'); ?>
                    </span>
                <?php endif; ?>
                <?php if ($bInfo['can_review']) :  ?>
                    <span class="btn" onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/order/reviewproduct', ['order_id' => $orderInfo['order_id']]) ?>'">
                        <?= Yii::$service->page->translate->__('Review'); ?>
                    </span>
                <?php endif; ?>
                <?php if ($bInfo['can_reorder']) :  ?>
                    <span class="btn rebuy" onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/order/reorder', ['order_id' => $orderInfo['order_id']]) ?>'">
                        <?= Yii::$service->page->translate->__('Reorder'); ?>
                    </span>
                <?php endif; ?>
                <?php if ($bInfo['can_cancel']) :  ?>
                    <span class="btn" onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/order/cancel', ['order_increment_id' => $orderInfo['increment_id']]) ?>'">
                        <?= Yii::$service->page->translate->__('Cancel'); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="info-table">
            <div class="table-item">
                <?= Yii::$service->page->translate->__('Order No'); ?>：<?= $orderInfo['increment_id'] ?>
            </div>
            <div class="table-item">
                <?= Yii::$service->page->translate->__('Order Date'); ?>：<?=  date('Y-m-d H:i:s',$orderInfo['created_at']); ?>
            </div>
            <a href="javascript:void" target="_blank" class="iconfont">&#xe63c;</a>
        </div>
    </div>
</div>