<?php
$this->breadcrumbs=array(
	'Adjustments',
);

$this->menu=array(
	array('label'=>'Create Adjustments', 'url'=>array('create')),
	array('label'=>'Manage Adjustments', 'url'=>array('admin')),
);
?>

<h1>Adjustments</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
