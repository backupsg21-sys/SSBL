<?php
$this->breadcrumbs=array(
	'Stock Transfer Histories'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List StockTransferHistory', 'url'=>array('index')),
	array('label'=>'Create StockTransferHistory', 'url'=>array('create')),
	array('label'=>'Update StockTransferHistory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete StockTransferHistory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage StockTransferHistory', 'url'=>array('admin')),
);
?>

<h1>View StockTransferHistory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'inventory_id',
		'sent_from_store_id',
		'sent_qty',
		'received_to_store_id',
		'received_qty',
		'received_date',
		'is_received',
	),
)); ?>
