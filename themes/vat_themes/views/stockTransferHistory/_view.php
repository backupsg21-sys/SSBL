<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('inventory_id')); ?>:</b>
	<?php echo CHtml::encode($data->inventory_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sent_from_store_id')); ?>:</b>
	<?php echo CHtml::encode($data->sent_from_store_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sent_qty')); ?>:</b>
	<?php echo CHtml::encode($data->sent_qty); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('received_to_store_id')); ?>:</b>
	<?php echo CHtml::encode($data->received_to_store_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('received_qty')); ?>:</b>
	<?php echo CHtml::encode($data->received_qty); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('received_date')); ?>:</b>
	<?php echo CHtml::encode($data->received_date); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('is_received')); ?>:</b>
	<?php echo CHtml::encode($data->is_received); ?>
	<br />

	*/ ?>

</div>