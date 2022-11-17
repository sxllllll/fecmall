<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class  Store extends \fecshop\components\Store  implements BootstrapInterface
{
    public $appName;

    public function bootstrap($app)
    {
        if ($this->appName == 'appadmin') {
            Yii::$service->admin->bootstrap($app);
            // 设置third theme： bbc base theme
            $thirdThemeDir = Yii::$service->page->theme->thirdThemeDir;
            if ($bbcBaseThemeDir = Yii::$app->params['bbcBaseThemeDir']) {
                if (is_array($thirdThemeDir) && !empty($thirdThemeDir)) {
                    $thirdThemeDir[] = $bbcBaseThemeDir;
                    Yii::$service->page->theme->thirdThemeDir = $thirdThemeDir;
                } else {
                    Yii::$service->page->theme->thirdThemeDir = [$bbcBaseThemeDir];
                }
            }
        } else if ($this->appName == 'appbdmin'){    
            Yii::$service->bdmin->bootstrap($app);
        } else {
            Yii::$service->store->bootstrap($app);
        }
    }
    
    protected $_bdmin_config = [];
    /**
     * @param $key | string, 配置的主Key
     * @param $subKey | string, 配置的子key
     */
    public function bdminGet($bdmin_user_id, $key, $subKey = '')
    {
        $this->initBdminConfig($bdmin_user_id);
        if (!$subKey) {
            
            return isset($this->_bdmin_config[$bdmin_user_id][$key]) ? $this->_bdmin_config[$bdmin_user_id][$key] : null;
        }
        
        return isset($this->_bdmin_config[$bdmin_user_id][$key][$subKey]) ? $this->_bdmin_config[$bdmin_user_id][$key][$subKey] : null;
    }
    
    
    public function initBdminConfig($bdmin_user_id)
    {
        if (!isset($this->_bdmin_config[$bdmin_user_id])) {
            
            $this->_bdmin_config[$bdmin_user_id] = Yii::$service->storeBdminConfig->getBdminConfig($bdmin_user_id);
        }
    }
    
    
}
