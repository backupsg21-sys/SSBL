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
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <?php echo "<div class='flash-success'>" . Yii::app()->user->getFlash('success') . '</div>'; ?>
    <?php endif; ?>
</div>

<fieldset style="float: left; width: 98%;">
    <legend>Print PO Voucher</legend>
    <table style="width:100%">
        <tr>
            <td>
                <table>
                    <tr>
                        <td>
                            PO No
                        </td>
                        <td>
                            <input type="text" name="poForReport" id="poForReport" style="width:84%;"/>
                        </td>
                        <td style="vertical-align: middle;">
                            <div id="ajaxLoaderReport" style="display: none;"><img
                                        src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/></div>
                        </td>
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
                            ), array(
                                    'href' => Yii::app()->createUrl('purchaseItems/poReportOfThis'),
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
        'resizable' => false
    )
));
?>
<div id='AjFlashReportPo' style="display:none;">

</div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<fieldset style="float: left; width: 98%;">
    <legend>Manage Purchases Requisitions</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'purchase-items-grid',
        'dataProvider' => $model->searchPartsLocal(),
        'mergeColumns' => array('local_import'),
        'mergeType' => 'nested',
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
            'effective_date',
            array(
                'name' => 'supplier_id',
                'value' => 'Suppliers::model()->supplierName($data->supplier_id)',
                'filter' => CHtml::listData(Suppliers::model()->findAll(array('order' => 'name ASC')), "id", "name"),
            ),
            'total_tax',
            'total',
            'total_vds',
            'grand_total',
            array(
                'name' => 'created_by',
                'value' => 'CHtml::encode(Users::model()->userNameOfThis($data->created_by))',
                'filter' => CHtml::listData(Users::model()->findAll(array('order' => 'username ASC')), "id", "username"),
            ),
            array(
                'header' => 'Approve Status',
                'name' => 'approve_count',
                'filter' => ProdModels::model()->getActiveStatus(),
                'value' => 'PurchaseItems::model()->approveStatus($data->id)',
                'type' => 'raw'
            ),
            array(
                'name' => 'id',
                'header' => 'Edit',
                'value' => 'PurchaseItems::model()->updateInfo($data->id,$data->local_import)',
                'filter' => '',
            ),
            array
            (
                'header' => 'Options',
                'template' => '{approve}',
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'approve' => array(
                        'label' => 'Approve',
                        'visible' => '$data->approve_count<2',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/approving.ico',
                        'url' => 'Yii::app()->controller->createUrl("approvePurchaseLocal",array("id"=>$data->id))',
                        'click' => "function(){
                              var r = confirm('Are you want to approve?');
                              if (r == true) {
                                   $.fn.yiiGridView.update('purchase-items-grid', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                            var dataObj = JSON.parse(data);
                                            if(dataObj.status == 'success')
                                                alertify.success(dataObj.message); 
                                            else 
                                                alertify.error(dataObj.message); 
                                             $.fn.yiiGridView.update('purchase-items-grid'); 
                                        }
                                   })
                              }
                              return false;
                        }
                     ",
                    ),
                ),
            ),
        )
    ));
    ?>
</fieldset>
<style>
    .items td {
        line-height: 28px;
    }
</style>
