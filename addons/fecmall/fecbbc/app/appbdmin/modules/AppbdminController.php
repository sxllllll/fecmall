<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */

namespace fecbbc\app\appbdmin\modules;

use fec\controllers\FecController;
use fec\helpers\CConfig;
use Yii;
use yii\base\InvalidValueException;
use yii\web\Controller;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AppbdminController extends Controller
{
    public $blockNamespace;
    public $enableCsrfValidation = true;
    
    /**
     * init theme component property : $fecshopThemeDir and $layoutFile
     * $fecshopThemeDir is appfront base theme directory.
     * layoutFile is current layout relative path.
     */
    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) {
            Yii::$service->url->redirectByUrlKey('/fecbdmin/login/index');
        }
        if (!Yii::$service->page->theme->fecshopThemeDir) {
            Yii::$service->page->theme->fecshopThemeDir = Yii::getAlias(CConfig::param('appbdminBaseTheme'));
        }
        if (!Yii::$service->page->theme->layoutFile) {
            Yii::$service->page->theme->layoutFile = CConfig::param('appbdminBaseLayoutName');
        }
        // 设置本地模板路径
        $localThemeDir = Yii::$app->params['localThemeDir'];
        if($localThemeDir){
            Yii::$service->page->theme->setLocalThemeDir($localThemeDir);
        }
        /*
         *  set i18n translate category.
         */
        Yii::$service->page->translate->category = 'appbdmin';
        /*
         * 自定义Yii::$classMap,用于重写
         */
    }

    public function beforeAction($action)
    {
        
        return true;
    }
    

    /**
     * @param $view|string , (only) view file name ,by this module id, this controller id , generate view relative path.
     * @param $params|Array,
     * 1.get exist view file from mutil theme by theme protity.
     * 2.get content by yii view compontent  function renderFile()  ,
     */
    public function render($view, $params = [])
    {
        $viewFile = Yii::$service->page->theme->getViewFile($view);
        $content = Yii::$app->view->renderFile($viewFile, $params, $this);

        return $this->renderContent($content);
    }

    /**
     * Get current layoutFile absolute path from mutil theme dir by protity.
     */
    public function findLayoutFile($view)
    {
        $layoutFile = '';
        $relativeFile = 'layouts/'.Yii::$service->page->theme->layoutFile;
        $absoluteDir = Yii::$service->page->theme->getThemeDirArr();
        foreach ($absoluteDir as $dir) {
            if ($dir) {
                $file = $dir.'/'.$relativeFile;
                if (file_exists($file)) {
                    $layoutFile = $file;
                    return $layoutFile;
                }
            }
        }
        throw new InvalidValueException('layout file is not exist!');
    }
    
    public function getFecadminBlock($blockname=''){
	    $_currentNameSpace = \fec\helpers\CModule::param("_currentNameSpace");
		//echo $_currentNameSpace;exit;
        if(empty($_currentNameSpace)){
			$message = "Modules Param '_currentNameSpace'  is not set , you can set like fecadmin\\Module";
			throw new \yii\web\HttpException(406,$message);
		}
		$modulesDir = "\\".$_currentNameSpace."\\block\\";
		$url_key = \fec\helpers\CUrl::getUrlKey();
		$url_key = trim($url_key,"/");
		$url_key = substr($url_key,strpos($url_key,"/")+1 );
		$url_key_arr = explode("/",$url_key);
		if(!isset($url_key_arr[1])) $url_key_arr[1] = 'index';
		if($blockname){
			$url_key_arr[count($url_key_arr)-1] = ucfirst($blockname);
		}else{
			$url_key_arr[count($url_key_arr)-1] = ucfirst($url_key_arr[count($url_key_arr)-1]);
		}
		
		$block_space = implode("\\",$url_key_arr);
		$blockFile = $modulesDir.$block_space;
		//查找是否在rewriteMap中存在重写
        //$relativeFile = Yii::mapGetName($relativeFile);
        $blockFile = Yii::mapGetName($blockFile);
        //echo $blockFile;exit;
        
		return new $blockFile;
		
    }

    public function getBlock($blockName = ''){
        if (!$blockName) {
            $blockName = $this->action->id;
        }
        if (!$this->blockNamespace) {
            $this->blockNamespace = Yii::$app->controller->module->blockNamespace;
        }
        if (!$this->blockNamespace) {
            throw new \yii\web\HttpException(406, 'blockNamespace is empty , you should config it in module->blockNamespace or controller blockNamespace ');
        }
        $viewId = $this->id;
        $viewId = str_replace('/', '\\', $viewId);
        $relativeFile = '\\'.$this->blockNamespace;
        $relativeFile .= '\\'.$viewId.'\\'.ucfirst($blockName);

        //查找是否在rewriteMap中存在重写
        $relativeFile = Yii::mapGetName($relativeFile);
        return new $relativeFile();

    }
}
