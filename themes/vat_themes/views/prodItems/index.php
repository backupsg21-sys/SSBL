<?php
$this->breadcrumbs=array(
	'Prod Items',
);

$this->menu=array(
	array('label'=>'Create ProdItems', 'url'=>array('create')),
	array('label'=>'Manage ProdItems', 'url'=>array('admin')),
);
?>

<h1>Prod Items</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
