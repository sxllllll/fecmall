<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecbbc\app\appadmin\modules\Supplier\controllers;

use fecshop\app\appadmin\modules\AppadminController;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ApplyController extends AppadminController
{
    public $enableCsrfValidation = true;
    
    public function actionManager()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function actionManageredit()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
    /*
    public function actionManagereditsave()
    {
        $data = $this->getBlock('manageredit')->save();
    }
    */

    public function actionManageraccept()
    {
        $this->getBlock('manageredit')->accept();
    }
    
    public function actionManagerrefuse()
    {
        $this->getBlock('manageredit')->refuse();
    }
    
    public function actionDownloadzip()
    {
        $zip_file = Yii::$app->request->get('zip_file');
        
        $zipPath = Yii::$service->bdminUser->bdminUser->uploadZipPath;
        $filePath = Yii::getAlias($zipPath.$zip_file);
        
        $file_fullpath = $filePath;
        if (!file_exists($file_fullpath)) {
            echo '文件不存在';exit;
        }
        //echo  $file_fullpath;exit;
        header("Content-Type: application/octet-stream");
        header('Content-Disposition: attachment; filename="'.$zip_file.'"');//文件描述，页面下载用的文件名，可以实现用不同的文件名下载同一个文件
        
        $data = fopen($file_fullpath, 'rb');
        while (!feof($data)) {
                echo @fread($data, 8192);
                flush();
                ob_flush();
        }
        fclose($data);
        
    }
    
}
