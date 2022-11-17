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
if(isset($parentThis['filters']) && !empty($parentThis['filters']) && is_array($parentThis['filters'])):
    $k = 0;
	foreach($parentThis['filters']  as $attr => $filter):
		$attrLabel = $filter['label'];
        $attrName = $filter['name'];
		if(is_array($filter['items']) && !empty($filter['items'])):
			$i = 0;
            foreach($filter['items'] as $item):
                $hasChost = false;
				$itemName    = $item['_id'];
                $itemLabel    = $item['label'];
                $itemCount    = $item['count'];
                $itemUrl    = $item['url'];
                $selected    = $item['selected'];
				if($itemName):
					$i++;
					if($i == 1):
?>
                    <li class="classify-item <?=  $selected  ?  'active-chose' : '' ?>" >
                        <p class="shower">
                            <span class="title"> <?= Yii::$service->page->translate->__($attrLabel); ?>：</span>
                            <span class="chose"></span>
                        </p>
                        <ul class="sub-classify" >
<?php           endif; ?>
                    <li class="sub-item <?= $selected ? 'chosed' : '';?>" rel="<?= $itemUrl ?>">
                       <?= $itemLabel; ?>(<?= $itemCount ?>)
                        <i class="iconfont chosed-icon">&#xe617;</i>
                    </li>
<?php		endif;      ?>
<?php   endforeach;     ?>
<?php   if($i >= 1):    ?>
                </ul>
            </li>
<?php	endif;      ?>
<?php  endif;      ?>
<?php  endforeach;    ?>
<?php  endif;      ?>
