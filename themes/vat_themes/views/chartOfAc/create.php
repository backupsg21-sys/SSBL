<?php
$this->breadcrumbs=array(
	'Chart Of Acs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ChartOfAc', 'url'=>array('index')),
	array('label'=>'Manage ChartOfAc', 'url'=>array('admin')),
);
?>

<h1>Create ChartOfAc</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>