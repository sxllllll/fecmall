<?php
/**
 * 快递鸟：http://www.kdniao.com/
 *
 */

namespace fecbbc\services\delivery;

use Yii;

class Kdiniao extends \fecshop\services\Service
{
    
    // $orderInfo = Yii::$service->delivery->kdiniao->getOrderTracesByJson('YZPY', '9896365268436');
    public $EBusinessID; // = '1586094';
    public $AppKey; // = '5fe54403-b368-4468-8fb5-e59e4a3d1236';
    public $ReqURL = 'http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx';
    
    public function init()
    {
        parent::init();
        $this->EBusinessID = Yii::$app->store->get('fecbbc_info', 'kuaidiniao_business_id');
        $this->AppKey = Yii::$app->store->get('fecbbc_info', 'kuaidiniao_app_key');
    }
    
    public function getDeliveryShipperName($shipperCode)
    {
        $arr = Yii::$service->delivery->getCompanyArr();
        if (isset($arr[$shipperCode])) {
            return $arr[$shipperCode];
        }
        
        return '';
    }
    // 是否是激活的公司
    public function isActiveCompany($c)
    {
        $arr = Yii::$service->delivery->getCompanyArr();
        if (isset($arr[$c])) {
            return true;
        }
        return false;
    }
    
    public function deliverystatusArr()
    {
        return [
            0 => '无轨迹',
            1 => '已揽收',
            2 => '在途中',
            3 => '已签收',
            4 => '问题件',
        ];
    }
    
    public function getDeliveryLabel($c)
    {
        $arr = $this->deliverystatusArr();
        if (isset($arr[$c])) {
            return $arr[$c];
        }
        
        return null;
    }
    
    /**
     * Json方式 查询订单物流轨迹
     * @return 
        [
            "LogisticCode"=> "9540739380702",
            "ShipperCode"=> "EMS"
            "Traces"=> [
                0=> [
                    "AcceptStation"=> "【城二石桥铺揽投部】已收件,揽投员:钟富正15523549757"，
                    "AcceptTime"  => "2019-06-11 18:43:11"
                ],
                1=> [
                    "AcceptStation"=> "离开【城二石桥铺揽投部】，下一站【重庆中心】",
                    "AcceptTime" => "2019-06-11 19:55:02",
                ],
                2=> [
                    "AcceptStation" => "到达【重庆中心】",
                    "AcceptTime" => "2019-06-11 21:16:43"
                ],
                3 => [
                    "AcceptStation" => "离开【重庆中心】，下一站【西安中心】",
                    "AcceptTime" => "2019-06-12 05:12:24",
                ]
            ],

            "State" => "2", 
            "Label" => '在途中',
            "EBusinessID" => "1539873",
            "Success" => true, 
        ]
     *    Test: 
     *    $no = '9540739380702';
     *    $type = 'EMS';
     *    Yii::$service->delivery->kdiniao-> getOrderTracesByJson($type, $no);
     */
    function getOrderTracesByJson($type, $no){
        $requestData= "{'OrderCode':'','ShipperCode':'".$type."','LogisticCode':'".$no."'}";
        
        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
        $result = $this->sendPost($this->ReqURL, $datas);	
        
        //根据公司业务处理返回的信息......
        
        $re = json_decode($result, true);
        $re['Label'] = $this->getDeliveryLabel($re['State']);
        $re['ShipperName'] = $this->getDeliveryShipperName($type);
        return $re;
    }
     
    /**
     *  post提交数据 
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据 
     * @return url响应返回的html
     */
    function sendPost($url, $datas) {
        $temps = array();	
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);		
        }	
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;	
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);  
        
        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容   
     * @param appkey Appkey
     * @return DataSign签名
     */
    function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }
    
    
    
    
    
    
}
