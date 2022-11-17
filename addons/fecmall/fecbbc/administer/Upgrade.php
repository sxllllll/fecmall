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
class Upgrade implements \fecshop\services\extension\UpgradeInterface
{
    /**
     * @var array
     * 必须按照版本号依次填写，否则升级会导致问题。
     * 如果安装的初始版本号为1.0.0，那么下面的升级版本号必须比初始版本号大才行
     */
    public $versions = [
        '1.1.0',
        '1.4.0',
        '1.5.0',
        '1.7.0',
        '1.9.0',
        '1.9.2',
        '1.9.12',
        '1.10.0',
        '1.10.1',
        // '1.0.3',
    ];
    

    /**
    * @param $version  最新版本号
    * @return boolean
    *  升级执行的函数，您可以在各个版本号需要执行的部分写入相应的操作。
    */
    public function run($version)
    {
        /**
         * 下面仅仅是一个sql例子，您可以将其换成您自己要升级的内容
         */
        switch ($version)
        {
            case '1.1.0' :
                $this->upgrade110();
                break;
            case '1.4.0' :
                $this->upgrade140();
                break;
            case '1.5.0' :
                $this->upgrade150();
                break;
             case '1.7.0' :
                $this->upgrade170();
                break;
            case '1.9.0' :
                $this->upgrade190();
                break;
            case '1.9.2' :
                $this->upgrade192();
                break;
            case '1.9.12' :
                $this->upgrade1912();
                break;
            case '1.10.0' :
                $this->upgrade1100();
                break;
            case '1.10.1' :
                $this->upgrade1101();
                break;
        }
       
        
        return true;
    }
    
