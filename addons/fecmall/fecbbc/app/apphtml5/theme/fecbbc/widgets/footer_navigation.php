<footer id="yoho-footer" class="yoho-footer" style="display: block;">
    <p class="op-row">
        
        <span class="back-to-top">
            <?= Yii::$service->page->translate->__('To Top'); ?>
            <i class="iconfont">&#xe615;</i>
        </span>
    </p>
    <div class="float-top  "></div>
    <p></p>
    <address class="copyright">
        CopyRight©2019 <?= Yii::$service->page->translate->__('Qingdao Fei Cat Technology Co., Ltd.'); ?>
    </address>
</footer>

<script>
<?php $this->beginBlock('footer_navigation') ?>
$(document).ready(function(){
    // 点击回到页面顶部
    $(".back-to-top").click(function(){
        $("html,body").animate({scrollTop:0},"slow");
    });
    $(".float-top").click(function(){
        $("html,body").animate({scrollTop:0},"slow");
    });
    
});
// 显示和隐藏回到页面顶部图标
$(window).scroll(function(){
    var scrollTop = $(this).scrollTop();
    var scrollHeight = $(document).height();
    var windowHeight = $(this).height();
    if (scrollTop + windowHeight >= scrollHeight) {
        $("#yoho-footer .float-top").removeClass("hover");
    } else if ( scrollTop + windowHeight >= 1000) {
        $("#yoho-footer .float-top").addClass("hover");
    } else if ($("#yoho-footer .float-top").hasClass("hover")){
        $("#yoho-footer .float-top").removeClass("hover");
    }
});
<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['footer_navigation'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>
  
 
