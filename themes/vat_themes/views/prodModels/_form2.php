<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'prod-models-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
        ));
?>
<div class="formDiv">
    <div style="text-align: center; font-size: 20px;">Update Product Info</div>
    <fieldset>
        <table style="width:100%">
            <tr>
                <td style="width: 33%; vertical-align: top;">
                    <fieldset>
                        <legend>Product Information</legend>
                        <table style="width:100%">
                            <tr>
                                <td style="width:140px"><?php echo $form->labelEx($model, 'item_id'); ?></td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                            $model, 'item_id', CHtml::listData(ProdItems::model()->findAll(array('order' => 'item_name ASC')), 'id', 'item_name'), array(
                                        'prompt' => 'Select',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'dataType' => 'json',
                                            'url' => CController::createUrl('prodModels/subCatOfThisCat'),
                                            'success' => 'function(data) {
                                                $("#ProdModels_brand_id").html(data.subCatList);
                                         }',
                                            'data' => array(
                                                'catId' => 'js:jQuery("#ProdModels_item_id").val()',
                                            ),
                                            'beforeSend' => 'function(){
                                                    document.getElementById("ProdModels_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                                            'complete' => 'function(){
                                            document.getElementById("ProdModels_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                        }',
                                        ),
                                    ));
                                    ?>
                                    <?php
                                    echo CHtml::link('', "", // the link for open the dialog
                                            array(
                                        'class' => 'add-additional-btn',
                                        'onclick' => "{addProdItem(); $('#dialogAddProdItem').dialog('open');}"));
                                    ?>

                                    <?php
                                    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                                        'id' => 'dialogAddProdItem',
                                        'options' => array(
                                            'title' => 'Add Quality',
                                            'autoOpen' => false,
                                            'modal' => true,
                                            'width' => 550,
                                            'resizable' => false,
                                        ),
                                    ));
                                    ?>
                                    <div class="divForForm">
                                        <div class="ajaxLoaderFormLoad" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
                                    </div>

                                    <?php $this->endWidget(); ?>

                                    <script type="text/javascript">
                                        // here is the magic
                                        function addProdItem() {
<?php
echo CHtml::ajax(array(
    'url' => array('prodItems/createProdItemsFromOutSide'),
    'data' => "js:$(this).serialize()",
    'type' => 'post',
    'dataType' => 'json',
    'beforeSend' => "function(){
                                                                $('.ajaxLoaderFormLoad').show();
                                                                }",
    'complete' => "function(){
                                                                $('.ajaxLoaderFormLoad').hide();
                                                                }",
    'success' => "function(data){
                                                                                                    if (data.status == 'failure')
                                                                                                    {
                                                                                                        $('#dialogAddProdItem div.divForForm').html(data.div);
                                                                                                              // Here is the trick: on submit-> once again this function!
                                                                                                        $('#dialogAddProdItem div.divForForm form').submit(addProdItem);
                                                                                                    }
                                                                                                    else
                                                                                                    {
                                                                                                        $('#dialogAddProdItem div.divForForm').html(data.div);
                                                                                                        setTimeout(\"$('#dialogAddProdItem').dialog('close') \",1000);
                                                                                                        $('#ProdModels_item_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
                                                                                                    }
                                                                                                                            }",
))
?>
                                            return false;
                                        }
                                    </script>
                                    <?php echo $form->error($model, 'item_id'); ?>
                                </td>   
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'brand_id'); ?></td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                            $model, 'brand_id', CHtml::listData(ProdBrands::model()->findAll(array('order' => 'brand_name ASC')), 'id', 'brand_name'), array(
                                        'prompt' => 'Select',
                                    ));
                                    ?>
                                    <?php
                                    echo CHtml::link('', "", // the link for open the dialog
                                            array(
                                        'class' => 'add-additional-btn',
                                        'onclick' => "{addProdBrand(); $('#dialogAddProdBrand').dialog('open');}"));
                                    ?>

                                    <?php
                                    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                                        'id' => 'dialogAddProdBrand',
                                        'options' => array(
                                            'title' => 'Add Product Type',
                                            'autoOpen' => false,
                                            'modal' => true,
                                            'width' => 550,
                                            'resizable' => false,
                                        ),
                                    ));
                                    ?>
                                    <div class="divForForm">
                                        <div class="ajaxLoaderFormLoad" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
                                    </div>

                                    <?php $this->endWidget(); ?>

                                    <script type="text/javascript">
                                        // here is the magic
                                        function addProdBrand() {
<?php
echo CHtml::ajax(array(
    'url' => array('prodBrands/createProdBrandsFromOutSide'),
    'data' => "js:$(this).serialize()",
    'type' => 'post',
    'dataType' => 'json',
    'beforeSend' => "function(){
                    $('.ajaxLoaderFormLoad').show();
                    }",
    'complete' => "function(){
                    $('.ajaxLoaderFormLoad').hide();
                    }",
    'success' => "function(data){
                                                        if (data.status == 'failure')
                                                        {
                                                            $('#dialogAddProdBrand div.divForForm').html(data.div);
                                                                  // Here is the trick: on submit-> once again this function!
                                                            $('#dialogAddProdBrand div.divForForm form').submit(addProdBrand);
                                                        }
                                                        else
                                                        {
                                                            $('#dialogAddProdBrand div.divForForm').html(data.div);
                                                            setTimeout(\"$('#dialogAddProdBrand').dialog('close') \",1000);
                                                            $('#ProdModels_brand_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
                                                        }
                                                                                }",
))
?>;
                                            return false;
                                        }
                                    </script>
                                    <div id="ajaxLoaderDrop" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
                                    <?php echo $form->error($model, 'brand_id'); ?>
                                </td>  
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'model_name'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'model_name', array('maxlength' => 255)); ?>
                                    <?php echo $form->error($model, 'model_name'); ?>
                                </td>            
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'code'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'code', array('maxlength' => 255)); ?>
                                    <?php echo $form->error($model, 'code'); ?>
                                </td> 
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'hs_code'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'hs_code', array('maxlength' => 255)); ?>
                                    <?php echo $form->error($model, 'hs_code'); ?>
                                </td> 
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'unit_id'); ?></td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                            $model, 'unit_id', CHtml::listData(Units::model()->findAll(array('order' => 'label ASC')), 'id', 'label'), array(
                                        'prompt' => 'Select',
                                    ));
                                    ?>
                                    <?php
                                    echo CHtml::link('', "", // the link for open the dialog
                                            array(
                                        'class' => 'add-additional-btn',
                                        'onclick' => "{addUnit(); $('#dialogAddUnit').dialog('open');}"));
                                    ?>

                                    <?php
                                    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                                        'id' => 'dialogAddUnit',
                                        'options' => array(
                                            'title' => 'Add Unit',
                                            'autoOpen' => false,
                                            'modal' => true,
                                            'width' => 550,
                                            'resizable' => false,
                                        ),
                                    ));
                                    ?>
                                    <div class="divForForm">                         
                                        <div class="ajaxLoaderFormLoad" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>

                                    </div>

                                    <?php $this->endWidget(); ?>

                                    <script type="text/javascript">
                                        // here is the magic
                                        function addUnit() {
<?php
echo CHtml::ajax(array(
    'url' => array('units/createUnitFromOutSide'),
    'data' => "js:$(this).serialize()",
    'type' => 'post',
    'dataType' => 'json',
    'beforeSend' => "function(){
                                                                $('.ajaxLoaderFormLoad').show();
                                                                }",
    'complete' => "function(){
                                                                $('.ajaxLoaderFormLoad').hide();
                                                                }",
    'success' => "function(data){
                                                                                                        if (data.status == 'failure')
                                                                                                        {
                                                                                                            $('#dialogAddUnit div.divForForm').html(data.div);
                                                                                                                  // Here is the trick: on submit-> once again this function!
                                                                                                            $('#dialogAddUnit div.divForForm form').submit(addUnit);
                                                                                                        }
                                                                                                        else
                                                                                                        {
                                                                                                            $('#dialogAddUnit div.divForForm').html(data.div);
                                                                                                            setTimeout(\"$('#dialogAddUnit').dialog('close') \",1000);
                                                                                                            $('#ProdModels_unit_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
                                                                                                        }
                                                                                                                                }",
))
?>
                                            return false;
                                        }
                                    </script>
                                    <?php echo $form->error($model, 'unit_id'); ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset style="height: 267px;">
                        <legend>Additional Information</legend>
                        <table style="width:100%">
                            <tr>
                                <td style="width:140px"><?php echo $form->labelEx($model, 'rebatable'); ?></td>
                                <td>
                                    <?php echo $form->checkBox($model, 'rebatable', array('value' => '1', 'uncheckValue' => '0')); ?>
                                    <?php echo $form->error($model, 'rebatable'); ?>
                                </td>                     
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'features'); ?></td>
                                <td><?php echo $form->textArea($model, 'features', array('rows' => 1, 'cols' => 55, 'style' => 'height:120px;min-width: 320px;')); ?>
                                    <?php echo $form->error($model, 'features'); ?>
                                </td>                     
                            </tr>
                        </table>
                    </fieldset>
                </td>
                <td style="width: 33%; vertical-align: top">
                    <fieldset style="height: 206px;">
                        <legend>Product Price & Qty</legend>
                        <table style="width: 90%">                           
                            <tr>
                                <td style="width:20%"><?php echo $form->labelEx($model, 'mrp'); ?></td>
                                <td><?php echo $form->textField($model, 'mrp', array('onkeyup' => 'calculateAmount($(this))', 'class' => 'mrp')); ?></td> 
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'gp_percent'); ?></td>
                                <td><?php echo $form->textField($model, 'gp_percent', array('class' => 'gp_percent')); ?></td> 
                            </tr>
                            <tr style="display: none">
                                <td><?php echo $form->labelEx($model, 'qty'); ?></td>
                                <td><?php echo $form->textField($model, 'qty'); ?></td> 
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'min_order_qty'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'min_order_qty'); ?>
                                    <?php echo $form->error($model, 'min_order_qty'); ?>
                                </td> 
                            </tr>
                            <tr>
                                <td style="width:20%"><?php echo $form->labelEx($model, 'purchase_discount'); ?></td>
                                <td><?php echo $form->textField($model, 'purchase_discount'); ?></td> 
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'base_tp'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'base_tp', array('onkeyup' => 'calculateAmount($(this))', 'class' => 'base_tp')); ?>
                                    <?php echo $form->error($model, 'base_tp'); ?>
                                </td> 
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset>
                        <legend>Tax Rate</legend>
                        <table style="width: 90%">
                            <tr>
                                <td><?php echo $form->labelEx($model, 'vat_calculation_method'); ?></td>
                                <td width="80%">
                                    <?php
                                    echo $form->dropDownList(
                                            $model, 'vat_calculation_method', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method'", 'order' => 'code ASC')), 'code', 'name'), array(
                                        //'prompt' => 'Select',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'dataType' => 'json',
                                            'url' => CController::createUrl('prodModels/subVatOfVat'),
                                            'success' => 'function(data) {
                                                $("#ProdModels_sub_vat_id").html(data.subVatList);
                                         }',
                                            'data' => array(
                                                'subVatId' => 'js:jQuery("#ProdModels_vat_calculation_method").val()',
                                            ),
                                            'beforeSend' => 'function(){
                                                    //document.getElementById("ProdModels_sub_vat_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                                            'complete' => 'function(){
                                            //document.getElementById("ProdModels_sub_vat_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                        }',
                                        ),
                                    ));
                                    ?>
                                </td> 
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'vat'); ?></td>
                                <td>
                                    <?php
                                    if ($model->vat_calculation_method == 1) {
                                        echo $form->dropDownList(
                                                $model, 'sub_vat_id', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_standard'", 'order' => 'code ASC')), 'code', 'name'), array(
                                        ));
                                    } else if ($model->vat_calculation_method == 2) {
                                        echo $form->dropDownList(
                                                $model, 'sub_vat_id', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_reducing'", 'order' => 'code ASC')), 'code', 'name'), array(
                                            'prompt' => 'Select',
                                        ));
                                    } else if ($model->vat_calculation_method == 3) {
                                        echo $form->dropDownList(
                                                $model, 'sub_vat_id', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_fixed'", 'order' => 'code ASC')), 'code', 'name'), array(
                                            'prompt' => 'Select',
                                        ));
                                    } else if ($model->vat_calculation_method == 4) {
                                        echo $form->dropDownList(
                                                $model, 'sub_vat_id', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_non_vatable'", 'order' => 'code ASC')), 'code', 'name'), array(
                                        ));
                                    } else if ($model->vat_calculation_method == 5) {
                                        echo $form->dropDownList(
                                                $model, 'sub_vat_id', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_zero'", 'order' => 'code ASC')), 'code', 'name'), array(
                                        ));
                                    } else if ($model->vat_calculation_method == 6) {
                                        echo $form->dropDownList(
                                                $model, 'sub_vat_id', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_mrp'", 'order' => 'code ASC')), 'code', 'name'), array(
                                        ));
                                    }
                                    ?>
                                    <?php echo $form->hiddenField($model, 'vat'); ?>
                                </td> 
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'sd'); ?></td>
                                <td><?php echo $form->textField($model, 'sd'); ?></td> 
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'cd'); ?></td>
                                <td><?php echo $form->textField($model, 'cd'); ?></td> 
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'rd'); ?></td>
                                <td><?php echo $form->textField($model, 'rd'); ?></td> 
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'ait'); ?></td>
                                <td><?php echo $form->textField($model, 'ait'); ?></td> 
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'atv'); ?></td>
                                <td><?php echo $form->textField($model, 'atv'); ?></td> 
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'tti'); ?></td>
                                <td><?php echo $form->textField($model, 'tti'); ?></td> 
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'exd'); ?></td>
                                <td><?php echo $form->textField($model, 'exd'); ?></td> 
                            </tr>
                        </table>
                    </fieldset>
                </td>                
            </tr>
        </table>
    </fieldset>

    <fieldset class="tblFooters">
        <span id="ajaxLoaderMRProd" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></span>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Update', array( 'id'=>'updateBtn')); ?>
    </fieldset>
