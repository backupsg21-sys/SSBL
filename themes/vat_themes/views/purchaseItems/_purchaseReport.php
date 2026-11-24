<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />

<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'sell-items-form',
        ));
?>
<fieldset>
    <legend>Search Conditions (Purchase)</legend>
        <table class="summaryTab">
            <tr>
                <td><label>From</label></td>
                <td><label>To</label></td>
                <td><label>Store</label></td>
                <td><label>Supplier</label></td>
                <td><label>Category</label></td>
                <td><label>Sub-Category</label></td>
                <td><label>Product Name</label></td>
                <td><label>Code</label></td>
            </tr>
            <tr>
                <td>
                    <?php
                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                    $dateTimePickerConfig1 = array(
                        'model' => $model,
                        'attribute' => 'dateFrom',
                        'mode' => 'date',
                        'language' => 'en-AU',
                        'options' => array(
                            'changeMonth'=>'true', 
                                'changeYear'=>'true',
                            'dateFormat' => 'yy-mm-dd',
                            'width' => '100',
                            'id' => 'dateFrom',
                        )
                    );
                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                    ?>
                </td>
                <td>
                    <?php
                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                    $dateTimePickerConfig2 = array(
                        'model' => $model,
                        'attribute' => 'dateTo',
                        'mode' => 'date',
                        'language' => 'en-AU',
                        'options' => array(
                            'changeMonth'=>'true', 
                                'changeYear'=>'true',
                            'dateFormat' => 'yy-mm-dd',
                            'width' => '100',
                            'id' => 'dateTo',
                        )
                    );
                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig2);
                    ?>
                </td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'storeId', CHtml::listData(Stores::model()->findAll(array('order' => 'id DESC')), 'id', 'store_name'), array(
                        'prompt' => 'Select',
                    ));
                    ?>
                </td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'supplier_id', CHtml::listData(Suppliers::model()->findAll(array('order' => 'name ASC')), 'id', 'name'), array(
                        'prompt' => 'Select',
                    ));
                    ?>
                </td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'item_id', CHtml::listData(ProdItems::model()->findAll(array('order' => 'id DESC')), 'id', 'item_name'), array(
                        'prompt' => 'Select',
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('prodModels/subCatOfThisCat'),
                            'success' => 'function(data) {
                                                $("#PurchaseItems_brand_id").html(data.subCatList);
                                         }',
                            'data' => array(
                                'catId' => 'js:jQuery("#PurchaseItems_item_id").val()',
                            ),
                            'beforeSend' => 'function(){
                                                    document.getElementById("PurchaseItems_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                            'complete' => 'function(){
                                            document.getElementById("PurchaseItems_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px";
                                        }',
                        ),
                    ));
                    ?>
                </td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'brand_id', CHtml::listData(ProdBrands::model()->findAll(array('order' => 'id DESC')), 'id', 'brand_name'), array(
                        'prompt' => 'Select',
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('prodModels/catProdOfThisSubCat'),
                            'success' => 'function(data) {
                                                $("#PurchaseItems_model_id").html(data.prodList);
                                                $("#jsPart").html(data.jsPart);
                                         }',
                            'data' => array(
                                'subCatId' => 'js:jQuery("#PurchaseItems_brand_id").val()',
                                'idPrefix' => 'PurchaseItems',
                            ),
                            'beforeSend' => 'function(){
                                                    document.getElementById("PurchaseItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                            'complete' => 'function(){
                                                    document.getElementById("PurchaseItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px";
                                        }',
                        ),
                    ));
                    ?>
                </td>   
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'model_id', CHtml::listData(ProdModels::model()->findAll(array('order' => 'model_name ASC')), 'id', 'model_name'), array(
                        'prompt' => 'Select',
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('prodModels/catSubCatOfThisProd'),
                            'success' => 'function(data) {
                                                $("#jsPart").html(data.jsPart);
                                         }',
                            'data' => array(
                                'prodId' => 'js:jQuery("#PurchaseItems_model_id").val()',
                                'idPrefix' => 'PurchaseItems',
                            ),
                            'beforeSend' => 'function(){
                                                    document.getElementById("PurchaseItems_code").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                         }',
                            'complete' => 'function(){
                                                    document.getElementById("PurchaseItems_code").style.background="#FFFFFF"; 
                                        }',
                        ),
                    ));
                    ?>
                </td>
                <td>
                    <?php echo $form->textField($model, 'code', array('maxlength' => 255)); ?>
                </td>
                <td>
                    <?php
                    echo CHtml::submitButton('Find', array(
                        'class' => 'findBtn',
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('prodModels/catSubCatProdOfThisCode'),
                            'success' => 'function(data) {
                                                $("#jsPart").html(data.jsPart);
                                         }',
                            'data' => array(
                                'prodCode' => 'js:jQuery("#PurchaseItems_code").val()',
                                'idPrefix' => 'PurchaseItems',
                            ),
                            'beforeSend' => 'function(){
                                                    document.getElementById("PurchaseItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                         }',
                            'complete' => 'function(){
                                                    document.getElementById("PurchaseItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                        }',
                        ),
                            )
                    );
                    ?>
                    <?php
                    echo CHtml::submitButton('Reset', array(
                        'class' => 'findBtn',
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('prodModels/reset'),
                            'success' => 'function(data) {
                                                $("#PurchaseItems_item_id").html(data.itemValue);
                                                $("#PurchaseItems_brand_id").html(data.brandValue);
                                                $("#PurchaseItems_model_id").html(data.modelValue);
                                                $("#PurchaseItems_code").val("");
                                                $("#PurchaseItems_pmodel").val("").selected=true;
                                                $("#PurchaseItems_pbrand").val("").selected=true;
                                                $("#PurchaseItems_color_id").val("").selected=true;
                                                $("#PurchaseItems_size_id").val("").selected=true;
                                                $("#PurchaseItems_sell_price").val("");
                                                $("#PurchaseItems_discount").val("");
                                            }',
                            'data' => array(
                                'sendingVal' => 'js:jQuery("#PurchaseItems_code").val()',
                            ),
                            'beforeSend' => 'function(){
                                                document.getElementById("PurchaseItems_item_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                                document.getElementById("PurchaseItems_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                                document.getElementById("PurchaseItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                                document.getElementById("PurchaseItems_code").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                             }',
                            'complete' => 'function(){
                                                document.getElementById("PurchaseItems_item_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                document.getElementById("PurchaseItems_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                document.getElementById("PurchaseItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                document.getElementById("PurchaseItems_code").style.background="#FFFFFF"; 
                                             }',
                        ),
                            )
                    );
                    ?>
                </td>
            </tr>  
            <tr>
                <td colspan="4" rowspan="2">
                    <?php
                    echo CHtml::submitButton('Search', array(
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => CController::createUrl('purchaseItems/purchaseReportView'),
                            'beforeSend' => 'function(){
                                if($("#PurchaseItems_dateFrom").val()=="" || $("#PurchaseItems_dateTo").val()==""){
                                    alertify.alert("Warning! Please select date range!");
                                    return false;
                                }else{
                                    $(".ajaxLoaderResultView").show();
                                }
                            }',
                            'complete' => 'function(){
                                $(".ajaxLoaderResultView").hide();
                            }',
                            'update' => '#resultDiv',
                        ),
                        'class' => 'custom_check_button',
                            )
                    );
                    ?>
                </td>
                <td><label>Model</label></td>
                <td><label>Brand</label></td>
                <td><label>Color</label></td>
                <td><label>Memo No.</label></td>
            </tr>
            <tr>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'pmodel', CHtml::listData(PModel::model()->findAll(array('order' => 'id DESC')), 'id', 'name'), array(
                        'prompt' => 'Select',
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('prodModels/prodInfoOfThisAdditionalOp'),
                            'success' => 'function(data) {
                                                                $("#PurchaseItems_model_id").html(data.prodList);
                                                         }',
                            'data' => array(
                                'pmodel' => 'js:jQuery("#PurchaseItems_pmodel").val()',
                                'pbrand' => 'js:jQuery("#PurchaseItems_pbrand").val()',
                                'color_id' => 'js:jQuery("#PurchaseItems_color_id").val()',
                                'size_id' => 'js:jQuery("#PurchaseItems_size_id").val()',
                            ),
                            'beforeSend' => 'function(){
                                                                    document.getElementById("PurchaseItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                                         }',
                            'complete' => 'function(){
                                                                    document.getElementById("PurchaseItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                        }',
                        ),
                    ));
                    ?>
                </td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'pbrand', CHtml::listData(PBrand::model()->findAll(array('order' => 'id DESC')), 'id', 'name'), array(
                        'prompt' => 'Select',
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('prodModels/prodInfoOfThisAdditionalOp'),
                            'success' => 'function(data) {
                                                                $("#PurchaseItems_model_id").html(data.prodList);
                                                         }',
                            'data' => array(
                                'pbrand' => 'js:jQuery("#PurchaseItems_pbrand").val()',
                                'pmodel' => 'js:jQuery("#PurchaseItems_pmodel").val()',
                                'color_id' => 'js:jQuery("#PurchaseItems_color_id").val()',
                                'size_id' => 'js:jQuery("#PurchaseItems_size_id").val()',
                            ),
                            'beforeSend' => 'function(){
                                                                    document.getElementById("PurchaseItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                                         }',
                            'complete' => 'function(){
                                                                    document.getElementById("PurchaseItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                        }',
                        ),
                    ));
                    ?>
                </td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'color_id', CriteriaValues::model()->findAllColorNames(), array(
                        'prompt' => 'Select',
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('prodModels/prodInfoOfThisAdditionalOp'),
                            'success' => 'function(data) {
                                                                $("#PurchaseItems_model_id").html(data.prodList);
                                                         }',
                            'data' => array(
                                'color_id' => 'js:jQuery("#PurchaseItems_color_id").val()',
                                'pmodel' => 'js:jQuery("#PurchaseItems_pmodel").val()',
                                'pbrand' => 'js:jQuery("#PurchaseItems_pbrand").val()',
                                'size_id' => 'js:jQuery("#PurchaseItems_size_id").val()',
                            ),
                            'beforeSend' => 'function(){
                                                                    document.getElementById("PurchaseItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                                         }',
                            'complete' => 'function(){
                                                                    document.getElementById("PurchaseItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                        }',
                        ),
                    ));
                    ?>
                </td>
                <td>
                    <?php echo $form->textField($model, 'supplier_bill_no', array('maxlength' => 50)); ?>
                </td>
            </tr>
        </table>
</fieldset>
<?php
$pcode = ProdModels::model()->findAll();
?>
<script>
    $(document).ready(function(){
        var codeSuggest = [
<?php foreach ($pcode as $pcd): ?>
    <?php echo '"' . $pcd->code . '"'; ?>,
<?php endforeach; ?>
        ];
        
        $( "#PurchaseItems_code" ).autocomplete({
            minLength: 0,
            source: codeSuggest
        });
    });
</script>
<?php $this->endWidget(); ?>

<fieldset>
    <legend>Purchase Report <div class="ajaxLoaderResultView" style="display: none; float: right;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div></legend>
    <div id="resultDiv">
    </div>
</fieldset>
<div id="jsPart"></div>