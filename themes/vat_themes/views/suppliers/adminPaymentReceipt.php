<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('suppliers-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php echo CHtml::link('Manage Payment Receipts', array('/paymentReceipt/admin'), array('class'=>'additionalBtn')); ?>

<fieldset style="float: left; width: 98%;">
    <legend>Suppliers Payment Receipt</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'suppliers-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'columns' => array(
            'company_name',
            array(
                'name' => 'totalPurchaseOrder',
                'value' => 'CHtml::encode(ReceivedPurchase::model()->totalPurchaseOrderOfThisSupplier($data->id))',
                'filter' => '',
                'type'=>'raw',
                'htmlOptions'=>array('style'=>'color: green;', 'class'=>'customClass'),
            ),
            array(
                'name' => 'totalPaymentReceipt',
                'value' => 'CHtml::encode(PaymentReceipt::model()->totalPaymentReceiptOfThisSupplier($data->id))',
                'filter' => '',
                'type'=>'raw',
                'htmlOptions'=>array('style'=>'color: green;', 'class'=>'customClass'),
            ),
            array
                (
                'header' => 'Options',
                'template' => '{paymentReceiptHistory}{addPaymentReceipt}',
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'paymentReceiptHistory' => array(
                         'label'=>'Payment Receipt History',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/1470920201_Time.png',
                        'url' => 'Yii::app()->controller->createUrl("paymentReceipt/paymentReceiptHistory",array("supplier_id"=>$data->id))',
                        'click' => "function(){
                                    $('#viewDialog').dialog('open');
                                    $.fn.yiiGridView.update('suppliers-grid', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                             $('#ajaxLoaderView').hide();  
                                              //$('#AjFlash').html(data).fadeIn().animate({opacity: 1.0}, 3000).fadeOut('slow');
                                              $('#AjFlash').html(data).show();
                                              $.fn.yiiGridView.update('suppliers-grid');
                                        },
                                        beforeSend: function(){
                                            $('#ajaxLoaderView').show();
                                        }
                                    })
                                    return false;
                              }
                     ",
                    ),
                    'addPaymentReceipt' => array(
                        'label' => 'Add Payment Receipt',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/payment-icon.png',
                        'url' => 'Yii::app()->controller->createUrl("paymentReceipt/addPaymentReceipt",array("supplier_id"=>$data->id))',
                        'click' => "function( e ){
                                e.preventDefault();
                                $( '#addMoneyReceipt-dialog' ).children( ':eq(0)' ).empty(); // Stop auto POST
                                addMoneyReceiptDialog( $( this ).attr( 'href' ) );
                                $( '#addMoneyReceipt-dialog' )
                                  .dialog( { title: 'Payment Receipt Form' } )
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
    'id' => 'addMoneyReceipt-dialog',
    'options' => array(
        'title' => 'ADD Payment Receipt',
        'autoOpen' => false,
        'modal' => true,
        'width' => 'auto',
        'resizable'=>false,
    ),
));
?>
<div class="addMoneyReceipt-dialog-content"></div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<?php
$addMoneyReceiptJS = CHtml::ajax(array(
            'url' => "js:url",
            'data' => "js:form.serialize() + action",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function( data )
  {
    if( data.status == 'failure' )
    {
      $( '#addMoneyReceipt-dialog div.addMoneyReceipt-dialog-content' ).html( data.content );
      $( '#addMoneyReceipt-dialog div.addMoneyReceipt-dialog-content form input[type=submit]' )
        .die() // Stop from re-binding event handlers
        .live( 'click', function( e ){ // Send clicked button value
          e.preventDefault();
          addMoneyReceiptDialog( false, $( this ).attr( 'name' ) );
      });
    }
    else
    {
      $( '#addMoneyReceipt-dialog div.addMoneyReceipt-dialog-content' ).html( data.content );
      if( data.status == 'success' ) // update all grid views on success
      {
        $( 'div.grid-view' ).each( function(){ // Change the selector if you use different class or element
          $.fn.yiiGridView.update( $( this ).attr( 'id' ) );
         });
      }
      setTimeout( \"$( '#addMoneyReceipt-dialog' ).dialog( 'close' ).children( ':eq(0)' ).empty();\", 1000 );
    }
  }"
        ));
?>

<?php Yii::app()->clientScript->registerScript('addMoneyReceiptDialog', "
function addMoneyReceiptDialog( url, act )
{
  var action = '';
  var form = $( '#addMoneyReceipt-dialog div.addMoneyReceipt-dialog-content form' );
  if( url == false )
  {
    action = '&action=' + act;
    url = form.attr( 'action' );
  }
  {$addMoneyReceiptJS}
}"); ?>

<?php
Yii::app()->clientScript->registerScript('addMoneyReceiptDialogCreate', "
jQuery( function($){
    $( 'a.addMoneyReceipt-dialog-create' ).bind( 'click', function( e ){
      e.preventDefault();
      $( '#addMoneyReceipt-dialog' ).children( ':eq(0)' ).empty();
      addMoneyReceiptDialog( $( this ).attr( 'href' ) );
      $( '#addMoneyReceipt-dialog' )
        .dialog( { title: 'Create' } )
        .dialog( 'open' );
    });
});
");
?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'viewDialog',
    'options' => array(
        'title' => 'Viewing Single Data',
        'autoOpen' => false,
        'modal' => true,
        'width' => 'auto',
        'resizable'=>false,
    ),
));
?>
<div id="ajaxLoaderView" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
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
