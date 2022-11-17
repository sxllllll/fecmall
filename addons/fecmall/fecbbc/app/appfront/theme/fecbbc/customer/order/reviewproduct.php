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
            <div class="swindle-info">
                <span><?= Yii::$service->page->translate->__('Important Tips') ?> ：</span>
                <?= Yii::$service->page->translate->__('Recently, fake customer service fraud incidents have occurred frequently, and the goods will not ask you for account password or guide transfer for any reason. Please be vigilant and beware of fraud') ?>
                。
            </div>
            <div class="detail-info ">
                <div class="status">
                    <p>
                        <?= Yii::$service->page->translate->__('Order No') ?>：
                        <em><?=  $orderInfo['increment_id'] ?></em>
                    </p>
                    <p class="cur-status clearfix ">
                        <?= Yii::$service->page->translate->__('Order Status'); ?>：
                        <?=  Yii::$service->page->translate->__($orderInfo['order_status']); ?>
                    </p>
                </div>
                <div class="order-info">
                    <p class="sub-title">
                        <span class="icon"></span>
                        <?= Yii::$service->page->translate->__('Order Info') ?>
                    </p>
                    <div class="content">
                            <p><?= Yii::$service->page->translate->__('Receiver Name') ?>：<?=  $orderInfo['customer_firstname'] ?></p>
                            <p><?= Yii::$service->page->translate->__('Address State') ?>：<?=  $orderInfo['customer_address_state'] ?>
                                                        <?=  $orderInfo['customer_address_city'] ?>
                                                        <?=  $orderInfo['customer_address_area'] ?>
                                                        <?=  $orderInfo['customer_address_street1'] ?></p>
                            <p><?= Yii::$service->page->translate->__('Contact Telephone') ?>: <?=  $orderInfo['customer_telephone'] ?></p>
                    </div>
                </div>
                <div class="pay-mode">
                    <p class="sub-title">
                        <span class="icon"></span>
                        <?= Yii::$service->page->translate->__('Pay And Shipping Method') ?>
                    </p>
                    <div class="content">
                        <p><?= Yii::$service->page->translate->__('Payment Method') ?>：<?= Yii::$service->page->translate->__($orderInfo['payment_method']) ?></p>
                        <p><?= Yii::$service->page->translate->__('Shipping Method') ?>：<?= Yii::$service->page->translate->__($orderInfo['shipping_method']) ?></p>
                    </div>
                </div>
                <div class="good-list">
                    <p class="sub-title">
                        <span class="icon"></span>
                        <?= Yii::$service->page->translate->__('Goods List') ?>
                    </p>
                    <div class="content">
                        <table>
                            <thead>
                            <tr><th class="product-info"><?= Yii::$service->page->translate->__('Goods Info') ?></th>
                            <th class="good-price"><?= Yii::$service->page->translate->__('Price') ?></th>
                            
                            <th class="num"><?= Yii::$service->page->translate->__('Qty') ?></th>
                            <th class="num"><?= Yii::$service->page->translate->__('Subtotal') ?></th>
                            <th class="num"><?= Yii::$service->page->translate->__('Operate') ?></th>
                            </tr></thead>
                            <tbody>
                                <?php if(is_array($orderInfo['products']) && !empty($orderInfo['products'])):  ?>
                                    <?php foreach($orderInfo['products'] as $product): ?>
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
                                                <p> <?= $orderInfo['currency_symbol'] ?><?= $product['price'] ?></p>
                                            </td>
                                            <td><?= $product['qty'] ?></td>
                                            <td><?= $orderInfo['currency_symbol'] ?><?= $product['row_total'] ?></td>
                                            <td>
                                                <div class="p-btns" style="line-height:20px;">
                                                    
                                                    <?php if (Yii::$service->product->review->hasReviewed($product['is_reviewed'])): ?>
                                                        <input type="button" value="<?= Yii::$service->page->translate->__('Reviewed'); ?>" style="    font-size: 0.6rem;width: auto;float: right;margin-right: 0.6rem;"   />
                                                    <?php else: ?>
                                                        <input type="button" value="<?= Yii::$service->page->translate->__('To Review'); ?>" style="    font-size: 0.6rem;width: auto;float: right;margin-right: 0.6rem;"  onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('catalog/reviewproduct/add', ['order_id' => $orderInfo['order_id'],   'item_id' => $product['item_id'], 'product_id' => $product['product_id']]) ?>'" />
                                                    <?php endif; ?>
                                                    
                                                    
                                                    <br>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <div class="order-balance-container">
                            
                            <div class="order-balance">
                                    <p>
                                        <?= Yii::$service->page->translate->__('Goods Subtotal'); ?>:
                                        <em>
                                            <?= $orderInfo['currency_symbol'] ?><?=  Format::price($orderInfo['subtotal']); ?>
                                        </em>
                                    </p>
                                    <p>
                                        <?= Yii::$service->page->translate->__('Shipping Cost'); ?> :
                                        <em>
                                        <?= $orderInfo['currency_symbol'] ?><?=  Format::price($orderInfo['shipping_total']); ?>
                                        </em>
                                    </p>
                                    <p>
                                        <?= Yii::$service->page->translate->__('Discount'); ?>:
                                        <em>
                                        -<?= $orderInfo['currency_symbol'] ?><?=  Format::price($orderInfo['subtotal_with_discount']); ?>
                                        </em>
                                    </p>
                                    <p>
                                        <?= Yii::$service->page->translate->__('Pay Amount'); ?>:
                                        <em class="payment">
                                            <?= $orderInfo['currency_symbol'] ?><?=  Format::price($orderInfo['grand_total']); ?>
                                        </em>
                                    </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-operation clearfix">

                </div>
            </div>
        </div>
        
        
    </div>
</div>    

  
