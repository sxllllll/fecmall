<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use yii\helpers\Html;
use fec\helpers\CRequest;
use fec\helpers\CUrl;
use fecadmin\models\AdminRole;
/** 
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
?>

<script>
    $(document).ready(function(){
        $(".dialog").on("click",".spu_attr_info .add_spu_attr_img ",function(){
            $(".spu_attr_info .add_spu_attr_img").removeClass("active");
            $(this).addClass("active");
            imgStr = '';
            $(".productimg tbody tr td img").each(function(){
                imgUrl = $(this).attr("src");
                ImgKey = $(this).attr("rel");
                imgStr += '<div class="product-img"><img src="'+ imgUrl +'"  rel="'+ImgKey+'"   /></div>';
            });
            $(".product_j_img .img_content").html(imgStr)
            $(".product_j_img").show();
        });
        
        $(".dialog").on("click",".product_j_img_close",function(){
            $(".product_j_img").hide();
        });                                
        
        $(".dialog").on("click",".product_j_img img",function(){
            imgUrl = $(this).attr("src");
            ImgKey = $(this).attr("rel");
            $imgOb = $(".spu_attr_info .add_spu_attr_img.active").parent().find("img");
            $imgOb.attr("src",imgUrl);
            $imgOb.attr("rel",ImgKey);
            $imgOb.show();
            $(".product_j_img").hide();
        });
        
        $historyInfo = <?= $groupSpuAttrSelectedJson != 'null'  ? $groupSpuAttrSelectedJson : '{}' ?>;
        $(".dialog").on("click",".spuAttrCheck",function(){
            // add selected   //////////////////////////////////////
            $(".spu_attr_one").each(function(){
                $hasSelected = false;
                $(this).find(".spuAttrCheck:checked").each(function(){
                    $hasSelected = true;
                });
                if ($hasSelected) {
                    $(this).addClass("hasSelected");
                } else {
                    $(this).removeClass("hasSelected");
                }
            });
            //////////////////////////////////////
            generateSpuList(2);
        });
        
        generateSpuList(1);
        
        function generateSpuList(vs)
        {
            if (vs == 2) {
                $historyInfo = {};
            }
            var htmlStr = '<tr>';
            var attrArr = [];
            var attrRows = {};
            $columnsAllRows = 0;
            // $(".spu_attr_one").each(function(){
            //////////////////////////////////////
            $(".spu_attr_one.hasSelected").each(function(){
            //////////////////////////////////////
                var obj = {};
                var spuName = $(this).attr('rel');
                obj.name = spuName;
                htmlStr += '<td class="sell-sku-cell sell-sku-cell-text">' + spuName + '</td>';
                rows = 0;
                var spuData = [];
                $(this).find(".spuAttrCheck:checked").each(function(){
                   var val = $(this).attr('rel');
                   spuData.push(val);
                   rows++;
                });
                // 计算rows数
                for (var x in attrRows){
                    v = attrRows[x];
                    attrRows[x] = v * rows;
                }
                attrRows[spuName] = 1;
                obj.data = spuData;
                attrArr.push(obj);
            });
            for (x in attrArr) {
                obj = attrArr[x];
                obj.rowSize = attrRows[obj.name];
            }
            htmlStr += '<td class="sell-sku-cell sell-sku-cell-text">Sku编码</td>';
            htmlStr += '<td class="sell-sku-cell sell-sku-cell-text">价格<span class="topPriceSpan" style="display:none;"><input type="text" class="topPrice"  /><button class="topButton p_price confirm">确定</button><button class="topButton p_price cancel">取消</button></span><i class="fa fa-pencil topPriceEdit" style="font-size:12px;margin-left:10px;"></i></td>';
            htmlStr += '<td class="sell-sku-cell sell-sku-cell-text">库存<span class="topQtySpan" style="display:none;"><input type="text" class="topQty"  /><button class="topButton p_qty confirm">确定</button><button class="topButton p_qty cancel">取消</button></span><i class="fa fa-pencil topQtyEdit" style="font-size:12px;margin-left:10px;"></i></td>';
            htmlStr += '</tr>';
            i = 0;
            hStr = '';
            // 计算历史
            $(".sell-sku-body-table tbody tr").each(function(){
                attrKey = '';
                $(this).find(".sell-sku-cell-text .spu_attr_content").each(function(){
                    attrV = $(this).html();
                    attrKey += attrV+ "_";
                });
                if (attrKey) {
                    sku_code = $(this).find(".sku_code").val();
                    sku_price =  $(this).find(".sku_price").val();
                    sku_qty =  $(this).find(".sku_qty").val();
                    sku_info = {};
                    sku_info.sku_code = sku_code;
                    sku_info.sku_price = sku_price;
                    sku_info.sku_qty = sku_qty;
                    $historyInfo[attrKey] = sku_info;
                }
            });
            if (attrArr.length >  0) {
                htmlStr += getTableStr(attrArr, i, hStr, '', '', vs); 
                $(".sell-sku-body-table tbody").html(htmlStr);
            }
        }
        //
        function getTableStr(attrArr, i, hStr, attrKey,skuGenerateKey, vs) {
            
            var initData = 1;
            if (vs != 1) {
                initData = 0;
            }
            
            var attrSKey = "";
            var skuSGenerateKey = "";
            var attrObj = attrArr[i];
            var htmlStr = '';
            for (var j = 0; j < attrObj.data.length; j++) {
                rowspan = attrObj.rowSize;
                spuName = attrObj.name;
                vData = attrObj.data;
                attrVal = vData[j];
                attrSKey = attrKey + attrVal+ "_";
                skuSGenerateKey = skuGenerateKey + "-" + attrVal;
                shStr = hStr;
                ii = i + 1;
                if ( ii >= attrArr.length) {
                    if (j > 0) {
                        reallyDo = 'sell-sku-cell-text'; 
                        replaceWith = "sell-sku-cell-text hide";
                        shStr = shStr.replace(new RegExp(reallyDo, 'g'), replaceWith);
                    }
                } else if(ii != 1){
                    if (j > 0) {
                        reallyDo = 'sell-sku-cell-text'; 
                        replaceWith = "sell-sku-cell-text hide";
                        shStr = shStr.replace(new RegExp(reallyDo, 'g'), replaceWith);
                    }
                }
                shStr += '<td class="sell-sku-cell sell-sku-cell-text" rowspan="'+ rowspan +'">';
                shStr += '<div class="cell-inner" style="min-width: 78px;">';
                shStr += '    <div class="sell-sku-cell-text">';
                shStr += '        <p class="spu_attr_content  sell-sku-cell-text-content" rel="'+spuName+'" title="'+attrVal+'">'+attrVal+'</p>';
                shStr += '    </div>';
                shStr += '</div>';
                shStr += '</td>';
                if ( ii < attrArr.length) {
                    htmlStr += getTableStr(attrArr, ii, shStr, attrSKey, skuSGenerateKey, vs);
                } else {
                    sku_code = "";
                    sku_price = "";
                    sku_qty = 0;
                    console.log(attrSKey);
                    if ($historyInfo.hasOwnProperty(attrSKey)) {
                        vv = $historyInfo[attrSKey];
                        sku_code = vv.sku_code;
                        sku_price = vv.sku_price;
                        sku_qty = vv.sku_qty;
                    } else { 
                        var spuCode = $("input[name='editFormData[spu]']").val();
                        //alert(spuCode);
                        if (spuCode) {
                            sku_code =  spuCode + skuSGenerateKey;
                            sku_code = sku_code.replace(/ /, "_")
                            sku_code = sku_code.replace(/[^a-zA-Z0-9-_ ]/g, '');
                        }
                    }
                    // 如果是初始化
                    if (initData==1 && !sku_price) {
                        sku_code = '';
                        sku_qty = '';
                    }
                    
                    htmlStr += '<tr>' + shStr;
                    htmlStr += '<td class="sell-sku-cell sell-sku-cell-input" rowspan="1">';
                    htmlStr += '    <div class="cell-inner" style="min-width: 160px;">';
                    htmlStr += '    <span class="sell-o-input"><span class="input-wrap">';
                    htmlStr += '    <span class="next-input next-input-single next-input-medium fusion-input">';
                    htmlStr += '    <input class="textInput valid sku_code" type="text" label="商家编码" name="skuOuterId" value="'+sku_code+'" maxlength="64" height="100%" style="width:94%">';
                    htmlStr += '    </span></span></span></div>';
                    htmlStr += '</td>';
                    htmlStr += '<td class="sell-sku-cell sell-sku-cell-money" rowspan="1">';
                    htmlStr += '    <div class="cell-inner" style="min-width: 90px;">';
                    htmlStr += '       <span class="sell-o-number">';
                    htmlStr += '        <span class="input-wrap">';
                    htmlStr += '        <span class="next-input next-input-single next-input-medium fusion-input">';
                    htmlStr += '        <input class="textInput valid sku_price" type="text" label="价格（元）"  value="'+sku_price+'" name="skuPrice" maxlength="15" height="100%">';
                    htmlStr += '        </span></span></span></div>';
                    htmlStr += '</td>';
                    htmlStr += '<td class="sell-sku-cell sell-sku-cell-positiveNumber" rowspan="1">';
                    htmlStr += '    <div class="cell-inner" style="min-width: 90px;">';
                    htmlStr += '    <span class="sell-o-number">';
                    htmlStr += '    <span class="input-wrap">';
                    htmlStr += '    <span class="next-input next-input-single next-input-medium fusion-input">';
                    htmlStr += '    <input class="textInput valid sku_qty" type="text" label="数量（件）"  value="'+sku_qty+'" name="skuStock" maxlength="15" height="100%">';
                    htmlStr += '    </span></span></span></div>';
                    htmlStr += '</td>';
                    htmlStr += '</tr>';
                    attrSKey = '';
                    skuSGenerateKey = '';
                }
            }
            
            return htmlStr;
        }
        
        $(".dialog").on("click",".add_spu_attr",function(){
            var rel = $(this).attr('rel');
            var str1 = ".spu_attr_input_" + rel;
            var str2 = ".spu_attr_info_" + rel ;
            var addVal = $(str1).val();
            var addType = $(this).attr('addType');
            var attrName = $(this).attr('attrName');
            //alert(addType);
            // alert(attrName);
            addVal = addVal.toLowerCase();
            if (!addVal) {
                alert("请填写值");
            } else {
                var isCF = 0;
                $(str2 + " input").each(function(){
                    v = $(this).attr('rel');
                    v = v.toLowerCase();
                    if (v == addVal) {
                        alert("添加的值重复");
                        isCF = 1;
                    }
                });
                if (isCF == 0) {
                    imageStr = '';
                    if (addType == 'addImage') {
                        imageStr = '<img attrName="'+attrName+'" attrval="'+ addVal +'" style="display:none;width:40px;"  src=""   /> <a rel="1" style=" height: 18px;  float: right;  " href="javascript:void(0)" class="add_spu_attr_img button">   <span  style="line-height: 18px;"> 添加图片</span></a>'
                    }
                    appendStr = '<span class="spu_attr_val_item" style="    margin-right: 10px;  font-size: 14px;  height: 40px; line-height: 40px; min-width: 185px;display: inline-block;"><input class="spuAttrCheck" type="checkbox" id="'+addVal+'" rel="'+addVal+'"><label for="'+addVal+'" style="text-transform: capitalize;font-size:14px;">'+addVal+'</label> '+ imageStr +' </span>';
                    //alert(appendStr);
                    $(str2).append(appendStr);
                }
            }
        });
    });
    
function getCategoryData(product_id,i){												
	$.ajax({
		url:'<?= CUrl::getUrl("catalog/productinfo/getproductcategory",['product_id'=>$product_id]); ?>',
		async:false,
		timeout: 80000,
		dataType: 'json', 
		type:'get',
		data:{
			'product_id':product_id,
		},
		success:function(data, textStatus){
			if(data.return_status == "success"){
				jQuery(".category_tree").html(data.menu);
				// $.fn.zTree.init($(".category_tree"), subMenuSetting, json);
				if(i){
					$("ul.tree", ".dialog").jTree();
				}
			}
		},
		error:function(){
			alert("<?=  Yii::$service->page->translate->__('load category info error') ?>");
		}
	});
}
</script>

<!-- ///////////////// 1   -->
<input type="hidden" id="img-radio-previous" />
<div class="pageContent"> 
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return thissubmit(this, dialogAjaxDoneCloseAndReflush);">
		<?php echo CRequest::getCsrfInputHtml();  ?>	
        <input type="hidden" name="edit_spu"  value="<?=  $edit_spu ?>" />
		<input type="hidden" name="operate"  value="<?=  $operate ?>" />
		<input type="hidden" class="primary_info"  value="<?= $primaryInfo ?>" />
       <input type="hidden" class="spu_attrs"  name="spu_attrs" value="" />
		<div class="tabs" >
			<div class="tabsHeader">
				<div class="tabsHeaderContent top_header">
					<ul>
						<li class="top_base_info"><a href="javascript:;"><span><?=  Yii::$service->page->translate->__('Basic Info') ?></span></a></li>
                        <li class="top_attr_group_price"><a href="javascript:;"><span>属性组&价格</span></a></li>
                        <li class="top_marking"><a href="javascript:;"><span>营销</span></a></li>
						<li class="top_seo"><a href="javascript:;"><span>SEO</span></a></li>
					</ul>
				</div>
			</div>
			<div class="tabsContent" style="height:550px;overflow:auto;">
				<div class="base_info topTabsContent">
					<input type="hidden"  value="<?=  $product_id; ?>" size="30" name="product_id" class="textInput ">
					<div  class="marking_title" style="margin-top:10px;">
                        基本信息：【经销商：<?= $supplierName ?>】
                    </div>
					<?= $baseInfo ?>
                    <div  class="marking_title">
                        产品图片：
                    </div>
                    <div >
                        <input type="hidden" name="image_main" class="image_main"  />
                        <input type="hidden" name="image_gallery" class="image_gallery"  />
                        <?=  $img_html ?>	
                        <div id="addpicContainer" style="padding-bottom:20px;">
                            <!-- 利用multiple="multiple"属性实现添加多图功能 -->
                            <!-- position: absolute;left: 10px;top: 5px;只针对本用例将input隐至图片底下。-->
                            <!-- height:0;width:0;z-index: -1;是为了隐藏input，因为Chrome下不能使用display:none，否则无法添加文件 -->
                            <!-- onclick="getElementById('inputfile').click()" 点击图片时则点击添加文件按钮 -->
                            <button style="" onclick="getElementById('inputfile').click()" class="scalable upload-image" type="button" title="Duplicate" id=""><span><span><span><?=  Yii::$service->page->translate->__('Browse Files') ?></span></span></span></button>
                            <input type="file" multiple="multiple" id="inputfile" style="margin:10px;height:0;width:0;z-index: -1; position: absolute;left: 10px;top: 5px;"/>
                            <span class="loading"></span>
                        </div>
                    </div>
                    <div  class="marking_title">
                        描述信息：
                    </div>
                    <div class="p_description"  style="position:relative;">
                        <?= $descriptionInfo ?>
                    </div>
                    <div  class="marking_title">
                        产品分类：
                    </div>
                    <div>
                        <script>
                            $(document).ready(function(){
                                id = '<?= $product_id; ?>' ;
                                getCategoryData(id,0);  
                            });
                        </script>
                        <input type="hidden" value="" name="category"  class="inputcategory"/>
                        <ul class="category_tree tree treeFolder treeCheck expand" >
                        </ul>
                    </div>
				</div>
                <div class="group_attr_price topTabsContent">
                    <div class="group_spu_attr">
                        <input type="hidden" value="" name="spuImgAttr"  class="spuImgAttr"/>
                        <div  class="marking_title" style="width: 74px; float: left;">
                            属性组：
                        </div>
                        <div class="attr_group_i" style="float: left;  margin: 38px 10px 20px 0px;">
                            <p class="edit_p">
                                <?= $attrGroup ?>
                            </p>
                        </div>
                        <div style="clear:both;"></div>
                        <div  class="product_group_attrs marking_title " style="margin-top: 10px;<?= $groupGeneralAttr ? 'display:block' : 'display:none' ?>">
                            属性：
                        </div>
                        <div class="product_group_attrs cpst groupGeneralAttr attr_group_i" style="<?= $groupGeneralAttr ? 'display:block' : 'display:none' ?>">
                            <?= $groupGeneralAttr ?>
                        </div>
                        <div style="clear:both;"></div>
                         <div  class="marking_title">
                            规格 & 价格 & 库存：
                        </div>
                        <div class="options_and_price">
                            <?= $optionsAndPrice ?>
                        </div> 
                        <br/><br/>
                        <div  class="marking_title" style="margin-top: 10px;">
                            成本价 & 销售：
                        </div>
                        <div class="cpst">
                            <?= $costPriceInfo ?>
                        </div>
                        <div  class="marking_title">
                            库存状态：
                        </div>
                        <div class="stock_status">
                            <?= $stockStatusInfo ?>
                        </div> 
                        <div  class="marking_title" style="margin-top: 10px;">
                            物流：
                        </div>
                        <div class="cpst">
                            <?= $shippingInfo ?>
                            <p style="float: left; width: 200px; line-height: 50px; margin-left: 10px;">
                                体积重(g)：<span class="volumn_weight_val"><?= $volume_weight  ?></span>
                            </p>
                            <div style="clear:both"></div>
                        </div>
                    </div> 
				</div>
                <div class="product_marking topTabsContent">
                    <div  class="marking_title">
                        上新：
                    </div>
                    <?= $markingAsNewInfo ?>
                    <!--
                    <div  class="marking_title">
                        分销：
                    </div>
                    <?= $markingDistributeInfo ?>
                    -->
                    <div  class="marking_title">
                        推荐：
                    </div>
                    <?= $relation ?>	
                    <div  class="marking_title">
                        分值：
                    </div>
                    <?= $scoreInfo ?>	
                    <div  class="marking_title">
                        特价：
                    </div>
                    <?= $priceInfo ?>
                    <div class="marking_title">
                        批发：
                    </div>
                    <div>
                        <div class="edit_p">
                            <label><?=  Yii::$service->page->translate->__('Tier Price') ?>：</label>
                            <input type="hidden" name="editFormData[tier_price]" class="tier_price_input"  />
                            <div class="tier_price" style="float:left;width:700px;">
                                <table style="">
                                    <thead>
                                        <tr>
                                            <th><?=  Yii::$service->page->translate->__('Qty') ?></th>
                                            <th><?=  Yii::$service->page->translate->__('Price') ?></th>
                                            <th><?=  Yii::$service->page->translate->__('Action') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(is_array($tier_price) && !empty($tier_price)){  ?>
                                            <?php foreach($tier_price as $one){ ?>
                                            <tr>
                                                <td>
                                                    <input class="tier_qty" type="text" value="<?= $one['qty'] ?>"> <?=  Yii::$service->page->translate->__('And Above') ?>
                                                </td>
                                                <td>
                                                    <input class="tier_price" type="text" value="<?= $one['price'] ?>">
                                                </td>
                                                <td>
                                                    <i class="fa fa-trash-o"></i>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot style="text-align:right;">
                                        <tr>
                                            <td colspan="100" style="text-align:right;">						
                                                <a rel="2" style="text-align:right;" href="javascript:void(0)" class="addProductTierPrice button">
                                                    <span><?=  Yii::$service->page->translate->__('Add Tier Price') ?></span>
                                                </a>					
                                            </td>				
                                        </tr>			
                                    </tfoot>
                                </table>
                                <script>
                                    $(document).ready(function(){
                                        $(".addProductTierPrice").click(function(){
                                            str = "<tr>";
                                            str +="<td><input class=\"tier_qty textInput \" type=\"text\"   /> <?=  Yii::$service->page->translate->__('And Above') ?> </td>";
                                            str +="<td><input class=\"tier_price textInput\" type=\"text\"   /></td>";
                                            str +="<td><i class='fa fa-trash-o'></i></td>";
                                            str +="</tr>";
                                            $(".tier_price table tbody").append(str);
                                        });
                                        $(".dialog").on("click",".tier_price table tbody tr td .fa-trash-o",function(){
                                            $(this).parent().parent().remove();
                                        });
                                        ///////////////// 1
                                        productimgval = $(".productimg input[type=radio]:checked").val();
                                        $("#img-radio-previous").val(productimgval);
                                        $(".dialog").on("click",".productimg input[type=radio]",function(){
                                            if (!productimgval) {
                                                $("#img-radio-previous").val($(this).val());
                                            }
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="topTabsContent">
                    <div  class="marking_title" style="margin-top: 10px;">
                        SEO Meta信息：
                    </div>
                    <div class="seo_info">
                        <?= $metaInfo ?>
                    </div>
                </div>
			</div>
			<div class="tabsFooter">
				<div class="tabsFooterContent"></div>
			</div>
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button onclick=""  value="accept" name="accept" type="submit"><?=  Yii::$service->page->translate->__('Save') ?></button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close"><?=  Yii::$service->page->translate->__('Cancel') ?></button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>	

<script>
    jQuery(document).ready(function(){
        jQuery("body").on('click',".delete_img",function(){
            jQuery(this).parent().parent().remove();
        });
        volumnWeight = function(){
            var p_long = $("input[name='editFormData[long]']").val();
            var p_width = $("input[name='editFormData[width]']").val();
            var p_high = $("input[name='editFormData[high]']").val();
            var volumnWeight = (parseFloat(p_long) * parseFloat(p_width) * parseFloat(p_high)) / <?= $volumeWeightCoefficient ?> * 1000;
            $(".volumn_weight_val").html(volumnWeight);
        };
        $(".product_long").bind('input porpertychange', volumnWeight);
        $(".product_width").bind('input porpertychange', volumnWeight);
        $(".product_high").bind('input porpertychange', volumnWeight);
        //对spu的唯一性进行检查 
        $("input[name='editFormData[spu]']").blur(function(){
            var productId = $("input[name='product_id']").val();
            var productSpu = "<?=  $edit_spu ?>";
            var fillSpu = $(this).val();
            if (!fillSpu) {
                
                return;
            }
            // 编辑已有产品，并且，spu没有改动，则直接返回
            if (productSpu && (productSpu == fillSpu)) {
                
                return;
            }
            var self = $(this);
            self.parent().find(".remark-text").html("");
            self.css("border-color", "#ced4da");
            $.ajax({
                url: '<?= CUrl::getUrl('catalog/productinfo/spucode')  ?>',
                type: 'GET',
                data: {
                    "product_id": productId,
                    'product_spu': productSpu,
                    "spu" : fillSpu
                },
                async: false,
                dataType: 'json', 
                timeout: 80000,
                success:function(data, textStatus){
                    if(data.statusCode != 200){
                        //alert("商品编码spu必须唯一");
                        self.parent().find(".remark-text").html("商品编码spu必须唯一");
                        self.css("border-color", "#cc0000");
                        //border-color: #cc0000;
                    } else {
                        // 如果是新增商品，那么填写完spu后，强制覆盖sku
                        if (!productId) {
                            var randomSku = data.randomSku;
                            if (randomSku) {
                                $(".options_and_price input[name='editFormData[sku]']").val(randomSku);
                            }
                        }
                    }
                },
                error:function(){
                    alert('<?=  Yii::$service->page->translate->__('ajax check spu code Error') ?>');
                }
            });
        });
        //响应文件添加成功事件
        $("#inputfile").change(function(){
            //创建FormData对象
            var thisindex = 0;
            jQuery(".productimg tbody tr").each(function(){
                rel = parseInt(jQuery(this).attr("rel"));
                //alert(rel);
                if(rel > thisindex){
                    thisindex = rel;
                }
            });
            //alert(thisindex);
            var data = new FormData();
            data.append('thisindex', thisindex);
            //为FormData对象添加数据
            $.each($('#inputfile')[0].files, function(i, file) {
                data.append('upload_file'+i, file);
            });
            //发送数据
            data.append("<?= CRequest::getCsrfName() ?>", "<?= CRequest::getCsrfValue() ?>");
            $.ajax({
                url:'<?= CUrl::getUrl('catalog/productinfo/imageupload')  ?>',
                type:'POST',
                data:data,
                async:false,
                dataType: 'json', 
                timeout: 80000,
                cache: false,
                contentType: false,		//不可缺参数
                processData: false,		//不可缺参数
                success:function(data, textStatus){
                    if(data.return_status == "success"){
                        jQuery(".productimg tbody ").append(data.img_str);
                    }
                },
                error:function(){
                    alert('<?=  Yii::$service->page->translate->__('Upload Error') ?>');
                }
            });
        });
    });
  
    $(document).ready(function(){
        $(".dialog").on("click", ".topPriceEdit", function(e){
            $(".topPriceSpan").show();
            $(this).hide();
        });

        $(".dialog").on("click", ".topButton.p_price.confirm", function(e){
            e.preventDefault();
            var topPrice = $(".topPrice").val();
            topPrice = parseFloat(topPrice);
            if (!topPrice) {
                alert("请输入价格");
                
                return;
            }
            $(".sku_price").val(topPrice);
            $(".topPriceEdit").show();
            $(".topPriceSpan").hide();
        });
 
        $(".dialog").on("click", ".topButton.p_price.cancel", function(e){
            e.preventDefault();
            $(".topPriceEdit").show();
            $(".topPriceSpan").hide();
        });
        
        $(".dialog").on("click", ".topQtyEdit", function(e){
            $(".topQtySpan").show();
            $(this).hide();
        });

        $(".dialog").on("click", ".topButton.p_qty.confirm", function(e){
            e.preventDefault();
            var topQty = $(".topQty").val();
            topQty = parseFloat(topQty);
            if (!topQty) {
                alert("请输入库存");
                return;
            }
            $(".sku_qty").val(topQty);
            $(".topQtyEdit").show();
            $(".topQtySpan").hide();
        });
     
        $(".dialog").on("click", ".topButton.p_qty.cancel", function(e){
            e.preventDefault();
            $(".topQtyEdit").show();
            $(".topQtySpan").hide();
        });
        
        $(".dialog").on("click", ".base_info .tabsHeader ul li", function(){
            $(".base_info .tabsHeader ul li").removeClass("selected");
            var lang = $(this).attr("lang");
            var s = ".base_info .tabsHeader ul li." + lang;
            $(s).addClass("selected");
            
            $(".base_info .tabsContent .tabsC").hide();
            var t = ".base_info .tabsContent .tabsC." + lang;
            $(t).show();
        });
        
        $(".dialog").on("click", ".seo_info .tabsHeader ul li", function(){
            $(".seo_info .tabsHeader ul li").removeClass("selected");
            var lang = $(this).attr("lang");
            var s = ".seo_info .tabsHeader ul li." + lang;
            $(s).addClass("selected");
            
            $(".seo_info .tabsContent .tabsC").hide();
            var t = ".seo_info .tabsContent .tabsC." + lang;
            $(t).show();
        });
        
        $(document).off("change").on("change",".attr_group",function(){
            options = {};
            val = $(this).val();
            pm = "?attr_group="+val;
            currentPrimayInfo = $(".primary_info").val();
            currentPrimayInfo = currentPrimayInfo ? '&'+currentPrimayInfo : '';
            url = '<?= CUrl::getUrl("catalog/productinfo/getattrgroupinfo"); ?>'+pm+currentPrimayInfo;
            $.ajax({
                url: url,
                async: false,
                timeout: 80000,
                dataType: 'json', 
                type: 'get',
                data: {},
                success:function(data, textStatus){
                    if(data.status == "success"){
                       $(".groupGeneralAttr").html(data.data.groupGeneralAttrHtml);
                        if (data.data.groupGeneralAttrHtml) {
                            $(".product_group_attrs").show();
                        } else {
                            $(".product_group_attrs").hide();
                        }
                        $(".options_and_price").html(data.data.optionsHtml);
                        $historyInfo = data.data.groupSpuAttrSelectedJson;
                    }
                },
                error:function(){
                    alert("<?=  Yii::$service->page->translate->__('load options error') ?>");
                }
            });
            
        });
    });


    /*
    function getMinDistributePriceEnd(){	
        costPrice = $("input[name='editFormData[cost_price]']").val();
        attrGroup = $("select[name='attr_group']").val();
        minDistributePriceEnd = 0;
        if (costPrice && attrGroup) {
            url = '<?= CUrl::getUrl("catalog/productinfo/getmindistributepriceend"); ?>?cost_price='+costPrice+'&attr_group='+attrGroup;
            $.ajax({
                url: url,
                async: false,
                timeout: 80000,
                dataType: 'json', 
                type: 'get',
                data: {},
                success:function(data, textStatus){
                    if(data.status == "success"){
                        minDistributePriceEnd = data.minDistributePriceEnd;
                    }
                },
                error:function(){
                    alert("<?=  Yii::$service->page->translate->__('error') ?>");
                }
            });
        }
        
        return minDistributePriceEnd;
    }
    */

    function thissubmit(thiss){
        $rad = $("#img-radio-previous").val();
        edit_spu_val = $("input[name=edit_spu]").val();
        has_spu_attr = $(".has_spu_attr").val();
        // 非新增产品 && 规格产品
        if (edit_spu_val && has_spu_attr) {
            $(".productimg input[type=radio]").each(function(){
                //if ($(this).val()  == $rad) {
                //    $(this).click();
               // }
            });
        }
        // product image
        main_image_image 		=  $('.productimg input[type=radio]:checked').val();
        main_image_label 		    =  $('.productimg input[type=radio]:checked').parent().parent().find(".image_label").val();
        main_image_sort_order 	=  $('.productimg input[type=radio]:checked').parent().parent().find(".sort_order").val();
        main_image_is_thumbnails    =  $('.productimg input[type=radio]:checked').parent().parent().find(".is_thumbnails").val();
        main_image_is_detail 	    =  $('.productimg input[type=radio]:checked').parent().parent().find(".is_detail").val();
        if(main_image_image){
            image_main = main_image_image+'#####'+main_image_label+'#####'+main_image_sort_order  +'#####'+main_image_is_thumbnails  +'#####'+main_image_is_detail;
            $(".tabsContent .image_main").val(image_main);
        }else{
            alert('<?=  Yii::$service->page->translate->__('You upload and select at least one main image') ?>');
            
            return false;
        }
        image_gallery = '';
        $('.productimg input[type=radio]').each(function(){
            if(!$(this).is(':checked')){
                gallery_image_image 		= $(this).val();
                gallery_image_label 		= $(this).parent().parent().find(".image_label").val();
                gallery_image_sort_order 	= $(this).parent().parent().find(".sort_order").val();
                gallery_image_is_thumbnails = $(this).parent().parent().find(".is_thumbnails").val();
                gallery_image_is_detail 	= $(this).parent().parent().find(".is_detail").val();
                image_gallery += gallery_image_image+'#####'+gallery_image_label+'#####'+gallery_image_sort_order +'#####'+gallery_image_is_thumbnails  +'#####'+gallery_image_is_detail+'|||||';
            }
        });
        // 【分销价格区间最大值】的区间范围
        /*
        minDistributePriceEnd = getMinDistributePriceEnd();
        distribute_price_end = $("input[name='editFormData[distribute_price_end]']").val();
        if (parseFloat(distribute_price_end) <= parseFloat(minDistributePriceEnd)) {
            alert('【分销价格区间最大值】必须大于' + minDistributePriceEnd);
            
            return false;
        }
        */
        $(".tabsContent .image_gallery").val(image_gallery);
        
        cate_str = "";
        jQuery(".category_tree div.ckbox.checked").each(function(){
            cate_id = jQuery(this).find("input").val();
            cate_str += cate_id+",";
        });
        
        jQuery(".category_tree div.ckbox.indeterminate").each(function(){
            cate_id = jQuery(this).find("input").val();
            cate_str += cate_id+",";
        });
        
        jQuery(".inputcategory").val(cate_str);
        // 成本价格
        cost_price_val = $("input[name='editFormData[cost_price]']").val();
        tierpriceIsLegal = true;
        
        tier_price_str = "";
        $(".tier_price table tbody tr").each(function(){
            tier_qty = $(this).find(".tier_qty").val();
            tier_price = $(this).find(".tier_price").val();
            if(tier_qty && tier_price){
                tier_price_str += tier_qty+'##'+tier_price+"||";
                if (parseFloat(tier_price) < parseFloat(cost_price_val)) {
                    alert("销售批发价格["+tier_price+"]，必须大于成本价["+cost_price_val+"]");
                    tierpriceIsLegal = false;
                    return false;
                }
            }
        });
        //成本价格
        if (!tierpriceIsLegal) {
            return false;
        }
        cost_tier_price_str = "";
        $(".cost_tier_price table tbody tr").each(function(){
            cost_tier_qty = $(this).find(".cost_tier_qty").val();
            cost_tier_price = $(this).find(".cost_tier_price").val();
            if(cost_tier_qty && cost_tier_price){
                cost_tier_price_str += cost_tier_qty+'##'+cost_tier_price+"||";
            }
        });
        // 成本价格
        v_special_price = $("input[name='editFormData[special_price]']").val();
        v_price = $("input[name='editFormData[price]']").val();
        priceIsLegal = true;
        /*
        distribute_price_begin = $("input[name='editFormData[distribute_price_begin]']").val();
        distribute_price_end = $("input[name='editFormData[distribute_price_end]']").val();
        
        if (parseFloat(distribute_price_begin) < parseFloat(cost_price_val)) {
            alert("分销价格区间最小值, 必须大于 商品成本价格");
            return false;
        }
        if (parseFloat(distribute_price_end) < parseFloat(distribute_price_begin)) {
            alert("分销价格区间最大值, 必须大于 分销价格区间最小值");
            return false;
        }
        */
        if (parseFloat(v_special_price) && parseFloat(v_special_price) < parseFloat(cost_price_val)) {
            alert("销售特价, 必须大于 商品成本价格");
            return false;
        }
         if (parseFloat(v_price) && parseFloat(v_price) < parseFloat(cost_price_val)) {
            alert("销售价格, 必须大于 商品成本价格");
            return false;
        }
        spuStr = '';
        isSkuPriceQtyEmpty = false;
        $(".sell-sku-body-table tr ").each(function(){
            skuStr = '';
            iss = 0;
            $(this).find("td.sell-sku-cell .cell-inner  p.spu_attr_content").each(function(){
                sAttr = $(this).attr('rel');
                sAttrVal = $(this).attr('title');
                if (sAttr && sAttrVal) {
                    skuStr += sAttr+ '###' + sAttrVal + '|||';
                    iss = 1;
                }
            });
            if (iss) {
                sSkuCodeVal = $(this).find("td.sell-sku-cell .sku_code").val();
                sSkuPriceVal = $(this).find("td.sell-sku-cell .sku_price").val();
                sSkuQtyVal = $(this).find("td.sell-sku-cell .sku_qty").val();
                if (sSkuCodeVal && sSkuPriceVal) {
                    // 成本价格
                    if (parseFloat(sSkuPriceVal) < parseFloat(cost_price_val)) {
                        alert("商品销售价格["+sSkuPriceVal+"]必须大于成本价"+"["+cost_price_val+"]");
                        priceIsLegal = false;
                        return false;
                    }
                    skuStr += 'sku###' + sSkuCodeVal + '|||';
                    skuStr += 'price###' + sSkuPriceVal + '|||';
                    skuStr += 'qty###' + sSkuQtyVal;
                    spuStr += skuStr + '***';
                } else if (sSkuCodeVal && !sSkuPriceVal){
                    isSkuPriceQtyEmpty = true;
                }
            }
        });
        //成本价格
        if (!priceIsLegal) {
            return false;
        }
        // 规格属性图片
        attr_gg_img = '';
        $(".group_spu_attr .spuAttrCheck:checked").each(function(){
            ob = $(this).parent().find("img");
            attrval = ob.attr("attrval");
            attrName = ob.attr("attrName");
            attrsrc = ob.attr("src");
            attrkey = ob.attr("rel");
            if (attrsrc && attrkey && attrval && attrName) {
                attr_gg_img +=  attrName + '####' + attrval + '####' + attrkey + '|||';
            }
        });
        $(".spuImgAttr").val(attr_gg_img);
        editSku = $("input[name='editFormData[sku]']").val();
        if (!editSku && isSkuPriceQtyEmpty) {
            alert("sku,价格，库存不能为空");
            return false;
        }
        if (!editSku && !spuStr) {
            alert("请选择一个属性组，并至少添加一个SKU以及价格和库存数量");
            // 切换tab
            $(".top_header ul li").removeClass("selected");
            $(".top_attr_group_price").addClass("selected");
            $(".topTabsContent").hide();
            $(".group_attr_price").show();
            
            return false;
        }
        $(".spu_attrs").val(spuStr);
        jQuery(".tier_price_input").val(tier_price_str);
        jQuery(".cost_tier_price_input").val(cost_tier_price_str);
        return validateCallback(thiss, dialogAjaxDoneCloseAndReflush);
    }
