
<div class="nav-wrapper clearfix">
    <div class="center-content">
        <?php if(is_array($categoryArr) && !empty($categoryArr)): ?>
        <ul class="sub-nav-list boys cure">
            <?php foreach($categoryArr as $category1): $k++; ?> 
            <?php $childMenu1 = $category1['childMenu'];   ?>
            <li class="contain-third">
                <a href="<?= $category1['url'] ?>">
                    <?= $category1['name'] ?>
                </a>
                <?php if(is_array($childMenu1) && !empty($childMenu1)): ?>
                    <div class="third-nav-wrapper" style="">
                        <div class="center-content">
                            <?php $i = 0; $ks=0;?>
                            <?php foreach($childMenu1 as $category2):  ?>
                            
                            <?php if ($i%9 == 0):  ?>
                            <dl class="category-list <?= $ks%4== 0 ? 'first-child' : '' ?>">
                            <?php $ks++ ?>
                            <?php $ks++ ?>
                            <?php endif;?>
                                <dt>
                                    <h3 class=""><a href="<?= $category2['url'] ?>"><?= $category2['name'] ?></a></h3>
                                </dt>
                            <?php $i++; ?>
                            <?php if ($i%9 == 0): ?>
                            </dl>
                            <?php endif;?>
                                <?php $childMenu2 = $category2['childMenu'];   ?>
                                <?php if(is_array($childMenu2) && !empty($childMenu2)): ?>
                                    <?php foreach($childMenu2 as $category3): ?>
                                        <?php if ($i%9 == 0): ?>
                                        <dl class="category-list <?= $ks%4== 0 ? 'first-child' : '' ?>">
                                        <?php $ks++ ?>
                                        <?php endif;?>
                                        <dd>
                                            <a href="<?= $category3['url'] ?>"><?= $category3['name'] ?></a>
                                        </dd>
                                         <?php $i++; ?>
                                         
                                        <?php if ($i%9 == 0): ?>
                                        </dl>
                                        <?php endif;?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if ($i%9 != 0): ?>
                            </dl>
                            <?php endif;?>
                            <div class="show-detail show" >
                                <?= isset($category1['menu_custom']) ? $category1['menu_custom'] : '';  ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
</div>
    