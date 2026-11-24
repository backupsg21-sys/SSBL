<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('purchase-items-grid', {
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
<?php if ($model->isNewRecord) { ?>
    <?php echo $this->renderPartial('_form', array('model' => $model)); ?>
<?php } else { ?>
    <?php echo $this->renderPartial('_form2', array('model' => $model)); ?>
<?php } ?>
<?php
$condition = "issue_date!='0000-00-00' GROUP BY po_no";
$poData = PurchaseItems::model()->findAll(array('condition' => $condition,), 'id');
?>
<script>
    $(document).ready(function(){
        var poSuggest = [
<?php foreach ($poData as $pod): ?>
    <?php echo '"' . $pod->po_no . '"'; ?>,
<?php endforeach; ?>
                ];
        
                $( "#poForReport" ).autocomplete({
                    minLength: 0,
                    source: poSuggest
                });
            });
</script>
<div id="statusMsg"></div>

<fieldset style="float: left; width: 98%;">
    <legend>Print PR Voucher</legend>
    <table>
        <tr>
            <td>
                PR No
            </td>
            <td>
                <?php echo "<font style='color: red; font-weight: bold;'>".VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::PURCHASE_ORDER)."</font>"; ?><input type="text" name="poForReport" id="poForReport"/>
            </td>
            <td style="vertical-align: middle;"><div id="ajaxLoaderReport" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div></td>
            <td>
                <?php
                echo CHtml::ajaxLink(
                        "Preview", Yii::app()->createUrl('purchaseItems/poReportOfThis'), array(
                    'type' => 'POST',
                    'beforeSend' => "function(){
                       $('#ajaxLoaderReport').show();
                     }",
                    'success' => "function( data ){
                        $('#poReportDialogBox').dialog('open'); 
                        $('#AjFlashReportPo').html(data).show();                                                                 
                    }",
                    'complete' => "function(){
                                           $('#ajaxLoaderReport').hide(); 
                    }",
                    'data' => array(
                        'po' => 'js:jQuery("#poForReport").val()'
                        )
                    ), 
                    array(
                        'href' => Yii::app()->createUrl('purchaseItems/poReportOfThis'),
                        'class' => 'findBtn'
                    )
                );
                ?>
            </td>          
        </tr>
    </table>
</fieldset>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'poReportDialogBox',
    'options' => array(
        'title' => 'PR Voucher Preview',
        'autoOpen' => false,
        'modal' => true,
        'width' => 1030,
        'resizable'=>false,
    ),
));
?>
<div id='AjFlashReportPo' style="display:none;">

</div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<fieldset style="float: left; width: 98%;">
    <legend>Manage Purchases Requisitions</legend>
    <div class="search-form">
        <?php //echo $this->renderPartial('_search', array('model'=>$model)); ?>
    </div>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'purchase-items-grid',
        'dataProvider' => $model->search(),
        'mergeColumns' => array('po_no'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
        'columns' => array(
            array(
                'name' => 'local_import',
                'value' => 'Lookup::item("local_import", $data->local_import)',
                'filter' => Lookup::items('local_import'),
            ),
            'ref_no',
            array(
                'name'=>'po_no',
                'value'=>'CHtml::encode(VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::PURCHASE_ORDER).($data->po_no))',
            ),
            'issue_date',
            'expected_receive_date',
            array(
                'name'=>'supplier_id',
                'value'=>'CHtml::encode(Suppliers::model()->supplierName($data->supplier_id))',
                'filter'=>CHtml::listData(Suppliers::model()->findAll(array('order' => 'id DESC')), "id", "name"),
            ),
			array(
                'name'=>'customer_id',
                'value'=>'CHtml::encode(SrvcCustomer::model()->nameOfThis2($data->customer_id))',
                'filter'=>CHtml::listData(SrvcCustomer::model()->findAll(array('order' => 'customer_name ASC')), "customer_id", "customer_name"),
            ),
            array(
                'name' => 'v_brand',
                //'header'=>'Additional Info',
                'value' => 'CHtml::encode(CustomField1::model()->nameOfThis($data->v_brand))',
                'filter' => CHtml::listData(CustomField1::model()->findAll(), 'id', 'title'),
            ),
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
                'filter' => CHtml::listData(ProdModels::model()->findAll(), 'id', 'nameAndUnit'),
            ),
            'code',
            array(
                'name' => 'order_qty',
                'value' => 'CHtml::encode($data->order_qty)',
                'filter' => '',
            ),
            array(
                'name' => 'purchase_price',
                'value' => 'CHtml::encode($data->purchase_price)',
                'filter' => '',
            ),
            array(
                'name' => 'po_no',
                'header' => 'Edit',
                'value' => 'PurchaseItems::model()->updateInfo($data->po_no)',
                'filter' => '',
            ),
            array(
                'name' => 'po_no',
                'header' => 'Delete',
                'value' => 'PurchaseItems::model()->deleteInfo($data->po_no)',
                'filter' => '',
            ),
        )
    ));
    ?>
</fieldset>
