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
    <legend>Print Service Voucher</legend>
    <table  style="width: 100%">
        <tr>
            <td  style="width: 50%">
                <table>
                    <tr>
                        <td>
                            Service No
                        </td>
                        <td>
                            <?php echo "<font style='color: red; font-weight: bold;'>PO</font>"; ?><input type="text" name="poForReport" id="poForReport" style="width:84%;"/>
                        </td>
                        <td style="vertical-align: middle;"><div id="ajaxLoaderReport" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div></td>
                        <td>
                            <?php
                            echo CHtml::ajaxLink(
                                    "Preview", Yii::app()->createUrl('purchaseItems/serviceReportOfThis'), array(
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
                                    'serviceNo' => 'js:jQuery("#poForReport").val()'
                                )
                                    ), array(
                                'href' => Yii::app()->createUrl('purchaseItems/serviceReportOfThis'),
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
    <legend>Manage Service Purchase Requisitions</legend>
    <div class="search-form">
        <?php //echo $this->renderPartial('_search', array('model'=>$model));  ?>
    </div>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'purchase-items-grid',
        'dataProvider' => $model->searchServices(),
        'mergeColumns' => array('po_no'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
        'columns' => array(
            array(
                'name' => 'service_no',
                'value' => 'VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::PURCHASE_ORDER).("-".$data->service_no)',
            ),
            'issue_date',
            array(
                'name' => 'supplier_id',
                'value' => 'Suppliers::model()->supplierName($data->supplier_id)',
                'filter' => CHtml::listData(Suppliers::model()->findAll(array('order' => 'id DESC')), "id", "name"),
            ),
            /* array(
                'name' => 'service_id',
                'value' => 'ProdModels::model()->modelName($data->model_id)',
            ),*/
            array(
                'header' => 'Approve Status',
                'name' => 'approve_count',
                'filter' => ProdModels::model()->getActiveStatus(),
                'value' => 'PurchaseItems::model()->approveStatus($data->id)',
            ),
            array(
                'name' => 'id',
                'header' => 'Edit',
                'value' => 'PurchaseItems::model()->updateInfoServices($data->id)',
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
                        'url' => 'Yii::app()->controller->createUrl("approvePurchaseServices",array("id"=>$data->id))',
                        'click' => "function(){
                              var r = confirm('Are you want to approve?');
                              if (r == true) {
                                   $.fn.yiiGridView.update('purchase-items-grid', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                             /*alertify.alert(data);*/
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
.items td{
    line-height: 28px;
}
</style>
