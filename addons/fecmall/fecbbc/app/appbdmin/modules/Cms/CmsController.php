<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\app\appbdmin\modules\Cms;

/*
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
use fecadmin\FecadminbaseController;
use Yii;
use fecbbc\app\appbdmin\modules\AppbdminController;

class CmsController extends AppbdminController
{
    public $enableCsrfValidation = true;
    
    //public function getViewPath()
    //{
    //    return Yii::getAlias('@fbbcbase/app/appbdmin/modules/Cms/views') . DIRECTORY_SEPARATOR . $this->id;
    //}
}
