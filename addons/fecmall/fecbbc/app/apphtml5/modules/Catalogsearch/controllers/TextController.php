<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Catalogsearch\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class TextController extends AppfrontController
{
    public function init()
    {
        parent::init();
        Yii::$service->page->theme->layoutFile = 'category_list.php';
    }

    //
    public function actionIndex()
    {
        
        $hot_searchs = Yii::$app->store->get('fecbbc_info', 'hot_searchs'); 
        $hot_search_arr = [];
        if ($hot_searchs) {
            $hot_search_arr = explode(',', $hot_searchs);
        }
        $data = [
            'hostSearch' => $hot_search_arr,
        ];

        return $this->render($this->action->id, $data);
    }
}
