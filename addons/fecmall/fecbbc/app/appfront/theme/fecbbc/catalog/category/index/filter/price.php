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
            ?>
                
                <div class="price section">
                    <span class="title"><?= Yii::$service->page->translate->__($attr); ?>：</span>
                    <div class="attr-content clearfix">
                        <?php
                        foreach($filter as $item):
                            $val = $item['val'];
                            $url = $item['url'];
                            $selected = $item['selected'] ? 'class="now"' : '';
                            if($val && $url):
                                ?>
                                <a class="attr" href="<?= $url;?>"  rel="nofollow">
                                    <?= Yii::$service->page->translate->__($val); ?>
                                </a>
                            <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
            <?php
        endif;
    endforeach;
endif;
?>
