<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecbbc\app\appbdmin\modules\Fecbdmin\controllers;
use Yii;
use fec\helpers\CRequest;
use fecadmin\FecbdminbaseController;
use fecbbc\app\appbdmin\modules\AppbdminController;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AccountController extends AppbdminController
{
	public $enableCsrfValidation = true;
	public $blockNamespace = 'fecbbc\\app\\appbdmin\\modules\\Fecbdmin\\block';
    
    public function init()
    {
        // Yii::$service->page->theme->layoutFile = 'dashboard.php';
        parent::init(); 
    }
    
    # 我的账户
    //public function actionIndex()
    //{
    //    $data = $this->getBlock()->getLastData();
	//	return $this->render($this->action->id,$data);
	//}
	
	# 我的账户
    public function actionManager()
    {
        //echo $this->action->id ;exit;
		$data = $this->getBlock()->getLastData();
		return $this->render($this->action->id,$data);
	}
	
	
	public function actionManageredit()
    {
		//echo $this->action->id ;exit;
		$data = $this->getBlock()->getLastData();
		return $this->render($this->action->id,$data);
	}
	
	public function actionManagereditsave()
    {
		
		//echo $this->action->id ;exit;
		$data = $this->getBlock("manageredit")->save();
		//return $this->render($this->action->id,$data);
	}
	
	public function actionManagerdelete()
    {
		//echo $this->action->id ;exit;
		$this->getBlock("manageredit")->delete();
		
	}

	
}








