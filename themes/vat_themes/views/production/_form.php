<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'material-recipe-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'model_id'); ?>
		<?php echo $form->textField($model,'model_id'); ?>
		<?php echo $form->error($model,'model_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'model_qty'); ?>
		<?php echo $form->textField($model,'model_qty'); ?>
		<?php echo $form->error($model,'model_qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rm_model_id'); ?>
		<?php echo $form->textField($model,'rm_model_id'); ?>
		<?php echo $form->error($model,'rm_model_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rm_qty'); ?>
		<?php echo $form->textField($model,'rm_qty'); ?>
		<?php echo $form->error($model,'rm_qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'waste_qty'); ?>
		<?php echo $form->textField($model,'waste_qty'); ?>
		<?php echo $form->error($model,'waste_qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'total_price'); ?>
		<?php echo $form->textField($model,'total_price'); ?>
		<?php echo $form->error($model,'total_price'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->