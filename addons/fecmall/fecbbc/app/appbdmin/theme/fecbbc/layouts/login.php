<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use fecadmin\myassets\LoginAsset;
use common\widgets\Alert;
use fec\helpers\CUrl;
# css config
$cssOptions = [
    # css config 1.
    [
        'css'	=>[
            'css/login.css',
        ],
    ],
];
\Yii::$service->page->asset->jsOptions 	= [];
\Yii::$service->page->asset->cssOptions = $cssOptions;
\Yii::$service->page->asset->register($this);
$logoPath = $this->assetManager->publish('@fecbbc/app/appbdmin/theme/fecbbc/assets/images/fecmall_logo.png');  // blue_logo.png
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>"  xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php $currentLangCode = Yii::$service->bdmin->getCurrentLangCode() ; ?>
<?php $langArr = Yii::$service->bdmin->getLangArr() ?>
<div data-v-5cb71550  class="login-container">
    <?= $content; ?>
</div>      
 
<ul data-v-54d0b3ce="" class="el-dropdown-menu el-popper el-dropdown-menu--medium " id="dropdown-menu-9947"  style="display:none;transform-origin: center top; z-index: 2001; position: fixed; top: 180px; left: 1103px;" x-placement="bottom-end">
    <?php foreach ($langArr as $code => $name): ?>
        <li data-v-54d0b3ce=""  class="el-dropdown-menu__item  <?= ($code == $currentLangCode) ? ' is-disabled' : ''  ?>  " rel="<?= $code ?>"><?= $name ?></li>
    <?php endforeach; ?>
    <div x-arrow="" class="popper__arrow" style="left: 59px;"></div>
</ul>

<?php $this->endBody() ?>
<script> 
　$(document).ready(function(){
    
        $(".set-language.el-dropdown").click(function(){
            
            if ($(this).hasClass("isHide")) {
                $(this).removeClass("isHide");
                $(".el-dropdown-menu--medium").show();
            } else {
                $(this).addClass("isHide");
                $(".el-dropdown-menu--medium").hide();
            }
        });
    
        $(".el-dropdown-menu li").click(function(){
            //alert(1);
            $langCode = $(this).attr("rel");
            $.ajax({
                url:'<?= Yii::$service->url->getUrl('fecbdmin/login/changelang')  ?>',
                async:true,
                timeout: 80000,
                dataType: 'json', 
                type:'get',
                data:{
                    'lang':$langCode,
                },
                success:function(data, textStatus){
                    if (data.status == "success"){
                        url = window.location.href;
                        arr = url.split("?");
                        u = arr[0] + '?lang=' + $langCode;
                        window.location.href = u;
                    }
                },
                error:function(){
                    alert('error');
                }
            });
        });    
    
    });  
</script> 
                            
</body>
</html>
<?php $this->endPage() ?>
