<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('sell-items-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div style="float: left; width: 100%;">
<?php if (Yii::app()->user->hasFlash('error')): ?>
    <?php echo "<div class='flash-error'>".Yii::app()->user->getFlash('error').'</div>'; ?>
<?php endif; ?>
</div>
<fieldset style="float: left; width: 98%;">
    <legend>Undo Canceled Orders</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'sell-items-grid',
        'dataProvider' => $model->searchUndoCancel(),
        'mergeColumns' => array('so_no'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
        'columns' => array(
           array(
                'name'=>'so_no',
                'value'=>'CHtml::encode(VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SELL_ORDER).($data->so_no))',
            ),
            array(
                'name'=>'customer_id',
                'value'=>'Customers::model()->customerName($data->customer_id)',
                'filter'=>CHtml::listData(Customers::model()->findAll(array('order' => 'id DESC')), "id", "company_name"),
            ),
            'date_of_sell',
            array(
                'name' => 'item_id',
                'value' => 'ProdItems::model()->itemName($data->item_id)',
                'filter' => CHtml::listData(ProdItems::model()->findAll(), 'id', 'item_name'),
            ),
            array(
                'name' => 'brand_id',
                'value' => 'ProdBrands::model()->brandName($data->brand_id)',
                'filter' => CHtml::listData(ProdBrands::model()->findAll(), 'id', 'brand_name'),
            ),
            array(
                'name' => 'model_id',
                'value' => 'ProdModels::model()->modelName($data->model_id)',
                'filter' => CHtml::listData(ProdModels::model()->findAll(), 'id', 'model_name'),
            ),
            array(
                'name' => 'model_id',
                'header'=>'Additional Info',
                'value' => 'ProdModels::model()->optionalImportantInfos($data->model_id)',
                'filter' => '',
            ),
            'code',
            'sell_price',
            'discount',
            'sell_qty',
            array(
                'name' => 'lineTotalWithDiscount',
                'header' => 'Line Total',
                'value' => 'CHtml::encode(SellItems::model()->lineTotalWithDiscount($data->sell_qty, $data->sell_price, $data->discount))',
                'filter' => '',
            ),
            array(
                'name'=>'sell_month',
                'header'=>'Warranty Left (M)',
                'value'=>'CHtml::encode(ProdModels::model()->warrantyMonthRemaining($data->model_id, $data->date_of_sell))',
                'filter'=>'',
            ),
            array
                (
                'header' => 'Options',
                'template' => '{undo}',
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'undo' => array(
                        'label' => 'Undo',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/undo.ico',
                        'url' => 'Yii::app()->controller->createUrl("undoCancell",array("so_no"=>$data->so_no))',
                        'click' => "function(){
                                       $.fn.yiiGridView.update('sell-items-grid', {
                                                type:'POST',
                                                url:$(this).attr('href'),
                                                success:function(data) {
                                                      alertify.alert(data);
                                                      $.fn.yiiGridView.update('sell-items-grid'); 
                                                }
                                            })
                                            return false;
                              }
                     ",
                    ),
                )
            ),
        )
    ));
    ?>
</fieldset>