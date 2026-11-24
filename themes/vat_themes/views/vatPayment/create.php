<?php
$this->breadcrumbs=array(
	'Sell Items'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SellItems', 'url'=>array('index')),
	array('label'=>'Manage SellItems', 'url'=>array('admin')),
);
?>

<h1>Create SellItems</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>