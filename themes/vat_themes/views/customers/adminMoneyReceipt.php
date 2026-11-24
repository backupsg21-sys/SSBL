<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('customers-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<fieldset style="float: left; width: 98%;">
    <legend>Customer's Money Receipts</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'customers-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'columns' => array(
            'id',
            'company_name',
            /*array(
                'name' => 'id',
                'header'=>'Transaction Details',
                'value' => 'SrvcCustomer::model()->transactionInfo($data->id)',
                'filter' => '',
                'htmlOptions'=>array('style'=>'text-align: right; font-weight: bold;'),
            ),*/
            array
                (
                'header' => 'Options',
                'template' => '{showMoneyReceipt}',
                'class' => 'CButtonColumn',
                //'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
                'buttons' => array(
                    'showMoneyReceipt' => array(
                        'label' => 'Show Invoice Item',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/payment-icon.png',
                        'url' => 'Yii::app()->controller->createUrl("/moneyReceipt/addMoneyReceiptForm",array("customer_id"=>$data->id))',
                        'click' => "function( e ){
                                e.preventDefault();
                                $( '#addInvoiceItem-dialog' ).children( ':eq(0)' ).empty(); // Stop auto POST
                                addInvoiceItemDialog( $( this ).attr( 'href' ) );
                                $( '#addInvoiceItem-dialog' )
                                  .dialog( { title: 'Money Receipt Form' } )
                                  .dialog( 'open' ); }",
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
        'title' => 'Money Receipt History',
        'autoOpen' => false,
        'modal' => true,
        'width' => 'auto',
        'resizable' => false,
    ),
));
?>
<div id="ajaxLoaderView" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
<div id='AjFlash' style="display:none; margin-top: 20px;">

</div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'addInvoiceItem-dialog',
    'options' => array(
        'title' => 'Create Money Receipt',
        'autoOpen' => false,
        'modal' => true,
        'width' => '1200px',
        'resizable' => false,
    ),
));
?>
<div class="addInvoiceItem-dialog-content"></div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<?php
$addInvoiceItemJS = CHtml::ajax(array(
            'url' => "js:url",
            'data' => "js:form.serialize() + action",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function( data )
  {
    if( data.status == 'failure' )
    {
      $( '#addInvoiceItem-dialog div.addInvoiceItem-dialog-content' ).html( data.content );
      $( '#addInvoiceItem-dialog div.addInvoiceItem-dialog-content form input[type=submit]' )
        .die() // Stop from re-binding event handlers
        .live( 'click', function( e ){ // Send clicked button value
          e.preventDefault();
          addInvoiceItemDialog( false, $( this ).attr( 'name' ) );
      });
    }
    else
    {
      $( '#addInvoiceItem-dialog div.addInvoiceItem-dialog-content' ).html( data.content );
      if( data.status == 'success' ) // update all grid views on success
      {
        $( 'div.grid-view' ).each( function(){ // Change the selector if you use different class or element
          $.fn.yiiGridView.update( $( this ).attr( 'id' ) );
         });
      }
      //setTimeout( \"$( '#addInvoiceItem-dialog' ).dialog( 'close' ).children( ':eq(0)' ).empty();\", 1000 );
    }
  }"
        ));
?>

<?php Yii::app()->clientScript->registerScript('addInvoiceItemDialog', "
function addInvoiceItemDialog( url, act )
{
  var action = '';
  var form = $( '#addInvoiceItem-dialog div.addInvoiceItem-dialog-content form' );
  if( url == false )
  {
    action = '&action=' + act;
    url = form.attr( 'action' );
  }
  {$addInvoiceItemJS}
}"); ?>
