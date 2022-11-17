<div class="homebuttom hide  boys">
            <div class="ul-arr"></div>
            <ul>
                <li>
                    <a href="<?= Yii::$service->url->homeUrl(); ?>">
                        <i class="iconfont">&#xe62a;</i>
                        <span><?= Yii::$service->page->translate->__('Home'); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::$service->url->getUrl('catalog/categorylist'); ?>">
                        <i class="iconfont">&#xe62d;</i>
                        <span><?= Yii::$service->page->translate->__('Category'); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::$service->url->getUrl('checkout/cart'); ?>" rel="nofollow">
                        <i class="iconfont">&#xe62c;</i>
                        <span><?= Yii::$service->page->translate->__('Cart'); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::$service->url->getUrl('customer/account'); ?>" rel="nofollow">
                        <i class="iconfont">&#xe62b;</i>
                        <span><?= Yii::$service->page->translate->__('My'); ?></span>
                    </a>
                </li>
            </ul>
        </div>
        <script>
        <?php $this->beginBlock('top-dh') ?>
        $(document).ready(function(){
            $(".new-nav-home").click(function(){
                if ($(".homebuttom").hasClass('hide')) {
                    $(".homebuttom").removeClass('hide');
                } else {
                    $(".homebuttom").addClass('hide');
                }
                
            });
        });
        <?php $this->endBlock(); ?>  
        </script>  
        <?php $this->registerJs($this->blocks['top-dh'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
        