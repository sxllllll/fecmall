<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
 
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title><?= \Yii::$app->view->title; ?></title>
<link rel="shortcut icon" href="<?=  Yii::$service->url->getUrl('favicon.ico'); ?>">
<link rel="apple-touch-icon" href="<?=  Yii::$service->url->getUrl('apple-touch-icon.png'); ?>">
<meta name="robots" content="INDEX,FOLLOW" />
<?php $parentThis->head() ?>
<script>
    (function(doc, win) {
        var html = doc.getElementsByTagName("html")[0],
        // orientationchange->手机屏幕转屏事件
        // resize->页面大小改变事件(一边pc端有用)
        reEvt = "orientationchange" in win ? "orientationchange" : "resize",
        reFontSize = function() {
            var clientW = doc.documentElement.clientWidth || doc.body.clientWidth;
            if(!clientW) {
                return;
            }
            html.style.fontSize = 23.31 * (clientW / 375) + "px";
        }
        win.addEventListener(reEvt, reFontSize);
        // DOMContentLoaded->dom加载完就执行,onload要dom/css/js都加载完才执行
        doc.addEventListener("DOMContentLoaded", reFontSize);
    })(document, window);
</script>