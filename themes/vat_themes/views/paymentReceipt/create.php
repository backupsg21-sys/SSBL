<?php
$this->breadcrumbs=array(
	'Payment Receipts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PaymentReceipt', 'url'=>array('index')),
	array('label'=>'Manage PaymentReceipt', 'url'=>array('admin')),
);
?>

<h1>Create PaymentReceipt</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>