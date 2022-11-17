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
    $arr = [ // css 
        'Account Dashboard' => 'fa-dashboard',
        'Account Information' => 'fa-user',
        'Address Book' => 'fa-map-marker',
        'My Orders' => 'fa-cart-arrow-down',
        'My Product Reviews' => 'fa-comments',
        'My Favorite' => 'fa-heart',
    ];
?>

<p class="title ucenter"></p>
<div class="user-thumb">
    <div class="thumb-bg">
        <img id="user-thumb" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/account-1.jpg')  ?>  ">
    </div>
</div>
<div class="nav-group">
    <!--
    <h2 class="nav-title row">
        <span class="title-icon"></span>
        账户中心
    </h2>
    -->
    <?php  if(!empty($leftMenuArr) && is_array($leftMenuArr)):  ?>
        <?php $i = 0; ?>
        <ul>
        <?php foreach($leftMenuArr as $one):  ?>
            <?php
                $active = '';
                $first = '';
                if($one['current'] == 'class="current"'){
                    $active = 'active';
                }
                if (!$i) {
                    $first = 'first';
                }
                    
                $i++;
            ?>
            <li class="row <?= $active ?> <?= $first ?>">
                <a href="<?= $one['url'] ?>"  class="<?= $active ?>" >
                    <?= Yii::$service->page->translate->__($one['name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
            <li class="row ">
                <a href="<?= Yii::$service->url->getUrl('distribute/account') ?>"  class="" >
                    分销商中心
                </a>
            </li>
        </ul>
    <?php endif; ?>
    </ul>
</div>
        
