<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main-wrap" id="main-wrap" >
    <header id="yoho-header" class="yoho-header boys">
        <a href="javascript:history.go(-1);" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title"><?= Yii::$service->page->translate->__('Search'); ?></p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="search-page yoho-page">
        <input type="hidden" value="<?= Yii::$service->page->translate->__('Product Search'); ?>" id="default-terms">
        <div id="search-input" class="search-input">            
            <form method="get" name="searchFrom" class="js_topSeachForm" action="<?= Yii::$service->url->getUrl('catalogsearch/index');   ?>">
                <i class="search-icon iconfont"></i>
                <input type="hidden" name="from" value="search">
                <input type="text" placeholder="<?= Yii::$service->page->translate->__('Product Search'); ?>" name="q"  class="buriedpoint search_text" autocomplete="off">
                <i class="clear-input iconfont hide" style="touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">&#xe626;</i>
                <button id="search" class="search buriedpoint" type="submit" style="font-size: 0.6rem; line-height: 1.2rem;"><?= Yii::$service->page->translate->__('Search'); ?></button>
            </form>
        </div>
        <div class="search-items clearfix">
            <div class="search-index">
                    <div class="search-group want-search">
                        <h3><?= Yii::$service->page->translate->__('Host Search'); ?></h3>
                        <div class="search-content">
                            <ul class="want clearfix">
                                <?php if (is_array($hostSearch) && !empty($hostSearch)): ?>
                                    <?php foreach ($hostSearch as $text): ?>
                                        <li >
                                            <a href="javascript:void(0);" class="s_text"><?= $text ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                   
                            </ul>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<script>
<?php $this->beginBlock('product-search') ?>
    $(document).ready(function(){
        // 搜索框输入文字，显示删除按键
        $(".search_text").on("input", function () {
            search_text = $(this).val();
            l = search_text.length;
            if (l >=1 ) {
                $(".clear-input").removeClass("hide");
            } else if (!$(".clear-input").hasClass("hide")) {
                $(".clear-input").addClass("hide");
            }
        });
        // 
        $(".clear-input").click(function(){
            $(".search_text").val('');
        });
        $(".search-items .s_text").click(function(){
            search_text = $(this).html();
            $(".search_text").val(search_text);
            $(".js_topSeachForm").submit();
        });
    });
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['product-search'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>


