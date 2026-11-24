<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'services-form',
    'action' => $this->createUrl('services/create'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>

<div id="formResult" class="ajaxTargetDiv">
</div>
<div id="formResultError" class="ajaxTargetDivErr">
</div>

<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New Services' : 'Update Services'); ?></legend>
        <table>
            <tr>
                <td style="width: 400px;">
                    <table style="width: 100%">
                        <tr>
                            <td width="120px"><?php echo $form->labelEx($model, 'name'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'name'); ?>
                                <?php echo $form->error($model, 'name'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'code'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'code'); ?>
                                <?php echo $form->error($model, 'code'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'category_id'); ?></td>
                            <td>
                                <?php echo $form->dropDownList($model, 'category_id', Lookup::items('ServiceCategory'), array('prompt' => 'select')); ?>
                                <?php echo $form->error($model, 'category_id'); ?>
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
                                <?php echo $form->error($model, 'unit_id'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'mrp'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'mrp'); ?>
                                <?php echo $form->error($model, 'mrp'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'purchase_price'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'purchase_price'); ?>
                                <?php echo $form->error($model, 'purchase_price'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'sell_price'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'sell_price'); ?>
                                <?php echo $form->error($model, 'sell_price'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'rebatable'); ?></td>
                            <td>
                                <?php echo $form->checkBox($model,'rebatable',array('value' => '1', 'uncheckValue'=>'0')); ?>
                                <?php echo $form->error($model, 'rebatable'); ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top;">
                    <table style="width: 100%">
                        <tr>
                            <td width="120px"><?php echo $form->labelEx($model, 'purchase_discount'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'purchase_discount'); ?>
                                <?php echo $form->error($model, 'purchase_discount'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'sell_discouont'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'sell_discouont'); ?>
                                <?php echo $form->error($model, 'sell_discouont'); ?>
                            </td>
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
                                                $("#Services_sub_vat_id").html(data.subVatList);
                                         }',
                                        'data' => array(
                                            'subVatId' => 'js:jQuery("#Services_vat_calculation_method").val()',
                                        ),
                                        'beforeSend' => 'function(){
                                                    //document.getElementById("Services_sub_vat_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                                        'complete' => 'function(){
                                            //document.getElementById("Services_sub_vat_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
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
                                    $model, 'sub_vat_id', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_standard'", 'order'=>'code ASC')), 'code', 'name'), array(
                                    'prompt' => 'Select',
                                ));
                                ?>
                                <?php echo $form->hiddenField($model, 'vat'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'sd'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'sd'); ?>
                                <?php echo $form->error($model, 'sd'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'description'); ?></td>
                            <td>
                                <?php echo $form->textArea($model, 'description', array('maxlength' => 300, 'rows' => 6, 'cols' => 50, 'style'=>'height: 60px;')); ?>
                                <?php echo $form->error($model, 'description'); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
        <div id="ajaxLoader" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
    </fieldset>

    <fieldset class="tblFooters">
        <?php
        echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('services/create', 'render' => true, array( 'id'=>'createBtn'))), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#services-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $.fn.yiiGridView.update("services-grid", {
		data: $(this).serialize()
	});
                    }else{
                        //$("#formResultError").html("Data not saved. Please solve the following errors.");
                        $.each(data, function(key, val) {
                            $("#services-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#services-form #"+key+"_em_").show();
                        });
                    }       
                }',
            'beforeSend' => 'function(){ 
                if($("#Services_sub_vat_id").val() == ""){                    
                    alertify.alert("Please select VAT");
                    return false;
                }
                $("#ajaxLoader").show();
             }'
        ));
        ?>
    </fieldset>
</div>
<style>
    .formDiv input[type=text], .formDiv select, .formDiv textarea{
        width:250px;
        border: 1px solid #AAA;
    }
</style>
<script>
    $("#Services_sub_vat_id").change(function () {
        var dataVal = $("#Services_sub_vat_id option:selected").text();
        $("#Services_vat").val(dataVal);
    });
</script>

<?php $this->endWidget(); ?>
