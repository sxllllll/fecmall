<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php $options_attr_arr = $parentThis['options']; ?>
<?php # 这里是 一种类似京东的处理方式。  ?>
<?php if(is_array($options_attr_arr) && !empty($options_attr_arr)):  ?>
    <div class="chose-items" style="height:auto">
	<?php foreach($options_attr_arr as $one):   ?>
    <div class="block-list">
        <span class="name"><?= Yii::$service->page->translate->__(ucfirst($one['label']).':'); ?></span>
        <ul class="size-row clearfix">
<?php       if(is_array($one['value']) && !empty($one['value'])):  ?>
<?php		    foreach($one['value'] as $info): ?>
<?php		        $attr_val = $info['attr_val']; ?>
<?php		        $chosed   = $info['active'] == 'current' ? 'chosed' : ''; ?>
<?php		        $zero_stock   = $info['active'] == 'noactive' ? 'zero-stock' : ''; ?>
<?php		        $url   = $info['url']; ?>
<?php			    if(isset($info['show_as_img']) && $info['show_as_img']): ?>
                        <li class="block <?= $chosed; ?> <?= $zero_stock ?>" rel="<?= $url ?>" >
                            <?= Yii::$service->page->translate->__($attr_val); ?>
                        </li>
<?php			    else: ?>
                        <li class="block <?= $chosed; ?> <?= $zero_stock ?>" rel="<?= $url ?>" >
                            <?= Yii::$service->page->translate->__($attr_val); ?>
                        </li>
<?php			    endif; ?>
<?php		    endforeach; ?>
<?php		endif; ?>
			</ul>
        </div>
<?php		    endforeach; ?>
    </div>
<?php		endif; ?>

<script>
	<?php $this->beginBlock('product_options') ?>
        
        $(document).ready(function(){
            // 加入购物车面板里面的规格属性点击 
            $(".good-detail-page").on("click",".chose-panel-add-cart .chose-items  ul.size-row li",function(){
            
                $url = $(this).attr("rel");
                if($url){
                    $.ajax({
                        async:true,
                        timeout: 60000,
                        dataType: 'json', 
                        type:'get',
                        data: {},
                        url: $url,
                        success:function(data, textStatus){ 
                            //alert(data);
                            imgHtml = data.imgHtml;
                            optionsHtml = data.optionsHtml;
                            priceHtml = data.priceHtml;
                            tierPriceHtml = data.tierPriceHtml;
                            product_id = data.product_id;
                            sku = data.sku;
                            $(".banner-product-images").html(imgHtml);
                            $(".product-options-panel").html(optionsHtml);
                            $(".product_view_id").val(product_id);
                            $(".sku").val(sku);
                            $(".goods-price").html(priceHtml);
                            $(".tier_price_info").html(tierPriceHtml); 
                            
                            lazyload();
                            var swiper = new Swiper('.swiper-container', {
                              pagination: {
                                el: '.pagination-inner',
                                dynamicBullets: true,
                              },
                            });
                        },
                        error:function (XMLHttpRequest, textStatus, errorThrown){}
                    });
                }
            });
            // 页面中的规格属性点击    
            $(".good-detail-page").on("click",".page-chose-panel   ul.size-row li",function(){ 
                
                $url = $(this).attr("rel");
                if($url){
                    $.ajax({
                        async:true,
                        timeout: 60000,
                        dataType: 'json', 
                        type:'get',
                        data: {},
                        url: $url,
                        success:function(data, textStatus){ 
                            //alert(data);
                            imgHtml = data.imgHtml;
                            optionsHtml = data.optionsHtml;
                            priceHtml = data.priceHtml;
                            tierPriceHtml = data.tierPriceHtml;
                            product_id = data.product_id;
                            sku = data.sku;
                            $(".banner-product-images").html(imgHtml);
                            $(".product-options-panel").html(optionsHtml);
                            $(".product_view_id").val(product_id);
                            $(".sku").val(sku);
                            $(".goods-price").html(priceHtml);
                            $(".tier_price_info").html(tierPriceHtml); 
                            
                            lazyload();
                            var swiper = new Swiper('.swiper-container', {
                              pagination: {
                                el: '.pagination-inner',
                                dynamicBullets: true,
                              },
                            });
                        },
                        error:function (XMLHttpRequest, textStatus, errorThrown){}
                    });
                }
            });
            
            
        });
    <?php $this->endBlock(); ?> 
	<?php $this->registerJs($this->blocks['product_options'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>     
        
