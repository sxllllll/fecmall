<?php  $groupSpuAttr = $parentThis['groupSpuAttr'];   ?>
<?php  $groupSpuAttrSelected = $parentThis['groupSpuAttrSelected'];   ?>
<?php  $groupSpuAttrImgs = $parentThis['groupSpuAttrImgs'];   ?>
<?php  $sku = $parentThis['sku'];   ?>
<?php  $price = $parentThis['price'];   ?>
<?php  $qty = $parentThis['qty'];   ?>

<?php $hasSpuAttr = false; ?>
    <?php if (is_array($groupSpuAttr) && !empty($groupSpuAttr)): $iu = 0;  ?>
        <!--  ///////////////// 1   -->
        <input type="hidden" class="has_spu_attr"  value="1"    />
        <?php foreach ($groupSpuAttr as $spuName => $spuData):   $iu++; ?>
            <?php  $selectedAttr = $groupSpuAttrSelected[$spuName]; ?>
            <?php if (!$hasSpuAttr) $hasSpuAttr = true; ?>
            <div class="spu_attr_one <?=  (is_array($selectedAttr) && !empty($selectedAttr)) ? 'hasSelected' : ''   ?>" style="    margin-top: 10px;  margin-bottom: 20px;   border: 1px solid #ddd; padding: 12px 12px 15px 15px;" rel="<?= $spuName ?>">
                <div style="margin-bottom: 10px;">
                    <label style="text-transform: capitalize;    min-width: 90px;  display: inline-block;"><?= $spuName ?></label>
                    <input type="text" style="width:100px;"  class="spu_attr_input spu_attr_input_<?= $iu  ?>" />
                    <a  rel="<?= $iu  ?>" style="text-align:right; float:none;" href="javascript:void(0)" class="add_spu_attr button" addType="<?= (isset($groupSpuAttrImgs[$spuName]) ) ? 'addImage' : '' ?>"  attrName="<?= $spuName ?>">
                        <span> <?=  Yii::$service->page->translate->__('Add') ?></span>
                    </a>
                </div>

                <div class="spu_attr_info spu_attr_info_<?= $iu  ?>">
                    <?php foreach ($spuData as $sd):  ?>
                        <span class="spu_attr_val_item" style="    margin-right: 10px;  font-size: 14px;  height: 40px; line-height: 40px; min-width: 185px;display: inline-block;">
                            <input  <?= (is_array($selectedAttr) && in_array($sd, $selectedAttr)) ?  'checked="checked"' :  '' ?>  class="spuAttrCheck" type="checkbox"  id="<?=  $sd?>" rel="<?=  $sd?>">
                            <label for="<?=  $sd?>"  style="text-transform: capitalize;font-size:14px;"><?=  $sd?></label>
                            <?php if (isset($groupSpuAttrImgs[$spuName][$sd]) && $groupSpuAttrImgs[$spuName][$sd]): ?>
                                <img attrName="<?= $spuName ?>" attrval="<?=  $sd?>" rel="<?= $groupSpuAttrImgs[$spuName][$sd]; ?>"  style="width:40px;"  src="<?=  Yii::$service->product->image->GetResize($groupSpuAttrImgs[$spuName][$sd], [40,40]);  ?>"   />
                                <a rel="1" style=" height: 18px;  float: right;  " href="javascript:void(0)" class="add_spu_attr_img button">
                                    <span  style="line-height: 18px;"> 添加图片</span>
                                </a>
                            <?php elseif(isset($groupSpuAttrImgs[$spuName])): ?>
                                <img attrName="<?= $spuName ?>" attrval="<?=  $sd?>" style="display:none;width:40px;"  src=""   />
                                <a rel="1" style=" height: 18px;  float: right;  " href="javascript:void(0)" class="add_spu_attr_img button">
                                    <span  style="line-height: 18px;"> 添加图片</span>
                                </a>
                            <?php endif; ?>
                            
                        </span>
                        
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (!$hasSpuAttr): ?>
        <p class="edit_p">
            <label><?=  Yii::$service->page->translate->__('Sku：') ?></label>
            <input type="text" value="<?= $sku ?>" size="30" name="editFormData[sku]" class="textInput required valid">
            <span class="remark-text"></span>
        </p>
        
        <p class="edit_p">
            <label><?=  Yii::$service->page->translate->__('Sale Price') ?>：</label>
            <input type="text" value="<?= $price ?>" size="30" name="editFormData[price]" class="textInput required">
            <span class="remark-text"></span>
        </p>
        
        <p class="edit_p">
            <label><?=  Yii::$service->page->translate->__('Stock Qty') ?>：</label>
            <input type="text" value="<?= $qty ?>" size="30" name="editFormData[qty]" class="textInput required">
            <span class="remark-text"></span>
        </p>
    
    <?php endif; ?>
    
    <div  class="product_j_img"  style="display:none;">
        <div class="product_j_img_close" style="position:absolute;right:10px;top:10px;cursor:pointer">
            ✕
        </div>
        <div class="img_content">
        
        </div>
    </div>
    
    <table class="sell-sku-inner-table sell-sku-body-table " style="transform: translateY(0px);width:100%">
        <colgroup>
            <col width="111px">
            <col width="113px">
            <col width="109px">
            <col width="111px">
            <col width="194px">
            <col width="151px">
        </colgroup>
        <tbody>
            
        </tbody>
    </table>
    <?php if (is_array($groupSpuAttr) && !empty($groupSpuAttr)): $iu = 0;  ?>
    <div style="margin-top:20px; color: #777;">
        如果某个规格属性不存在，直接留空不填写即可。
    
    </div>
    <?php endif; ?>