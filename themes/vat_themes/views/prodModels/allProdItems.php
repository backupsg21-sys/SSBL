
<?php
$allProdItems=ProdItems::model()->findAll(array('order' => 'id DESC'));
echo CHtml::tag('option', array('value' => ''), 'Select', true);
foreach ($allProdItems as $api) {
    echo CHtml::tag('option', array('value' => $api->id), CHtml::encode($api->item_name), true);
}
?>
