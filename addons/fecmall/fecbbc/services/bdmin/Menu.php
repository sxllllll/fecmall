<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
namespace fecbbc\services\bdmin;

use fec\helpers\CUrl;
use fecshop\services\Service;
use Yii;

/**
 * Page Menu services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Menu extends Service
{
    /**
     * @var array 后台菜单配置, 参看@fecshop/config/services/Page.php的配置
     */
    public $menuConfig;
    
    protected $_configMenu;
    public function getConfigMenu($menu='')
    {
        if (!$this->_configMenu) {
            
            $this->_configMenu = $this->getConfigMenuArr();
        }
        
        return $this->_configMenu;
    }
    
    public function getConfigMenuArr($menu='')
    {
        if (empty($menu)) {
            $menu = $this->menuConfig;
        }
        /**
         * 进行sort_order排序，以及enable处理
         */
        if (is_array($menu) && !empty($menu)) {
            $menu = $this->arraySortAndRemoveDisableMenu($menu, 'sort_order', 'desc');
            foreach ($menu as $k=>$one) {
                if (isset($one['child']) && is_array($one['child']) && !empty($one['child'])) {
                    $menu[$k]['child'] = $this->getConfigMenuArr($one['child']);
                }
            }
        }

        return $menu;
        
    }
    /**
     * 排序菜单函数，并且去掉status值为false的子项
     */
    public function arraySortAndRemoveDisableMenu($array, $keys, $dir='asc')
    {  
		$keysvalue = $new_array = array();  
		foreach ($array as $k=>$v){  
            // 如果enable设置值为false，则代表隐藏掉该菜单
            if (isset($v['enable']) && $v['enable'] === false) {
                continue;
            }
			$keysvalue[$k] = isset($v[$keys]) ? $v[$keys] : 0; 
		}  
		if($dir == 'asc'){  
			asort($keysvalue);  
		}else{  
			arsort($keysvalue);  
		}  
		reset($keysvalue);  
		foreach ($keysvalue as $k=>$v){  
			$new_array[$k] = $array[$k];  
		}  
		return $new_array;  
	}
    
    
    public function getLeftMenuHtml(){
        $menuArr = $this->getConfigMenu();

        return $this->getLeftMenuTreeHtml($menuArr);
    }


    # 得到后台显示菜单（左侧）
    public function getLeftMenuTreeHtml($treeArr='', $i=1){
        $str = '';
        foreach($treeArr as $node){
            $name = Yii::$service->page->translate->__($node["label"]);
            $url_key = $node["url_key"];
            if($i == 1){
                $str .=	'<div class="accordionHeader">
							<h2><span>Folder</span>'.$name .'
                                <span class="first_collapsable"></span>
                            </h2>
						</div>
						<div class="accordionContent">';
                if($this->hasChild($node)){
                    $str .='<ul class="tree treeFolder">';
                    $str .= $this->getLeftMenuTreeHtml($node['child'],$i+1);
                    $str .='</ul>';
                }
                $str .=	'</div>';
            }else{
                if($this->hasChild($node)){
                    //$str .=		'<li><a href="'.CUrl::getUrl($url_key).'" target="navTab" rel="page1">'.$name.'</a>';
                    $str .=		'<li><a href="javascript:void(0)" >'.$name.'</a>';
                    $str .=			'<ul>';
                    $str .= $this->getLeftMenuTreeHtml($node['child'],$i+1);
                    $str .=			'</ul>';
                    $str .=		'</li>';
                }else{
                    $str .='<li><a href="'.CUrl::getUrl($url_key).'" target="navTab" rel="page1">'.$name.'</a></li>';
                }
            }
        }
        
        return $str;
    }

    public function hasChild($node){
        if(isset($node['child']) && !empty($node['child'])){
            
            return true;
        }
        
        return false;
    }
    
}
