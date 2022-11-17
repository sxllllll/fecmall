<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appadmin\modules\Supplier\block\apply;

use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use Yii;
use fec\helpers\CRequest;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manager extends AppadminbaseBlock implements AppadminbaseBlockInterface
{
    
    protected $_auditAcceptUrl;
    protected $_auditRefuseUrl;
    
    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        /*
         * edit data url
         */
        $this->_editUrl = CUrl::getUrl('supplier/apply/manageredit');
        /*
         * delete data url
         */
        $this->_deleteUrl = CUrl::getUrl('supplier/apply/managerdelete');
        $this->_auditAcceptUrl = CUrl::getUrl('supplier/apply/manageraccept');
        $this->_auditRefuseUrl = CUrl::getUrl('supplier/apply/managerrefuse');
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->bdminUser->bdminUser;
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
            'pagerForm'    => $pagerForm,
            'searchBar'     => $searchBar,
            'editBar'         => $editBar,
            'thead'           => $thead,
            'tbody'           => $tbody,
            'toolBar'         => $toolBar,
        ];
    }

    /**
     * get search bar Arr config.
     */
    public function getSearchArr()
    {
        $deleteStatus = Yii::$service->customer->getStatusDeleted();
        $activeStatus = Yii::$service->customer->getStatusActive();

        $data = [
            [    // selecit的Int 类型
                'type' => 'select',
                'title'  => Yii::$service->page->translate->__('Audit Status'),
                'name' => 'is_audit',
                'columns_type' =>'int',  // int使用标准匹配， string使用模糊查询
                'value'=> Yii::$service->bdminUser->bdminUser->getAuditStatusArr(),
            ],
            
            [    // selecit的Int 类型
                'type' => 'select',
                'title'  => Yii::$service->page->translate->__('Authorized Type'),
                'name' => 'authorized_type',
                'columns_type' =>'int',  // int使用标准匹配， string使用模糊查询
                'value'=> Yii::$service->bdminUser->bdminUser->getAuthorizedTypes(),
            ],
            
            [    // selecit的Int 类型
                'type' => 'select',
                'title'  => Yii::$service->page->translate->__('Authorized Role'),
                'name' => 'authorized_role',
                'columns_type' =>'int',  // int使用标准匹配， string使用模糊查询
                'value'=> Yii::$service->bdminUser->bdminUser->getAuthorizedRoles(),
            ],
            
            
            [    // 时间区间类型搜索
                'type'   => 'inputdatefilter',
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
                'orderField'    => $this->_primaryKey,
                'label'           => Yii::$service->page->translate->__('Id'),
                'width'          => '50',
                'align'           => 'center',
            ],
            [
                'orderField'    => 'name',
                'label'           => Yii::$service->page->translate->__('Name'),
                'width'          => '50',
                'align'           => 'left',
            ],
            [
                'orderField'    => 'telephone',
                'label'           => Yii::$service->page->translate->__('Telephone'),
                'width'          => '50',
                'align'           => 'left',
            ],
            
            [
                'orderField'    => 'authorized_type',
                'label'           => Yii::$service->page->translate->__('Authorized Type'),
                'width'          => '50',
                'align'           => 'left',
                'display'        => Yii::$service->bdminUser->bdminUser->getAuthorizedTypes(),
            ],
            [
                'orderField'    => 'authorized_role',
                'label'           => Yii::$service->page->translate->__('Authorized Role'),
                'width'          => '50',
                'align'           => 'left',
                'display'        => Yii::$service->bdminUser->bdminUser->getAuthorizedRoles(),
            ],
            
            [
                'orderField'    => 'is_audit',
                'label'           => Yii::$service->page->translate->__('Audit Status'),
                'width'          => '50',
                'align'           => 'left',
                'display'        => Yii::$service->bdminUser->bdminUser->getAuditStatusArr(),
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
     * list table body.
     */
    public function getTableTbody()
    {
        $searchArr = $this->getSearchArr();
        if (is_array($searchArr) && !empty($searchArr)) {
            $where = $this->initDataWhere($searchArr);
        }
        $where[] = [ 'not', [ 'audit_at' => null ] ];

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
    
    /**
     * get edit html bar, it contains  add ,eidt ,delete  button.
     */
    //public function getEditBar()
    //{
    //    return '';
    //}
    
    
    public function getTableTbodyHtml($data)
    {
        $fields = $this->getTableFieldArr();
        $str = '';
        $csrfString = CRequest::getCsrfString();
        foreach ($data as $one) {
            $str .= '<tr target="sid_user" rel="'.$one[$this->_primaryKey].'">';
            $str .= '<td><input name="'.$this->_primaryKey.'s" value="'.$one[$this->_primaryKey].'" type="checkbox"></td>';
            foreach ($fields as $field) {
                $orderField = $field['orderField'];
                $display = $field['display'];
                $translate = $field['translate'];
                $val = $one[$orderField];
                $display_title = '';
                if ($val) {
                    if (isset($field['display']) && !empty($field['display'])) {
                        $display = $field['display'];
                        $val = $display[$val] ? $display[$val] : $val;
                        $display_title = $val;
                    }
                    if (isset($field['convert']) && !empty($field['convert'])) {
                        $convert = $field['convert'];
                        foreach ($convert as $origin =>$to) {
                            if (strstr($origin, 'mongodate')) {
                                if (isset($val->sec)) {
                                    $timestamp = $val->sec;
                                    if ($to == 'date') {
                                        $val = date('Y-m-d', $timestamp);
                                    } elseif ($to == 'datetime') {
                                        $val = date('Y-m-d H:i:s', $timestamp);
                                    } elseif ($to == 'int') {
                                        $val = $timestamp;
                                    }
                                    $display_title = $val;
                                }
                            } elseif (strstr($origin, 'date')) {
                                if ($to == 'date') {
                                    $val = date('Y-m-d', strtotime($val));
                                } elseif ($to == 'datetime') {
                                    $val = date('Y-m-d H:i:s', strtotime($val));
                                } elseif ($to == 'int') {
                                    $val = strtotime($val);
                                }
                                $display_title = $val;
                            } elseif ($origin == 'int') {
                                if ($to == 'date') {
                                    $val = date('Y-m-d', $val);
                                } elseif ($to == 'datetime') {
                                    $val = date('Y-m-d H:i:s', $val);
                                } elseif ($to == 'int') {
                                    $val = $val;
                                }
                                $display_title = $val;
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
                        $val = Yii::$service->fecshoplang->getDefaultLangAttrVal($val, $orderField);
                    }
                }
                if ($translate) {
                    $val = Yii::$service->page->translate->__($val);
                }
                $str .= '<td><span title="'.$display_title.'">'.$val.'</span></td>';
            }
            $str .= '<td>
						<a title="' . Yii::$service->page->translate->__('Edit') . '" target="dialog" class="btnEdit" mask="true" drawable="true" width="1200" height="680" href="'.$this->_editUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" ><i class="fa fa-eye"></i></a>
					</td>';
            $str .= '</tr>';
        }

        return $str;
    }
    
     public function getEditBar()
    {
        return '<ul class="toolBar">
					<li><a csrfName="' .CRequest::getCsrfName(). '" csrfVal="' .CRequest::getCsrfValue(). '" title="' . Yii::$service->page->translate->__('Are you sure you want to accept these apply in bulk') . '?" target="selectedTodo" rel="'.$this->_primaryKey.'s" postType="string" href="'.$this->_auditAcceptUrl.'" class="edit"><span>' . Yii::$service->page->translate->__('Bulk Approved') . '</span></a></li>
					<li><a csrfName="' .CRequest::getCsrfName(). '" csrfVal="' .CRequest::getCsrfValue(). '" title="' . Yii::$service->page->translate->__('Are you sure you want to refuse these apply in bulk') . '?" target="selectedTodo" rel="'.$this->_primaryKey.'s" postType="string" href="'.$this->_auditRefuseUrl.'" class="edit"><span>' . Yii::$service->page->translate->__('Bulk Not Approved') . '</span></a></li>
				</ul>';
    }
    
    
    
    
}
