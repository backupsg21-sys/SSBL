<?php
$this->breadcrumbs=array(
	'Prod Models'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ProdModels', 'url'=>array('index')),
	array('label'=>'Manage ProdModels', 'url'=>array('admin')),
);
?>

<h1>Create ProdModels</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>