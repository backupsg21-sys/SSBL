<?php
$this->breadcrumbs=array(
	'Vds'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Vds', 'url'=>array('index')),
	array('label'=>'Create Vds', 'url'=>array('create')),
	array('label'=>'Update Vds', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Vds', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Vds', 'url'=>array('admin')),
);
?>

<h1>View Vds #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'purchase_id',
		'po_no',
		'vds',
		'vds_type',
		'sales_id',
		'so_no',
		'parts_service',
		'supplier_id',
		'customer_id',
		'issue_date',
		'is_paid',
		'created_datetime',
		'created_by',
		'updated_datetime',
		'updated_by',
	),
)); ?>
