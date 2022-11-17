<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appadmin\modules\Config\block\fecphone;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use Yii;

/**
 * block cms\staticblock.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manager extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    public $_saveUrl;
    // 需要配置
    public $_key = 'base_fecphone';
    public $_type;
    protected $_attrArr = [
        // 阿里大鱼配置部分
        'access_key_id',   // blog主页的title
        'access_key_secret',   //
        'sign_name',   // 
        'register_account_captcha_template_code',
        'forgot_password_captcha_template_code',
        'order_payment_is_send_sms',
        'order_payment_template_code',
        'session_storage_capcha_code_timeout',
        // 微信配置部分
        //'wx_fuwuhao_app_id',
        //'wx_fuwuhao_secret',
        'wx_fuwuhao_encoding_aes_key',
        'wx_fuwuhao_token',
        'wx_fuwuhao_share_success_alert',
        'wx_fuwuhao_token_time_out',
        'wx_fuwuhao_pc_qr_code_reflush',
        'wx_fuwuhao_pc_qr_code_timeout',
        
        'wx_fuwuhao_home_page_title',
        'wx_fuwuhao_home_page_desc',
        'wx_fuwuhao_home_page_image_url',
        'wx_fuwuhao_category_page_default_desc',
        'wx_fuwuhao_category_page_image_url',
        'wx_fuwuhao_product_page_default_desc',
        
        'wx_custom_menu',
    ];
    
    public function init()
    {
        // 需要配置
        $this->_saveUrl = CUrl::getUrl('config/fecphone/managersave');
        $this->_editFormData = 'editFormData';
        $this->setService();
        $this->_param = CRequest::param();
        $this->_one = $this->_service->getByKey([
            'key' => $this->_key,
        ]);
        if ($this->_one['value']) {
            $this->_one['value'] = unserialize($this->_one['value']);
        }
    }
    
    
    
    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        $id = ''; 
        if (isset($this->_one['id'])) {
           $id = $this->_one['id'];
        } 
        return [
            'id'            =>   $id, 
            'editBar'      => $this->getEditBar(),
            'textareas'   => $this->_textareas,
            'lang_attr'   => $this->_lang_attr,
            'saveUrl'     => $this->_saveUrl,
        ];
    }
    public function setService()
    {
        $this->_service = Yii::$service->storeBaseConfig;
    }
    public function getEditArr()
    {
        return [
            
            [
                'label'  => Yii::$service->page->translate->__('阿里大鱼-AccessKeyID'),
                'name' => 'access_key_id',
                'display' => [
                    'type' => 'inputString',
                    'lang' => false,
                ],
                'require' => true,
                'remark' => '阿里大鱼短信服务的AccessKeyID',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('阿里大鱼-AccessKeySecret'),
                'name' => 'access_key_secret',
                'display' => [
                    'type' => 'inputString',
                    'lang' => false,
                ],
                'require' => true,
                'remark' => '阿里大鱼短信服务的AccessKeySecret',
            ],
            [
                'label'  => Yii::$service->page->translate->__('阿里大鱼-signName'),
                'name' => 'sign_name',
                'display' => [
                    'type' => 'inputString',
                    'lang' => false,
                ],
                'require' => true,
                'remark' => '阿里大鱼短信服务的signName',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('阿里大鱼-注册账户-短信模板'),
                'name' => 'register_account_captcha_template_code',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => true,
                'remark' => '注册账户获取验证码的短信模板，需要在阿里云短信服务添加相应的短信模板，审核通过后可以得到，譬如：SMS_183760292',
            ],
            [
                'label'  => Yii::$service->page->translate->__('阿里大鱼-忘记密码-短信模板'),
                'name' => 'forgot_password_captcha_template_code',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => true,
                'remark' => '忘记密码获取验证码的短信模板，需要在阿里云短信服务添加相应的短信模板，审核通过后可以得到，譬如：SMS_183760292',
            ],
            
            
            [
                'label'  => Yii::$service->page->translate->__('阿里大鱼-订单支付后是否发送短信'),
                'name' => 'order_payment_is_send_sms',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$service->sms->orderPaymentSendSms =>  Yii::$service->page->translate->__('Send Sms'),
                        Yii::$service->sms->orderPaymentNotSendSms =>  Yii::$service->page->translate->__('Not Send Sms'),
                    ],
                ],
                'require' => 1,
                'remark' => '用户订单支付成功后，是否进行短信的发送',
            ],
            
            
            [
                'label'  => Yii::$service->page->translate->__('阿里大鱼-订单支付-短信模板'),
                'name' => 'order_payment_template_code',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '订单支付成功后发送的的短信模板，需要在阿里云短信服务添加相应的短信模板，审核通过后可以得到，譬如：SMS_183760292',
            ],
            [
                'label'  => Yii::$service->page->translate->__('阿里大鱼-验证码过期时间（秒）'),
                'name' => 'session_storage_capcha_code_timeout',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '默认600秒, 账户注册，以及密码找回，发送的验证码的过期时间（单位：秒）',
            ],
            
            /*
            [
                'label'  => Yii::$service->page->translate->__('微信公众号-AppId'),
                'name' => 'wx_fuwuhao_app_id',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => true,
                'remark' => '微信公众服务号（注意是服务号，而不是订阅号），申请的AppId',
            ],
            [
                'label'  => Yii::$service->page->translate->__('微信公众号-AppSecret'),
                'name' => 'wx_fuwuhao_secret',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => true,
                'remark' => '微信公众服务号（注意是服务号，而不是订阅号），申请的AppSecret',
            ],
            */
            [
                'label'  => Yii::$service->page->translate->__('微信公众号-encodingAESKey'),
                'name' => 'wx_fuwuhao_encoding_aes_key',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => false,
                'remark' => '用于微信方推送给服务器通知的消息，加密解密Key',
            ],
            [
                'label'  => Yii::$service->page->translate->__('微信公众号-Token'),
                'name' => 'wx_fuwuhao_token',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => false,
                'remark' => '微信公众服务号的token，该值为自定义填写，此出填写的值，必须和微信公众服务号的token一致',
            ],
             [
                'label'  => Yii::$service->page->translate->__('微信公众号-缓存Token过期时间'),
                'name' => 'wx_fuwuhao_token_time_out',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '默认：3600，必须小于7200，系统会将微信获取的各种token，ticket等保存到cache中',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('微信PC扫码登陆-用户登陆Ajax频率'),
                'name' => 'wx_fuwuhao_pc_qr_code_reflush',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '默认：2秒，单位秒，用户关注公众号登陆商城，ajax间隔请求的频率，也就是间隔多少秒请求',
            ],
            [
                'label'  => Yii::$service->page->translate->__('微信PC扫码登陆-Ajax请求超时时间'),
                'name' => 'wx_fuwuhao_pc_qr_code_timeout',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '默认：60，单位；秒，ajax间隔请求，当超过X秒后停止',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('微信H5分享-分享成功显示的内容'),
                'name' => 'wx_fuwuhao_share_success_alert',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '在微信中打开商城，进行分享，分享成功后的文字弹框内容',
            ],
            
            
            [
                'label'  => Yii::$service->page->translate->__('微信H5分享-首页页面-Title'),
                'name' => 'wx_fuwuhao_home_page_title',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => 'h5首页，微信分享，自定义title部分',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('微信H5分享-首页页面-Tab描述'),
                'name' => 'wx_fuwuhao_home_page_desc',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => 'h5首页，微信分享，自定义title部分',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('微信H5分享-首页页面-图片URL'),
                'name' => 'wx_fuwuhao_home_page_image_url',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => 'h5首页，微信分享，自定义图片URL部分, 请填写完整的图片url',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('微信H5分享-分类页面-Tab描述'),
                'name' => 'wx_fuwuhao_category_page_default_desc',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => 'h5分类页面，微信分享，自定义Tab描述部分',
            ],
            [
                'label'  => Yii::$service->page->translate->__('微信H5分享-分类页面-图片URL'),
                'name' => 'wx_fuwuhao_category_page_image_url',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => 'h5分类页面，微信分享，自定义图片URL部分, 请填写完整的图片url',
            ],
            
            
            [
                'label'  => Yii::$service->page->translate->__('微信H5分享-产品页面-Tab描述'),
                'name' => 'wx_fuwuhao_product_page_default_desc',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => 'h5分类页面，微信分享，自定义Tab描述部分，譬如：`更多品牌折扣，尽在Fecmall开源商城`',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('微信- 自定义菜单'),
                'name' => 'wx_custom_menu',
                'display' => [
                    'type' => 'textarea',
                    'notEditor' => true,
                ],
                'remark' => '文档地址：https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Creating_Custom-Defined_Menu.html',
            ],
        ];
    }
     
    
    public function getArrParam(){
        $request_param = CRequest::param();
        $this->_param = $request_param[$this->_editFormData];
        $param = [];
        $attrVals = [];
        foreach($this->_param as $attr => $val) {
            if (in_array($attr, $this->_attrArr)) {
                $attrVals[$attr] = $val;
            } else {
                $param[$attr] = $val;
            }
        }
        $param['value'] = $attrVals;
        $param['key'] = $this->_key;
        
        return $param;
    }
    
    /**
     * save article data,  get rewrite url and save to article url key.
     */
    public function save()
    {
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        // 设置 bdmin_user_id 为 当前的user_id
        $arrParam = $this->getArrParam();
        $this->_service->saveConfig($arrParam);
        $this->customWxMenu();
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message'    => Yii::$service->page->translate->__('Save Success'),
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => $errors,
            ]);
            exit;
        }
    }
    
    public function customWxMenu()
    {
        // wx 自定义菜单
        if ($wx_custom_menu = $this->_param['wx_custom_menu']) {
            $result = Yii::$service->wx->custommenu->createMenu($wx_custom_menu);
            
            $res = json_decode($result, true);
            if ($res['errcode'] !=  0) {
                echo json_encode([
                    'statusCode' => '300',
                    'message'    => '微信自定义菜单报错：'.$result,
                ]);exit;
            }
        }
        
    }
    
    
    public function getVal($name, $column){
        if (is_object($this->_one) && property_exists($this->_one, $name) && $this->_one[$name]) {
            
            return $this->_one[$name];
        }
        $content = $this->_one['value'];
        if (is_array($content) && !empty($content) && isset($content[$name])) {
            
            return $content[$name];
        }
        
        return '';
    }
}