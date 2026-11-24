<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'prod-models-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'clientOptions' => array('validateOnSubmit' => false),
));
?>

<div class="formDiv">
    <fieldset>
        <legend><?php echo($model->isNewRecord ? 'Add New Product Info' : 'Update Product Info'); ?></legend>
        <table>
            <tr>
                <td style="width: 160px;"><?php echo $form->labelEx($model, 'item_id'); ?></td>
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
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'item_id'); ?></td>
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
                </td>
                <td>
                    <div id="ajaxLoaderDrop" style="display: none;"><img
                                src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/></div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'brand_id'); ?></td>
                <td></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'model_name'); ?></td>
                <td><?php echo $form->textField($model, 'model_name', array('maxlength' => 255)); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'model_name'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'code'); ?></td>
                <td><?php echo $form->textField($model, 'code', array('maxlength' => 255)); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'code'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'hs_code'); ?></td>
                <td><?php echo $form->textField($model, 'hs_code', array('maxlength' => 255)); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'hs_code'); ?></td>
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
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'mrp'); ?></td>
                <td><?php echo $form->textField($model, 'mrp'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'vat_calculation_method'); ?></td>
                <td colspan="2">
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
                <td>
                    <?php echo $form->labelEx($model, 'vat'); ?>
                </td>
                <td>
                    <?php
                    echo $form->dropDownList(
                        $model, 'sub_vat_id', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_standard'", 'order' => 'code ASC')), 'code', 'name'), array(
                        'prompt' => 'Select',
                        'style' => 'width:280px;',
                    ));
                    ?>
                    <?php echo $form->hiddenField($model, 'vat'); ?>
                </td>
            </tr>

        </table>
    </fieldset>

    <fieldset class="tblFooters">
        <span id="ajaxLoaderMRProd" style="display: none;"><img
                    src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/></span>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Update', array('id' => 'createBtn')); ?>
    </fieldset>
</div>
<script type="text/javascript">
    function loadingDivDisplay() {
        $("#ajaxLoaderMRProd").show();
    }
</script>
<?php $this->endWidget(); ?>
<script>
    $("#ProdModels_sub_vat_id").change(function () {
        var dataVal = $("#ProdModels_sub_vat_id option:selected").text();
        $("#ProdModels_vat").val(dataVal);
    });
    $("#createBtn").click(function () {
        if ($('#ProdModels_sub_vat_id').val() == '') {
            alertify.alert("Please select VAT");
            return false;
        } else {
            $("#ajaxLoaderMRProd").show();
        }
    });
</script>
<style>
    .divForForm input[type=text] {
        width: 280px;
    }

    .divForForm select {
        width: 280px;
    }
</style>
