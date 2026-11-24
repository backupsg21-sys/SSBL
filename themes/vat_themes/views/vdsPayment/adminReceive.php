<?php
$this->pageTitle = "VDS Receive";

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('vds-receive-grid', {
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

<!--

<fieldset style="float: left; width: 98%;">
    <legend>Print VDS Payment Report</legend>
    <table style="width:100%">
        <tr>
            <td style="width: 50%;">
                <table>
                    <tr>
                        <td>
                            Challan No
                        </td>
                        <td>
                            <input type="text" name="soForReport" id="soForReport" style="width: 94%;"/>
                        </td>
                        <td style="vertical-align: middle;"><div id="ajaxLoaderReport" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div></td>
                        <td>
                            <?php
echo CHtml::ajaxLink("Preview", Yii::app()->createUrl('vdsPayment/challanOfThis'), array(
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
        'href' => Yii::app()->createUrl('vatPayment/challanOfThis'),
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
-->

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'soReportDialogBox',
    'options' => array(
        'title' => 'VDS Receive Preview',
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
    <legend>Manage VDS Receive</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'vds-receive-grid',
        'dataProvider' => $model->searchAdminReceive(),
        'mergeColumns' => array('mushok_no'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
        'columns' => array(
            /*'po_no', */
            /*array(
                'header' => 'Parts/Service',
                'name' => 'parts_service',
                'value' => '($data->parts_service==1)? "Parts": "Service" ',
                'filter' => Lookup::items('parts_service'),
            ),*/
            'payment_date',
            'tr_challan_no',
            'tr_challan_date',
            array(
                'name' => 'customer_id',
                'value' => 'Customers::model()->customerName($data->customer_id)',
                'filter' => CHtml::listData(Customers::model()->findAll(), 'id', 'company_name'),
            ),
            /*array(
                'name' => 'payment_method',
                'value' => 'Lookup::item("payment_type", $data->payment_method)',
                'filter' => Lookup::items('payment_type'),
            ),*/
            'amount',
            array
            (
                'header' => 'Options',
                'template' => '{update}',
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'update' => array(
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/view.png',
                        'url' => 'Yii::app()->controller->createUrl("vdsReceiveMoney",array("id"=>$data->id))',
                        'click' => "function( e ){
                            e.preventDefault();
                            $( '#update-dialog' ).children( ':eq(0)' ).empty(); // Stop auto POST
                            updateDialog( $( this ).attr( 'href' ) );
                            $( '#update-dialog' )
                              .dialog( { title: 'SALES VDS RECEIVE MONEY' } )
                              .dialog( 'open' ); }",
                    ),
                )
            ),
            /*
            array
                (
                'header' => 'Options',
                'template' => '{approve}',
                'class' => 'CButtonColumn',
                'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
                'buttons' => array(
                    'approve' => array(
                        'label' => 'Approve',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/approving.ico',
                        'click' => 'function(){return confirm("Are you want to approve?");}',
                        'url' => 'Yii::app()->controller->createUrl("approveSales",array("id"=>$data->id))',
                    ),
                )
            ),*/
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




<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'update-dialog',
    'options' => array(
        'title' => 'Vds Payment Form',
        'autoOpen' => false,
        'modal' => true,
        'width' => '600px',
        'resizable' => false,
    ),
));
?>
<div class="update-dialog-content"></div>
<?php $this->endWidget(); ?>

<?php
$updateJS = CHtml::ajax(array(
    'url' => "js:url",
    'data' => "js:form.serialize() + action",
    'type' => 'post',
    'dataType' => 'json',
    'success' => "function( data )
  {
    if( data.status == 'failure' )
    {
      $( '#update-dialog div.update-dialog-content' ).html( data.content );
      $( '#update-dialog div.update-dialog-content form input[type=submit]' )
        .die() // Stop from re-binding event handlers
        .live( 'click', function( e ){ // Send clicked button value
          e.preventDefault();
          updateDialog( false, $( this ).attr( 'name' ) );
      });
    }
    else
    {
      $( '#update-dialog div.update-dialog-content' ).html( data.content );
      if( data.status == 'success' ) // Update all grid views on success
      {
        $( 'div.grid-view' ).each( function(){ // Change the selector if you use different class or element
          $.fn.yiiGridView.update( $( this ).attr( 'id' ) );
        });
      }
      setTimeout( \"$( '#update-dialog' ).dialog( 'close' ).children( ':eq(0)' ).empty();\", 1000 );
    }
  }"
));
?>

<?php Yii::app()->clientScript->registerScript('updateDialog', "
function updateDialog( url, act )
{
  var action = '';
  var form = $( '#update-dialog div.update-dialog-content form' );
  if( url == false )
  {
    action = '&action=' + act;
    url = form.attr( 'action' );
  }
  {$updateJS}
}"); ?>


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
<script>
    $('#soForReport').attr('autocomplete', 'off');
    $('input').attr('autocomplete', 'off');
</script>
