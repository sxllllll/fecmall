<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\app\appbdmin\modules\Sales\block\ordersettle;

use fec\helpers\CUrl;
use Yii;
use fecbbc\app\appbdmin\interfaces\base\AppbdminbaseBlockEditInterface;
use fecbbc\app\appbdmin\modules\AppbdminbaseBlockEdit;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit extends AppbdminbaseBlockEdit implements AppbdminbaseBlockEditInterface
{
    //public $_saveUrl;

    public function init()
    {
        //$this->_saveUrl = CUrl::getUrl('sales/ordersettle/managereditsave');
        parent::init();
        
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        $bdmin_user_id = Yii::$app->user->identity->id;
        if ($bdmin_user_id != $this->_one['bdmin_user_id']) { // 权限
            
            return [];
        }
        return [
            'editBar'      => $this->getEditBar(),
            //'saveUrl'     => $this->_saveUrl,
        ];
    }
    public function setService()
    {
        $this->_service = Yii::$service->statistics->bdminMonth;
    }
    
    
    public function getEditArr()
    {
        
        return [
            [
                'label'  => Yii::$service->page->translate->__('Id'),
                'name' => 'id',
                'display' => [
                    'type' => 'inputString',

                ],
                'require' => 0,
            ],
            
            
            [
                'label'  => Yii::$service->page->translate->__('year'),
                'name' => 'year',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
             [
                'label'  => Yii::$service->page->translate->__('month'),
                'name' => 'month',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Month Total'),
                'name' => 'month_total',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
            
            [
                'label'  => Yii::$service->page->translate->__('Complete Order Total'),
                'name' => 'complete_order_total',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Admin Refund Return Total'),
                'name' => 'admin_refund_return_total',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],

            [
                'label'  => Yii::$service->page->translate->__('Bdmin Refund Return Total'),
                'name' => 'bdmin_refund_return_total',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
           
           
            
        ];
    }
    
   
}
