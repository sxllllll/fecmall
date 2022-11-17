<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
?>

<div class="user-me-page me-page yoho-page clearfix">
    <p class="home-path">
        <span class="path-icon"></span>
        <a href="<?= Yii::$service->url->homeUrl(); ?>"><?= Yii::$service->page->translate->__('Home') ?></a>
        &nbsp;&nbsp;&gt;&nbsp;&nbsp;
        <span><?= Yii::$service->page->translate->__('Address Manager') ?></span>
    </p>    
    <div class="home-navigation block">
        <?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>
    </div>
    <div class="me-main">
        <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
        <div class="address block">
            <div class="title">
                <h2><?= Yii::$service->page->translate->__('Home') ?></h2>
                <a href="<?= Yii::$service->url->getUrl('customer/address/edit') ?>" style="float:right;">
                    <?= Yii::$service->page->translate->__('Add New Address');?>
                </a>
            </div>
            
            <div class="main" style="min-height:600px;">
                <div class="address-tip"><p><?= Yii::$service->page->translate->__('Please check the address information carefully') ?></p></div>
                <ul class="address-list">
                    <?php   if(is_array($coll) && !empty($coll)):   ?>
					<?php 		foreach($coll as $one): ?>
                        <li class="address-content <?=  ($one['is_default'] == 1) ? 'preferred' : '' ?>   " addressid="">
                            <div class="address-detail">
                                <strong><?= Yii::$service->page->translate->__('Receiver Name') ?>：<?= $one['first_name'] ?></strong>
                                <br>
                                <?= Yii::$service->page->translate->__('Address State') ?>：<?= $one['state'] ?><?= $one['city'] ?><?= $one['area'] ?><?= $one['street1'] ?>
                                <br>
                                <?= Yii::$service->page->translate->__('Contact Telephone') ?>：<?= $one['telephone'] ?>
                                <br>
                            </div>
                            <div class="address-edit">
                                <a href="<?= Yii::$service->url->getUrl('customer/address/edit',['address_id' => $one['address_id']]); ?>" class="address-modify"><?= Yii::$service->page->translate->__('Modify') ?></a>
                                <a href="javascript:deleteAddress(<?= $one['address_id'] ?>)" class="address-del"><?= Yii::$service->page->translate->__('Remove') ?></a>
                                <br>
                                <?php if ($one['is_default'] == 1): ?>
                                <a href="javascript:void(0);" class="btn-c2 default-btn">
                                    <span>
                                        <?= Yii::$service->page->translate->__('Already set as default address') ?>
                                    </span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php 		endforeach; ?>
					<?php 	endif; ?>
                    <li class="clear"></li>
                </ul>
            </div>
        </div>
    </div>
</div>    

<script>
    function deleteAddress(address_id){
        var r=confirm("<?= Yii::$service->page->translate->__('do you readly want delete this address?') ?>");
        if (r==true){ 
            url = "<?= Yii::$service->url->getUrl('customer/address') ?>";
            doPost(url, {"method": "remove", "address_id": address_id, "<?= CRequest::getCsrfName() ?>": "<?= CRequest::getCsrfValue() ?>" });
        }
    }
</script>
	