</script>

<style>
.groupGeneralAttr .edit_p{
    float: left;
    min-width: 156px;
}
.sell-sku-body-table .textInput.topPrice , .sell-sku-body-table .textInput.topQty{
    width: 40px;
    border: 1px solid #ccc;
    margin: 0 10px;
    display: inline-block;
    height: 10px;
    padding-left:5px;
}
.sell-sku-body-table .topPrice, .sell-sku-body-table .topQty{
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    padding: .375rem .75rem;
    width: 40px;
    border: 1px solid #ccc;
    margin: 0 10px;
    display: inline-block;
    height: 10px;
    padding-left:5px;
}
.topButton{
    display: inline-block;
    margin: 0 10px 0 0;
    height: 22px;
    line-height: 17px;
    color: #666;
}
.dialog .tabsContent .group_attr_price .edit_p label{
    min-width:90px;
}
.dialog .tabsContent .edit_p.product_is_in_stock label
{
    min-width:90px
}
.volumn_weight_val{color:#cc0000;}
.product_long , .product_width, .product_high{
    width:200px; float:left;
}
.edit_p.product_long input, .edit_p.product_width input, .edit_p.product_high input {
    width:80px;
}
.dialog .tabsContent .cpst .edit_p.product_long label, .dialog .tabsContent .cpst .edit_p.product_width label, .dialog .tabsContent .cpst .edit_p.product_high label {
    width:90px;
    min-width:90px;
}
.product_marking{
    text-align:left;
}
.seo_info .meta_keywords  .tabsHeader{
    display:none;
}
.seo_info .meta_description  .tabsHeader{
    display:none;
}
.marking_title {
    margin: 44px 10px 20px 14px;
    color: #1463AA;
    font-weight: 500;
    position: relative;
    font-size: 1.2rem;
}
.marking_title::before {
    content: '';
    position: absolute;
    width: 4px;
    height: 14px;
    background: #00aeff;
    top: 0px;
    left: -12px;
}
.product_j_img img{
    max-width: 70px;
    max-height: 70px;
    display: block;
    margin: 10px auto;
    vertical-align: bottom;
}
.product_j_img {
    display: block;
    width: 648px;
    height: 428px;
    position: fixed;
    top: 20%;
    left: 20%;
    margin-left: -74px;
    margin-top: -14px;
    padding: 10px 10px 10px 15px;
    text-align: left;
    position: absolute;
    z-index: 2001;
    background: #fff;
    border: 1px solid #ccc;
    overflow:auto;
}
.product_j_img .product-img{
    border: 1px solid #ccc;
    width: 100px;
    height: 100px;
    float: left;
    margin: 10px;
}
.product_j_img .product-img:hover{
    cursor:pointer;
    border:1px solid #cc0000;
}
.sell-sku-body-table td.sell-sku-cell .fa{
    position: relative;
    line-height: 25px;
    padding: 4px 8px;
    float:right;
}
.sell-sku-body-table td.sell-sku-cell {
    position: relative;
    line-height: 25px;
    padding: 4px 8px;
}
.remark-text{
    color: #cc0000;
}
.sell-sku-body-table td {
    color: #323b44;
    font-size: 12px;
    border-right: 1px solid #c6d1db;
    text-overflow: ellipsis;
    word-break: break-all;
    text-align: left;
    vertical-align: middle;
    min-width: 40px;
    border-bottom: 1px solid #c6d1db;
}
.sell-sku-body-table .hide {
    display: none!important;
}
.sell-sku-body-table .textInput{
    border:none;
    padding-left:0;
}
.sell-sku-body-table {
    border-top: 1px solid #c6d1db;
    border-left: 1px solid #c6d1db;
}
.checker{float:left;}
.dialog .pageContent {background:none;}
.dialog .pageContent .pageFormContent{background:none;}
.edit_p{display:block;height:35px;}
.edit_p label{float:left;line-height: 20px;min-width:200px;}
.edit_p input{width:600px;}
.tabsContent .tabsContent .edit_p label{min-width:194px;}
.edit_p .cost_tier_price input.cost_tier_qty{width:30px;}
.cost_tier_price table tfoot tr td a{    margin: 10px 0 0;}
.edit_p .cost_tier_price input{
	width:100px;
}
.cost_tier_price table thead tr th{
	background: #ddd none repeat scroll 0 0;
    border: 1px solid #ccc;
    padding: 4px 10px;
    width: 100px;
}
.cost_tier_price table tbody tr td{
	background: #fff;
    border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
    padding:3px;
    width: 100px;
}
.edit_p .tier_price input{
	width:100px;
}
.tier_price table thead tr th{
	background: #ddd none repeat scroll 0 0;
    border: 1px solid #ccc;
    padding: 4px 10px;
    width: 100px;
}
.tier_price table tbody tr td{
	background: #fff;
    border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
    padding:3px;
    width: 100px;
}
.custom_option_list table thead tr th{
	background: #ddd none repeat scroll 0 0;
    border: 1px solid #ccc;
    padding: 4px 10px;
    width: 100px;
}
.custom_option_list table tbody tr td{
	background: #fff;
    border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
    padding:3px;
    width: 100px;
}
.add_spu_attr_img{display:none;}
.spu_attr_val_item:hover   .add_spu_attr_img{display:block;}
.spu_attr_val_item{border:1px solid #fff;}
.spu_attr_val_item:hover{border:1px solid #ccc;}
.edit_p .tier_price input.tier_qty{width:30px;}
.custom_option{padding:10px 5px;}
.custom_option span{margin:0 2px 0 10px;}
.custom_option .nps{float:left;margin:0 0 10px 0}
.custom_option_img_list img {cursor:pointer;}
.dialog .tabsContent .cpst .edit_p  label{
    min-width: 90px;
}
.attr_group_i select{
    height: 25px;
    color: #555;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    background: none;
}  
.spu_attr_input{
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    padding: .375rem .75rem;
    width:100px;
    margin:0;
}
</style>
<script>
    var div = document.getElementById("container");
    var w = div.offsetWidth;    // 返回元素的总宽度
    var h = div.offsetHeight;    // 返回元素的总高度
    var vsss = h*0.95 - 150;
    $(".pageForm > .tabs >.tabsContent").css("height", vsss+'px');
</script>