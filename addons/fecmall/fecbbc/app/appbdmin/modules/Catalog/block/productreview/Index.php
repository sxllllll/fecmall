<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\app\appbdmin\modules\Catalog\block\productreview;

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
class Index extends AppbdminbaseBlock implements AppbdminbaseBlockInterface
{
    public $_auditUrl;
    public $_auditRejectedUrl;

    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        $this->_auditUrl = CUrl::getUrl('catalog/productreview/manageraudit');
        $this->_auditRejectedUrl = CUrl::getUrl('catalog/productreview/managerauditrejected');
        /*
         * edit data url
         */
        $this->_editUrl = CUrl::getUrl('catalog/productreview/manageredit');
        /*
         * delete data url
         */
        $this->_deleteUrl = CUrl::getUrl('catalog/productreview/managerdelete');
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->product->review;
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
            'pagerForm'        => $pagerForm,
            'searchBar'        => $searchBar,
            'editBar'        => $editBar,
            'thead'        => $thead,
            'tbody'        => $tbody,
            'toolBar'    => $toolBar,
        ];
    }

    /**
     * get search bar Arr config.
     */
    public function getSearchArr()
    {
        $activeStatus = Yii::$service->product->review->activeStatus();
        $refuseStatus = Yii::$service->product->review->refuseStatus();
        $noActiveStatus = Yii::$service->product->review->noActiveStatus();
        $data = [
            [    // selecit???Int ??????
                'type' => 'select',
                'title' => Yii::$service->page->translate->__('Review Status'),
                'name' => 'status',
                'columns_type' => 'int',  // int????????????????????? string??????????????????
                'value' => [                    // select ????????????
                    $noActiveStatus => Yii::$service->page->translate->__('Pending Review'),
                    $activeStatus    => Yii::$service->page->translate->__('Approved'),
                    $refuseStatus    => Yii::$service->page->translate->__('Not Approved'),
                ],
            ],
            [    // ???????????????
                'type' => 'inputtext',
                'title'  => Yii::$service->page->translate->__('Spu'),
                'name' => 'product_spu',
                'columns_type' => 'string',
            ],
            [    // ????????????????????????
                'type' => 'inputdatefilter',
                'title'  => Yii::$service->page->translate->__('Review Date'),
                'name' => 'review_date',
                'columns_type' => 'int',
                'value'=>[
                    'gte' => Yii::$service->page->translate->__('Review Begin'),
                    'lt' => Yii::$service->page->translate->__('Review End'),
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
        $activeStatus = Yii::$service->product->review->activeStatus();
        $refuseStatus = Yii::$service->product->review->refuseStatus();
        $noActiveStatus = Yii::$service->product->review->noActiveStatus();

        $table_th_bar = [
            [
                'orderField'    => '_id',
                'label'           => Yii::$service->page->translate->__('Id'),
                'width'          => '50',
                'align'           => 'left',

            ],
            [
                'orderField'     => 'product_id',
                'label'            => Yii::$service->page->translate->__('Product Id'),
                'width'           => '80',
                'align'            => 'center',
            ],

            [
                'orderField'    => 'rate_star',
                'label'           => Yii::$service->page->translate->__('Rate Star'),
                'width'          => '110',
                'align'           => 'center',
                'width'          => '30',
            ],

            [
                'orderField'    => 'name',
                'label'           => Yii::$service->page->translate->__('Review Person'),
                'width'          => '110',
                'align'           => 'center',
            ],


            [
                'orderField'    => 'review_date',
                'label'           => Yii::$service->page->translate->__('Review Date'),
                'width'          => '110',
                'align'           => 'center',
                'convert'       => ['int' => 'datetime'],
            ],

            [
                'orderField'    => 'store',
                'label'           => Yii::$service->page->translate->__('Store'),
                'width'          => '110',
                'align'           => 'left',
            ],

            [
                'orderField'    => 'lang_code',
                'label'           => Yii::$service->page->translate->__('Lang Code'),
                'width'          => '65',
                'align'           => 'center',

            ],

            [
                'orderField'    => 'status',
                'label'           => Yii::$service->page->translate->__('Status'),
                'width'          => '120',
                'display'        => [
                    $noActiveStatus => Yii::$service->page->translate->__('Pending Review'),
                    $activeStatus     => Yii::$service->page->translate->__('Approved'),
                    $refuseStatus    => Yii::$service->page->translate->__('Not Approved'),
                ],   
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
        $identity = Yii::$app->user->identity;
        $where[] = [
            'bdmin_user_id' => $identity->id,
        ];
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
     * rewrite parent getTableTbodyHtml($data).
     */
    public function getTableTbodyHtml($data)
    {
        $fileds = $this->getTableFieldArr();
        $str .= '';
        $csrfString = \fec\helpers\CRequest::getCsrfString();
        $user_ids = [];
        $product_ids = [];
        foreach ($data as $one) {
            $user_ids[] = $one['audit_user'];
            $product_ids[] = $one['product_id'];
        }
        $users = Yii::$service->adminUser->getIdAndNameArrByIds($user_ids);
        $product_skus = Yii::$service->product->getSkusByIds($product_ids);

        foreach ($data as $one) {
            $str .= '<tr target="sid_user" rel="'.$one[$this->_primaryKey].'">';
            $str .= '<td><input name="'.$this->_primaryKey.'s" value="'.$one[$this->_primaryKey].'" type="checkbox"></td>';
            foreach ($fileds as $field) {
                $orderField = $field['orderField'];
                $display = $field['display'];
                $val = $one[$orderField];
                if ($orderField == 'audit_user') {
                    //var_dump($users);
                    $val = isset($users[$val]) ? $users[$val] : $val;
                    $str .= '<td>'.$val.'</td>';
                    continue;
                }
                if ($orderField == 'product_id') {
                    //echo 11;
                    //var_dump($product_skus);
                    $val = isset($product_skus[$val]) ? $product_skus[$val] : $val;
                    $str .= '<td>'.$val.'</td>';
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
						<a title="' . Yii::$service->page->translate->__('View') . '" target="dialog" class="btnEdit" mask="true" drawable="true" width="1200" height="680" href="'.$this->_editUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" ><i class="fa fa-eye"></i></a>
					</td>';
            $str .= '</tr>';
        }

        return $str;
    }
    
    public function getTableTheadHtml($table_th_bar)
    {
        $table_th_bar = $this->getTableTheadArrInit($table_th_bar);
        $this->_param['orderField'] = $this->_param['orderField'] ? $this->_param['orderField'] : $this->_primaryKey;
        $this->_param['orderDirection'] = $this->_param['orderDirection'];
        foreach ($table_th_bar as $k => $field) {
            if ($field['orderField'] == $this->_param['orderField']) {
                $table_th_bar[$k]['class'] = $this->_param['orderDirection'];
            }
        }
        $str = '<thead><tr>';
        $str .= '<th width="22"><input type="checkbox" group="'.$this->_primaryKey.'s" class="checkboxCtrl"></th>';
        foreach ($table_th_bar as $b) {
            $width = $b['width'];
            $label = $b['label'];
            $orderField = $b['orderField'];
            $class = isset($b['class']) ? $b['class'] : '';
            $align = isset($b['align']) ? 'align="'.$b['align'].'"' : '';
            $str .= '<th width="'.$width.'" '.$align.' orderField="'.$orderField.'" class="'.$class.'">'.$label.'</th>';
        }
        $str .= '<th width="80" >' . Yii::$service->page->translate->__('View') . '</th>';
        $str .= '</tr></thead>';

        return $str;
    }
    
    public function getEditBar()
    {
        return '';
    }
}
