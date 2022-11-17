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
        <span><?= Yii::$service->page->translate->__('Product Review') ?></span>
    </p>    
    <div class="home-navigation block">
        <?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>
    </div>
    <div class="me-main" style="line-height:20px;">
        <h2 class="title" style="font-size:14px;font-weight:bold">
                <?= Yii::$service->page->translate->__('Product Review') ?>
            </h2>
        <?php $i=0;  if(is_array($coll) && !empty($coll)):  ?>
                <table border="0" class="jud_list" style="width:100%; margin-top:30px;" cellspacing="0" cellpadding="0">
                    <?php foreach($coll as $one):  $i++;?>   
                      <tr valign="top" style="border-bottom: 1px solid #eaeaea;">
                        <td width="160">
                            <?php $main_image = isset($one['image']['main']['image']) ? $one['image']['main']['image'] : '' ?>
                            <a class="product_img" href="<?= Yii::$service->url->getUrl($one['url_key']);  ?>">                    
                                <img src="<?= Yii::$service->product->image->getResize($main_image,[80,80],false) ?>" width="" height="" align="absmiddle" />
                            </a>
                        </td>
                        <td width="180">
                            <span class="commstar">
                                <?php for ($i=1;$i<=5;$i++): ?>
                                    <span class="star star<?= $i ?> <?= ($one['rate_star'] == $i) ? 'hover' : ''?>"></span>
                                <?php endfor;?>
                            </span>
                            <br/>
                            <?php if($one['status'] == $noActiveStatus): ?>
                                <div class="review_moderation">
                                    <?= Yii::$service->page->translate->__('Your Review is awaiting moderation...');?>
                                </div>
                            <?php elseif($one['status'] == $refuseStatus): ?>
                                <div class="review_refuse">
                                    <?= Yii::$service->page->translate->__('Your Review is refused.');?>
                                </div>
                            <?php elseif($one['status'] == $activeStatus): ?>
                                <div class="review_accept">
                                    <?= Yii::$service->page->translate->__('Your Review is accept.');?>
                                </div>
                            <?php endif; ?>
                                                        
                        </td>
                        <td>
                            <?= $one['review_content'] ?><br />
                            <font color="#999999">
                                <?= $one['review_date'] ? date('Y-m-d H:i:s',$one['review_date']) : '' ?>
                            </font>
                        </td>
                      </tr>
                  <?php endforeach; ?>
                </table>
                <?php endif; ?>
                <?php if($pageToolBar): ?>
                    <div class="pageToolbar">
                        <label class="title"></label><?= $pageToolBar ?>
                    </div>
                <?php endif; ?>
    </div>
</div>    
