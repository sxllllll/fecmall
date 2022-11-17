<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Catalog\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CategorylistController extends AppfrontController
{
    public $enableCsrfValidation = true;
    public $blockNamespace = 'fecbbc\app\apphtml5\modules\Catalog\block';
    
    public function init()
    {
        parent::init();
        Yii::$service->page->theme->layoutFile = 'category_list.php';
    }

    // 分类页面。
    public function actionIndex()
    {
        $data = $this->getBlock()->getLastData();
        if(is_array($data)){
            return $this->render($this->action->id, $data);
        }
    }

}
