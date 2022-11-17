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
    foreach($parentThis['filters']  as $attr => $filter):
        $attrLabel = $filter['label'];
        $attrName = $filter['name'];
		if(is_array($filter['items']) && !empty($filter['items'])):
            $i = 0;
            foreach($filter['items'] as $item):
            //var_dump($item);exit;
				$itemName    = $item['_id'];
                $itemLabel    = $item['label'];
                $itemCount    = $item['count'];
                $itemUrl    = $item['url'];
                $selected    = $item['selected'];
				if($itemName):
                    $i++;
                    if($i == 1):
                        ?>
                        <div class="channel section">
                            <span class="title"><?= Yii::$service->page->translate->__($attrLabel); ?>：</span>
                            <div class="attr-content clearfix">
                        <?php endif; ?>
                    
                    <a class="attr <?= $selected ? 'checked' : '';?>" href="<?= $itemUrl;?>" >
                        <?= $itemLabel; ?>(<?= $itemCount; ?>)
                    </a>
                    <?php
                endif;
            endforeach;
            if($i >= 1):
                ?>
                    </div>
                </div>
                <?php
            endif;
        endif;
    endforeach;
endif;
?>

