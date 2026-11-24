<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('stock-transfer-history-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="statusMsg"></div>
<fieldset style="float: left; width: 98%;">
    <legend>Manage stock-transfer-history</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'stock-transfer-history-grid',
        'dataProvider' => $model->search(),
        'mergeColumns' => array('sent_date'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'columns' => array(
            array(
                'name' => 'sent_from_store_id',
                'value' => 'CHtml::encode(Stores::model()->storeName($data->sent_from_store_id))',
                'filter' => CHtml::listData(Stores::model()->findAll(), 'id', 'store_name'),
            ),
            'sent_qty',
            'sent_date',
            array(
                'name' => 'item_id',
                'value' => 'CHtml::encode(ProdItems::model()->itemName($data->item_id))',
                'filter' => CHtml::listData(ProdItems::model()->findAll(), 'id', 'item_name'),
            ),
            array(
                'name' => 'brand_id',
                'value' => 'CHtml::encode(ProdBrands::model()->brandName($data->brand_id))',
                'filter' => CHtml::listData(ProdBrands::model()->findAll(), 'id', 'brand_name'),
            ),
            array(
                'name' => 'model_id',
                'value' => 'CHtml::encode(ProdModels::model()->modelName($data->model_id))',
            ),
            'code',
            array(
                'name' => 'received_to_store_id',
                'value' => 'CHtml::encode(Stores::model()->storeName($data->received_to_store_id))',
                'filter' => CHtml::listData(Stores::model()->findAll(), 'id', 'store_name'),
            ),
            array(
                'name' => 'receive_location_id',
                'value' => 'CHtml::encode(Location::model()->nameOfThis($data->receive_location_id))',
            ),
            'received_qty',
            'received_date',
            array(
                'name' => 'is_received',
                'value' => 'CHtml::encode(StockTransferHistory::model()->imgRepresentationOfThisStatus($data->is_received))',
                'filter' => Lookup::items('is_received'),
            ),
            array
                (
                'header' => 'Options',
                'template' => '{delete}',
                'class' => 'CButtonColumn',
                'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").html(data); }'
            ),
        )
    ));
    ?>
</fieldset>
