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
//var_dump($reviw_rate_star_info);
?>
<table border="0" class="jud_tab" cellspacing="0" cellpadding="0" >
  <tr>
    <td width="175" class="jud_per">
        <p><?= round($reviw_rate_star_average) ?><?= Yii::$service->page->translate->__('Star'); ?></p><?= Yii::$service->page->translate->__('Praise Rate'); ?>
    </td>
    <td width="300" style="padding:0 0 20px 0">
        <table border="0" class="jud_tab" style="width:100%;margin:0 0 20px 0;" cellspacing="0" cellpadding="0" >
          <tr>
            <td width="90">5<?= Yii::$service->page->translate->__('Star'); ?><font color="#999999">（<?=  $reviw_rate_star_info['star_5'] ?>%）</font></td>
            <td>
                <div class="lineBlock ">
                    <div class="proportionBox" style="width: <?=  $reviw_rate_star_info['star_5'] ?>%"> </div>
                </div>
            </td>
          </tr>
          <tr>
            <td width="90">4<?= Yii::$service->page->translate->__('Star'); ?><font color="#999999">（<?=  $reviw_rate_star_info['star_4'] ?>%）</font></td>
            <td>
                <div class="lineBlock ">
                    <div class="proportionBox" style="width: <?=  $reviw_rate_star_info['star_4'] ?>%"> </div>
                </div>
            </td>
          </tr>
          <tr>
            <td width="90">3<?= Yii::$service->page->translate->__('Star'); ?><font color="#999999">（<?=  $reviw_rate_star_info['star_3'] ?>%）</font></td>
            <td>
                <div class="lineBlock ">
                    <div class="proportionBox" style="width: <?=  $reviw_rate_star_info['star_3'] ?>%"> </div>
                </div>
            </td>
          </tr>
          <tr>
            <td width="90">2<?= Yii::$service->page->translate->__('Star'); ?><font color="#999999">（<?=  $reviw_rate_star_info['star_2'] ?>%）</font></td>
            <td>
                <div class="lineBlock ">
                    <div class="proportionBox" style="width: <?=  $reviw_rate_star_info['star_2'] ?>%"> </div>
                </div>
            </td>
          </tr>
          <tr>
            <td width="90">1<?= Yii::$service->page->translate->__('Star'); ?><font color="#999999">（<?=  $reviw_rate_star_info['star_1'] ?>%）</font></td>
            <td>
                <div class="lineBlock ">
                    <div class="proportionBox" style="width: <?=  $reviw_rate_star_info['star_1'] ?>%"> </div>
                </div>
            </td>
          </tr>
        </table>
    </td>
    <td width="350" class="jud_bg">
        <?= Yii::$service->page->translate->__('Only users who purchased the item can comment on the item'); ?>
    </td>
  </tr>
</table>

<?php  if(is_array($coll) && !empty($coll)):  ?>                
<table border="0" class="jud_list" style="width:100%; margin-top:30px; line-height:26px;" cellspacing="0" cellpadding="0">
    <?php foreach($coll as $one):  ?>
      <tr valign="top">
        <td width="160">
            <img  class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/account-1.jpg')  ?>" width="20" height="20" align="absmiddle" />
            &nbsp;
            <span style=" line-height: 30px;  display: inline-block; vertical-align: bottom; font-size: 14px;">
                <?= $one['name'] ?>
            </span>
        </td>
        <td width="180">
            <span class="commstar">
                <?php for ($i=1;$i<=5;$i++): ?>
                    <span class="star star<?= $i ?> <?= ($one['rate_star'] == $i) ? 'hover' : ''?>"></span>
                <?php endfor;?>
            </span>
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
<div style="margin: 30px 20px 20px;   float: right;">
    <?= $review_page; ?>
</div>
    
