<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\apphtml5\modules\Coupon;

use fecshop\app\apphtml5\modules\AppfrontModule;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 * Catalog Module 模块
 */
class Module extends AppfrontModule
{
    public $blockNamespace;

    public function init()
    {
        parent::init();
        // 以下代码必须指定
        $nameSpace = __NAMESPACE__;
        // 如果 Yii::$app 对象是由类\yii\web\Application 实例化出来的。
        if (Yii::$app instanceof \yii\web\Application) {
            // 设置模块 controller namespace的文件路径
            $this->controllerNamespace = $nameSpace . '\\controllers';
            // 设置模块block namespace的文件路径
            $this->blockNamespace = $nameSpace . '\\block';
        
        }
        
        /// 设置该模块的view(theme)的默认layout文件。
        Yii::$service->page->theme->layoutFile = 'main.php';
        
    }
}
