<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stock-transfer-history-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'inventory_id'); ?>
		<?php echo $form->textField($model,'inventory_id'); ?>
		<?php echo $form->error($model,'inventory_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sent_from_store_id'); ?>
		<?php echo $form->textField($model,'sent_from_store_id'); ?>
		<?php echo $form->error($model,'sent_from_store_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sent_qty'); ?>
		<?php echo $form->textField($model,'sent_qty'); ?>
		<?php echo $form->error($model,'sent_qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'received_to_store_id'); ?>
		<?php echo $form->textField($model,'received_to_store_id'); ?>
		<?php echo $form->error($model,'received_to_store_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'received_qty'); ?>
		<?php echo $form->textField($model,'received_qty'); ?>
		<?php echo $form->error($model,'received_qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'received_date'); ?>
		<?php echo $form->textField($model,'received_date'); ?>
		<?php echo $form->error($model,'received_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_received'); ?>
		<?php echo $form->textField($model,'is_received'); ?>
		<?php echo $form->error($model,'is_received'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->