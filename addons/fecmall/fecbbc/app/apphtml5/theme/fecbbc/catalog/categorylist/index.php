<div class="main-wrap" id="main-wrap">
    <div class="category-page yoho-page">
        <div id="search-input" class="search-input">
            <a href="<?= Yii::$service->url->getUrl('catalogsearch/text') ?>">
                <i class="search-icon iconfont">&#xe60f;</i>
                <p><?= Yii::$service->page->translate->__('Search Product');  ?></p>
            </a>
        </div>
        
        <div class="category-container clearfix">
                <div class="content boys " style="height: 1070px;">
                    <?php $i=0; if (is_array($top_categorys) && !empty($top_categorys)): ?>
                        <ul class="primary-level">
                            <?php foreach ($top_categorys as $category_id => $top_category): $i++?>
                                <li class="p-level-item <?=  $active_category_id == $category_id ? 'focus' : ''  ?>">
                                    <a href="<?= Yii::$service->url->getUrl('catalog/categorylist', ['top_cate_id' => $category_id]);  ?>">
                                        <?= $top_category['name'] ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    
                    <div class="sub-level-container">
                        <?php $i=1; if (is_array($child_categorys) && !empty($child_categorys)): ?>
                        <ul class="sub-level ">
                            <li>
                                <a href="<?= Yii::$service->url->getUrl($category_url);  ?>">
                                    <?= Yii::$service->page->translate->__('All');  ?> <?= $category_name; ?>
                                </a>
                            </li>
                            <?php foreach ($child_categorys as $category_id => $child_category): $i++?>
                                <li>
                                    <a href="<?= Yii::$service->url->getUrl($child_category['url_key']);  ?>">
                                        <?= $child_category['name'] ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
        </div>
    </div>
    <div class="footer-tab">
        <a class="tab-item" href="<?= Yii::$service->url->homeUrl(); ?>">
            <p class="iconfont tab-icon">&#xe62a;</p>
            <p class="tab-name"><?= Yii::$service->page->translate->__('Home');  ?></p>
        </a>
        <a class="tab-item current  boys" href="<?= Yii::$service->url->getUrl('catalog/categorylist') ?>">
            <p class="iconfont tab-icon">&#xe62d;</p>
            <p class="tab-name"><?= Yii::$service->page->translate->__('Category');  ?></p>
        </a>
        <a class="tab-item " href="<?= Yii::$service->url->getUrl('checkout/cart') ?>" rel="nofollow">
            <p class="iconfont tab-icon">&#xe62c;</p>
            <p class="tab-name"><?= Yii::$service->page->translate->__('Cart');  ?></p>
        </a>
        <a class="tab-item " href="<?= Yii::$service->url->getUrl('customer/account') ?>" rel="nofollow">
            <p class="iconfont tab-icon">&#xe62b;</p>
            <p class="tab-name"><?= Yii::$service->page->translate->__('My');  ?></p>
        </a>
    </div>
</div>