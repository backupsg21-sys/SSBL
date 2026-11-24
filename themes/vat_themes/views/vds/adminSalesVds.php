<fieldset style="float: left; width: 98%;">
    <legend>Sales VDS List</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'vds-grid',
       'dataProvider' => $model->searchAdminSalesVds(),
        'mergeColumns' => array('so_no'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
        'columns' => array(
            array(
                'name' => 'so_no',
                'value' => 'CHtml::encode(VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SELL_ORDER).("-".$data->so_no))',
            ),
            array(
                'name' => 'challan_no',
                'value' => 'CHtml::encode(SellItems::model()->challanNoBySalesID($data->sales_id))',
            ),
            'issue_date',
            array(
                'name' => 'vds',
                'value' => 'CHtml::encode($data->vds)',
            ),
            array(
                'name' => 'customer_id',
                'value' => 'CHtml::encode(Customers::model()->customerName($data->customer_id))',
                'filter' => CHtml::listData(Customers::model()->findAll(array('order' => 'company_name ASC')), "id", "company_name"),
            ),
            array(
                'header' => 'BIN',
                'name' => 'bin',
                'value' => 'CHtml::encode(Customers::model()->customerBin($data->customer_id))',
            ),
            //'payment_challan_no',
            array
                (
                'header' => 'Options',
                'template' => '{update}',
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'update' => array(
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/sell_price.ico',
                        'click' => "function( e ){
                            e.preventDefault();
                            $( '#update-dialog' ).children( ':eq(0)' ).empty(); // Stop auto POST
                            updateDialog( $( this ).attr( 'href' ) );
                            $( '#update-dialog' )
                              .dialog( { title: 'VDS Receive Information' } )
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
    'id' => 'update-dialog',
    'options' => array(
        'title' => 'VDS Receive Information',
        'autoOpen' => false,
        'modal' => true,
        'width' => '500px',
        'resizable'=>false,
    ),
));
$this->pageTitle = "Sales VDS Follow-up";
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
