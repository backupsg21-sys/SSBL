<?php
$this->breadcrumbs=array(
	'Sell Prices',
);

$this->menu=array(
	array('label'=>'Create SellPrice', 'url'=>array('create')),
	array('label'=>'Manage SellPrice', 'url'=>array('admin')),
);
?>

<h1>Sell Prices</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
