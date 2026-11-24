<?php
$this->breadcrumbs=array(
	'Chart Of Acs'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ChartOfAc', 'url'=>array('index')),
	array('label'=>'Create ChartOfAc', 'url'=>array('create')),
	array('label'=>'View ChartOfAc', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ChartOfAc', 'url'=>array('admin')),
);
?>

<h1>Update ChartOfAc <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>