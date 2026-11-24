<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('adjustments-grid', {
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
    <legend>Print VAT Adjustment Voucher</legend>
    <table style="width:100%">
        <tr>
            <td style="width: 50%;">
                <table>
                    <tr>
                        <td>
                            Challan No
                        </td>
                        <td>
                            <input type="text" name="soForReport" id="soForReport" style="width: 84%;"/>
                        </td>
                        <td style="vertical-align: middle;">
                            <div id="ajaxLoaderReport" style="display: none;"><img
                                        src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/></div>
                        </td>
                        <td>
                            <?php
                            echo CHtml::ajaxLink(
                                "Preview", Yii::app()->createUrl('adjustments/challanOfThis'), array(
                                'type' => 'POST',
                                'beforeSend' => "function(){
                                   $('#ajaxLoaderReport').show();
                                 }",
                                'success' => "function( data ){
                                    //alert( data );
                                    $('#soReportDialogBox').dialog('open'); 
                                    $('#AjFlashReportSo').html(data).show();                                                                 
                                }",
                                'complete' => "function(){
                                                       $('#ajaxLoaderReport').hide(); 
                                }",
                                'data' => array('challan' => 'js:jQuery("#soForReport").val()')
                            ), array(
                                    'href' => Yii::app()->createUrl('adjustments/challanOfThis'),
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
    'id' => 'soReportDialogBox',
    'options' => array(
        'title' => 'VAT Payment Preview',
        'autoOpen' => false,
        'modal' => true,
        'width' => 1030,
        'resizable' => false,
    ),
));
?>
<div id='AjFlashReportSo' class="" style="display:none;">

</div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<fieldset style="float: left; width: 98%;">
    <legend>Manage Adjustments</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'vat-payment-grid',
        'dataProvider' => $model->search(),
        'mergeColumns' => array('payment_date', 'challan_no'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
        'columns' => array(
            'payment_date',
			'effective_date',
            array(
                'name' => 'challan_no',
                'value' => '$data->challan_no',
            ),
            'customer_name',
            array(
                'name' => 'treasury_purpose',
                'value' => 'TreasuryPurpose::nameOfAdjustmentPurpose($data->treasury_purpose)',
            ),
            array(
                'header' => 'Code',
                'name' => 'treasury_purpose',
                'value' => 'TreasuryPurpose::model()->codeOfAdjustmentPurpose($data->treasury_purpose)',
            ),
            array(
                'name' => 'payment_method',
                'value' => 'Lookup::item("payment_type", $data->payment_method)',
                'filter' => Lookup::items('payment_type'),
            ),
            'value',
            'amount',
            array(
                'name' => 'created_by',
                'value' => 'CHtml::encode(Users::model()->userNameOfThis($data->created_by))',
                'filter' => CHtml::listData(Users::model()->findAll(['order' => 'username asc']), "id", "username"),
            ),
            array(
                'name' => 'is_approved',
                'value' => 'CHtml::encode(Adjustments::model()->statusValue($data->is_approved))',
                'filter' => [Adjustments::PENDING => 'Pending', Adjustments::APPROVED => 'Approved', Adjustments::DENIED => 'Denied'],
            ),
            array
            (
                'header' => 'Options',
                'template' => '{update}{approve}',
                'class' => 'CButtonColumn',
                'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
                'buttons' => array(
                    'update' => array(
                        'visible' => '$data->is_approved == 1',
                    ),
                    'approve' => array(
                        'label' => 'Approve',
                        'visible' => '$data->is_approved == 1',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/approving.ico',
                        'click' => 'function(){return confirm("Are you want to approve?");}',
                        'url' => 'Yii::app()->controller->createUrl("approve",array("id"=>$data->id))',
                    ),
                )
            ),
        )
    ));
    ?>
</fieldset>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'viewDialog',
    'options' => array(
        'title' => Yii::t('user', 'Viewing Single Data'),
        'autoOpen' => false,
        'modal' => true,
        'width' => 'auto',
        'resizable' => false,
    ),
));
?>
<div id="ajaxLoaderView" style="display: none;"><img
            src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/></div>
<div id='AjFlash' style="display:none; margin-top: 20px;">

</div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<style>
    /* grid border */
    .grid-view table.items th, .grid-view table.items td {
        border: 1px solid gray !important;
        text-align: center;
    }

    /* disable selected for merged cells */
    .grid-view td.merge {
        background: none repeat scroll 0 0 #F8F8F8;
    }
</style>
