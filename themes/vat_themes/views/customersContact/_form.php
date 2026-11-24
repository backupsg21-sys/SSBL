<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'customers-contact-form',
            'action' => $this->createUrl('customersContact/create'),
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>


<div id="formResult" class="ajaxTargetDiv">

</div>
<div id="formResultError" class="ajaxTargetDivErr">

</div>

<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New Contact' : 'Update Contact'); ?></legend>
        <fieldset>
            <table>
                <tr>
                    <td><?php echo $form->labelEx($model,'district'); ?></td>
                    <td><?php echo $form->textField($model,'district',array('maxlength'=>255)); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model,'customer_name'); ?></td>
                    <td><?php echo $form->textField($model,'customer_name',array('maxlength'=>255)); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model,'customer_name'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model,'contact_one'); ?></td>
                    <td><?php echo $form->textField($model,'contact_one',array('maxlength'=>20)); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model,'contact_one'); ?></td>
                </tr>
                
                <tr>
                    <td> <?php echo $form->labelEx($model,'contact_two'); ?></td>
                    <td><?php echo $form->textField($model,'contact_two',array('maxlength'=>20)); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model,'contact_two'); ?></td>
                </tr>
                
                <tr>
                    <td> <?php echo $form->labelEx($model,'contact_three'); ?></td>
                    <td><?php echo $form->textField($model,'contact_three',array('maxlength'=>50)); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model,'contact_three'); ?></td>
                </tr>
                
                <tr>
                    <td> <?php echo $form->labelEx($model,'remarks'); ?></td>
                    <td><?php echo $form->textField($model,'remarks',array('maxlength'=>50)); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model,'remarks'); ?></td>
                </tr>
            </table>
	</fieldset>
      <table>
            <tr>
                <td>
                    <div id="ajaxLoader" style="display: none;">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" />
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>

    <fieldset class="tblFooters">
        <?php
        echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('customersContact/create', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                    $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#customers-contact-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $.fn.yiiGridView.update("customers-contact-grid", {
                                data: $(this).serialize()
                        });
                    }else{
                        //$("#formResultError").html("Data not saved. Please solve the following errors.");
                        $.each(data, function(key, val) {
                            $("#customers-contact-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#customers-contact-form #"+key+"_em_").show();
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

<?php $this->endWidget(); ?>
