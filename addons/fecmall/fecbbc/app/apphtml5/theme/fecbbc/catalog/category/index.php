<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main-wrap" id="main-wrap">
    <header id="yoho-header" class="yoho-header boys">
        <a href="javascript:history.go(-1);" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title"><?= Yii::$service->page->translate->__('Category Product'); ?></p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    
    <div class="good-list-page yoho-page">
        <div class="filter-tab">
            <ul id="list-nav" class="list-nav clearfix">
                <li class="default  active buriedpoint  goods-sort first-li-more">
                    <a href="javascript:void(0);">
                        <span class="nav-txt"><?= Yii::$service->page->translate->__('Sort'); ?></span>
                        <span class="iconfont drop">&#xe613;</span>
                    </a>
                </li>
                
                <li class="filter buriedpoint goods-filter"  style="float:right;">
                    <a href="javascript:void(0);">
                        <span class="nav-txt"><?= Yii::$service->page->translate->__('Filter'); ?></span>
                        <span class="iconfont cur">&#xe613;</span>
                    </a>
                </li>
            </ul>
            <?php
                $parentThis = [
                    'query_item' => $query_item,
                ];
                $toolbar = Yii::$service->page->widget->render('category/toolbar', $parentThis);
                echo $toolbar;
            ?>
            
        </div>    
        <div id="goods-container" class="goods-container">
            
            <div class="default-goods container clearfix">
                <?php if (is_array($products) && !empty($products)): ?>
                    <?=  Yii::$service->page->widget->render('cms/productlist' , ['products' => $products] ); ?>
                <?php else: ?>
                    <div class="no-goods">
                        <?= Yii::$service->page->translate->__('there is no goods');  ?>
                    </div>
                <?php endif; ?>
                <div class="scroll-preloader"></div>
            </div>
            
            
            <div class="filter-mask hide" style="touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                <div class="filter-body">
                    <ul class="classify">
                        <?php
                            # Category Left Filter Product Attributes
                            $parentThis = [
                                'filters' => $filter_info,
                            ];
                            echo Yii::$service->page->widget->render('category/filter_attr', $parentThis);
                        ?>
                        <?php
                            # Category Left Filter Product Price
                            $parentThis = [
                                'filter_price' => $filter_price,
                            ];
                            echo Yii::$service->page->widget->render('category/filter_price', $parentThis);
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?= Yii::$service->page->widget->render('base/footer_navigation',$this); ?>
</div>



<script>
    <?php $this->beginBlock('category_product_filter') ?> 
    lazyload();
    $(document).ready(function(){
        // 默认显示第一个filter属性内容
        $(".filter-body ul li.classify-item:first").addClass("active");
        // 切换filter属性显示
        $(".filter-body ul li.classify-item").click(function(){
            $(".filter-body ul li.classify-item").removeClass("active");
            $(this).addClass("active");
            
        });
        // 点击filter，跳转
        $(".filter-body ul li.classify-item ul li.sub-item").click(function(){
            $url = $(this).attr("rel");
            window.location.href = $url ;
        });
        // sort部分的弹出层
        $(".goods-sort a").click(function(){
            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
                $(".drop-list").addClass("hide");
            } else {
                $(this).addClass("active");
                $(".drop-list").removeClass("hide");
                $(".goods-filter").removeClass("active");
            }
            $(".goods-sort").addClass("active");
            $(".filter-mask").addClass("hide");
        });
        // filter部分的弹出层
        $(".goods-filter a").click(function(){
            if ($(".goods-filter").hasClass("active")) {
                $(".goods-filter").removeClass("active");
                $(".filter-mask").addClass("hide");
            } else {
                $(".goods-filter").addClass("active");
                $(".filter-mask").removeClass("hide");
                $(".goods-sort").removeClass("active");
            }
            $(".drop-list").addClass("hide");
            $(".goods-sort a").removeClass("active")
        });
    });
    // 无限加载产品ajax
    var loadingProduct = false;
    var loadComplete = false;
    var pageNum = 1;
	var maxPage = <?= $page_count ? $page_count : 1 ?>;
	var isLoad =false;
    $(document).scroll(function(){
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        
        if (!isLoad && scrollTop + windowHeight >= scrollHeight) {
            isLoad = true;
            loadingProduct = true;
            if(maxPage <= pageNum){
                $('.scroll-preloader').hide();
            } else {
                load();
            }
            isLoad = false;
        }
    });
    
    function load(){
        if (!loadingProduct) {
            return;
        }
        pageNum++;
        $.ajax({
            type: "GET",
            url: window.location.href,
            data: {p: pageNum},
            dataType: "json",
            beforeSend: function(){
                $(".scroll-preloader").show();
            },
            success: function (data) {
                loadingProduct = false;
                $(".scroll-preloader").hide();
                //alert(data);
				html = data.html;
				//alert(html);
				$('.default-goods.container').append(html);
                lazyload();
            }
        });
    }
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['category_product_filter'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
<?= Yii::$service->page->trace->getTraceCategoryJsCode($this, $categoryM, $products)  ?>
<?php
    // 添加微信share
    $categoryPageTitle = $name;
    $categoryPageDesc = Yii::$service->wx->h5share->categoryPageDesc;
    $categoryPageImgUrl = Yii::$service->wx->h5share->categoryPageImgUrl;
    $wx_param = [ 'title' => $categoryPageTitle, 'imgUrl' => $categoryPageImgUrl,  'desc' => $categoryPageDesc];
    echo Yii::$service->page->widget->render('base/wx', ['wx_param' => $wx_param]); 
?>
<style>
.good-info{margin-bottom:0.2rem}
</style>