<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main container one-column">
<?= Yii::$service->page->widget->render('base/flashmessage'); ?>
	<div class="col-main">
		<div class="std">
			<div class="review_lists">
				<div class="review_list_product" style="width:100%">
					<div style="width:170px;float:left;">
						<a href="<?= $url ?>">
							<img src="<?= Yii::$service->product->image->getResize($main_img,[150,150],false) ?>">
						</a>
					</div>
					
					<div style="float:left;" class="product-feature-details">
						
						<h1 class="product-title mb-15"><?= $name; ?></h1>
                        <?php $star = round($reviw_rate_star_average) ?>
                        <div class="rating d-inline-block mb-15">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <?php if ($i <= $star): ?>
                                    <i class="fa fa-star active"></i>
                                <?php else: ?>
                                    <i class="fa fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>

                        <p class="d-inline-block ml-10 review-link"><a rel="nofollow" href="#text-reviews">(<?= $review_count ?> customer review)</a></p>

                        <h2 class="product-price mb-0">
                            <?= Yii::$service->page->widget->render('product/price',['price_info' => $price_info]); ?>
                        </h2>
                        
					</div>
					<div class="clear"></div>
				</div>
				<div class="product-Reviews"> 
					
					<h3>
						<a href=""><?= Yii::$service->page->translate->__('Product Review');?></a>
					</h3>
                    <div class="clear"></div>
                    
                    
                    <div class="box pro_commit">
                        <div class="averageWarp">
                            <span class="lineBlock fon14"><?= Yii::$service->page->translate->__('Average Rating'); ?>: </span>
                            <a  lehref="#" class="review_star review_star_<?= round($reviw_rate_star_average) ?>" onclick="javascript:return false;"></a>
                        </div>
                        <div class="clear"></div>
                        <div class="lbBox writeRiviewTitle">
                            <ul class="lineBlock proportionStars">
                                <li class="lbBox">
                                    <span class="lineBlock fz_blue"><?= Yii::$service->page->translate->__('5 stars'); ?></span>
                                    <div class="lineBlock proportionBox">
                                        <div style="width: <?=  $reviw_rate_star_info['star_5'] ?>%"> </div>
                                    </div>
                                    <span class="lineBlock"><?=  $reviw_rate_star_info['star_5'] ?>%</span>
                                </li>
                                <li class="lbBox">
                                    <span class="lineBlock fz_blue"><?= Yii::$service->page->translate->__('4 stars'); ?></span>
                                    <div class="lineBlock proportionBox">
                                        <div style="width: <?=  $reviw_rate_star_info['star_4'] ?>%"> </div>
                                    </div>
                                    <span class="lineBlock"><?=  $reviw_rate_star_info['star_4'] ?>%</span>
                                </li>
                                <li class="lbBox">
                                    <span class="lineBlock fz_blue"><?= Yii::$service->page->translate->__('3 stars'); ?></span>
                                    <div class="lineBlock proportionBox">
                                        <div style="width: <?=  $reviw_rate_star_info['star_3'] ?>%"> </div>
                                    </div>
                                    <span class="lineBlock"><?=  $reviw_rate_star_info['star_3'] ?>%</span>				
                                </li>
                                <li class="lbBox">
                                    <span class="lineBlock fz_blue"><?= Yii::$service->page->translate->__('2 stars'); ?></span>
                                    <div class="lineBlock proportionBox">
                                        <div style="width: <?=  $reviw_rate_star_info['star_2'] ?>%"> </div>
                                    </div>
                                    <span class="lineBlock"><?=  $reviw_rate_star_info['star_2'] ?>%</span>
                                </li>
                                <li class="lbBox">
                                    <span class="lineBlock fz_blue"><?= Yii::$service->page->translate->__('1 stars'); ?></span>
                                    <div class="lineBlock proportionBox">
                                        <div style="width: <?=  $reviw_rate_star_info['star_1'] ?>%"> </div>
                                    </div>
                                    <span class="lineBlock"><?=  $reviw_rate_star_info['star_1'] ?>%</span>
                                </li>
                            </ul>
                            <div class="lineBlock writeRiviewBtn">
                                <button type="submit" title="Save" class="button btn btn-primary addreview" onclick="javascrtpt:window.location.href='<?= Yii::$service->url->getUrl('catalog/reviewproduct/add',['spu'=>$spu,'_id'=>$_id]); ?>'"><span><span><?= Yii::$service->page->translate->__('Write a Customer Review'); ?></span></span></button>
                            </div>
                            
                            <div class="clear"></div>
                            <div class="sg1">
                                <b class="lineBlock rate_star_avge" ><?= $reviw_rate_star_average ?></b>
                                <span class="lineBlock"><?= Yii::$service->page->translate->__('based on {review_count} Customer Reviews',['review_count' => $review_count]) ?></span>
                            </div>
                        
                        </div>
                        
                    
                    
                        <div class="product-Reviews_top">
                            <?php  if(is_array($coll) && !empty($coll)):  ?>
                            <ul id="review_description">
                                <?php foreach($coll as $one):  ?>
                                <li>
                                    <div class="review_description_left">
                                        <a href="#" class="review_star review_star_<?= $one['rate_star'] ?>" onclick="javascript:return false;"></a>
                                        <p><?= Yii::$service->page->translate->__('By');?> <?= $one['name'] ?></p>
                                        <span><?= $one['review_date'] ? date('Y-m-d H:i:s',$one['review_date']) : '' ?></span>
                                    </div>
                                    <div class="review_description_right">
                                        <input id="review_url_407" value="" type="hidden">
                                        <span class="review_description_right_span"><b><?= $one['summary'] ?></b></span>
                                        <div class="review_description_centen">
                                            <div class="addsize"></div>
                                            <div class="review-content">
                                                <?= $one['review_content'] ?>
                                            </div>
                                            <div class="moderation">
                                            <?php if($one['status'] == $noActiveStatus): ?>  
                                                <?= Yii::$service->page->translate->__('Your Review is awaiting moderation...');?>
                                            <?php elseif($one['status'] == $refuseStatus): ?>
                                                <?= Yii::$service->page->translate->__('Your Review is refused.');?>
                                            <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                        <?php if($pageToolBar): ?>
                        <div class="pageToolbar">
                            <label class="title"><?= Yii::$service->page->translate->__('Page:');?></label><?= $pageToolBar ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>