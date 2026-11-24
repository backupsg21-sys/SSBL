
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'inventory_id',
		'sell_price',
		'start_date',
		'end_date',
		'is_active',
	),
)); ?>
