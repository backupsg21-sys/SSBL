
<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'users-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>
<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New Authitem' : 'Update Authitem: ' . $model->name); ?></legend>
        <table>
            <tr>
                <td><?php echo $form->labelEx($model, 'name'); ?></td>
                <td><?php echo $form->textField($model, 'name', array('maxlength' => 50)); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'name'); ?></td>
            </tr>

            <tr>
                <td><?php echo $form->labelEx($model, 'description'); ?></td>
                <td><?php echo $form->textField($model, 'description', array('maxlength' => 50)); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'description'); ?></td>
            </tr>
            
			<tr>
                <td><?php echo $form->labelEx($model, 'status'); ?></td>
                <td>
                    <?php $activeStatus = array(1 => 'Active', 0 => 'Inactive'); ?>
                    <?php echo $form->dropDownList($model, 'status', $activeStatus) ?> 
                </td>            
            </tr>

            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'status'); ?></td>
            </tr>

        </table>
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