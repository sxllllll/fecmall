<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>


<ul class="search-suggest"></ul>
<ul class="search-suggest-history"></ul>
<div class="search-2016">
    <form method="get" name="searchFrom" class="js_topSeachForm" action="<?= Yii::$service->url->getUrl('catalogsearch/index');   ?>">
        <input class="search-key"  type="text" name="q" id="query-key"  lang="zh-CN"  value="<?=  \Yii::$service->helper->htmlEncode(Yii::$app->request->get('q'));  ?>" maxlength="30">
        <a class="search-btn" href="javascript:void(0);"></a>
    </form>
    <ul class="search-hot">
        <?php  
            $hot_searchs = Yii::$app->store->get('fecbbc_info', 'hot_searchs'); 
            $hot_search_arr = [];
            if ($hot_searchs) {
                $hot_search_arr = explode(',', $hot_searchs);
            }
            if (is_array($hot_search_arr) && !empty($hot_search_arr)):
                foreach ($hot_search_arr as $hot_search):
        ?>
            <li>
                <a style="display: block;" href="<?= Yii::$service->url->getUrl('catalogsearch/index', ['q' => $hot_search]) ?>" title="<?=$hot_search ?>"
                   act="" rel="nofollow" target="_blank">
                    <span class="searchvalue"><?= $hot_search ?></span>
                </a>
            </li>
        
        <?php endforeach; ?>
        <?php endif; ?>
        
    </ul>
</div>


<script>
    // add to cart js
    <?php $this->beginBlock('top_search') ?>
    $(document).ready(function(){   // onclick="ShowDiv_1('MyDiv1','fade1')"
        $(".search-btn").click(function(){
            $(".js_topSeachForm").submit();
        });
    });
    <?php $this->endBlock(); ?>
    <?php $this->registerJs($this->blocks['top_search'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>                   