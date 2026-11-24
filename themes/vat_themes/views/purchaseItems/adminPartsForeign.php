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
        <?php echo "<div class='flash-error'>" . Yii::app()->user->getFlash('error') . '</div>'; ?>
    <?php endif; ?>
</div>


<fieldset style="float: left; width: 98%;">
    <legend>Print PO Voucher</legend>
    <table style="width:100%">
        <tr>
            <td style="width: 50%;">
                <table>
                    <tr>
                        <td>
                            PO No
                        </td>
                        <td>
                            <input type="text" name="poForReport" id="poForReport" style="width:84%;"/>
                        </td>
                        <td style="vertical-align: middle;"><div id="ajaxLoaderReport" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div></td>
                        <td>
                            <?php
                            echo CHtml::ajaxLink(
                                    "Preview", Yii::app()->createUrl('purchaseItems/poReportOfThisForeign'), array(
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
                                    ), array(
                                'href' => Yii::app()->createUrl('purchaseItems/poReportOfThisForeign'),
                                'class' => 'findBtn'
                                    )
                            );
                            ?>
                        </td>          
                    </tr>
                </table>  
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
        'resizable' => false,
    ),
));
?>
<div id='AjFlashReportPo' style="display:none;">

</div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<fieldset style="float: left; width: 98%;">
    <legend>Manage Purchases Requisitions (Foreign)</legend>
    <div class="search-form">
        <?php //echo $this->renderPartial('_search', array('model'=>$model));  ?>
    </div>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'purchase-items-grid',
        'dataProvider' => $model->searchPartsForeign(),
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
                'name' => 'po_no',
                'value' => '$data->po_no',
            ),
            'issue_date',
            array(
                'name' => 'supplier_id',
                'value' => 'Suppliers::model()->supplierName($data->supplier_id)',
                'filter' => CHtml::listData(Suppliers::model()->findAll(array('order' => 'id DESC')), "id", "name"),
            ),
            array(
                'name' => 'model_id',
                'value' => 'ProdModels::model()->modelName($data->model_id)',
            ),
            array(
                'name' => 'order_qty',
                'value' => '$data->order_qty',
                'filter' => '',
            ),
            array(
                'name' => 'id',
                'header' => 'Edit',
                'value' => 'PurchaseItems::model()->updateInfoForeign($data->id)',
                'filter' => '',
            ),
            array
                (
                'htmlOptions' => array('style' => 'width: 80px; text-align: left !important;'),
                'header' => 'Options',
                'template' => '{approve}',
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'approve' => array(
                        'label' => 'Approve',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/approving.ico',
                        'click' => 'function(){return confirm("Are you want to approve?");}',
                        'url' => 'Yii::app()->controller->createUrl("approvePurchaseForeicn",array("id"=>$data->id))',
                    ),
                )
            ),
        )
    ));
    ?>
</fieldset>
