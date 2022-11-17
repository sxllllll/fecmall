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
        <a href="<?= Yii::$service->url->getUrl('customer/order') ?>" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title">
            <?= Yii::$service->page->translate->__('To Review'); ?>
        </p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
	
    <div class="contaniner fixed-cont">
        <section class="assess">
            <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
            <form class="review_form" method="post" action="">
                <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
                <input type="hidden" name="editForm[rate_star]"  class="rate_star" value="5" />
                <p>
                    <img src="<?= Yii::$service->product->image->getResize($main_img,[159,159],false) ?>"/>
                    <textarea style="border:none;width:68%" name="editForm[review_content]" rows="5" placeholder="<?= Yii::$service->page->translate->__('Please write down the feelings of the baby, and help others very much'); ?>" ></textarea>
                </p>	
                <ul>
                    <li>
                        <?= Yii::$service->page->translate->__('Product Review'); ?>
                    </li>
                    <li class="assess-right" style="padding: 0.2rem; margin-left: 0.3rem;">
                        <img onclick="level(1)" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn01.png')  ?>"/>
                        <img onclick="level(2)" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn01.png')  ?>"/>
                        <img onclick="level(3)" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn01.png')  ?>"/>
                        <img onclick="level(4)" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn01.png')  ?>"/>
                        <img onclick="level(5)" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn01.png')  ?>"/>
                    </li>
                </ul>
            </form>
        </section>
    </div>

    <footer class="assess-footer fixed-footer ">
        <ul>
            <div class="submit review_product">
                <?= Yii::$service->page->translate->__('Add Review'); ?>
            </div>
        </ul>
    </footer>
</div>  

<style>
.submit {
    margin: .75rem auto 0;
    width: 11.75rem;
    height: 2.2rem;
    color: #fff;
    background: #444;
    text-align: center;
    font-size: .8rem;
    line-height: 2.2rem;
}
</style>     
<script type="text/javascript">
<?php $this->beginBlock('product_review') ?>
    $(document).ready(function(){
        $(".review_product").click(function(){
            $(".review_form").submit();
            
        });
        
    });
    $(".assess-footer ul li label").on('touchstart',function(){
        if($(this).hasClass('assd')){
            $(".assess-footer ul li label").removeClass("assd")
        }else{
            $(".assess-footer ul li label").addClass("assd")
        };
    });
    //var start_l = 5;
    function level(s)
    {
        $(".rate_star").val(s);
        var str = '';
        var k = 6-s;
        for(i=1;i<s+1;i++)
        {	
            str += "<img onclick='level("+i+")' src='<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn01.png')  ?>'/>";
        }
        for(j=1;j<k;j++)
        {
            var d=j+s
            str += "<img onclick='level("+d+")' src='<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn001.png')  ?>'/>";
        }
        $('.assess-right').html(str);
    }

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['product_review'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>
    