</div>
 
<?php $this->endWidget(); ?>
<script>
    function calculateAmount(element) {
        var base_tp = parseFloat(element.parents('tr').find('.base_tp').val());
        if (isNaN(base_tp))
            base_tp = 0;
        var mrp = parseFloat(element.parents('tr').find('.mrp').val());
        if (isNaN(mrp))
            mrp = 0;

        var result = mrp - base_tp;
        var gps = ((result / mrp) * 100).toFixed(2);
        if (gps >= 0) {
            element.parents('tr').find('.gp_percent').val((gps));
        }
    }
</script>

<style>
    input[type=text], select{
        width:340px !important;
    }
    input[type=text] {
        width: 80%;
    }
    textarea {
        float: left;
        height:50px;
    }
    fieldset legend {
        padding: 3px 5px !important;
    }
    textarea {
        border: 1px solid #AAA;
    }
</style>
<script>
    $("#ProdModels_sub_vat_id").change(function () {
        var dataVal = $("#ProdModels_sub_vat_id option:selected").text();
        $("#ProdModels_vat").val(dataVal);
    });
    $("#updateBtn").click(function () {
      if ($('#ProdModels_sub_vat_id').val() == '') {
            alertify.alert("Please select VAT");
            return false;
        }
        else{
             $("#ajaxLoaderMRProd").show();
        }
    });
</script>