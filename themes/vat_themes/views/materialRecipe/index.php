<?php
$this->breadcrumbs=array(
	'Material Recipes',
);

$this->menu=array(
	array('label'=>'Create MaterialRecipe', 'url'=>array('create')),
	array('label'=>'Manage MaterialRecipe', 'url'=>array('admin')),
);
?>

<h1>Material Recipes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
