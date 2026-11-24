<fieldset style="float: left; width: 98%;">
    <legend>Manage Sales Orders</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'sell-items-grid',
        'dataProvider' => $model->searchMoneyReceipt(),
        'mergeColumns' => array('so_no'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        //'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
        'columns' => array(
            array(
                'name' => 'so_no',
                'value' => 'VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SELL_ORDER).($data->so_no)',
            ),
            array(
                'name' => 'customer_id',
                'value' => 'Customers::model()->customerName($data->customer_id)',
                'filter' => CHtml::listData(Customers::model()->findAll(array('order' => 'id DESC')), "id", "company_name"),
            ),
            'date_of_sell',
            //'delivery_date',
            array(
                'name' => 'model_id',
                'value' => 'ProdModels::model()->modelName($data->model_id)',
            ),
            'sell_qty',
            array
                (
                'header' => 'Options',
                'template' => '{showMoneyReceipt}',
                'class' => 'CButtonColumn',
                //'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
                'buttons' => array(
                    'showMoneyReceipt' => array(
                        'label' => 'Show Invoice Item',
                        //'visible' =>'$data->full_paid ==1',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/payment-icon.png',
                        'url' => 'Yii::app()->controller->createUrl("/moneyReceipt/addMoneyReceiptForm",array("soNo"=>$data->so_no))',
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