    // 1.10.1
    public function upgrade1101()
    {
        $db = Yii::$app->getDb();
        
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ( 'Product Bulk Enable', 'catalog_product_info_manager', 22, '/catalog/productinfo/bulkenable',1615859481,1615859481, 1)")->execute();
        $lastInsertId = $db->getLastInsertID() ;
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1615859481, 1615859481)")->execute();

        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ( 'Product Bulk Disable', 'catalog_product_info_manager', 23, '/catalog/productinfo/bulkdisable',1615859481,1615859481, 1)")->execute();
        $lastInsertId = $db->getLastInsertID() ;
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1615859481, 1615859481)")->execute();

        return true;
    }
    
    // 1.10.0
    public function upgrade1100()
    {
        $db = Yii::$app->getDb();

        $db->createCommand(
            "
                ALTER TABLE `bdmin_user` ADD `telephone` VARCHAR( 50 ) NULL COMMENT '电话',
                ADD `tax_point` DECIMAL( 12, 2 ) NULL COMMENT '税点',
                ADD `invoice` VARCHAR( 100 ) NULL COMMENT '发票要求',
                ADD `authorized_brand` VARCHAR( 100 ) NULL COMMENT '授权品牌',
                ADD `authorized_type` INT( 5 ) NULL COMMENT '授权类型',
                ADD `authorized_role` INT( 5 ) NULL COMMENT '授权权限',
                ADD `authorized_at` INT( 12 ) NULL COMMENT '授权书有效期',
                ADD `cooperationed_at` INT( 12 ) NULL COMMENT '合作协议有效期',
                ADD `is_audit` INT( 5 ) NULL COMMENT '审核状态，1代表通过，2代表等待审核，3代表审核拒绝',
                ADD `shipping_date` VARCHAR( 150 ) NULL COMMENT '发货时效',
                ADD `order_date` VARCHAR( 150 ) NULL COMMENT '截单时间',
                ADD `remark` VARCHAR( 150 ) NULL COMMENT '备注',
                ADD `zip_file` VARCHAR( 150 ) NULL COMMENT '其他',
                ADD `audit_at` INT( 12 ) NULL COMMENT '审核时间'
            "
        )->execute();
        
        
        // 1
        $db->createCommand(
            "INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Supplier Apply List', 'supplier-apply', 1, '/supplier/apply/manager', 1612015992, 1612015992, 1)"
        )->execute();

        $lastInsertId = $db->getLastInsertID() ;

        $db->createCommand(
            "INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)"
        )->execute();
        
        // 2
        $db->createCommand(
            "INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Supplier Apply View', 'supplier-apply', 2, '/supplier/apply/manageredit', 1612016024, 1612016024, 1)"
        )->execute();

        $lastInsertId = $db->getLastInsertID() ;

        $db->createCommand(
            "INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)"
        )->execute();
        
        // 3
        $db->createCommand(
            "INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Supplier Apply Accept', 'supplier-apply', 3, '/supplier/apply/manageraccept', 1612016080, 1612016080, 1)"
        )->execute();

        $lastInsertId = $db->getLastInsertID() ;

        $db->createCommand(
            "INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)"
        )->execute();
        
        // 4
        $db->createCommand(
            "INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Supplier Apply Refuse', 'supplier-apply', 4, '/supplier/apply/managerrefuse', 1612016111, 1612016111, 1)"
        )->execute();

        $lastInsertId = $db->getLastInsertID() ;

        $db->createCommand(
            "INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)"
        )->execute();
        
        // 5
        $db->createCommand(
            "INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Supplier Apply Download Zip', 'supplier-apply', 5, '/supplier/apply/downloadzip', 1612018662, 1612018662, 1)"
        )->execute();

        $lastInsertId = $db->getLastInsertID() ;

        $db->createCommand(
            "INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)"
        )->execute();
        

        return true;
    }
    
    // 1.9.12
    public function upgrade1912()
    {
        $db = Yii::$app->getDb();

        $db->createCommand(
            "
                ALTER TABLE `bdmin_coupon` ADD `is_show_in_product_page` INT( 5 ) NOT NULL DEFAULT '1' COMMENT '是否在产品页面显示该优惠券，1代表显示，2代表不显示'
            "
        )->execute();
        

        return true;
    }
    
    
    // 1.9.2
    public function upgrade192()
    {
        $sourcePath = Yii::getAlias('@fecbbc/app/appimage/common/addons/fecbbc/upgrade192');
        
        Yii::$service->extension->administer->copyThemeFile($sourcePath);
        
        return true;
    }
    
    // 1.9.0
    public function upgrade190()
    {
        $sourcePath = Yii::getAlias('@fecbbc/app/appimage/common/addons/fecbbc/upgrade190');
        
        Yii::$service->extension->administer->copyThemeFile($sourcePath);
        
        $db = Yii::$app->getDb();

        $db->createCommand(
            "
                CREATE TABLE IF NOT EXISTS `customer_sms_code` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `key` varchar(80) DEFAULT NULL COMMENT '验证码类型',
                  `phone` varchar(39) DEFAULT NULL COMMENT '验证码手机',
                  `code` varchar(20) DEFAULT NULL COMMENT '验证码字符串',
                  `created_at` int(12) DEFAULT NULL,
                  `updated_at` int(12) DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  KEY `phone` (`phone`,`code`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            "
        )->execute();
        
        return true;
    }
    
    // 1.7.0 加入经销商优惠券。
    public function upgrade170()
    {
        $this->copyImageFile();
        $db = Yii::$app->getDb();
        
        $db->createCommand(
            "
                CREATE TABLE IF NOT EXISTS `bdmin_coupon` (
                  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
                  `code` varchar(100) DEFAULT NULL COMMENT '优惠券编码',
                  `name` varchar(150) DEFAULT NULL COMMENT '优惠券名称',
                  `total_count` int(12) DEFAULT NULL COMMENT '优惠券总数',
                  `assign_count` int(12) DEFAULT '0' COMMENT '优惠券发放数',
                  `discount_cost` decimal(12,2) DEFAULT NULL COMMENT '优惠金额',
                  `use_condition` decimal(12,2) DEFAULT NULL COMMENT '使用门槛',
                  `condition_product_type` varchar(30) DEFAULT NULL COMMENT '优惠券产品限制类型：all代表经销商所有产品可用，sku代表指定的产品列表可用，category代表该分类下，该分销商的产品',
                  `condition_product_skus` text COMMENT '当condition_product_type为sku的时候，该字段有效，sku为英文逗号隔开的字符串，并且开头结尾都有，类似于   `sku1,sku2,sku3`,这样做的用意是为了可以通过模糊匹配搜索优惠券',
                  `condition_product_category_ids` text COMMENT '当condition_product_type为category的时候有效',
                  `assign_begin_at` int(12) DEFAULT NULL COMMENT '优惠券的发放有效开始时间',
                  `assign_end_at` int(12) DEFAULT NULL COMMENT '优惠券的发放有效结束时间',
                  `created_at` int(12) DEFAULT NULL COMMENT '优惠券创建时间',
                  `updated_at` int(12) DEFAULT NULL COMMENT '优惠券更新时间',
                  `bdmin_user_id` int(12) DEFAULT NULL COMMENT '经销商id',
                  `remark` varchar(200) DEFAULT NULL COMMENT '优惠券备注',
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `code` (`code`),
                  KEY `bdmin_user_id` (`bdmin_user_id`,`assign_end_at`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            "
        )->execute();
        
        
        $db->createCommand(
            "
                CREATE TABLE IF NOT EXISTS `customer_coupon` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `customer_id` int(12) DEFAULT NULL COMMENT '用户id',
                  `coupon_id` int(12) DEFAULT NULL COMMENT '优惠券id',
                  `name` text COMMENT '优惠券名称',
                  `code` varchar(100) DEFAULT NULL COMMENT '优惠券卷码',
                  `bdmin_user_id` int(12) DEFAULT NULL COMMENT '经销商id',
                  `discount_cost` decimal(12,2) DEFAULT NULL COMMENT '优惠金额',
                  `use_condition` decimal(12,2) DEFAULT NULL COMMENT '使用门槛',
                  `condition_product_type` varchar(30) DEFAULT NULL COMMENT '优惠券产品限制类型：all代表经销商所有产品可用，sku代表指定的产品列表可用，category代表该分类下，该分销商的产品',
                  `condition_product_skus` text COMMENT '当condition_product_type为sku的时候，该字段有效，sku为英文逗号隔开的字符串，并且开头结尾都有，类似于   `sku1,sku2,sku3`,这样做的用意是为了可以通过模糊匹配搜索优惠券。',
                  `condition_product_category_ids` text COMMENT '当condition_product_type为category的时候有效，分类id',
                  `active_begin_at` int(12) DEFAULT NULL COMMENT '优惠券的有效开始时间',
                  `active_end_at` int(12) DEFAULT NULL COMMENT '优惠券的有效结束时间',
                  `is_used` int(5) DEFAULT NULL COMMENT '是否被使用，1代表已经被使用，2代表未使用',
                  `created_at` int(12) DEFAULT NULL COMMENT '创建时间',
                  `updated_at` int(12) DEFAULT NULL COMMENT '更新时间',
                  `remark` varchar(200) DEFAULT NULL COMMENT '备注',
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `customer_id` (`customer_id`,`code`),
                  KEY `bdmin_user_id` (`bdmin_user_id`,`active_end_at`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            "
        )->execute();
        
        
        
        $db->createCommand(
            "
                CREATE TABLE IF NOT EXISTS `store_bdmin_config` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `key` varchar(100) NOT NULL,
                  `value` text NOT NULL,
                  `bdmin_user_id` int(12) NOT NULL,
                  `created_at` int(12) NOT NULL,
                  `updated_at` int(12) NOT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `bdmin_user_id` (`bdmin_user_id`,`key`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            "
        )->execute();
        
        
        return true;
    }
    
    // 1.5.0
    public function upgrade150()
    {
        $db = Yii::$app->getDb();

        ////
        
        // 1
        $db->createCommand(
            "INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Fecphone Manager', 'config_fecphone', 1, '/config/fecphone/manager', 1581670328, 1581670328, 1)"
        )->execute();

        $lastInsertId = $db->getLastInsertID() ;

        $db->createCommand(
            "INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)"
        )->execute();
        
        // 2
        $db->createCommand(
            "INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Fecphone Save', 'config_fecphone', 2, '/config/fecphone/managersave', 1581670374, 1581670374, 1)"
        )->execute();

        $lastInsertId = $db->getLastInsertID() ;

        $db->createCommand(
            "INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)"
        )->execute();
        
        $db->createCommand(
            "
            ALTER TABLE `customer` ADD UNIQUE (`phone`)
            "
        )->execute();
        
        $db->createCommand(
            "
                CREATE TABLE `customer_wx_qr_code_log` (
                    `id` INT( 12 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                    `openid` VARCHAR( 120 ) NULL ,
                    `eventkey` VARCHAR( 50 ) NULL ,
                    `created_at` INT( 12 ) NULL ,
                    `updated_at` INT( 12 ) NULL ,
                    UNIQUE (
                    `openid` ,
                    `eventkey`
                    )
                ) ENGINE = INNODB;
            "
        )->execute();
        
        
        $db->createCommand(
            "
                ALTER TABLE `customer` ADD `wx_password_is_set` INT( 5 ) NULL DEFAULT '1' COMMENT '1代表已经设置密码，2代表未设置密码'
            "
        )->execute();
        
        $sourcePath = Yii::getAlias('@fecbbc/app/appimage/common/addons/fecbbc/fecphone');
        
        Yii::$service->extension->administer->copyThemeFile($sourcePath);
        
        return true;
    }
    
    // 1.4.0
    public function upgrade140()
    {
        // 增加测试 - 冗余的字段
        $sql = "
            UPDATE `admin_url_key` SET `url_key` = '/sales/refund/managereditsave' WHERE `url_key` = '/sales/refund/managersave';
            ";
        Yii::$app->getDb()->createCommand($sql)->execute();
    }
    
    
    
    // 1.1.0
    public function upgrade110()
    {
        // 增加测试 - 冗余的字段
        $sql = "
            ALTER TABLE `product_flat` ADD `is_deputy` INT( 5 ) NULL DEFAULT NULL COMMENT 'spu的后台展示。是否代表产品，1代表是代表产品，2代表不是代表产品', ADD INDEX ( `is_deputy` )
           ";
        Yii::$app->getDb()->createCommand($sql)->execute();
    }
    
    // 1.0.3
    public function upgrade103()
    {
        // 删除测试 - 冗余的字段
        // $sql = "ALTER TABLE fecmall_addon_test1 ADD COLUMN redundancy_field_567 varchar(48);";
        // Yii::$app->getDb()->createCommand($sql)->execute();
    }
    
    
    
    /**
     * 复制图片文件到appimage/common/addons/{namespace}，如果存在，则会被强制覆盖
     */
    public function copyImageFile()
    {
        
        $sourcePath = Yii::getAlias('@fecbbc/app/appimage/common/addons/fecbbc/dian');
        
        Yii::$service->extension->administer->copyThemeFile($sourcePath);
        
        return true;
    }
    
    
}