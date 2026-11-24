<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />

<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'sell-items-form',
        ));
?>
<fieldset>
    <legend>Search Conditions (Sales)</legend>
    <fieldset style="float: left; width: 98%; height: 155px;">
        <table class="summaryTab">
            <tr>
                <td><label>From</label></td>
                <td><label>To</label></td>
                <td><label>Store</label></td>
                <td><label>Company</label></td>
                <td><label>Customer</label></td>
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
                    <?php echo $form->dropDownList($model, 'company_id', Lookup::items('company_name'), array('prompt' => 'select') ); ?>
                </td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'customer_id', CHtml::listData(Customers::model()->findAll(array('order' => 'company_name ASC')), 'id', 'company_name'), array(
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
                                                $("#SellItems_brand_id").html(data.subCatList);
                                         }',
                            'data' => array(
                                'catId' => 'js:jQuery("#SellItems_item_id").val()',
                            ),
                            'beforeSend' => 'function(){
                                                    document.getElementById("SellItems_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                            'complete' => 'function(){
                                            document.getElementById("SellItems_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px";
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
                                                $("#SellItems_model_id").html(data.prodList);
                                                $("#jsPart").html(data.jsPart);
                                         }',
                            'data' => array(
                                'subCatId' => 'js:jQuery("#SellItems_brand_id").val()',
                                'idPrefix' => 'SellItems',
                            ),
                            'beforeSend' => 'function(){
                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                            'complete' => 'function(){
                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px";
                                        }',
                        ),
                    ));
                    ?>
                </td>   
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'model_id', CHtml::listData(ProdModels::model()->findAll(array('order' => 'id DESC')), 'id', 'model_name'), array(
                        'prompt' => 'Select',
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('prodModels/catSubCatOfThisProd'),
                            'success' => 'function(data) {
                                                $("#jsPart").html(data.jsPart);
                                         }',
                            'data' => array(
                                'prodId' => 'js:jQuery("#SellItems_model_id").val()',
                                'idPrefix' => 'SellItems',
                            ),
                            'beforeSend' => 'function(){
                                                    document.getElementById("SellItems_code").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                         }',
                            'complete' => 'function(){
                                                    document.getElementById("SellItems_code").style.background="#FFFFFF"; 
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
                                'prodCode' => 'js:jQuery("#SellItems_code").val()',
                                'idPrefix' => 'SellItems',
                            ),
                            'beforeSend' => 'function(){
                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                         }',
                            'complete' => 'function(){
                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
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
                                                $("#SellItems_item_id").html(data.itemValue);
                                                $("#SellItems_brand_id").html(data.brandValue);
                                                $("#SellItems_model_id").html(data.modelValue);
                                                $("#SellItems_code").val("");
                                                $("#SellItems_pmodel").val("").selected=true;
                                                $("#SellItems_pbrand").val("").selected=true;
                                                $("#SellItems_color_id").val("").selected=true;
                                                $("#SellItems_size_id").val("").selected=true;
                                                $("#SellItems_sell_price").val("");
                                                $("#SellItems_discount").val("");
                                            }',
                            'data' => array(
                                'sendingVal' => 'js:jQuery("#SellItems_code").val()',
                            ),
                            'beforeSend' => 'function(){
                                                document.getElementById("SellItems_item_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                                document.getElementById("SellItems_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                                document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                                document.getElementById("SellItems_code").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                             }',
                            'complete' => 'function(){
                                                document.getElementById("SellItems_item_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                document.getElementById("SellItems_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                document.getElementById("SellItems_code").style.background="#FFFFFF"; 
                                             }',
                        ),
                            )
                    );
                    ?>
                </td>
            </tr>  
            <tr>
                <td colspan="5" rowspan="2">
                    <?php
                    echo CHtml::submitButton('Search', array(
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => CController::createUrl('sellItems/sellReportView'),
                            'beforeSend' => 'function(){
                                if($("#SellItems_dateFrom").val()=="" || $("#SellItems_dateTo").val()==""){
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
                <td><label>Size</label></td>
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
                                                                $("#SellItems_model_id").html(data.prodList);
                                                         }',
                            'data' => array(
                                'pmodel' => 'js:jQuery("#SellItems_pmodel").val()',
                                'pbrand' => 'js:jQuery("#SellItems_pbrand").val()',
                                'color_id' => 'js:jQuery("#SellItems_color_id").val()',
                                'size_id' => 'js:jQuery("#SellItems_size_id").val()',
                            ),
                            'beforeSend' => 'function(){
                                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                                         }',
                            'complete' => 'function(){
                                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
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
                                                                $("#SellItems_model_id").html(data.prodList);
                                                         }',
                            'data' => array(
                                'pbrand' => 'js:jQuery("#SellItems_pbrand").val()',
                                'pmodel' => 'js:jQuery("#SellItems_pmodel").val()',
                                'color_id' => 'js:jQuery("#SellItems_color_id").val()',
                                'size_id' => 'js:jQuery("#SellItems_size_id").val()',
                            ),
                            'beforeSend' => 'function(){
                                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                                         }',
                            'complete' => 'function(){
                                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
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
                                                                $("#SellItems_model_id").html(data.prodList);
                                                         }',
                            'data' => array(
                                'color_id' => 'js:jQuery("#SellItems_color_id").val()',
                                'pmodel' => 'js:jQuery("#SellItems_pmodel").val()',
                                'pbrand' => 'js:jQuery("#SellItems_pbrand").val()',
                                'size_id' => 'js:jQuery("#SellItems_size_id").val()',
                            ),
                            'beforeSend' => 'function(){
                                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                                         }',
                            'complete' => 'function(){
                                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                        }',
                        ),
                    ));
                    ?>
                </td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'size_id', CriteriaValues::model()->findAllSizeNames(), array(
                        'prompt' => 'Select',
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('prodModels/prodInfoOfThisAdditionalOp'),
                            'success' => 'function(data) {
                                                                $("#SellItems_model_id").html(data.prodList);
                                                         }',
                            'data' => array(
                                'size_id' => 'js:jQuery("#SellItems_size_id").val()',
                                'pmodel' => 'js:jQuery("#SellItems_pmodel").val()',
                                'pbrand' => 'js:jQuery("#SellItems_pbrand").val()',
                                'color_id' => 'js:jQuery("#SellItems_color_id").val()',
                            ),
                            'beforeSend' => 'function(){
                                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                                         }',
                            'complete' => 'function(){
                                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                        }',
                        ),
                    ));
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
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
        
        $( "#SellItems_code" ).autocomplete({
            minLength: 0,
            source: codeSuggest
        });
    });
</script>
<?php $this->endWidget(); ?>

<fieldset>
    <legend>Sales Report <div class="ajaxLoaderResultView" style="display: none; float: right;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div></legend>
    <div id="resultDiv"></div>
</fieldset>
<div id="jsPart"></div>