<div class="main-wrap" id="main-wrap">
    <header id="yoho-header" class="yoho-header boys">
        <a href="javascript:history.go(-1);" class="iconfont nav-back">&#xe610;</a>
        <span class="iconfont nav-home new-nav-home">&#xe638;</span>
        <p class="nav-title"><?= Yii::$service->page->translate->__('Account Center');?></p>
        <?= Yii::$service->page->widget->render('base/header_navigation',$this); ?>
    </header>
    <div class="new-home-c yoho-page">
        <?php if (!$isGuest): ?>
            <div class="header">
                <a href="<?= Yii::$service->url->getUrl('customer/editaccount') ?>">
                    <div class="left">
                        <div class="user-avatar" style="background-image: url(<?= Yii::$service->image->getImgUrl('addons/fecbbc/02456ade977d8dfdbc4ca548b196c1d62b.png') ?>);"></div>
                        <div class="level level-0"></div>
                    </div>
                    <div class="right">
                        <div class="name eps"><?= Yii::$service->page->translate->__('Edit Account');?></div>
                        <div class="trend-code-c">
                            <div class="scroll-c go-scroll">
                                <div class="scroll-words trend-code">
                                    <?= $email ?>
                                </div>
                            </div>
                            <div class="dot">&nbsp;</div>
                            <div class="iconfont edit code-set">&#xe731;</div>
                        </div>
                    </div>
                </a>
            </div>
        <?php else: ?>
            <div class="header">
                <a class="login-btn" href="<?= Yii::$service->url->getUrl('customer/account/login'); ?>">
                    <?= Yii::$service->page->translate->__('Login');?>&nbsp;/&nbsp;
                    <?= Yii::$service->page->translate->__('Register');?>
                </a>
            </div>
        <?php endif; ?>
        
        <div class="list s-list">
            <div class="list-item">
                <div class="body">
                    <div class="main eps"><?= Yii::$service->page->translate->__('My Order')   ?></div>
                    <a href="<?= Yii::$service->url->getUrl('customer/order') ?>">
                        <div class="value"><?= Yii::$service->page->translate->__('All Order')   ?></div>
                    </a>
                    <div class="arr iconfont">&#xe604;</div>
                </div>
            </div>
        </div>
        
        <div class="service">
            <a href="<?= Yii::$service->url->getUrl('customer/order', ['order_status' => 'waiting_payment']) ?>">
                <div class="service-item">
                    <div class="iconfont pic">&#xe736;</div>
                    <p class="name"><?= Yii::$service->page->translate->__('Pending')   ?></p>
                </div>
            </a>
            <a href="<?= Yii::$service->url->getUrl('customer/order', ['order_status' => 'waiting_shipping']) ?>">
                <div class="service-item">
                    <div class="iconfont pic">&#xe735;</div>
                    <p class="name"><?= Yii::$service->page->translate->__('Shipping')   ?></p>
                </div>
            </a>
            <a href="<?= Yii::$service->url->getUrl('customer/order', ['order_status' => 'waiting_receive']) ?>">
                <div class="service-item">
                    <div class="iconfont pic">&#xe737;</div>
                    <p class="name"><?= Yii::$service->page->translate->__('Shipped')   ?></p>
                </div>
            </a>
        </div>
         
        <div class="list">
            <a class="list-item" href="<?= Yii::$service->url->getUrl('customer/editpassword'); ?>">
                <div class="pic iconfont">&#xe723;
                </div>
                <div class="body">
                    <div class="main eps"><?= Yii::$service->page->translate->__('Modify Password')   ?></div>
                    <div class="value"></div>
                    <div class="arr iconfont">&#xe604;</div>
                </div>
            </a>
            <a class="list-item" href="<?= Yii::$service->url->getUrl('customer/address'); ?>">
                <div class="pic iconfont">&#xe637;</div>
                <div class="body">
                    <div class="main eps"><?= Yii::$service->page->translate->__('My Address')   ?></div>
                    <div class="value"></div>
                    <div class="arr iconfont">&#xe604;</div>
                </div>
            </a>
            <a class="list-item" href="<?= Yii::$service->url->getUrl('customer/productfavorite'); ?>">
                <div class="pic iconfont">&#xe605; </div>
                <div class="body">
                    <div class="main eps"><?= Yii::$service->page->translate->__('My Favorite')   ?></div>
                    <div class="value"></div>
                    <div class="arr iconfont">&#xe604;</div>
                </div>
            </a>
            <a class="list-item" href="<?= Yii::$service->url->getUrl('customer/productreview'); ?>">
                <div class="pic iconfont">&#xe738; </div>
                <div class="body">
                    <div class="main eps"><?= Yii::$service->page->translate->__('My Review')   ?></div>
                    <div class="value"></div>
                    <div class="arr iconfont">&#xe604;</div>
                </div>
            </a>
            
            <a class="list-item" href="<?= Yii::$service->url->getUrl('customer/coupon'); ?>">
                <div class="pic iconfont">&#xe739;</div>
                <div class="body">
                    <div class="main eps"><?= Yii::$service->page->translate->__('My Coupon')   ?></div>
                    <div class="value"></div>
                    <div class="arr iconfont">&#xe604;</div>
                </div>
            </a>
            
            <a class="list-item" href="<?= Yii::$service->url->getUrl('customer/contacts'); ?>">
                <div class="pic iconfont">&#xe730;</div>
                <div class="body">
                    <div class="main eps"><?= Yii::$service->page->translate->__('Services & Contacts')   ?></div>
                    <div class="value"></div>
                    <div class="arr iconfont">&#xe604;</div>
                </div>
            </a>
        </div>
        <?php if (!$isGuest): ?>
        <div class="list">
            <button id="smsLoginBtn" class="sms-login-btn active" type="button">
                <?= Yii::$service->page->translate->__('Logout')   ?>
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>
<style>
#smsLoginBtn{
    width: 13.5rem;
height: 1.75rem;
border-radius: .1rem;
background-color: #b0b0b0;
font-size: .8rem;
color:
#fff;
margin: 1rem 1rem 2rem;
}
#smsLoginBtn.active {
    background-color: 
    #444 !important;
}
</style>
<script>
<?php $this->beginBlock('customer_account') ?>  
$(document).ready(function(){
    $("#smsLoginBtn").click(function(){
        window.location.href="<?= Yii::$service->url->getUrl('customer/account/logout'); ?>";
    });
});
<?php $this->endBlock(); ?>  
<?php $this->registerJs($this->blocks['customer_account'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 


