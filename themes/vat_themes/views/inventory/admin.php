<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('inventory-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php
if ($model->isNewRecord) {
    echo $this->renderPartial('_form', array('model' => $model));
} else {
    echo $this->renderPartial('_form2', array('model' => $model));
}
?>

<div style="float: left; width: 100%;">
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <?php echo "<div class='flash-error'>" . Yii::app()->user->getFlash('error') . '</div>'; ?>
    <?php endif; ?>
</div>

<fieldset style="float: left; width: 98%;">
    <legend>Manage Inventory Stock Record</legend>
    <div class="search-form">
        <?php //echo $this->renderPartial('_search', array('model'=>$model));  ?>
    </div>
    <b><font color="red">?</font></b> Agreed means, the row data was updated by agreeing with the condition if the value
    is 1.
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'inventory-grid',
        'dataProvider' => $model->search(),
        'mergeColumns' => array('transaction_date'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
        'columns' => array(
            array(
                'name' => 'transaction_date',
                'footer' => "Total =",
                'footerHtmlOptions' => array(
                    'colspan' => '7',
                    'style' => '
                        background-color: #98B8E8;
                        text-align: right;
                        font-size: 12px;
                        font-weight: bold;'
                ),
            ),
            array(
                'name' => 'store_id',
                'value' => 'Stores::model()->nameOfThis($data->store_id)',
                'filter' => CHtml::listData(Stores::model()->findAll(['order' => 'store_name']), 'id', 'store_name'),
                'footerHtmlOptions' => array('style' => 'display: none;'),
            ),
            array(
                'name' => 'item_id',
                'value' => 'ProdItems::model()->itemName($data->item_id)',
                'filter' => CHtml::listData(ProdItems::model()->findAll(['order' => 'item_name']), 'id', 'item_name'),
                'footerHtmlOptions' => array('style' => 'display: none;'),
            ),
            array(
                'name' => 'brand_id',
                'value' => 'ProdBrands::model()->brandName($data->brand_id)',
                'filter' => CHtml::listData(ProdBrands::model()->findAll(['order' => 'brand_name']), 'id', 'brand_name'),
                'footerHtmlOptions' => array('style' => 'display: none;'),
            ),
            array(
                'name' => 'model_id',
                'value' => 'ProdModels::model()->modelName($data->model_id)',
                'footerHtmlOptions' => array('style' => 'display: none;'),
            ),
            array(
                'header' => 'Part No/ Code',
                'name' => 'code',
                'footerHtmlOptions' => array('style' => 'display: none;'),
            ),
            array(
                'header' => 'Unit',
                'name' => 'unit_id',
                'value' => 'Units::model()->unitLabel($data->unit_id)',
                'filter' => CHtml::listData(Units::model()->findAll(['order' => 'label']), 'id', 'label'),
                'footerHtmlOptions' => array('style' => 'display: none;'),
            ),
            /*
              array(
              'name' => 'model_id',
              'header'=>'Additional Info',
              'value' => 'CHtml::encode(ProdModels::model()->optionalImportantInfos($data->model_id))',
              'filter' => '',
              'footerHtmlOptions'=>array('style'=>'display: none;'),
              ), */
            array(
                'name' => 'stock_in',
                'footer' => Inventory::model()->getTotals($model->search()->getKeys(), Inventory::STOCKIN),
                'footerHtmlOptions' => array(
                    'style' => '
                        background-color: #98B8E8;
                        font-size: 12px;
                        font-weight: bold;'
                ),
            ),
            array(
                'name' => 'stock_out',
                'footer' => Inventory::model()->getTotals($model->search()->getKeys(), Inventory::STOCKOUT),
                'footerHtmlOptions' => array(
                    'style' => '
                        background-color: #98B8E8;
                        font-size: 12px;
                        font-weight: bold;'
                ),
            ),
          //  'is_agree',
            array(
                'name' => 'is_opening',
                'value' => 'Lookup::item("yes_no",$data->is_opening)',
                'filter' => Lookup::items('yes_no'),
            ),
            /*array
            (
                'header' => 'Options',
                'template' => '{delete}', //{update}
                'class' => 'CButtonColumn',
                'footerHtmlOptions' => array('style' => 'background-color: #98B8E8;'),
            ),*/
        )
    ));
    ?>
</fieldset>

