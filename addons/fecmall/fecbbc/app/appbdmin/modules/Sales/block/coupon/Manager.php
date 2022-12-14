<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\app\appbdmin\modules\Sales\block\coupon;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecbbc\app\appbdmin\interfaces\base\AppbdminbaseBlockInterface;
use fecbbc\app\appbdmin\modules\AppbdminbaseBlock;
use Yii;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manager extends AppbdminbaseBlock implements AppbdminbaseBlockInterface
{
    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        /*
         * edit data url
         */
        $this->_editUrl = CUrl::getUrl('sales/coupon/manageredit');
        /*
         * delete data url
         */
        $this->_deleteUrl = CUrl::getUrl('sales/coupon/managerdelete');
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->coupon;
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
            'pagerForm'     => $pagerForm,
            'searchBar'      => $searchBar,
            'editBar'          => $editBar,
            'thead'            => $thead,
            'tbody'            => $tbody,
            'toolBar'          => $toolBar,
        ];
    }

    /**
     * get search bar Arr config.
     */
    public function getSearchArr()
    {
        $data = [

            [    // 字符串类型
                'type' => 'inputtext',
                'title'  => Yii::$service->page->translate->__('Coupon Code'),
                'name' => 'code',
                'columns_type' => 'string',
            ],
            [    // 时间区间类型搜索
                'type'  => 'inputdatefilter',
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
                'orderField'    => 'code',
                'label'           => Yii::$service->page->translate->__('Coupon Code'),
                'width'          => '50',
                'align'           => 'left',
                //'lang'		   => true,
            ],
            [
                'orderField'    => 'name',
                'label'           => Yii::$service->page->translate->__('Coupon Name'),
                'width'          => '50',
                'align'           => 'left',
                //'lang'		   => true,
            ],
            
            [
                'orderField'    => 'total_count',
                'label'           => Yii::$service->page->translate->__('Coupon Total Count'),
                'width'          => '50',
                'align'           => 'left',
                //'lang'		   => true,
            ],
            [
                'orderField'    => 'assign_count',
                'label'           => Yii::$service->page->translate->__('Assign Count'),
                'width'          => '50',
                'align'           => 'left',
                //'lang'		   => true,
            ],
            
            [
                'orderField'    => 'discount_cost',
                'label'           => Yii::$service->page->translate->__('Discount Cost'),
                'width'          => '50',
                'align'           => 'left',
                //'lang'		   => true,
            ],
            [
                'orderField'    => 'use_condition',
                'label'           => Yii::$service->page->translate->__('Use Condition'),
                'width'          => '50',
                'align'           => 'left',
                //'lang'		   => true,
            ],
            [
                'orderField'    => 'condition_product_type',
                'label'           => Yii::$service->page->translate->__('Condition Type'),
                'width'          => '50',
                'align'           => 'left',
                'display'        => [
                    Yii::$service->coupon->condition_product_type_all => Yii::$service->page->translate->__('All Product'),
                    Yii::$service->coupon->condition_product_type_sku => Yii::$service->page->translate->__('Specify Sku'),
                    Yii::$service->coupon->condition_product_type_category_id => Yii::$service->page->translate->__('Specify Category'),
                ]
            ],
            
            [
                'orderField'    => 'assign_begin_at',
                'label'           => Yii::$service->page->translate->__('Assign Begin At'),
                'width'          => '110',
                'align'           => 'center',
                'convert'       => ['int' => 'date'],
            ],
            [
                'orderField'    => 'assign_end_at',
                'label'           => Yii::$service->page->translate->__('Assign End At'),
                'width'          => '110',
                'align'           => 'center',
                'convert'       => ['int' => 'date'],
            ],
            
            [
                'orderField'    => 'created_at',
                'label'           => Yii::$service->page->translate->__('Created At'),
                'width'          => '110',
                'align'           => 'center',
                'convert'       => ['int' => 'datetime'],
            ],
            [
                'orderField'    => 'updated_at',
                'label'           => Yii::$service->page->translate->__('Updated At'),
                'width'          => '110',
                'align'           => 'center',
                'convert'       => ['int' => 'datetime'],
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
        $str .= '';
        $csrfString = \fec\helpers\CRequest::getCsrfString();
        $user_ids = [];
        foreach ($data as $one) {
            $user_ids[] = $one['created_person'];
        }
        $users = Yii::$service->adminUser->getIdAndNameArrByIds($user_ids);
        foreach ($data as $one) {
            $str .= '<tr target="sid_user" rel="'.$one[$this->_primaryKey].'">';
            $str .= '<td><input name="'.$this->_primaryKey.'s" value="'.$one[$this->_primaryKey].'" type="checkbox"></td>';
            foreach ($fileds as $field) {
                $orderField = $field['orderField'];
                $display = $field['display'];
                $val = $one[$orderField];
                
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
						<a title="' . Yii::$service->page->translate->__('Edit')  . '" target="dialog" class="btnEdit" mask="true" drawable="true" width="1200" height="680" href="'.$this->_editUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" ><i class="fa fa-pencil"></i></a>
						<a title="' . Yii::$service->page->translate->__('Remove')  . '" target="ajaxTodo" href="'.$this->_deleteUrl.'?'.$csrfString.'&'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" class="btnDel"     csrfName="' .CRequest::getCsrfName(). '" csrfVal="' .CRequest::getCsrfValue(). '"       ><i class="fa fa-trash-o"></i></a>
					</td>';
            $str .= '</tr>';
        }

        return $str;
    }
    
    /**
     * list table body.
     */
    public function getTableTbody()
    {
        $searchArr = $this->getSearchArr();
        if (is_array($searchArr) && !empty($searchArr)) {
            $where = $this->initDataWhere($searchArr);
        }
        $where[] = ['bdmin_user_id' => Yii::$app->user->identity->id];
        //var_dump($where);
        $filter = [
            'numPerPage'    => $this->_param['numPerPage'],
            'pageNum'        => $this->_param['pageNum'],
            'orderBy'        => [$this->_param['orderField'] => (($this->_param['orderDirection'] == 'asc') ? SORT_ASC : SORT_DESC)],
            'where'            => $where,
            'asArray'        => $this->_asArray,
        ];
        $coll = $this->_service->coll($filter);
        $data = $coll['coll'];
        $this->_param['numCount'] = $coll['count'];

        return $this->getTableTbodyHtml($data);
    }
    
    
}
