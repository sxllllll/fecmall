<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>

<div class="login-page passport-page yoho-page clearfix">
    <div style="font-size: 12px;text-align: center">
        <div style="display: inline-block;width: 990px;  background-color: #f5f5f5; line-height: 25px; overflow: hidden; margin-top: 10px;">
            <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
                
        </div>
    </div>
    <div class="passport-cover" style="width:450px;"> 
        <div class="cover-content">
                <a href="" target="_bank">
                    <img class="cover-img" src="<?= Yii::$service->image->getImgUrl('addons/fecbbc/l_img.png')  ?>" />
                </a>
        </div>
    </div>        
    <form  enctype="multipart/form-data"  id="register-form" action="<?= Yii::$service->url->getUrl('customer/account/bdmin'); ?>" method="post" >
        <?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
        <div class="content" style="margin-top: 100px; margin-bottom: 100px;">
            <ul class="login-ul">
                <div class="desktop-login">
                    <li class="relative clearfix">
                        <h2 class="title">经销商申请</h2>
                    </li>
                    
                    <li class="relative password-login ">
                        <input value="<?= $username ?>" id="username" name="editForm[username]" class="username input va " type="text" placeholder="账户">
                        <span class="err-tip username hide">
                            <i></i>
                            <em>请填写账户</em>
                        </span>
                    </li>
                    
                    <li class="relative password-login ">
                        <input value="" id="password" name="editForm[password]" class="password input va " type="password" placeholder="密码">
                        <span class="err-tip password hide">
                            <i></i>
                            <em>请填写密码</em>
                        </span>
                    </li>
                    
                    
                    <li class="relative password-login ">
                        <input value="<?= $person ?>" id="person" name="editForm[person]" class="person input va " type="text" placeholder="公司名称">
                        <span class="err-tip person hide">
                            <i></i>
                            <em>请填写公司名称</em>
                        </span>
                    </li>
                    
                    <li class="relative password-login ">
                        <input value="<?= $telephone ?>" id="telephone" name="editForm[telephone]" class="account input va " type="phone" placeholder="联系电话">
                        <span class="err-tip telephone hide">
                            <i></i>
                            <em>请填写联系电话</em>
                        </span>
                    </li>
                    
                    <li class="relative password-login ">
                        <input value="<?= $email ?>" id="email" name="editForm[email]" class="account input va " type="text" placeholder="电子邮箱">
                        <span class="err-tip email hide">
                            <i></i>
                            <em>请填写电子邮箱</em>
                        </span>
                    </li>
                    
                    <li class="relative password-login ">
                        <input value="<?= $bdmin_bank_name ?>" id="bdmin_bank_name" name="editForm[bdmin_bank_name]" class="account input va " type="text" placeholder="开户姓名">
                        <span class="err-tip bdmin_bank_name hide">
                            <i></i>
                            <em>请填写开户姓名</em>
                        </span>
                    </li>
                    
                    
                    
                    <li class="relative password-login ">
                        <input value="<?= $bdmin_bank ?>" id="bdmin_bank" name="editForm[bdmin_bank]" class="account input va " type="text" placeholder="开户银行">
                        <span class="err-tip bdmin_bank hide">
                            <i></i>
                            <em>请填写开户银行</em>
                        </span>
                    </li>
                    
                    <li class="relative password-login ">
                        <input value="<?= $bdmin_bank_account ?>" id="bdmin_bank_account" name="editForm[bdmin_bank_account]" class="account input va " type="text" placeholder="银行账户">
                        <span class="err-tip bdmin_bank_account hide">
                            <i></i>
                            <em>请填写银行账户</em>
                        </span>
                    </li>
                    
                    <li class="relative password-login ">
                        <input value="<?= $tax_point ?>" id="tax_point" name="editForm[tax_point]" class="account input va " type="text" placeholder="税点">
                        <span class="err-tip tax_point hide">
                            <i></i>
                            <em>请填写税点</em>
                        </span>
                    </li>
                    
                    <li class="relative password-login ">
                        <input value="<?= $invoice ?>" id="invoice" name="editForm[invoice]" class="account input va " type="text" placeholder="发票信息">
                        <span class="err-tip invoice hide">
                            <i></i>
                            <em>请填写发票信息</em>
                        </span>
                    </li>
                    <li class="relative password-login ">
                        <input value="<?= $authorized_brand ?>" id="authorized_brand" name="editForm[authorized_brand]" class="account input va " type="text" placeholder="授权品牌">
                        <span class="err-tip authorized_brand hide">
                            <i></i>
                            <em>请填写授权品牌</em>
                        </span>
                    </li>
                    
                    
                    <li class="relative password-login ">
                        <select name="editForm[authorized_type]" id="authorized_type" class="select_type">
                            <option value="">授权类型</option>
                            <?= $authorizedTypeOptions ?>
                        </select>
                        <span class="err-tip authorized_type hide">
                            <i></i>
                            <em>请填写授权类型</em>
                        </span>
                    </li>
                    <li class="relative password-login ">
                        <select name="editForm[authorized_role]" id="authorized_role" class="select_type">
                            <option value="">授权权限</option>
                            <?= $authorizedRoleOptions ?>
                        </select>
                        <span class="err-tip authorized_role hide">
                            <i></i>
                            <em>请填写授权权限</em>
                        </span>
                    </li>
                    
                    <!--
                    <li class="relative password-login ">
                        <input id="authorized_letter" name="authorized_letter" class="" type="file" placeholder="授权书">
                        <span>授权书(PDF,Doc)</span>
                        <span class="err-tip authorized_letter hide">
                            <i></i>
                            <em>请上传授权书(PDF,Doc)</em>
                        </span>
                    </li>
                    -->
                    
                    <li class="relative password-login ">
                        <input value="<?= $authorized_at ?>" id="authorized_at" name="editForm[authorized_at]" class="account input va " type="text" placeholder="授权有效期，格式：2020-01-01">
                        <span class="err-tip authorized_at hide">
                            <i></i>
                            <em>请填写授权有效期</em>
                        </span>
                    </li>
                    
                    <!--
                    <li class="relative password-login ">
                        <input id="cooperation_letter" name="cooperation_letter" class="" type="file" placeholder="合作书">
                        <span>合作书(PDF,Doc)</span>
                        <span class="err-tip 	cooperation_letter hide">
                            <i></i>
                            <em>请上传合作书(PDF,Doc)</em>
                        </span>
                    </li>
                    -->
                    
                    <li class="relative password-login ">
                        <input value="<?= $cooperationed_at ?>" id="cooperationed_at" name="editForm[cooperationed_at]" class="account input va " type="text" placeholder="合作有效期，格式：2020-01-01">
                        <span class="err-tip cooperationed_at hide">
                            <i></i>
                            <em>请填写合作有效期</em>
                        </span>
                    </li>
                  
                    
                    <li class="relative password-login ">
                        <input value="<?= $shipping_date ?>" id="shipping_date" name="editForm[shipping_date]" class="account input va " type="text" placeholder="截单时间">
                        <span class="err-tip shipping_date hide">
                            <i></i>
                            <em>请填写截单时间</em>
                        </span>
                    </li>
                    <li class="relative password-login ">
                        <input value="<?= $order_date ?>" id="order_date" name="editForm[order_date]" class="account input va " type="text" placeholder="发货时效">
                        <span class="err-tip order_date hide">
                            <i></i>
                            <em>请填写发货时效</em>
                        </span>
                    </li>
                    <li class="relative password-login ">
                        <input value="<?= $remark ?>" id="remark" name="editForm[remark]" class="account input va " type="text" placeholder="备注">
                        
                    </li>
                    
                    
                    <li class="relative password-login ">
                        <input id="zip_file" name="zip_file" class="" type="file" placeholder="zip包上传">
                        <span>zip压缩包上传(合作书，授权书等)</span>
                        <span class="err-tip zip_file hide">
                            <i></i>
                            <em>请上传合作书，授权书</em>
                        </span>
                    </li>
                    
                    
            
                    <li class="desktop-login">
                        <span id="register-btn" class="register-btn btn">
                            提交申请
                        </span>
                    </li>

                </div>
            </ul>
        </div>
    </form>
