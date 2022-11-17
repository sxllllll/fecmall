<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appfront\modules\Catalogsearch\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class IndexController extends \fecshop\app\appfront\modules\Catalogsearch\controllers\IndexController
{
    public $blockNamespace = 'fecbbc\app\appfront\modules\Catalogsearch\block';
    
    public function init()
    {
        parent::init();
        Yii::$service->page->theme->layoutFile = 'category_view.php';
    }
    
}
