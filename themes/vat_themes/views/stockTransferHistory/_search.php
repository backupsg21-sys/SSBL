<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'inventory_id'); ?>
		<?php echo $form->textField($model,'inventory_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sent_from_store_id'); ?>
		<?php echo $form->textField($model,'sent_from_store_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sent_qty'); ?>
		<?php echo $form->textField($model,'sent_qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'received_to_store_id'); ?>
		<?php echo $form->textField($model,'received_to_store_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'received_qty'); ?>
		<?php echo $form->textField($model,'received_qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'received_date'); ?>
		<?php echo $form->textField($model,'received_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_received'); ?>
		<?php echo $form->textField($model,'is_received'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->