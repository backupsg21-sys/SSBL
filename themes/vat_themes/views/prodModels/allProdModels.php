
<?php
$allProdItems=ProdModels::model()->findAll(array('order' => 'id DESC'));
echo CHtml::tag('option', array('value' => ''), 'Select', true);
foreach ($allProdItems as $api) {
    echo CHtml::tag('option', array('value' => $api->id), CHtml::encode($api->model_name), true);
}
?>
