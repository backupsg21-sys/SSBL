<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'customers-contact-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>

<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New Contact' : 'Update Contact'); ?></legend>
        <fieldset>
            <legend>Contact</legend>
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
    </fieldset>

   <fieldset class="tblFooters">
       <span id="ajaxLoaderMR" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></span>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Update', array('onclick'=>'loadingDivDisplay();')); ?>
    </fieldset>
</div>
<script type="text/javascript">
    function loadingDivDisplay(){
       $("#ajaxLoaderMR").show();
    }
</script>
<?php $this->endWidget(); ?>
