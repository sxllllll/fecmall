<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
namespace fecbbc\app\appbdmin\modules\Fecbdmin\controllers;
use Yii;
use fec\helpers\CRequest;
use fecadmin\FecadminbaseController;
use fecbbc\app\appbdmin\modules\AppbdminController;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class IndexController extends AppbdminController
{
	public $enableCsrfValidation = true;
    
    public function init()
    {
        parent::init(); 
        Yii::$service->page->theme->layoutFile = 'dashboard.php';
        
    }
    
    public function actionIndex()
    {
		return $this->render('index');
	}
	
	
	
	
}








