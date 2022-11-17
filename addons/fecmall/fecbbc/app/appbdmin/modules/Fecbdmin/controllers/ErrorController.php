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
class ErrorController extends AppbdminController
{
	public $enableCsrfValidation = true;
    
	# 刷新缓存
    public function actionIndex()
    {
        echo "<br><b> Page: 404 !!!! ,页面找不到 ".Yii::$app->request->getUrl().",，请先建立相应的module/controller/action
        ，再访问该URL
        </b>";
        exit;
	}
	
	
	
	
}








