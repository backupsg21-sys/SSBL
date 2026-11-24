<?php
$this->breadcrumbs=array(
	'Adjustments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Adjustments', 'url'=>array('index')),
	array('label'=>'Manage Adjustments', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>