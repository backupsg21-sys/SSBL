<?php
$this->breadcrumbs=array(
	'Payment Receipts'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List PaymentReceipt', 'url'=>array('index')),
	array('label'=>'Create PaymentReceipt', 'url'=>array('create')),
	array('label'=>'Update PaymentReceipt', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete PaymentReceipt', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PaymentReceipt', 'url'=>array('admin')),
);
?>

<h1>View PaymentReceipt #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'supplier_id',
		'amount',
		'date',
		'remarks',
	),
)); ?>
