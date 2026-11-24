<?php
$this->breadcrumbs=array(
	'Payment Receipts',
);

$this->menu=array(
	array('label'=>'Create PaymentReceipt', 'url'=>array('create')),
	array('label'=>'Manage PaymentReceipt', 'url'=>array('admin')),
);
?>

<h1>Payment Receipts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
