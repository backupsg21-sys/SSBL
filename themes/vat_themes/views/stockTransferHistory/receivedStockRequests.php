
<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('stock-transfer-history-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="statusMsg"></div>
<fieldset style="float: left; width: 98%;">
    <legend>Receive Stock</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'stock-transfer-history-grid',
        'dataProvider' => $model->searchReceive(),
        'mergeColumns' => array('sent_date'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'columns' => array(
            array(
                'name' => 'sent_from_store_id',
                'value' => 'CHtml::encode(Stores::model()->storeName($data->sent_from_store_id))',
                'filter' => CHtml::listData(Stores::model()->findAll(), 'id', 'store_name'),
            ),
            'sent_qty',
            'sent_date',
            array(
                'name' => 'item_id',
                'value' => 'CHtml::encode(ProdItems::model()->itemName($data->item_id))',
                'filter' => CHtml::listData(ProdItems::model()->findAll(), 'id', 'item_name'),
            ),
            array(
                'name' => 'brand_id',
                'value' => 'CHtml::encode(ProdBrands::model()->brandName($data->brand_id))',
                'filter' => CHtml::listData(ProdBrands::model()->findAll(), 'id', 'brand_name'),
            ),
            array(
                'name' => 'model_id',
                'value' => 'CHtml::encode(ProdModels::model()->modelName($data->model_id))',
                'filter' => CHtml::listData(ProdModels::model()->findAll(), 'id', 'model_name'),
            ),
            'code',
            array(
                'name' => 'received_to_store_id',
                'value' => 'CHtml::encode(Stores::model()->storeName($data->received_to_store_id))',
                'filter' => CHtml::listData(Stores::model()->findAll(), 'id', 'store_name'),
            ),
            'received_qty',
            'received_date',
            array(
                'name' => 'is_received',
                'value' => 'CHtml::encode(StockTransferHistory::model()->imgRepresentationOfThisStatus($data->is_received))',
                'filter' => Lookup::items('is_received'),
            ),
            array
                (
                'header' => 'Options',
                'template' => '{receive}',
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'receive' => array(
                        'label' => 'Receive',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/receive.ico',
                        'url' => 'Yii::app()->controller->createUrl("stockTransferReqReceive",array("id"=>$data->id))',
                        'click' => "function( e ){
                                e.preventDefault();
                                $( '#receivestock-dialog' ).children( ':eq(0)' ).empty(); // Stop auto POST
                                receivestockDialog( $( this ).attr( 'href' ) );
                                $( '#receivestock-dialog' )
                                  .dialog( { title: 'Receive Stock' } )
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
    'id' => 'receivestock-dialog',
    'options' => array(
        'title' => 'Receive Stock',
        'autoOpen' => false,
        'modal' => true,
        'width' => 'auto',
        'resizable'=>false,
    ),
));
?>
<div id="ajaxLoaderViewreceivestock" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>

<div class="receivestock-dialog-content"></div>
<?php $this->endWidget(); ?>
<?php
$receivestockJS = CHtml::ajax(array(
            'url' => "js:url",
            'data' => "js:form.serialize() + action",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function( data )
  {
    if( data.status == 'failure' )
    {
      $( '#receivestock-dialog div.receivestock-dialog-content' ).html( data.content );
      $( '#receivestock-dialog div.receivestock-dialog-content form input[type=submit]' )
        .die() // Stop from re-binding event handlers
        .live( 'click', function( e ){ // Send clicked button value
          e.preventDefault();
          receivestockDialog( false, $( this ).attr( 'name' ) );
      });
    }
    else
    {
      if( data.status == 'success' ) // Update all grid views on success
      {
        $('#receivestock-dialog div.receivestock-dialog-content' ).html( data.content );
        setTimeout( \"$( '#receivestock-dialog' ).dialog( 'close' );\", 1000 );
        $( 'div.grid-view' ).each( function(){ // Change the selector if you use different class or element
          $.fn.yiiGridView.update( $( this ).attr( 'id' ) );
        });
      }     
    }
  }"
        ));
?>

<?php Yii::app()->clientScript->registerScript('receivestockDialog', "
function receivestockDialog( url, act )
{
  var action = '';
  var form = $( '#receivestock-dialog div.receivestock-dialog-content form' );
  if( url == false )
  {
    action = '&action=' + act;
    url = form.attr( 'action' );
  }
  {$receivestockJS}
}"); ?>

<?php
Yii::app()->clientScript->registerScript('receivestockDialogCreate', "
jQuery( function($){
    $( 'a.receivestock-dialog-create' ).bind( 'click', function( e ){
      e.preventDefault();
      $( '#receivestock-dialog' ).children( ':eq(0)' ).empty();
      receivestockDialog( $( this ).attr( 'href' ) );
      $( '#receivestock-dialog' )
        .dialog( { title: 'Receive Stock' } )
        .dialog( 'open' );
    });
});
");
?>
