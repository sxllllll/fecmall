<div id="yoho-tip" class="yoho-tip" style="display: none;" ></div>

<script>
	<?php $this->beginBlock('afterContent') ?>
    
    function addTips(str){
        $("#yoho-tip").html(str).fadeIn().delay(1500).fadeOut();
    }
    
	<?php $this->endBlock(); ?> 
	<?php $this->registerJs($this->blocks['afterContent'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>
  
 