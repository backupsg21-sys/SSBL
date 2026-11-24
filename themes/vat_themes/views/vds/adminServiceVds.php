<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('vds-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
$this->pageTitle = "Service VDS Follow-up";
?>
<div style="float: left; width: 100%;">
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <?php echo "<div class='flash-error'>" . Yii::app()->user->getFlash('error') . '</div>'; ?>
    <?php endif; ?>
</div>

<fieldset style="float: left; width: 98%;">
    <legend>Service VDS List</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'vds-grid',
       'dataProvider' => $model->searchAdminServiceVds(),
        'mergeColumns' => array('po_no'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
        'columns' => array(
            array(
                'name' => 'po_no',
                'value' => 'CHtml::encode(VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::PURCHASE_ORDER).("-".$data->po_no))',
            ),
            'issue_date',
            array(
                'name' => 'vds',
                'value' => 'CHtml::encode($data->vds)',
            ),
            array(
                'name' => 'supplier_id',
                'value' => 'CHtml::encode(Suppliers::model()->supplierName($data->supplier_id))',
                'filter' => CHtml::listData(Suppliers::model()->findAll(array('order' => 'name ASC')), "id", "name"),
            ),
            array(
                'header' => 'BIN',
                'name' => 'bin',
                'value' => 'CHtml::encode(Suppliers::model()->supplierBin($data->supplier_id))',
            ),
        )
    ));
    ?>
</fieldset>
