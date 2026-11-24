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
        <?php echo "<div class='flash-error'>" . Yii::app()->user->getFlash('error') . '</div>'; ?>
    <?php endif; ?>
</div>

<fieldset style="float: left; width: 98%;">
    <legend>Print SO Report</legend>
    <table style="width:100%">
        <tr>
            <td style="width: 50%;">
                <table>
                    <tr>
                        <td>
                            SO No
                        </td>
                        <td>
                            <?php echo "<font style='color: red; font-weight: bold;'>" . VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SELL_ORDER) . "</font>"; ?>
                            <input type="text" name="soForReport" id="soForReport" style="width: 84%;"/>
                        </td>
                        <td style="vertical-align: middle;">
                            <div id="ajaxLoaderReport" style="display: none;"><img
                                        src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/></div>
                        </td>
                        <td>
                            <?php
                            echo CHtml::ajaxLink(
                                "Preview", Yii::app()->createUrl('sellItems/soReportOfThis'), array(
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
                                'data' => array('so' => 'js:jQuery("#soForReport").val()')
                            ), array(
                                    'href' => Yii::app()->createUrl('sellItems/soReportOfThis'),
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
        'title' => 'SO Report Preview',
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
    <legend>Manage Sales Orders</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'sell-items-grid',
        'dataProvider' => $model->search(),
        'mergeColumns' => array('so_no'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
        'columns' => array(

            array(
                'name' => 'so_no',
                'value' => 'VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SELL_ORDER).("-".$data->so_no)',
            ),
            'po_no',
            array(
                'name' => 'customer_id',
                'value' => 'Customers::model()->customerName($data->customer_id)',
                'filter' => CHtml::listData(Customers::model()->findAll(array('order' => 'company_name ASC')), "id", "company_name"),
            ),
            'sales_date',
            'total_tax',
            'total',
            'total_vds',
            'grand_total',
            array(
                'name' => 'created_by',
                'value' => 'CHtml::encode(Users::model()->userNameOfThis($data->created_by))',
                'filter' => CHtml::listData(Users::model()->findAll(['order' => 'username asc']), "id", "username"),
            ),
            array(
                'header' => 'Approve Status',
                'name' => 'approve_count',
                'filter' => ProdModels::model()->getActiveStatus(),
                'value' => 'SellItems::model()->approveStatus($data->id)',
                'type' => 'raw'
            ),
            array(
                'name' => 'id',
                'header' => 'Edit',
                'value' => 'SellItems::model()->updateInfo($data->id)',
                'filter' => '',
            ),

            array
            (
                'header' => 'Options',
                'template' => '{approve}{preview}',
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'approve' => array(
                        'label' => 'Approve',
                        'visible' => '$data->approve_count<2',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/approving.ico',
                        'url' => 'Yii::app()->controller->createUrl("approveSales",array("id"=>$data->id))',
                        'click' => "function(){
                              var r = confirm('Are you want to approve?');
                              if (r == true) {
                                   $.fn.yiiGridView.update('sell-items-grid', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                             /*alertify.alert(data);*/
                                             $.fn.yiiGridView.update('sell-items-grid'); 
                                        }
                                   })
                              }
                              return false;
                        }
                     ",
                    ),
                    'preview' => array(
                        'label' => '',
                        'url' => 'SellItems::model()->previewMushok($data->id)',
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

    .grid-view table.items td, .grid-view table.items th {
        border: 1px solid #c1c0c0 !important;
    }

    .grid-view table.items th {
        border: 1px solid #229954 !important;
    }
</style>
<style>
    .items td {
        line-height: 28px;
    }
</style>
