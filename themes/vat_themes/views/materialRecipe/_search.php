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
		<?php echo $form->label($model,'model_id'); ?>
		<?php echo $form->textField($model,'model_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'unit_id'); ?>
		<?php echo $form->textField($model,'unit_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'model_qty'); ?>
		<?php echo $form->textField($model,'model_qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rm_model_id'); ?>
		<?php echo $form->textField($model,'rm_model_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rm_qty'); ?>
		<?php echo $form->textField($model,'rm_qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'waste_qty'); ?>
		<?php echo $form->textField($model,'waste_qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'total_price'); ?>
		<?php echo $form->textField($model,'total_price'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->