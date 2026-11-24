<?php
$this->breadcrumbs=array(
	'Vds',
);

$this->menu=array(
	array('label'=>'Create Vds', 'url'=>array('create')),
	array('label'=>'Manage Vds', 'url'=>array('admin')),
);
?>

<h1>Vds</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
