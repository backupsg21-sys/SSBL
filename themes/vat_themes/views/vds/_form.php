<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'vds-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'purchase_id'); ?>
		<?php echo $form->textField($model,'purchase_id'); ?>
		<?php echo $form->error($model,'purchase_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'po_no'); ?>
		<?php echo $form->textField($model,'po_no'); ?>
		<?php echo $form->error($model,'po_no'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vds'); ?>
		<?php echo $form->textField($model,'vds'); ?>
		<?php echo $form->error($model,'vds'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vds_type'); ?>
		<?php echo $form->textField($model,'vds_type'); ?>
		<?php echo $form->error($model,'vds_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sales_id'); ?>
		<?php echo $form->textField($model,'sales_id'); ?>
		<?php echo $form->error($model,'sales_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'so_no'); ?>
		<?php echo $form->textField($model,'so_no'); ?>
		<?php echo $form->error($model,'so_no'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'parts_service'); ?>
		<?php echo $form->textField($model,'parts_service'); ?>
		<?php echo $form->error($model,'parts_service'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'supplier_id'); ?>
		<?php echo $form->textField($model,'supplier_id'); ?>
		<?php echo $form->error($model,'supplier_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'customer_id'); ?>
		<?php echo $form->textField($model,'customer_id'); ?>
		<?php echo $form->error($model,'customer_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'issue_date'); ?>
		<?php echo $form->textField($model,'issue_date'); ?>
		<?php echo $form->error($model,'issue_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_paid'); ?>
		<?php echo $form->textField($model,'is_paid'); ?>
		<?php echo $form->error($model,'is_paid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_datetime'); ?>
		<?php echo $form->textField($model,'created_datetime'); ?>
		<?php echo $form->error($model,'created_datetime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_by'); ?>
		<?php echo $form->textField($model,'created_by'); ?>
		<?php echo $form->error($model,'created_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updated_datetime'); ?>
		<?php echo $form->textField($model,'updated_datetime'); ?>
		<?php echo $form->error($model,'updated_datetime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updated_by'); ?>
		<?php echo $form->textField($model,'updated_by'); ?>
		<?php echo $form->error($model,'updated_by'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->