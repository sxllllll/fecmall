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
$media_size = $parentThis['media_size'];
$image = $parentThis['image'];
$productImgMagnifier = $parentThis['productImgMagnifier'];
$small_img_width = 103;   //$media_size['small_img_width'];
$small_img_height = 103;  //$media_size['small_img_height'];
$middle_img_width = 470; //$media_size['middle_img_width'];
?>
<?php  $main_img = isset($image['main']['image']) ? $image['main']['image'] : '' ?>

<?php
if(isset($image['gallery']) && is_array($image['gallery']) && !empty($image['gallery'])):
    $gallerys = $image['gallery'];
    $gallerys = \fec\helpers\CFunc::array_sort($gallerys,'sort_order',$dir='asc');
    if(is_array($image['main']) && !empty($image['main'])):
        $main_arr[] = $image['main'];
        $gallerys = array_merge($main_arr,$gallerys);
    endif;
elseif(is_array($image['main']) && !empty($image['main'])):
    $main_arr[] = $image['main'];
    $gallerys = $main_arr;
endif;
if(is_array($gallerys) && !empty($gallerys)):
?>

    <div class="pull-left imgs clearfix">
            <div class="pull-left img">
                <div class="tags clearfix"></div>
                <div id="min-img">
                    <div class="main_MiddleImg" id="middleDiv">
                        <img id="img-show" class="img-show lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getUrl($main_img);  ?>" alt="" />
                        <div class="magnifier move-object " id="move-object" style="display: none; width: 236px; height: 197px; top: 247px; left: 159px;" ></div>
                        <div class="magnifier move-over" id="move-over"></div>
                    </div>
                    <div id="max" class="magnifier max " style="display:none;">
                        <img id='bigImg' class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getUrl($main_img);  ?>" />
                    </div>
                </div>
            </div>
            <div id="thumbs" class="pull-right thumbs">
                <div class="thumb-wrap">
                    <?php
                $k = 0;
                foreach($gallerys as $gallery):
                    $image		= $gallery['image'];
                    $sort_order = $gallery['sort_order'];
                    $label 		= $gallery['label'];
                    $k++;
                ?>
                    <img src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getResize($image,$middle_img_width,false) ?>" 
                        width="58" height="58" 
                        tsImgS="<?= Yii::$service->product->image->getUrl($image);  ?>"
                        class="thumb lazyload <?= $k == 1 ?  'active' : '' ?>" 
                        />
                    <?php endforeach; ?>
                </div>
        </div>
    </div>
 <?php endif; ?>   
    
    
 
