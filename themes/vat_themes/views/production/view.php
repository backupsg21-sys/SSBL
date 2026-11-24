<?php
$this->breadcrumbs=array(
	'Material Recipes'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List MaterialRecipe', 'url'=>array('index')),
	array('label'=>'Create MaterialRecipe', 'url'=>array('create')),
	array('label'=>'Update MaterialRecipe', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MaterialRecipe', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MaterialRecipe', 'url'=>array('admin')),
);
?>

<h1>View MaterialRecipe #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'model_id',
		'unit_id',
		'model_qty',
		'rm_model_id',
		'rm_qty',
		'waste_qty',
		'total_price',
	),
)); ?>
