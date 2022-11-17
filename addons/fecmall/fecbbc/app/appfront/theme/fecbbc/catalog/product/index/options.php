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
    <div class="product_options">
    <?php foreach($options_attr_arr as $one):   ?>
    
        <div class="chose-size row clearfix">
            <span class="title pull-left"><?= Yii::$service->page->translate->__(ucfirst($one['label']).' :'); ?></span>
            <div id="sizes" class="size-wrapper pull-left">
                <ul class="colors pull-left clearfix">
                    <?php       if(is_array($one['value']) && !empty($one['value'])):  ?>
                        <?php		    foreach($one['value'] as $info): ?>
                        <?php		        $attr_val = $info['attr_val']; ?>
                        <?php		        $active   = $info['active']; ?>
                        <?php		        $url   = $info['url']; ?>
                        <?php			    $checked = ($active == 'current') ? 'focus' : '' ?>
                        <?php			//$url = ''; ?>
                        <?php			//$active = 'class="active"'; ?>
                        <?php			//if(isset($attr1_2_attr2[$attr1Val])){ ?>
                        <?php			//	$url = Yii::$service->url->getUrl($attr1_2_attr2[$attr1Val]['url_key']); ?>
                        <?php			//}else{ ?>
                        <?php			//	$url = Yii::$service->url->getUrl($info['url_key']); ?>
                        <?php			//} ?>
                        <?php			//if($attr1Val == $current_attr1){ ?>
                        <?php			//	$active = 'class="current"'; ?>
                        <?php			    if(isset($info['show_as_img']) && $info['show_as_img']): ?>
                        <li class=" <?=$active ?> <?= $checked ?>  pull-left  img_options"  title="" rel="<?= $url  ?>">
                            <img  class="lazyload" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getResize($info['show_as_img'],[22,30],false); ?>" alt="">
                            <span class="color-name">
                                <?= Yii::$service->page->translate->__($attr_val); ?>
                            </span>
                        </li>    
                    <?php else: ?>
                        <li class=" <?=$active ?> <?= $checked ?>  pull-left"  title="" rel="<?= $url  ?>">
                            <span class="color-name">
                                <?= Yii::$service->page->translate->__($attr_val); ?>
                            </span>
                        </li>
                    <?php endif; ?>
                    <?php
                        endforeach;
                    endif;
                    ?>  
                </ul>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
            
<script>
    <?php $this->beginBlock('product_options') ?>
    $(document).ready(function(){
        $(".product-detail-page").on("click",".product_options ul li",function(){
            $url = $(this).attr("rel");
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
                couponHtml = data.couponHtml;
                tierPriceHtml = data.tierPriceHtml;
                product_id = data.product_id;
                sku = data.sku;
                $(".product_images .imgs").html(imgHtml);
                $(".trade-content .product_options").html(optionsHtml);
                $(".product_view_id").val(product_id);
                $(".sku").val(sku);
                $(".price_info").html(priceHtml);
                $(".coupon_info").html(couponHtml);
                  
                $(".tier_price_info").html(tierPriceHtml); 
                lazyload();
                
                // 重新初始化图片放大器
                // var ulNode=document.getElementById("smallUl");
                // var liNodes=ulNode.getElementsByTagName("li");
                var middleDiv=document.getElementById("middleDiv");
                //var MiddleImg=middleDiv.getElementsByTagName("img")[0];
                var BigImg=document.getElementById("bigImg");
                var BigDIV=BigImg.parentNode;
                middleDiv.onmousemove= function (e) {
                    var moveIcon=document.getElementById("move-object");//放大镜
                    var event=window.event||e;
                    var mouseX=event.pageX;//鼠标相对于网页左边的水平距离
                    var mouseY=event.pageY;//鼠标相对于网页上边的垂直距离
                    var divPosX=this.offsetLeft+this.parentNode.parentNode.offsetLeft;//middleDiv相对于body左边的距离
                    var divPosY=this.offsetTop+this.parentNode.parentNode.offsetTop;//middleDiv相对于body上边的距离
                    
                    var distX=mouseX-divPosX;//鼠标当前位置到middleDiv左边的水平位置
                    var distY=mouseY-divPosY;//鼠标当前位置到middleDiv上边的垂直位置
                    console.log("鼠标当前位置到0："+divPosX,divPosY);
                     console.log("鼠标当前位置到1："+this.offsetLeft, this.offsetTop);
                    console.log("鼠标当前位置到2："+this.parentNode.parentNode.offsetLeft, this.parentNode.offsetTop);

                    var glassWidth=moveIcon.clientWidth;//放大镜可视宽度
                    var glassHeight=moveIcon.clientHeight;//放大镜可视高度
                    var middleDivHeight=middleDiv.clientHeight;
                    var middleDivWidth=middleDiv.clientWidth;
                    console.log("放大镜的宽高："+glassWidth,glassHeight);
                    var styleLeft,styleTop;
                    if(distX-glassWidth/2>0 && distX+glassWidth/2<middleDivWidth ){
                        styleLeft=distX-glassWidth/2;

                    }else if(distX-glassWidth/2<0){
                        styleLeft=0;
                    }else if(distX+glassWidth/2>middleDivWidth){
                        styleLeft=middleDivWidth-glassWidth;
                    }
                    if(distY-glassHeight/2>0 && distY+glassHeight/2<middleDivHeight ){
                        styleTop=distY-glassHeight/2;

                    }else if(distY-glassHeight/2<0){
                        styleTop=0;
                    }else if(distY+glassHeight/2>middleDivHeight){
                        styleTop=middleDivHeight-glassHeight;
                    }
                    moveIcon.style.top=styleTop+"px";
                    moveIcon.style.left=styleLeft+"px";
                    BigDIV.scrollTop=styleTop*1.8;    //用bigDIV的滚动距离做的
                    BigDIV.scrollLeft=styleLeft*1.8;
                    console.log( BigDIV.scrollTop,BigDIV.scrollLeft)

                };
                middleDiv.onmouseenter=function(){
                    var moveIcon=document.getElementById("move-object");//放大镜
                    moveIcon.style.display="block";//移入时放大镜显示
                    BigImg.parentNode.style.display="block";
                }

                middleDiv.onmouseleave= function () {
                    var moveIcon=document.getElementById("move-object");//放大镜
                    moveIcon.style.display="none";
                    BigImg.parentNode.style.display="none";
                }
            },
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
        });
    });
    <?php $this->endBlock(); ?>
</script>
<?php $this->registerJs($this->blocks['product_options'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
