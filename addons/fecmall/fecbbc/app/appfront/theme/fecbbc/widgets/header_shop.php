<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>

<div class="tool-wrapper clearfix">
    <input type="hidden" class="currentBaseUrl" value="<?= $currentBaseUrl ?>" />
    <input type="hidden" class="logoutUrl" value="<?= $logoutUrl ?>" />
    <input type="hidden" class="logoutStr" value="<?= Yii::$service->page->translate->__('Logout'); ?>" />
    <input type="hidden" class="welcome_str" value="<?= Yii::$service->page->translate->__('Welcome!'); ?>" />
    <div class="center-content">
        <div class="yoho-group-map left top_lang">
            <a href="javascript:void(0)" rel="<?= $currentStore ?>" class="current_lang" ><?= $currentStoreLang ?></a>
            <span class="icon-bottomarrow" style="display: inline-block;vertical-align: middle;line-height: 30px;"></span>
            <ul class="yoho-group-list lang_list">
                <?php foreach($stores as $store=> $langName):   ?>
                    <li  class="store_lang"  rel="<?= $store ?>">
                        <a class="yoho-group"  href="javascript:void(0)"><?= $langName ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="yoho-group-map left top_currency">
            <a href="javascript:void(0)" class="current_lang" >
                <?= $currency['symbol'] ?><?= $currency['code'] ?>
            </a>
            <span class="icon-bottomarrow" style="display: inline-block;vertical-align: middle;line-height: 30px;"></span>
            <ul class="yoho-group-list language-currency-list">
                <?php foreach($currencys as $c):    ?>
                    <li rel="<?= $c['code'] ?>">
                        <a class="yoho-group"  href="javascript:void(0)">
                            <label ><?= $c['symbol'] ?></label>
                            <?= $c['code'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>        
                
        <div class="yoho-buy-tools right">
            <ul>
                <li id="loginBox">
                    <span class="hi">Hi~</span>
                    [ <a id="signin-url" href="<?= Yii::$service->url->getUrl('customer/account/login') ?>" class="loginbar" rel="nofollow"><?= Yii::$service->page->translate->__('Login') ?></a> ]
                    [ <a id="reg-url" href="<?= Yii::$service->url->getUrl('customer/account/register') ?>" class="registbar" rel="nofollow"><?= Yii::$service->page->translate->__('Join Free') ?></a> ]
                </li>
                
                <li class="myyoho" id="myYohoBox">
                    <span class="tag-seprate"></span>
                    <a href="<?= Yii::$service->url->getUrl('customer/account') ?>" rel="nofollow"><?= Yii::$service->page->translate->__('Account Center') ?></a>
                    <span class="icon-bottomarrow"></span>
                    <div class="simple-user-center hide">
                        <div class="account-info-header">
                            <div class="user-img">
                                    <img src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/account-1.jpg')  ?>">  
                            </div>
                            <div class="user-name">
                                <a href=""></a>
                            </div>
                            <h4 class="user-level"><span><?= Yii::$service->page->translate->__('Member') ?></span></h4>
                        </div>
                        <ul class="account-info-content">
                            <li>
                                <a href="<?= Yii::$service->url->getUrl('customer/order')  ?>"><?= Yii::$service->page->translate->__('My Order') ?></a>
                                
                            </li>
                            <li>
                                <a href="<?= Yii::$service->url->getUrl('customer/productfavorite')  ?>"><?= Yii::$service->page->translate->__('My Favorite') ?></a>
                                
                            </li>
                            <li>
                                <a href="<?= Yii::$service->url->getUrl('customer/coupon')  ?>"><?= Yii::$service->page->translate->__('My Coupon') ?></a>
                                
                            </li>
                        </ul>

                        <div class="account-info-footer">
                        </div>
                    </div>
                </li>  
                <li class="myorder">
                    <span class="tag-seprate"></span>
                    <a href="<?= Yii::$service->url->getUrl('coupon/fetch/lists')  ?>" rel="nofollow">
                        <?= Yii::$service->page->translate->__('Fetch Coupon') ?>
                    </a>
                </li>
                
                <li class="myorder">
                    <span class="tag-seprate"></span>
                    <span class="icon-papers"></span>
                    <a href="<?= Yii::$service->url->getUrl('customer/order')  ?>" rel="nofollow"><?= Yii::$service->page->translate->__('My Order') ?></a>
                </li>
                <li class="mycollect">
                    <span class="tag-seprate"></span>
                    <span class="icon-heart"></span>
                    <a href="<?= Yii::$service->url->getUrl('customer/productfavorite')  ?>" rel="nofollow"><?= Yii::$service->page->translate->__('My Favorite') ?></a>
                </li>
                
                <li class="help nav-drop-down-container">
                    <span class="tag-seprate"></span>
                    <span class="iconfont">&#xe702;</span>
                    <a href="<?= Yii::$service->url->getUrl('customer/contacts')  ?>"><?= Yii::$service->page->translate->__('Services') ?></a>
                </li>
                <li class="we-chat download-code">
                    <span class="tag-seprate"></span>
                    <span class="iconfont">&#xe704;</span>
                    <a href=""><?= Yii::$service->page->translate->__('About Us') ?></a>
                    <div class="download-app-box">
                        <div class="we-chat-img code-img"></div>
                        <h5 class="qr-words">微信扫一扫</h5>
                        <h5 class="qr-words">关注公众号，送球鞋</h5>
                    </div>
                </li>
                <li class="phoneapp download-code" id="phoneApp">
                    <span class="tag-seprate"></span>
                    <span class="icon-phone"></span>
                    <a href="" rel="nofollow"><?= Yii::$service->page->translate->__('Mobiles') ?></a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="head-wrapper clearfix">
    <div class="center-content">
        <div class="outlets-logo"></div>
        <h1 class="main-logo" style="margin-top:10px;">
        <?php
            $bdmin_user_id = Yii::$app->request->get('bdmin_user_id');
            $bdmin_logo = Yii::$app->store->bdminGet($bdmin_user_id, 'bdmin_config', 'bdmin_logo');
            if ($bdmin_logo) {
                $logoImgUrl = Yii::$service->image->getUrlByRelativePath($bdmin_logo);
            }
        ?>
            <a href="<?= Yii::$service->url->homeUrl() ?>" class="main-link" title="">
                <img style="    max-width: 240px; max-height: 60px;" src="<?= $logoImgUrl ?>"  />
            </a>
        </h1>
        <div class="func-area">
            <?= Yii::$service->page->widget->render('base/topsearch',$this); ?>
            <div class="go-cart">
                <a href="<?= Yii::$service->url->getUrl('checkout/cart') ?>" rel="nofollow">
                    <span class="iconfont ">&#xe600;</span>
                    <span class="goods-num-tip">0</span>
                </a>
                <div class="mini-cart-wrapper">
                    <div class="loading-cart">
                        <h3><?= Yii::$service->page->translate->__('Loading, Please wait') ?></h3>
                    </div>
                    <div class="empty-cart">
                        <h3><?= Yii::$service->page->translate->__('There is no product in your cart') ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
   