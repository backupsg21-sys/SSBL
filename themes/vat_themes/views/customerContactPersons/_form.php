<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'customer-contact-persons-form',
            'action' => $this->createUrl('customerContactPersons/create'),
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>

<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New Contact Person Info' : 'Update Contact Person Info'); ?></legend>
        <table>
            <tr>
                    <td><?php echo $form->labelEx($model, 'company_id'); ?></td>
                    <td>
                        <?php
                        echo $form->dropDownList(
                                $model, 'company_id', CHtml::listData(Customers::model()->findAll(array('order' => 'id DESC')), 'id', 'company_name'), array(
                            'prompt' => 'Select',
                        ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'company_id'); ?></td>
                </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'contact_person_name'); ?></td>
                <td><?php echo $form->textField($model, 'contact_person_name', array('maxlength' => 255)); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'contact_person_name'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'designation_id'); ?></td>
                <td><?php echo $form->textField($model, 'designation_id', array('maxlength' => 255)); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'designation_id'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'contact_number1'); ?></td>
                <td><?php echo $form->textField($model, 'contact_number1', array('maxlength' => 20)); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'contact_number1'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'contact_number2'); ?></td>
                <td><?php echo $form->textField($model, 'contact_number2', array('maxlength' => 20)); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'contact_number2'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'contact_number3'); ?></td>
                <td><?php echo $form->textField($model, 'contact_number3', array('maxlength' => 20)); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'contact_number3'); ?></td>
            </tr>
             <tr>
                <td><?php echo $form->labelEx($model, 'email'); ?></td>
                <td><?php echo $form->textField($model, 'email', array('maxlength' => 50)); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'email'); ?></td>
            </tr>
        </table>
        <div id="ajaxLoader" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
    </fieldset>

    <fieldset class="tblFooters">
        <?php
        echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('customerContactPersons/create', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#formResultError").hide();
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#customer-contact-persons-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $.fn.yiiGridView.update("customer-contact-persons-grid", {
		data: $(this).serialize()
	});
                    }else{
                        $("#formResultError").html("Data not saved. Please solve the above errors.");
                        $.each(data, function(key, val) {
                            $("#customer-contact-persons-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#customer-contact-persons-form #"+key+"_em_").show();
                        });
                    }       
                }',
            'beforeSend' => 'function(){                        
                $("#ajaxLoader").show();
             }'
        ));
        ?>
    </fieldset>
    
<div id="formResult" class="ajaxTargetDiv">

</div>
<div id="formResultError" class="ajaxTargetDivErr">

</div>

</div>

<?php $this->endWidget(); ?>
