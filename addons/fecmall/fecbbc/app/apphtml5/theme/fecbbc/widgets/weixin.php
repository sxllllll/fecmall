<script src="http://res.wx.qq.com/open/js/jweixin-1.6.0.js"></script>
<script>
<?php
    $shareSuccessAlert = Yii::$service->wx->h5share->shareSuccessAlert;
    $wx_param = $parentThis['wx_param'];
    $title = $wx_param['title'];  // require
    $imgUrl = $wx_param['imgUrl']; // require
    $desc = $wx_param['desc'] ? $wx_param['desc'] : '' ;
    $link = Yii::$service->wx->h5share->getPageShareLink($wx_param['link']);
?>
<?php 
    $signPackage = Yii::$service->wx->h5share->getSignPackage();
    // var_dump($signPackage);
    // weixin jssdk: https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/JS-SDK.html
?>
wx.config({
  debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
  appId: '<?= $signPackage["appId"];?>', // 必填，公众号的唯一标识
  timestamp: <?= $signPackage["timestamp"];?>, // 必填，生成签名的时间戳
  nonceStr: '<?= $signPackage["nonceStr"];?>', // 必填，生成签名的随机串
  signature: '<?= $signPackage["signature"];?>',// 必填，签名
  jsApiList: ['onMenuShareAppMessage', 'onMenuShareTimeline'] // 必填，需要使用的JS接口列表
});
//wx.error(function(res){
  // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
//    console.log(res);
//});
  var wxSharelink = '<?= $link ?>';
  var shareSuccessAlert = '<?= $shareSuccessAlert ?>';
    wx.ready(function () {
      wx.onMenuShareAppMessage({ 
        title: '<?= $title; ?>', // 分享标题
        desc: '<?= $desc; ?>', // 分享描述
        link: wxSharelink, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: '<?= $imgUrl ?>', // 分享图标
        success: function () {
            // 设置成功
            if (shareSuccessAlert) {
                alert(shareSuccessAlert);  
            }
        }
      });
      
      wx.onMenuShareTimeline({ 
        title: '<?= $title; ?>', // 分享标题
        desc: '<?= $desc; ?>', // 分享描述
        link: wxSharelink, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: '<?= $imgUrl ?>', // 分享图标
        success: function () {
            if (shareSuccessAlert) {
                alert(shareSuccessAlert);  
            }
        }
      });
      
    }); 
</script>