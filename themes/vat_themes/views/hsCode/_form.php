<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'hs-code-form',
    'action' => $this->createUrl('hsCode/create'),
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
        <legend><?php echo ($model->isNewRecord ? 'Add New HS Code' : 'Update HS Code'); ?></legend>
        <table style="width: 70%">
            <tr>
                <td style="width: 50%">
                    <table style="width: 100%">
                        <tr>
                            <td><?php echo $form->labelEx($model, 'hs_code'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'hs_code'); ?>
                                <?php echo $form->error($model, 'hs_code'); ?>
                            </td>            
                        </tr>
                       <tr>
                            <td><?php echo $form->labelEx($model, 'country_id'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList(
                                        $model, 'country_id', CHtml::listData(Countries::model()->findAll(array('order' => 'country ASC')), 'id', 'country'), array(
                                    'prompt' => 'Select',
                                ));
                                ?>
                                <?php echo $form->error($model, 'country_id'); ?>
                            </td>            
                        </tr>
                         
                        
                        <tr>
                            <td><?php echo $form->labelEx($model, 'cd'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'cd'); ?>
                                <?php echo $form->error($model, 'cd'); ?>
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
                            <td><?php echo $form->labelEx($model, 'vat'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'vat'); ?>
                                <?php echo $form->error($model, 'vat'); ?>
                            </td>            
                        </tr>
                         <tr>
                            <td><?php echo $form->labelEx($model, 'ait'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'ait'); ?>
                                <?php echo $form->error($model, 'ait'); ?>
                            </td>            
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'rd'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'rd'); ?>
                                <?php echo $form->error($model, 'rd'); ?>
                            </td>            
                        </tr>
                       
                    </table>
                </td>
                <td style="width: 50%">
                    <table style="width: 100%">
                        <tr>
                            <td><?php echo $form->labelEx($model, 'atv'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'atv'); ?>
                                <?php echo $form->error($model, 'atv'); ?>
                            </td>            
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'tti'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'tti'); ?>
                                <?php echo $form->error($model, 'tti'); ?>
                            </td>            
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'exd'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'exd'); ?>
                                <?php echo $form->error($model, 'exd'); ?>
                            </td>            
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'description'); ?></td>
                            <td>
                                <?php echo $form->textArea($model, 'description', array('maxlength' => 300, 'rows' => 6, 'cols' => 50)); ?>
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
        echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('hsCode/create', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#hs-code-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $.fn.yiiGridView.update("hs-code-grid", {
		data: $(this).serialize()
	});
                    }else{
                        //$("#formResultError").html("Data not saved. Please solve the following errors.");
                        $.each(data, function(key, val) {
                            $("#hs-code-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#hs-code-form #"+key+"_em_").show();
                        });
                    }       
                }',
            'beforeSend' => 'function(){ 
                
                $("#ajaxLoader").show();
             }'
        ));
        ?>
    </fieldset>
</div>
<style>
    select{
        width:74%;
    }
</style>
<?php $this->endWidget(); ?>
