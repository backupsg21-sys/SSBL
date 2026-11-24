<?php
$this->breadcrumbs=array(
	'Adjustments'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Adjustments', 'url'=>array('index')),
	array('label'=>'Create Adjustments', 'url'=>array('create')),
	array('label'=>'Update Adjustments', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Adjustments', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Adjustments', 'url'=>array('admin')),
);
?>

<h1>View Adjustments #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'sl_no',
		'payment_date',
		'year',
		'month',
		'treasury_purpose',
		'challan_no',
		'amount',
		'payment_method',
		'bank_id',
		'branch',
		'account_name',
		'account_number',
		'cheque_date',
		'cheque_number',
		'bank_remarks',
		'customer_name',
		'address',
		'note',
		'description',
		'created_datetime',
		'created_by',
		'updated_datetime',
		'updated_by',
	),
)); ?>
