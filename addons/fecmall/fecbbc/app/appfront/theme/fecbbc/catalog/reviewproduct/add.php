<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
use fecshop\app\appfront\helper\Format;
?>

<div class="order-detail-page me-page yoho-page clearfix">
    <p class="home-path">
        <span class="path-icon"></span>
        <a href="<?= Yii::$service->url->homeUrl(); ?>"><?= Yii::$service->page->translate->__('Home') ?></a>
        &nbsp;&nbsp;&gt;&nbsp;&nbsp;
        <span><?= Yii::$service->page->translate->__('Goods Review') ?></span>
    </p>    
    <div class="home-navigation block">
        <?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>

    </div>
    <div class="me-main">
        <div class="myaccount-content" style="margin:20px;">
                <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
                <form class="review_form" method="post" action="">
                    <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
                    <input type="hidden" name="editForm[rate_star]"  class="rate_star" value="5" />
                    <p>
                        <img style="float:left;" src="<?= Yii::$service->product->image->getResize($main_img,[159,159],false) ?>"/>
                        <textarea class="review_content" name="editForm[review_content]" rows="5" placeholder="<?= Yii::$service->page->translate->__('Please write down the feelings about the goods, and help others very much.'); ?>" ></textarea>
                    </p>	
                    <ul style="margin-top:20px;">
                        <li style="float: left;  margin-right: 10px;  line-height: 30px;">
                            <?= Yii::$service->page->translate->__('Goods Review'); ?>
                        </li>
                        <li class="assess-right">
                            <img onclick="level(1)" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn01.png')  ?>"/>
                            <img onclick="level(2)" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn01.png')  ?>"/>
                            <img onclick="level(3)" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn01.png')  ?>"/>
                            <img onclick="level(4)" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn01.png')  ?>"/>
                            <img onclick="level(5)" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/detail-iocn01.png')  ?>"/>
                        </li>
                    </ul>
                    <ul style="margin-top:50px;">
                        <input type="submit" value="<?= Yii::$service->page->translate->__('Add Review'); ?>" class="log_btn">
                    </ul>
                </form>
            </div>
       
    </div>
</div>    


<style>
.review_content{
    width: 579px;
    height: 159px;
    border: none;
    padding: 10px;
    color: #777;
    border: 1px solid #dadada;
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
    
