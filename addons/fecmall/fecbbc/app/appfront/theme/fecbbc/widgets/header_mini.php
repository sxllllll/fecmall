<div class="simple-header">
    <input type="hidden" class="currentBaseUrl" value="<?= $currentBaseUrl ?>" />
    <input type="hidden" class="logoutUrl" value="<?= $logoutUrl ?>" />
    <input type="hidden" class="logoutStr" value="<?= Yii::$service->page->translate->__('Logout'); ?>" />
    <input type="hidden" class="welcome_str" value="<?= Yii::$service->page->translate->__('Welcome!'); ?>" />
    <div class="header-inner clearfix">
        <h2 class="logo">
            <a href="<?= Yii::$service->url->homeUrl() ?>">
                <img style="height:40px;" src="<?= Yii::$service->image->getImgUrl('appfront/custom/logo.png') ?>" alt="">
            </a>
        </h2>
        <ul class="header-tool clearfix">
            <li id="loginBox">
                <span class="hi">Hi~</span>
                [ <a id="signin-url" href="<?= Yii::$service->url->getUrl('customer/account/login') ?>" class="loginbar" rel="nofollow"><?= Yii::$service->page->translate->__('Login') ?></a> ]
                [ <a id="reg-url" href="<?= Yii::$service->url->getUrl('customer/account/register') ?>" class="registbar" rel="nofollow"><?= Yii::$service->page->translate->__('Join Free') ?></a> ]
            </li>
                
            <li class="tool-options">
                <span><a href="<?= Yii::$service->url->getUrl('customer/account') ?>">FECMALL</a></span>
                <i class="options-icon down iconfont"></i>
                <i class="options-icon up iconfont"></i>
                <div class="tool-select">
                    <a href="<?= Yii::$service->url->getUrl('customer/productfavorite') ?>"><?= Yii::$service->page->translate->__('My Favorite') ?></a>
                    <a href="<?= Yii::$service->url->getUrl('customer/coupon') ?>"><?= Yii::$service->page->translate->__('My Coupon') ?></a>
                </div>
            </li>
            <li>
                <a href="<?= Yii::$service->url->getUrl('customer/order') ?>"><?= Yii::$service->page->translate->__('My Order') ?></a>
            </li>
            <li>
                <a href="<?= Yii::$service->url->getUrl('customer/contacts') ?>"><?= Yii::$service->page->translate->__('Services') ?></a>
            </li>
            
        </ul>
    </div>
</div>