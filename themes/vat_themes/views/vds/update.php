<?php
$this->breadcrumbs=array(
	'Vds'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Vds', 'url'=>array('index')),
	array('label'=>'Create Vds', 'url'=>array('create')),
	array('label'=>'View Vds', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Vds', 'url'=>array('admin')),
);
?>

<h1>Update Vds <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>