<?php
$this->breadcrumbs=array(
	'Vds'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Vds', 'url'=>array('index')),
	array('label'=>'Manage Vds', 'url'=>array('admin')),
);
?>

<h1>Create Vds</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>