<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
?>
<?php
use fecshop\app\apphtml5\helper\Format;
?>
<div class="main-wrap" id="main-wrap">
    <header id="yoho-header" class="yoho-header boys">
        <a href="<?= Yii::$service->url->getUrl('customer/account') ?>" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title">
            <?= Yii::$service->page->translate->__('Product Favorite'); ?>
        </p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
    <div class="yoho-favorite-page yoho-page">
        <div class="fav-content" id="fav-content" style="touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
            <div class="fav-type show">
                <?php if(is_array($coll) && !empty($coll)):  ?>
                    <ul class="fav-product-list">
                        <?php foreach($coll as $one): ?>
                        <?php  $main_img = $one['image']['main']['image'];  ?>
                        <li data-id="1315603" class="">
                            <a href="<?= $product_one['url'] ?>">
                                <div class="fav-img-box">
                                    <img src="<?= Yii::$service->product->image->getResize($main_img,[160,200],false) ?>" alt="">
                                </div>
                            </a>
                            <div class="fav-info-list">
                                <h2><?= Yii::$service->store->getStoreAttrVal($one['name'],'name')  ?></h2>
                                <div class="fav-price">
                                    <?= Yii::$service->page->widget->DiRender('category/price', ['productModel' => $one]); ?> 
                                </div>
                                <div class="save-price">
                                    <span class="del-fav iconfont" onclick='doPost("<?= Yii::$service->url->getUrl('customer/productfavorite') ?>", {"type":"remove", "favorite_id":"<?= $one['favorite_id'] ?>", "<?= CRequest::getCsrfName() ?>": "<?= CRequest::getCsrfValue() ?>" })'>
                                        &#xe621;
                                    </span>
                                </div>
                            </div>
                            
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="fav-content-loading hide"></div>
                    <div class="fav-box ">
                        <span class="fav-null">
                            <?= Yii::$service->page->translate->__('You have no favorite product yet'); ?>
                        </span>
                        <a class="go-shopping" href="<?= Yii::$service->url->homeUrl();  ?>">
                            <?= Yii::$service->page->translate->__('Walk Around'); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="fav-load-more fav-load-background hide"></div>
            </div>
        </div>
    </div>
</div>



<script>
<?php $this->beginBlock('favoriteProduct') ?>
	function doPost(to, p) { // 
        var myForm = document.createElement("form");
        myForm.method = "post";
        myForm.action = to;
        for (var i in p){
            //alert(p[i]);
            var myInput = document.createElement("input");
            myInput.setAttribute("name", i); // 
            myInput.setAttribute("value", p[i]); // 
            myForm.appendChild(myInput);
        }
        document.body.appendChild(myForm);
        myForm.submit();
        document.body.removeChild(myForm); // 
    }

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['favoriteProduct'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>

