<?php
$this->breadcrumbs=array(
	'Sell Items',
);

$this->menu=array(
	array('label'=>'Create SellItems', 'url'=>array('create')),
	array('label'=>'Manage SellItems', 'url'=>array('admin')),
);
?>

<h1>Sell Items</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
