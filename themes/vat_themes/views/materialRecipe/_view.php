<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('model_id')); ?>:</b>
	<?php echo CHtml::encode($data->model_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('unit_id')); ?>:</b>
	<?php echo CHtml::encode($data->unit_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('model_qty')); ?>:</b>
	<?php echo CHtml::encode($data->model_qty); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rm_model_id')); ?>:</b>
	<?php echo CHtml::encode($data->rm_model_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rm_qty')); ?>:</b>
	<?php echo CHtml::encode($data->rm_qty); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('waste_qty')); ?>:</b>
	<?php echo CHtml::encode($data->waste_qty); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('total_price')); ?>:</b>
	<?php echo CHtml::encode($data->total_price); ?>
	<br />

	*/ ?>

</div>