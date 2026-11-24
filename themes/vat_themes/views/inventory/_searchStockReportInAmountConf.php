
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />
<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'inventory-form',
            'action' => Yii::app()->createUrl('/inventory/stockReportViewInAmountConf'),
        ));
?>
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
        
        $( "#Inventory_code" ).autocomplete({
            minLength: 0,
            source: codeSuggest
        });
    });
</script>
<fieldset>
    <legend>Search Conditions</legend>
    <table class="summaryTab">
        <tr>
            <td colspan="8" style="font-size: 13px;">
                Search conditions
            </td>
        </tr>
        <tr>
            <td><?php echo $form->label($model, 'startDate'); ?></td>
            <td><?php echo $form->label($model, 'endDate'); ?></td>
            <td><?php echo $form->label($model, 'Store'); ?></td>
            <td><?php echo $form->label($model, 'item_id'); ?></td>
            <td><?php echo $form->label($model, 'brand_id'); ?></td>
            <td><?php echo $form->label($model, 'model_id'); ?></td>
            <td><?php echo $form->label($model, 'code'); ?></td>
            <td></td>
        </tr>
        <tr>
            <td>
                <?php
                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $dateTimePickerConfig1 = array(
                    'model' => $model,
                    'attribute' => 'startDate',
                    'mode' => 'date',
                    'language' => 'en-AU',
                    'options' => array(
                        'changeMonth'=>'true', 
                                'changeYear'=>'true',
                        'dateFormat' => 'yy-mm-dd',
                        'width' => '100',
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
                    'attribute' => 'endDate',
                    'mode' => 'date',
                    'language' => 'en-AU',
                    'options' => array(
                        'changeMonth'=>'true', 
                                'changeYear'=>'true',
                        'dateFormat' => 'yy-mm-dd',
                        'width' => '100',
                    )
                );
                $this->widget('CJuiDateTimePicker', $dateTimePickerConfig2);
                ?>
            </td>
            <td>
                <?php
                echo $form->dropDownList(
                    $model, 'store_id', CHtml::listData(Stores::model()->findAll(), 'id', 'store_name'), array(
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
                                                $("#Inventory_brand_id").html(data.subCatList);
                                         }',
                        'data' => array(
                            'catId' => 'js:jQuery("#Inventory_item_id").val()',
                        ),
                        'beforeSend' => 'function(){
                                            document.getElementById("Inventory_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                        'complete' => 'function(){
                                            document.getElementById("Inventory_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px";
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
                                                $("#Inventory_model_id").html(data.prodList);
                                                $("#jsPart").html(data.jsPart);
                                         }',
                        'data' => array(
                            'subCatId' => 'js:jQuery("#Inventory_brand_id").val()',
                            'idPrefix' => 'Inventory',
                        ),
                        'beforeSend' => 'function(){
                                          document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                        'complete' => 'function(){
                                        	document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px";
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
                            'prodId' => 'js:jQuery("#Inventory_model_id").val()',
                            'idPrefix' => 'Inventory',
                        ),
                        'beforeSend' => 'function(){
                                         	document.getElementById("Inventory_code").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                         }',
                        'complete' => 'function(){
                                        	document.getElementById("Inventory_code").style.background="#FFFFFF"; 
                                        }',
                    ),
                ));
                ?>
            </td>        
            <td><?php echo $form->textField($model, 'code', array('maxlength' => 255)); ?></td>     
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
                            'prodCode' => 'js:jQuery("#Inventory_code").val()',
                            'idPrefix' => 'Inventory',
                        ),
                        'beforeSend' => 'function(){
                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                         }',
                        'complete' => 'function(){
                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
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
                                                $("#Inventory_item_id").html(data.itemValue);
                                                $("#Inventory_brand_id").html(data.brandValue);
                                                $("#Inventory_model_id").html(data.modelValue);
                                                $("#Inventory_code").val("");
                                                $("#Inventory_pmodel").val("").selected=true;
                                                $("#Inventory_pbrand").val("").selected=true;
                                                $("#Inventory_color_id").val("").selected=true;
                                                $("#Inventory_size_id").val("").selected=true;
                                                $("#Inventory_sell_price").val("");
                                                $("#Inventory_discount").val("");
                                            }',
                        'data' => array(
                            'sendingVal' => 'js:jQuery("#Inventory_code").val()',
                        ),
                        'beforeSend' => 'function(){
                                                document.getElementById("Inventory_item_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                                document.getElementById("Inventory_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                                document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                                document.getElementById("Inventory_code").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                             }',
                        'complete' => 'function(){
                                                document.getElementById("Inventory_item_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                document.getElementById("Inventory_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                document.getElementById("Inventory_code").style.background="#FFFFFF"; 
                                             }',
                    ),
                        )
                );
                ?>
            </td>
        </tr> 
        <tr>
            <td colspan="3"></td>
            <td><label>Model</label></td>
            <td><label>Brand</label></td>
            <td><label>Color</label></td>
            <td><label>Size</label></td>
        </tr>
        <tr>
            <td>
            	<select id="showallornot" name="showallornot">
                	<option value="Current">Current</option>
                    <option value="Show All">Show All</option>
                    
                </select>
            </td>
            <td colspan="2">
                <?php
                echo CHtml::ajaxSubmitButton('Search', CHtml::normalizeUrl(array('inventory/stockReportViewInAmountConf', 'render' => true)), array(
                    'dataType' => 'json',
                    'type' => 'post',
                    'success' => 'function(data) {
                        $("#ajaxLoader").hide(); 
                        $("#resultDiv").html(data.content);
                    }',
                    'beforeSend' => 'function(){ 
                        if($("#Inventory_startDate").val()=="" || $("#Inventory_endDate").val()==""){
                            alertify.alert("Warning! Please select a Start & End Date!");
                            return false;
                        }
                        $("#ajaxLoader").show();
                    }',
                ));
                ?>
            </td>
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
                                                                $("#Inventory_model_id").html(data.prodList);
                                                         }',
                        'data' => array(
                            'pmodel' => 'js:jQuery("#Inventory_pmodel").val()',
                            'pbrand' => 'js:jQuery("#Inventory_pbrand").val()',
                            'color_id' => 'js:jQuery("#Inventory_color_id").val()',
                            'size_id' => 'js:jQuery("#Inventory_size_id").val()',
                        ),
                        'beforeSend' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                                         }',
                        'complete' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
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
                                                                $("#Inventory_model_id").html(data.prodList);
                                                         }',
                        'data' => array(
                            'pbrand' => 'js:jQuery("#Inventory_pbrand").val()',
                            'pmodel' => 'js:jQuery("#Inventory_pmodel").val()',
                            'color_id' => 'js:jQuery("#Inventory_color_id").val()',
                            'size_id' => 'js:jQuery("#Inventory_size_id").val()',
                        ),
                        'beforeSend' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                                         }',
                        'complete' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
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
                                                                $("#Inventory_model_id").html(data.prodList);
                                                         }',
                        'data' => array(
                            'color_id' => 'js:jQuery("#Inventory_color_id").val()',
                            'pmodel' => 'js:jQuery("#Inventory_pmodel").val()',
                            'pbrand' => 'js:jQuery("#Inventory_pbrand").val()',
                            'size_id' => 'js:jQuery("#Inventory_size_id").val()',
                        ),
                        'beforeSend' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                                         }',
                        'complete' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
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
                                                                $("#Inventory_model_id").html(data.prodList);
                                                         }',
                        'data' => array(
                            'size_id' => 'js:jQuery("#Inventory_size_id").val()',
                            'pmodel' => 'js:jQuery("#Inventory_pmodel").val()',
                            'pbrand' => 'js:jQuery("#Inventory_pbrand").val()',
                            'color_id' => 'js:jQuery("#Inventory_color_id").val()',
                        ),
                        'beforeSend' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                                         }',
                        'complete' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                        }',
                    ),
                ));
                ?>
            </td>
        </tr>
    </table>
    <div id="jsPart"></div>
</fieldset>

<?php $this->endWidget(); ?>

