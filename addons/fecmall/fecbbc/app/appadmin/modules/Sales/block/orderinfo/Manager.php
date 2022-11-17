<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\app\appadmin\modules\Sales\block\orderinfo;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use Yii;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manager extends \fecshop\app\appadmin\modules\Sales\block\orderinfo\Manager
{
    protected $_exportExcelUrl;
    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        /*
         * edit data url
         */
        $this->_editUrl = CUrl::getUrl('sales/orderinfo/manageredit');
        /*
         * delete data url
         */
        $this->_deleteUrl = CUrl::getUrl('sales/orderinfo/managerdelete');
        
        $this->_exportExcelUrl = CUrl::getUrl('sales/orderinfo/managerexport');
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->order;
        parent::init();
    }

    public function getLastData()
    {
        // hidden section ,that storage page info
        $pagerForm = $this->getPagerForm();
        // search section
        $searchBar = $this->getSearchBar();
        // edit button, delete button,
        $editBar = $this->getEditBar();
        // table head
        $thead = $this->getTableThead();
        // table body
        $tbody = $this->getTableTbody();
        // paging section
        $toolBar = $this->getToolBar($this->_param['numCount'], $this->_param['pageNum'], $this->_param['numPerPage']);

        return [
            'pagerForm'   => $pagerForm,
            'searchBar'    => $searchBar,
            'editBar'        => $editBar,
            'thead'          => $thead,
            'tbody'          => $tbody,
            'toolBar'        => $toolBar,
        ];
    }
    
    public $_active_suppliers;
    public function getActiveSupplierArr(){
        if (empty($this->_active_suppliers)) {
            $supplierArr = Yii::$service->bdminUser->getAllActiveUser();
            if (is_array($supplierArr ) && !empty($supplierArr )) {
                foreach ($supplierArr  as $one) {
                    $this->_active_suppliers[$one['id']] = $one['username'];
                }
            }
        }
        return $this->_active_suppliers;
    }
    /**
     * get search bar Arr config.
     */
    public function getSearchArr()
    {
        $orderStatus = Yii::$service->order->getAllStatusArr();
        $data = [
            [    // 字符串类型
                'type' => 'inputtext',
                'title'  => Yii::$service->page->translate->__('Increment Id'),
                'name' => 'increment_id',
                'columns_type' => 'string',
            ],
            [    // selecit的Int 类型
                'type' => 'select',
                'title'  => Yii::$service->page->translate->__('Order Status'),
                'name' => 'order_status',
                'columns_type' => 'string',  // int使用标准匹配， string使用模糊查询
                'value' => $orderStatus,
            ],
            [    // selecit的Int 类型
                'type' => 'select',
                'title'  => Yii::$service->page->translate->__('Supplier')
                ,
                'name' => 'bdmin_user_id',
                'columns_type' => 'int',  // int使用标准匹配， string使用模糊查询
                'value' => $this->getActiveSupplierArr(),
            ],
            [    // 字符串类型
                'type' => 'inputtext',
                'title'  => Yii::$service->page->translate->__('Customer Phone'),
                'name' => 'customer_id',
                'columns_type' => 'string',
            ],
            [    // 时间区间类型搜索
                'type' => 'inputdatefilter',
                'title'  => Yii::$service->page->translate->__('Created At'),
                'name' => 'created_at',
                'columns_type' => 'int',
                'value' => [
                    'gte' => Yii::$service->page->translate->__('Created Begin'),
                    'lt'    => Yii::$service->page->translate->__('Created End'),
                ],
            ],
        ];

        return $data;
    }

    /**
     * config function ,return table columns config.
     */
    public function getTableFieldArr()
    {
        $table_th_bar = [
            [
                'orderField'   => $this->_primaryKey,
                'label'          => Yii::$service->page->translate->__('Id'),
                'width'         => '50',
                'align'          => 'center',
            ],
            [
                'orderField'   => 'increment_id',
                'label'          => Yii::$service->page->translate->__('Increment Id'),
                'width'         => '50',
                'align'          => 'left',
                //'lang'		  => true,
            ],
            [
                'orderField'    => 'bdmin_user_id',
                'label'           => Yii::$service->page->translate->__('Supplier'),
                'width'          => '50',
                'align'           => 'left',
                'display'        => $this->getActiveSupplierArr(),
            ],
            [
                'orderField'   => 'created_at',
                'label'          => Yii::$service->page->translate->__('Created At'),
                'width'         => '50',
                'align'          => 'left',
                'convert'      => ['int' => 'date'],
                //'lang'        => true,
            ],
            [
                'orderField'   => 'order_status',
                'label'          => Yii::$service->page->translate->__('Order Status'),
                'width'         => '50',
                'align'          => 'left',
                //'lang'        => true,
            ],
            [
                'orderField'   => 'items_count',
                'label'          => Yii::$service->page->translate->__('Itmes Count'),
                'width'         => '50',
                'align'          => 'left',
                //'lang'        => true,
            ],
            [
                'orderField'   => 'total_weight',
                'label'          => Yii::$service->page->translate->__('Total Weight') ,
                'width'         => '50',
                'align'          => 'left',
                //'lang'        => true,
            ],
            [
                'orderField'    => 'base_grand_total',
                'label'           => Yii::$service->page->translate->__('Base Grand Total'),
                'width'          => '50',
                'align'           => 'left',
                //'lang'		   => true,
            ],
            [
                'orderField'    => 'payment_method',
                'label'           => Yii::$service->page->translate->__('Payment Method'),
                'width'          => '50',
                'align'           => 'left',
                'display'        => Yii::$service->payment->getPaymentLabels(),
                //'lang'		   => true,
            ],
            [
                'orderField'   => 'shipping_method',
                'label'          => Yii::$service->page->translate->__('Shipping Method'),
                'width'         => '50',
                'align'          => 'left',
                //'lang'		  => true,
            ],
            [
                'orderField'    => 'base_shipping_total',
                'label'           => Yii::$service->page->translate->__('Base Shipping Total'),
                'width'          => '50',
                'align'           => 'left',
                //'lang'		   => true,
            ],
            [
                'orderField'   => 'customer_address_country',
                'label'          => Yii::$service->page->translate->__('Country'),
                'width'         => '50',
                'align'          => 'left',
                //'lang'		  => true,
            ],
            [
                'orderField'    => 'customer_id',
                'label'           => Yii::$service->page->translate->__('Customer Phone'),
                'width'          => '50',
                'align'           => 'left',
                //'lang'		   => true,
            ],
        ];

        return $table_th_bar;
    }

    /**
     * rewrite parent getTableTbodyHtml($data).
     */
    public function getTableTbodyHtml($data)
    {
        $fileds = $this->getTableFieldArr();
        $str = '';
        $csrfString = CRequest::getCsrfString();
        $user_ids = [];
        $customerIds = [];
        foreach ($data as $one) {
            $user_ids[] = $one['created_person'];
            $customerIds[] = $one['customer_id'];
        }
        $users = Yii::$service->adminUser->getIdAndNameArrByIds($user_ids);
        $customers = Yii::$service->customer->getPhoneByIds($customerIds);
        foreach ($data as $one) {
            $str .= '<tr target="sid_user" rel="'.$one[$this->_primaryKey].'">';
            $str .= '<td><input name="'.$this->_primaryKey.'s" value="'.$one[$this->_primaryKey].'" type="checkbox"></td>';
            foreach ($fileds as $field) {
                $orderField = $field['orderField'];
                $display = $field['display'];
                $val = $one[$orderField];
                if ($orderField == 'created_person') {
                    $val = isset($users[$val]) ? $users[$val] : $val;
                    $str .= '<td>'.$val.'</td>';
                    continue;
                }
                if ($orderField == 'customer_id') {
                    $val = isset($customers[$one['customer_id']]) ? $customers[$one['customer_id']] : $one['customer_id'];
                    $str .= '<td>'.$val.'</td>';
                    continue;
                }
                if ($orderField == 'order_status') {
                    $order_status = $val;
                    $order_operate_status = $one['order_operate_status'];
                    if ($order_operate_status != Yii::$service->order->operate_status_normal) {
                        $str .= '<td>'.Yii::$service->page->translate->__($order_operate_status).'</td>';
                    } else {
                        $str .= '<td>'.Yii::$service->page->translate->__($order_status).'</td>';
                    }
                    
                    continue;
                }
                if ($val) {
                    if (isset($field['display']) && !empty($field['display'])) {
                        $display = $field['display'];
                        $val = $display[$val] ? $display[$val] : $val;
                    }
                    if (isset($field['convert']) && !empty($field['convert'])) {
                        $convert = $field['convert'];
                        foreach ($convert as $origin =>$to) {
                            if (strstr($origin, 'mongodate')) {
                                if (isset($val->sec)) {
                                    $timestramp = $val->sec;
                                    if ($to == 'date') {
                                        $val = date('Y-m-d', $timestramp);
                                    } elseif ($to == 'datetime') {
                                        $val = date('Y-m-d H:i:s', $timestramp);
                                    } elseif ($to == 'int') {
                                        $val = $timestramp;
                                    }
                                }
                            } elseif (strstr($origin, 'date')) {
                                if ($to == 'date') {
                                    $val = date('Y-m-d', strtotime($val));
                                } elseif ($to == 'datetime') {
                                    $val = date('Y-m-d H:i:s', strtotime($val));
                                } elseif ($to == 'int') {
                                    $val = strtotime($val);
                                }
                            } elseif ($origin == 'int') {
                                if ($to == 'date') {
                                    $val = date('Y-m-d', $val);
                                } elseif ($to == 'datetime') {
                                    $val = date('Y-m-d H:i:s', $val);
                                } elseif ($to == 'int') {
                                    $val = $val;
                                }
                            } elseif ($origin == 'string') {
                                if ($to == 'img') {
                                    $t_width = isset($field['img_width']) ? $field['img_width'] : '100';
                                    $t_height = isset($field['img_height']) ? $field['img_height'] : '100';
                                    $val = '<img style="width:'.$t_width.'px;height:'.$t_height.'px" src="'.$val.'" />';
                                }
                            }
                        }
                    }

                    if (isset($field['lang']) && !empty($field['lang'])) {
                        //var_dump($val);
                        //var_dump($orderField);
                        $val = Yii::$service->fecshoplang->getDefaultLangAttrVal($val, $orderField);
                    }
                }
                $str .= '<td>'.$val.'</td>';
            }
            $str .= '<td>
						<a title="' . Yii::$service->page->translate->__('Edit') . '" target="dialog" class="btnEdit" mask="true" drawable="true" width="1200" height="680" href="'.$this->_editUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" ><i class="fa fa-pencil"></i></a>
						<!-- <a title="' . Yii::$service->page->translate->__('Delete') . '" target="ajaxTodo" href="'.$this->_deleteUrl.'?'.$csrfString.'&'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" class="btnDel"><i class="fa fa-trash-o"></i></a>
						-->
					</td>';
            $str .= '</tr>';
        }

        return $str;
    }
    
    /**
     * @param $searchArr|Array.
     * generate where Array by  $this->_param and $searchArr.
     * foreach $searchArr , check each one if it is exist in this->_param.
     */
    public function initDataWhere($searchArr)
    {
        $where = [];
        foreach ($searchArr as $field) {
            $type = $field['type'];
            $name = $field['name'];
            $lang = $field['lang'];
            // 处理订单状态的搜索
            if ($name == 'order_status') {
                $order_status = $this->_param[$name];
                if ($order_status) {
                    $operate_status_arr = Yii::$service->order->getOperateStatusArr();
                    if (isset($operate_status_arr[$order_status])) {
                        $where[] = [
                            'order_operate_status' => $order_status
                        ];
                    } else {
                        $where[] = [
                            'order_status' => $order_status
                        ];
                    }
                    
                    continue;
                }
            }
            
            if ($name == 'customer_id') {
                $customerPhone = $this->_param[$name];
                if ($customerPhone) {
                    $customerM = Yii::$service->customer->getUserIdentityByPhone($customerPhone);
                    if (isset($customerM['id']) && $customerM['id']) {
                        $where[] = [
                            'customer_id' => $customerM['id']
                        ];
                    } else {
                        $where[] = [
                            'customer_id' => $customerPhone
                        ];
                    }
                    
                    continue;
                }
            }
            $columns_type = isset($field['columns_type']) ? $field['columns_type'] : '';
            if ($this->_param[$name] || $this->_param[$name.'_gte'] || $this->_param[$name.'_lt']) {
                if ($type == 'inputtext' || $type == 'select' || $type == 'chosen_select') {
                    if ($columns_type == 'string') {
                        if ($lang) {
                            $langname = $name.'.'.\Yii::$service->fecshoplang->getDefaultLangAttrName($name);
                            $where[] = ['like', $langname, $this->_param[$name]];
                        } else {
                            $val = $this->_param[$name];
                            if($name == '_id'){
                                $val = new \MongoDB\BSON\ObjectId($val);
                                $where[] = [$name => $val];
                            } else {
                                $where[] = ['like', $name, $val];
                            }
                        }
                    } elseif ($columns_type == 'int') {
                        $where[] = [$name => (int) $this->_param[$name]];
                    } elseif ($columns_type == 'float') {
                        $where[] = [$name => (float) $this->_param[$name]];
                    } elseif ($columns_type == 'date') {
                        $where[] = [$name => $this->_param[$name]];
                    } else {
                        $where[] = [$name => $this->_param[$name]];
                    }
                } elseif ($type == 'inputdatefilter') {
                    $_gte = $this->_param[$name.'_gte'];
                    $_lt = $this->_param[$name.'_lt'];
                    if ($columns_type == 'int') {
                        $_gte = strtotime($_gte);
                        $_lt = strtotime($_lt);
                    }
                    if ($_gte) {
                        $where[] = ['>=', $name, $_gte];
                    }
                    if ($_lt) {
                        $where[] = ['<', $name, $_lt];
                    }
                } elseif ($type == 'inputfilter') {
                    $_gte = $this->_param[$name.'_gte'];
                    $_lt = $this->_param[$name.'_lt'];
                    if ($columns_type == 'int') {
                        $_gte = (int) $_gte;
                        $_lt = (int) $_lt;
                    } elseif ($columns_type == 'float') {
                        $_gte = (float) $_gte;
                        $_lt = (float) $_lt;
                    }
                    if ($_gte) {
                        $where[] = ['>=', $name, $_gte];
                    }
                    if ($_lt) {
                        $where[] = ['<', $name, $_lt];
                    }
                } else {
                    $where[] = [$name => $this->_param[$name]];
                }
            }
        }
        //var_dump($where);
        return $where;
    }
    
    /**
     * get edit html bar, it contains  add ,eidt ,delete  button.
     */
    public function getEditBar()
    {
        /*
        if(!strstr($this->_currentParamUrl,"?")){
            $csvUrl = $this->_currentParamUrl."?type=export";
        }else{
            $csvUrl = $this->_currentParamUrl."&type=export";
        }
        target="dwzExport" targetType="navTab"  rel="'.$this->_primaryKey.'s"
        <li class="line">line</li>
        <li><a class="icon csvdownload"   href="'.$csvUrl.'" target="dwzExport" targetType="navTab" title="实要导出这些记录吗?"><span>导出EXCEL</span></a></li>
        */
        return '<ul class="toolBar">
					<li class="line">line</li>
                    <li><a class="icon exportOrderExcel" href="javascript:void()"  postType="string"  target="_blank" title="' . Yii::$service->page->translate->__('Are you sure you want to export the selected order') . '?"><span>' . Yii::$service->page->translate->__('Export Excel') . '</span></a></li>
				</ul>
                <script>
                    $(document).ready(function(){
                        $(".exportOrderExcel").click(function(){
                            var selectOrderIds = \'\';
                            $(\'.grid input:checkbox[name=order_ids]:checked\').each(function(k){
                                if(k == 0){
                                    selectOrderIds = $(this).val();
                                }else{
                                    selectOrderIds += \',\'+$(this).val();
                                }
                            });
                            if (!selectOrderIds) {
                                var message = "' . Yii::$service->page->translate->__('Choose at least one order') . '";
                                alertMsg.error(message);
                            } else {
                                url = "'.$this->_exportExcelUrl.'" ;
                                doPost(url, {"order_ids": selectOrderIds, "'.CRequest::getCsrfName().'": "'.CRequest::getCsrfValue() .'"});
                            }
                        });
                    });
                </script> 
        ';
    }
    
}
