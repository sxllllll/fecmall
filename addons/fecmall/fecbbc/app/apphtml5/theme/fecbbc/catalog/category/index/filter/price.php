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
if(isset($parentThis['filter_price']) && !empty($parentThis['filter_price']) && is_array($parentThis['filter_price'])):
	foreach($parentThis['filter_price']  as $attr => $filter):
		$attrUrlStr = Yii::$service->url->category->attrValConvertUrlStr($attr);
		if(is_array($filter) && !empty($filter)):
            $hasChost = false;
            foreach($filter as $item){
                $val = $item['_id'];
                $selected = $item['selected'] ? 'chosed' : '';
                if ($selected) {
                    $hasChost = true ;
                }
            }
?>
    <li class="classify-item <?=  $hasChost  ?  'active-chose' : '' ?>" >
        <p class="shower">
            <span class="title"> <?= Yii::$service->page->translate->__($attr); ?>：</span>
            <span class="chose"></span>
        </p>
        <ul class="sub-classify" >
<?php foreach($filter as $item): ?>
<?php	$val = $item['val'];
			$url = $item['url'];
            $selected = $item['selected'] ? 'chosed' : '';
?>
<?php	if($val && $url):   ?>
            <li class="sub-item <?= $selected ?>" rel="<?= $url ?>">
               <?= Yii::$service->page->translate->__($val); ?>(<?= $count ?>)
                <i class="iconfont chosed-icon">&#xe617;</i>
            </li>
<?php  endif; ?>
<?php  endforeach;?>
        </ul>
    </li>
<?php  endif; ?>
<?php  endforeach;?>
<?php  endif; ?>