</div>
<style>
.passport-page .input{
    width:350px;
}
.passport-page .err-tip{
    left:385px;
}
.passport-cover .cover-content{
    padding-top:100px;
}

.select_type{
    border: 1px solid #dbdbdb;
    height: 42px;
    width: 356px;
    text-indent: 10px;
    color: #666;
    font-size: 16px;
}
</style>
<script>
<?php $this->beginBlock('registerAccount') ?>
$(document).ready(function(){
    $(".register-btn").click(function(){
        $i = 1;
        if (!$("#username").val()) {
            $(".err-tip.username").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.username").addClass("hide");
        }
        
        if (!$("#password").val()) {
            $(".err-tip.password").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.password").addClass("hide");
        }
        
        if (!$("#person").val()) {
            $(".err-tip.person").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.person").addClass("hide");
        }
        
        if (!$("#telephone").val()) {
            $(".err-tip.telephone").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.telephone").addClass("hide");
        }
        if (!$("#email").val()) {
            $(".err-tip.email").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.email").addClass("hide");
        }
        
        
        
        
        
        
        
        
        if (!$("#bdmin_bank_name").val()) {
            $(".err-tip.bdmin_bank_name").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.bdmin_bank_name").addClass("hide");
        }
        
        if (!$("#bdmin_bank").val()) {
            $(".err-tip.bdmin_bank").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.bdmin_bank").addClass("hide");
        }
        if (!$("#bdmin_bank_account").val()) {
            $(".err-tip.bdmin_bank_account").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.bdmin_bank_account").addClass("hide");
        }
        
        if (!$("#tax_point").val()) {
            $(".err-tip.tax_point").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.tax_point").addClass("hide");
        }
        
        
        
        
        
        
        
        
        
        
        
        
        
        if (!$("#invoice").val()) {
            $(".err-tip.invoice").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.invoice").addClass("hide");
        }
        if (!$("#authorized_brand").val()) {
            $(".err-tip.authorized_brand").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.authorized_brand").addClass("hide");
        }
        if (!$("#authorized_type").val()) {
            $(".err-tip.authorized_type").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.authorized_type").addClass("hide");
        }
        if (!$("#authorized_role").val()) {
            $(".err-tip.authorized_role").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.authorized_role").addClass("hide");
        }
        
          
                    
                    
                    
        
        if (!$("#cooperationed_at").val()) {
            $(".err-tip.cooperationed_at").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.cooperationed_at").addClass("hide");
        }
        if (!$("#shipping_date").val()) {
            $(".err-tip.shipping_date").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.shipping_date").addClass("hide");
        }
        if (!$("#order_date").val()) {
            $(".err-tip.order_date").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.order_date").addClass("hide");
        }
        if (!$("#zip_file").val()) {
            $(".err-tip.zip_file").removeClass("hide");
            $i =0;
        } else {
            $(".err-tip.zip_file").addClass("hide");
        }
        
        
        
        if ($i) {
            $("#register-form").submit();
        }
    });
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['registerAccount'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>



