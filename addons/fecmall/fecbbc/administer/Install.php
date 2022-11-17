<?php
/**
 * Fecmall Addons
 */
namespace fecbbc\administer;
use Yii;
/**
 * 应用安装类
 * 您可以在这里添加类变量，在配置中的值可以注入进来。
 */
class Install implements \fecshop\services\extension\InstallInterface
{
    // 安装初始版本号，不需要改动，不能为空
    public $version = '1.0.0';
    // 类变量，在config.php中可以通过配置注入值
    public $test;
    
    /**
     * @return mixed|void
     */
    public function run()
    {
        if (!$this->installDbSql()) {
            return false;
        }
        if (!$this->copyImageFile()) {
            return false;
        }
        
        return true;
    }
    
    /**
     * 进行数据库的初始化
     * sql语句执行，多个sql用分号  `;`隔开
     */
    public function installDbSql()
    {
        $db = Yii::$app->getDb();
        
        ////

        // 1
        $db->createCommand(
            "INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Fecbbc Config Manager', 'config_fecbbc_manager', 1, '/config/fecbbc/manager', 1572925266, 1572925266, 1)"
        )->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand(
            "INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)"
        )->execute();
        
        // 2
        $db->createCommand(
            "INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Fecbbc Config Manager Save', 'config_fecbbc_manager', 2, '/config/fecbbc/managersave', 1572925393, 1572925393, 1)"
        )->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand(
            "INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)"
        )->execute();
        
        
        /////////////////////
        // 1
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Supplier Account List', 'supplier-account', 1, '/supplier/account/index', 1552012790, 1552133760, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
         $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();

        // 2
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Supplier Account Edit', 'supplier-account', 2, '/supplier/account/manageredit', 1552130585, 1552133755, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        // 3
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Supplier Account Save', 'supplier-account', 3, '/supplier/account/managereditsave', 1552130619, 1552133749, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        
        // 4
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Supplier Account Delete', 'supplier-account', 4, '/supplier/account/managerdelete', 1552130667, 1552133742, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        
        // 5
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Order After Sale Return List', 'order-after-sale', 1, '/sales/returnlist/manager', 1553477824, 1553477824, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        
        // 6
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Order After Sale Return Info', 'order-after-sale', 2, '/sales/returnlist/manageredit', 1553477852, 1553477852, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        
        // 7
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Admin Refund List', 'order-refund', 1, '/sales/refund/manager', 1553477914, 1553477914, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        
        // 8
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Admin Refund Edit', 'order-refund', 2, '/sales/refund/manageredit', 1553477996, 1553477996, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        
        // 9
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Admin Refund Save', 'order-refund', 3, '/sales/refund/managersave', 1553478031, 1553478031, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        
        // 10
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Admin Refund Accept', 'order-refund', 4, '/sales/refund/manageraccept', 1553478064, 1553478064, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        
        
        // 11
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Bdmin Refund', 'order-refund', 6, '/sales/refundbdmin/manager', 1553478105, 1553502711, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        
        // 12
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Order Settle', 'order-statistics', 1, '/sales/ordersettle/manager', 1553653551, 1553653551, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        // 12-2
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Order Settle Edit', 'order-statistics', 3, '/sales/ordersettle/manageredit', 1579187795, 1579187795, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        
        // 13
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Home Page Config', 'config-homepage', 1, '/cms/homepage/manager', 1554107046, 1554117146, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        // 14
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Home Page Config Save', 'config-homepage', 2, '/cms/homepage/managereditsave', 1554107065, 1554117214, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        // 15
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Base Info Config', 'config-baseinfo', 1, '/cms/baseinfo/manager', 1554117085, 1554117085, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        // 16
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Base Info Config Save', 'config-baseinfo', 2, '/cms/baseinfo/managereditsave', 1554117204, 1554117204, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        // 17
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Order Operate Log', 'order-log', 1, '/sales/orderlog/manager', 1554818927, 1554818927, 1)")->execute();
        
        $lastInsertId = $db->getLastInsertID() ;
        
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        
        
        
        
        
        /////////////////////////////
         $arr = [
            "
            CREATE TABLE IF NOT EXISTS `bdmin_user` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `username` varchar(50) DEFAULT NULL COMMENT '用户名',
              `password_hash` varchar(80) DEFAULT NULL COMMENT '密码',
              `password_reset_token` varchar(60) DEFAULT NULL COMMENT '密码token',
              `email` varchar(60) DEFAULT NULL COMMENT '邮箱',
              `person` varchar(100) DEFAULT NULL COMMENT '用户姓名',
              `code` varchar(100) DEFAULT NULL,
              `auth_key` varchar(60) DEFAULT NULL,
              `status` int(5) DEFAULT NULL COMMENT '状态',
              `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
              `updated_at` int(11) DEFAULT NULL COMMENT '更新时间',
              `password` varchar(50) DEFAULT NULL COMMENT '密码',
              `access_token` varchar(60) DEFAULT NULL,
              `access_token_created_at` int(11) DEFAULT NULL COMMENT 'access token 的创建时间，格式为Int类型的时间戳',
              `allowance` int(11) DEFAULT NULL,
              `allowance_updated_at` int(11) DEFAULT NULL,
              `created_at_datetime` datetime DEFAULT NULL,
              `updated_at_datetime` datetime DEFAULT NULL,
              `birth_date` datetime DEFAULT NULL COMMENT '出生日期',
              PRIMARY KEY (`id`),
              UNIQUE KEY `username` (`username`),
              UNIQUE KEY `access_token` (`access_token`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
            "
            ,
            
            "
            ALTER TABLE  `customer` ADD  `bdmin_user_id` INT( 11 ) NOT NULL COMMENT  '该用户所属的供应商的id', ADD INDEX (  `bdmin_user_id` )
            "
            ,
            "
            ALTER TABLE  `sales_flat_order` ADD  `bdmin_user_id` INT( 11 ) NOT NULL COMMENT  '供应商的id' AFTER  `customer_id` , ADD INDEX (  `bdmin_user_id` )
            "
            ,
            "
            ALTER TABLE  `sales_flat_order_item` ADD  `bdmin_user_id` INT( 11 ) NOT NULL COMMENT  '供应商的id' AFTER  `customer_id` ,ADD INDEX (  `bdmin_user_id` )
            "
            ,
            "
            ALTER TABLE  `sales_flat_order` CHANGE  `order_status`  `order_status` VARCHAR( 80 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT  '订单流程状态'
            "
            ,
            
            "
            ALTER TABLE  `sales_flat_order` ADD  `order_operate_status` VARCHAR( 80 ) NULL COMMENT  '订单操作状态' AFTER  `order_status`
            ",
            
            "
            ALTER TABLE  `sales_flat_order_item` ADD  `item_status` VARCHAR( 80 ) NULL COMMENT  '订单商品状态，退款，换货等状态'
            ",
            "
            CREATE TABLE IF NOT EXISTS `sales_flat_order_after_sale` (
                `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                `order_id` INT( 11 ) NOT NULL ,
                `item_id` INT( 11 ) NOT NULL ,
                `sku` VARCHAR( 100 ) NULL ,
                `price` DECIMAL( 12, 2 ) NULL ,
                `qty` INT( 11 ) NULL ,
                `tracking_number` VARCHAR( 100 ) NULL ,
                `created_at` INT( 11 ) NULL ,
                `updated_at` INT( 11 ) NULL
                ) ENGINE = INNODB; 
            ",
            
            "
            ALTER TABLE  `sales_flat_order_after_sale` ADD  `status` VARCHAR( 50 ) NOT NULL COMMENT  '退款状态' AFTER  `item_id` , ADD INDEX (  `status` )
            ",
            
            "
            ALTER TABLE  `sales_flat_order_after_sale` ADD  `bdmin_user_id` INT( 11 ) NULL COMMENT  '供应商id' AFTER  `order_id` , ADD INDEX (  `bdmin_user_id` )
            ",
            
            "
            ALTER TABLE  `sales_flat_order_after_sale` ADD  `customer_id` INT( 11 ) NULL AFTER  `bdmin_user_id` , ADD INDEX (  `customer_id` )
            ",
            
            "
           ALTER TABLE  `sales_flat_order_after_sale` ADD  `base_price` DECIMAL( 12, 2 ) NULL AFTER  `price` 
            ",
            "
            ALTER TABLE  `sales_flat_order_after_sale` ADD  `image` VARCHAR( 255 ) NULL AFTER  `sku` 
            ",
            "
            ALTER TABLE  `sales_flat_order_after_sale` ADD  `product_id` VARCHAR( 100 ) NULL AFTER  `sku`
            ",
            
            "
            ALTER TABLE  `sales_flat_order_after_sale` ADD  `custom_option_sku` VARCHAR( 100 ) NULL AFTER  `sku`
            ",
            
            "
            ALTER TABLE  `sales_flat_order_after_sale` ADD  `currency_code` VARCHAR( 20 ) NULL AFTER  `product_id` 
            ",
            
            "
            ALTER TABLE  `sales_flat_order_after_sale` ADD  `order_to_base_rate` DECIMAL( 12, 4 ) NULL COMMENT  '汇率' AFTER  `currency_code` 
            ",
            
            "
            ALTER TABLE  `sales_flat_order_after_sale` ADD  `increment_id` VARCHAR( 50 ) NULL AFTER  `order_id` 
            ",
            "
            ALTER TABLE  `sales_flat_order_after_sale` ADD  `payment_method` VARCHAR( 50 ) NULL AFTER  `increment_id`
            ",
            
            "
            CREATE TABLE IF NOT EXISTS `refund_bdmin` (
                `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                `bdmin_user_id` INT( 11 ) NOT NULL COMMENT  '供应商id',
                `as_id` INT( 11 ) NULL COMMENT  'after sale id',
                `increment_id` VARCHAR( 50 ) NULL ,
                `price` DECIMAL( 12, 2 ) NULL ,
                `base_price` DECIMAL( 12, 2 ) NULL ,
                `currency_code` VARCHAR( 20 ) NULL ,
                `order_to_base_rate` DECIMAL( 12, 2 ) NULL ,
                `customer_id` INT( 11 ) NULL ,
                `customer_bank_name` VARCHAR( 50 ) NULL ,
                `customer_bank_account` VARCHAR( 100 ) NULL ,
                `created_at` INT( 11 ) NULL ,
                `updated_at` INT( 11 ) NULL ,
                `type` VARCHAR( 60 ) NULL COMMENT  '退款的类型',
                INDEX (  `bdmin_user_id` )
                ) ENGINE = INNODB; 
            ",
            
            "
            CREATE TABLE IF NOT EXISTS `refund_admin` (
                `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                `bdmin_user_id` INT( 11 ) NOT NULL COMMENT  '供应商id',
                `as_id` INT( 11 ) NULL COMMENT  'after sale id',
                `increment_id` VARCHAR( 50 ) NULL ,
                `price` DECIMAL( 12, 2 ) NULL ,
                `base_price` DECIMAL( 12, 2 ) NULL ,
                `currency_code` VARCHAR( 20 ) NULL ,
                `order_to_base_rate` DECIMAL( 12, 2 ) NULL ,
                `customer_id` INT( 11 ) NULL ,
                `customer_bank_name` VARCHAR( 50 ) NULL ,
                `customer_bank_account` VARCHAR( 100 ) NULL ,
                `created_at` INT( 11 ) NULL ,
                `updated_at` INT( 11 ) NULL ,
                `type` VARCHAR( 60 ) NULL COMMENT  '退款的类型',
                INDEX (  `bdmin_user_id` )
                ) ENGINE = INNODB; 
            ",
            
            "
            ALTER TABLE  `refund_admin` ADD  `customer_bank` VARCHAR( 100 ) NULL AFTER  `customer_bank_name` 
            ",
            
            "
            ALTER TABLE  `refund_bdmin` ADD  `customer_bank` VARCHAR( 100 ) NULL AFTER  `customer_bank_name` 
            ",
            
            "
            ALTER TABLE  `customer` ADD  `customer_bank` VARCHAR( 100 ) NULL COMMENT  '银行名称（银行，支付宝，微信）',
            ADD  `customer_bank_name` VARCHAR( 100 ) NULL COMMENT  '银行账户对应的姓名',
            ADD  `customer_bank_account` VARCHAR( 100 ) NULL COMMENT  '银行账户'
            ",
            
                
            "
            ALTER TABLE  `bdmin_user` ADD  `bdmin_bank` VARCHAR( 100 ) NULL COMMENT  '银行名称（银行，支付宝，微信）',
            ADD  `bdmin_bank_name` VARCHAR( 100 ) NULL COMMENT  '银行账户对应的姓名',
            ADD  `bdmin_bank_account` VARCHAR( 100 ) NULL COMMENT  '银行账户'
           ",
            
            "
            ALTER TABLE  `refund_admin` ADD  `status` VARCHAR( 50 ) NULL 
            ",
            
            "
            ALTER TABLE  `refund_bdmin` ADD  `status` VARCHAR( 50 ) NULL 
            ",
            
            "
            ALTER TABLE  `refund_admin` ADD  `customer_email` VARCHAR( 100 ) NULL AFTER  `customer_id` 
            ",
            
            "
            ALTER TABLE  `refund_bdmin` ADD  `customer_email` VARCHAR( 100 ) NULL AFTER  `customer_id` 
            ",
            "
            ALTER TABLE  `refund_admin` ADD  `refunded_at` INT( 11 ) NULL COMMENT  '退款时间' AFTER  `updated_at`
            ",
            "
            ALTER TABLE  `refund_bdmin` ADD  `refunded_at` INT( 11 ) NULL COMMENT  '退款时间' AFTER  `updated_at`
            ",
            
            "
            CREATE TABLE IF NOT EXISTS `statistical_bdmin_month` (
                `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                `bdmin_user_id` INT( 11 ) NOT NULL COMMENT  '供应商ID',
                `complete_order_total` DECIMAL( 12, 2 ) NULL COMMENT  '完成的订单金额',
                `refund_return_total` DECIMAL( 12, 2 ) NULL COMMENT  '退货-退款总额',
                `month` INT( 5 ) NULL COMMENT  '月份',
                `updated_at` INT( 11 ) NULL COMMENT  '更新时间',
                `created_at` INT( 11 ) NULL COMMENT  '创建时间'
                ) ENGINE = INNODB;
            ",
            //"
            //ALTER TABLE  `sales_flat_order` ADD  `received_at` INT( 11 ) NULL COMMENT  '用户订单收货时间' AFTER  `updated_at`
            //",
            
            "
            ALTER TABLE  `statistical_bdmin_month` ADD  `year` INT( 5 ) NULL AFTER  `month`
            ",
            
            "
            ALTER TABLE  `statistical_bdmin_month` ADD  `month_total` DECIMAL( 12, 2 ) NULL COMMENT  '月结算金额 = 订单总额 - 退款总额' AFTER  `refund_return_total`
            ",
            
            "
            ALTER TABLE  `statistical_bdmin_month` CHANGE  `refund_return_total`  `admin_refund_return_total` DECIMAL( 12, 2 ) NULL DEFAULT NULL COMMENT  '平台退货-退款总额'
            " ,
            "
            ALTER TABLE  `statistical_bdmin_month` ADD  `bdmin_refund_return_total` DECIMAL( 12, 2 ) NULL COMMENT  '供应商退款(货到付款类型的订单退款)-退款总额' AFTER  `admin_refund_return_total`
            ",
            
            "
            ALTER TABLE  `statistical_bdmin_month` CHANGE  `month_total`  `month_total` DECIMAL( 12, 2 ) NULL DEFAULT NULL COMMENT  '月结算金额 = 完成订单总额 - 平台退货-退款总额'
            ",
            
            //"
            //ALTER TABLE  `customer_address` ADD  `area` VARCHAR( 50 ) NULL COMMENT  '城市对应的区' AFTER  `city`
            //",
            
            "
            ALTER TABLE  `sales_flat_order` ADD  `customer_address_area` VARCHAR( 50 ) NULL COMMENT  '订单地址城市对应的区' AFTER  `customer_address_city`
            ",
            
            
            "
            ALTER TABLE  `sales_flat_order` ADD  `dispatched_at` INT( 11 ) NULL COMMENT  '订单的发货时间' AFTER  `updated_at`
            ",
            
            "
            ALTER TABLE  `sales_flat_order` ADD  `recevie_delay_days` INT( 11 ) NULL COMMENT  '收货时间延迟的天数，用户可以发起延迟收货天数的操作' AFTER  `dispatched_at`
            ",
            
            "
            ALTER TABLE  `sales_flat_order` ADD  `bdmin_audit_acceptd_at` INT( 11 ) NULL COMMENT  '供应商审核通过的时间' AFTER  `updated_at`
            ",
            
            "
            ALTER TABLE  `customer` CHANGE  `bdmin_user_id`  `bdmin_user_id` INT( 11 ) NULL COMMENT  '该用户所属的供应商的id'
            ",
            "
            ALTER TABLE  `customer` ADD  `phone` VARCHAR( 20 ) NULL COMMENT  '手机号' AFTER  `email`
            ",
            
            "
            ALTER TABLE  `bdmin_user` ADD  `uuid` VARCHAR( 100 ) NULL COMMENT  '供应商的唯一编码', ADD UNIQUE (`uuid`)
            ",
        ];
    
        foreach ($arr as $sql) {
            $db->createCommand($sql)->execute();
        }
        
        
        // 订单产品表-增加评论字段
        $db->createCommand(
            "ALTER TABLE `sales_flat_order_item` ADD `is_reviewed` INT( 5 ) NULL DEFAULT '2' COMMENT '1代表已经评论，2代表未评论'"
        )->execute();
        // 订单表-增加评论字段
        $db->createCommand(
            "ALTER TABLE `sales_flat_order` ADD `is_reviewed` INT( 5 ) NULL DEFAULT '2' COMMENT '1代表已经评论，2代表未评论'"
        )->execute();
        
        // 订单表-增加评论字段
        $db->createCommand(
            "
            ALTER TABLE `sales_flat_order` 
            ADD `received_at` INT( 12 ) NULL COMMENT '订单收货时间',
            ADD `reviewed_at` INT( 12 ) NULL COMMENT '订单评论时间'
            "
        )->execute();
        
        
        
        
        ////////////////////////////
        $arr = [
            
            '
            INSERT INTO `bdmin_user` (`id`, `username`, `password_hash`, `password_reset_token`, `email`, `person`, `code`, `auth_key`, `status`, `created_at`, `updated_at`, `password`, `access_token`, `access_token_created_at`, `allowance`, `allowance_updated_at`, `created_at_datetime`, `updated_at_datetime`, `birth_date`, `bdmin_bank`, `bdmin_bank_name`, `bdmin_bank_account`, `uuid`) VALUES
(3, \'fecshop\', \'$2y$13$sR7jfcfHULF9sSx9VY70auBZob9kGI1skGLBB1CDX5SiXrswBqFzO\', NULL, \'2358269014@qq.com\', \'terry\', NULL, \'EzZd2MFyeS3nkyZ1QX4FvsQnlHelfbzM\', 1, NULL, NULL, \'\', \'Kfyho3dAwWSRxUopzZ_OPzaQSsl-25Jg\', NULL, NULL, NULL, \'2019-05-09 12:33:31\', \'2019-05-09 12:33:31\', NULL, NULL, NULL, NULL, \'9928e826-7213-11e9-8dd4-00163e021360\');
            '
            ,
            
            "
            ALTER TABLE  `sales_flat_order` CHANGE  `bdmin_user_id`  `bdmin_user_id` INT( 11 ) NULL COMMENT  '供应商的id'
            ",
            
            "
            ALTER TABLE  `sales_flat_order_item` CHANGE  `bdmin_user_id`  `bdmin_user_id` INT( 11 ) NULL COMMENT  '供应商的id'
            ",
            
            "
            ALTER TABLE  `sales_flat_order` ADD  `payment_no` VARCHAR( 50 ) NULL COMMENT  '订单交易编码' AFTER  `order_id` , ADD INDEX (  `payment_no` )
            ",
            
            "
            ALTER TABLE  `sales_flat_order` CHANGE  `payment_no`  `payment_no` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT  '订单交易编码'
            ",
            "
            ALTER TABLE  `sales_flat_order` CHANGE  `payment_method`  `payment_method` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT  '支付方式'
            ",
            "
            ALTER TABLE  `sales_flat_order` CHANGE  `shipping_method`  `shipping_method` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT  '货运方式'
            ",
            // mysql add  bdmin_user_id
            "
                ALTER TABLE `product_flat` ADD `bdmin_user_id` INT( 12 ) NOT NULL COMMENT '经销商id', ADD INDEX ( `bdmin_user_id` )
            ",
            "
                ALTER TABLE `review` ADD `bdmin_user_id` INT( 12 ) NULL COMMENT '经销商id', ADD INDEX ( `bdmin_user_id` )
            ",
            "
                ALTER TABLE `favorite` ADD `bdmin_user_id` INT( 12 ) NOT NULL , ADD INDEX ( `bdmin_user_id` )
            ",
            
            
            // fectb
            
             
            "
                ALTER TABLE `sales_flat_order_after_sale` ADD `tracking_company` VARCHAR( 30 ) NULL COMMENT '物流公司' AFTER `tracking_number`
            ",
        
            
            
            ];
    
        foreach ($arr as $sql) {
            $db->createCommand($sql)->execute();
        }
        
        $db->createCommand(
            "
                CREATE TABLE IF NOT EXISTS `bdmin_shipping_theme` (
                    `id` INT( 12 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                    `created_at` INT( 12 ) NULL ,
                    `updated_at` INT( 12 ) NULL ,
                    `bdmin_user_id` INT( 12 ) NULL ,
                    `label` TEXT NULL ,
                    `type` VARCHAR( 50 ) NULL ,
                    `first_weight` DECIMAL( 12, 2 ) NULL ,
                    `first_cost` DECIMAL( 12, 2 ) NULL ,
                    `next_weight` DECIMAL( 12, 2 ) NULL ,
                    `next_cost` DECIMAL( 12, 2 ) NULL ,
                    INDEX ( `bdmin_user_id` )
                ) ENGINE = INNODB;
            "
        )->execute();
        
        
        $db->createCommand(
            "
            CREATE TABLE IF NOT EXISTS `sales_order_process_log` (
                `id` INT( 12 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                `created_at` INT( 12 ) NULL ,
                `updated_at` INT( 12 ) NULL ,
                `order_id` INT( 12 ) NULL ,
                `increment_id` VARCHAR( 50 ) NULL ,
                `customer_id` INT( 12 ) NULL ,
                `bdmin_user_id` INT( 12 ) NULL ,
                `type` VARCHAR( 50 ) NULL ,
                INDEX ( `order_id` , `bdmin_user_id` )
                ) ENGINE = INNODB;

            "
        )->execute();
        
        
        
        return true;
    }
    
    
    /**
     * 复制图片文件到appimage/common/addons/{namespace}，如果存在，则会被强制覆盖
     */
    public function copyImageFile()
    {
        $sourcePath = Yii::getAlias('@fecbbc/app/appimage/common/addons/fecbbc');
        
        Yii::$service->extension->administer->copyThemeFile($sourcePath);
        
        return true;
    }
    
}