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
    //$reviw_rate_star_info = $parentThis['reviw_rate_star_info'];
    //$review_count = $parentThis['review_count'];
    //$reviw_rate_star_average = $parentThis['reviw_rate_star_average'];
?>
<div class="comment-content-main content-main clearfix">
    <div class="product-Reviews">
        <div id="pic_list_2" class="scroll_horizontal">
            <div class="clear"></div>
            <div class="box pro_commit assess">
                <ul>
                    <li>
                        <?= Yii::$service->page->translate->__('Review Star') ?>
                    </li>
                    <li class="assess-right" style="margin: 0.2rem 0 0 0.3rem">
                        <?php  
                            $star = round($reviw_rate_star_average);      
                            $star = $star? $star : 1;
                            for ($i=1;$i<=5;$i++):
                        ?>
                            <?php if ($i <= $star): ?>
                                <img  src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn01.png')  ?>"/>
                            <?php else: ?>
                                <img  src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn001.png')  ?>"/>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </li>
                </ul>
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
                    
                </div>
                
                <div class="product-Reviews_top">
                    <?php  if(is_array($coll) && !empty($coll)):  ?>
                            
                        <?php foreach($coll as $one):  ?>
                            <div class="card">
                                <div class="fec-card-header">
                                    <?= $one['summary'] ?>
                                </div>
                                <div class="fec-card-content">
                                    <div class="fec-card-content-inner">
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
                                        <div class="review_list_remark">
                                            <p><?= Yii::$service->page->translate->__('By');?> <?= $one['name'] ?></p>
                                            <span><?= $one['review_date'] ? date('Y-m-d H:i:s',$one['review_date']) : '' ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="fec-card-footer">
                                    <a href="#" class="review_star review_star_<?= $one['rate_star'] ?>" onclick="javascript:return false;"></a>
                                </div>
                            </div>
                        <?php  endforeach; ?>
                    
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
                
                
            </div>
        </div>
    </div>
</div>
<a class="comment-content-footer tap-hightlight" href="<?= Yii::$service->url->getUrl('catalog/reviewproduct/lists',['spu'=>$spu,'_id'=>$_id]); ?>" rel="nofollow">
    <?= Yii::$service->page->translate->__('View  All Review'); ?>(<?= $review_count; ?>) 
    <span class="iconfont">&#xe604;</span>
</a>

                    
                    
                    