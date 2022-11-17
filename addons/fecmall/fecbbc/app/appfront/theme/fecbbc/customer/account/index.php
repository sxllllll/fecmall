<div class="user-me-page me-page yoho-page clearfix">
    <p class="home-path">
        <span class="path-icon"></span>
        <a href="<?= Yii::$service->url->homeUrl(); ?>"><?= Yii::$service->page->translate->__('Home') ?></a>
        &nbsp;&nbsp;&gt;&nbsp;&nbsp;
        <span><?= Yii::$service->page->translate->__('Account Center') ?></span>
    </p>    
    <div class="home-navigation block">
        <?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>
    </div>
    <div class="me-main">
        <div class="account block">
            <div class="title">
                <h2>
                    <?= Yii::$service->page->translate->__('Account Security') ?>
                    <span>SECURITY</span>
                </h2>
            </div>
            <div class="edit-box">
                <div class="main-info">
                    <p class="p1">
                        <?= Yii::$service->page->translate->__('Account Info') ?>
                    </p>
                    <p>
                        <?= Yii::$service->page->translate->__('Level'); ?>：<?= Yii::$service->page->translate->__('Register User'); ?> <br />
                        <?= Yii::$service->page->translate->__('From your My Account Dashboard you have the ability to view a snapshot of your recent account activity and update your account information. Select a link below to view or edit information.'); ?>
                    </p>
                    <div class="m_notice">
                        <?= Yii::$service->page->translate->__('User Center Announcement'); ?>!
                    </div>
                </div>
            </div>
            
            <div class="edit-box">
                <div class="main-info">
                    <p class="p1">
                        <?= Yii::$service->page->translate->__('Account Info'); ?>
                    </p>
                    <table border="0" class="acc_tab" style="width:870px;" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="td_l b_none"><?= Yii::$service->page->translate->__('Phone'); ?>：</td>
                        <td><?= $email ?></td>
                      </tr>
                      
                      <tr>
                        <td class="td_l b_none"><?= Yii::$service->page->translate->__('Register Date'); ?>：</td>
                        <td><?= date('Y-m-d H:i:s', $created_at) ?></td>
                      </tr>
                      
                    </table>
                    <br/>
                    <table border="0" class="mon_tab" style="width:870px; margin-bottom:20px;" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="33%"><?= Yii::$service->page->translate->__('User Level'); ?>：<span style="color:#555555;"><?= Yii::$service->page->translate->__('Ordinary member'); ?></span></td>
                        
                      </tr>
                      
                    </table>
            
                </div>
            </div>
            
            <div class="edit-box">
                <div class="main-info">
                    <p class="p1">
                        <?= Yii::$service->page->translate->__('Address Info'); ?>
                    </p>            
                    
                    <table border="0" class="mon_tab" style="width:870px; margin-bottom:20px;" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="33%">
                            <a href="<?= $accountAddressUrl ?>"><?= Yii::$service->page->translate->__('Manager Addresses'); ?></a>   
                        </td>
                        
                      </tr>
                      
                    </table>
                </div>
            </div>
            
            <div class="edit-box">
                <div class="main-info">
                    <p class="p1">
                        <?= Yii::$service->page->translate->__('Order Info'); ?>
                    </p>        
                    <table border="0" class="mon_tab" style="width:870px; margin-bottom:20px;" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="33%">
                            <a href="<?= $accountOrderUrl ?>"><?= Yii::$service->page->translate->__('View Order'); ?></a>
                        </td>
                      </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

