<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('prod-models-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php echo CHtml::link('Manage Sale Prices', array('/sellPrice/admin'), array('class'=>'additionalBtn')); ?>

<fieldset style="float: left; width: 98%;">
    <legend>Add Purchase Prices</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'prod-models-grid',
        'dataProvider' => $model->search(),
        'mergeColumns' => array('item_id', 'brand_id'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'columns' => array(  
//            array(
//                    'name' => 'barcode_image',
//                    'value'=>'ProdModels::model()->findBarcodeImgOfThis($data->id)',
//                ),
             array(
                'name' => 'item_id',
                'value' => 'ProdItems::model()->itemName($data->item_id)',
                'filter' => CHtml::listData(ProdItems::model()->findAll(), 'id', 'item_name'),
            ),
            array(
                'name' => 'brand_id',
                'value' => 'ProdBrands::model()->brandName($data->brand_id)',
                'filter' => CHtml::listData(ProdBrands::model()->findAll(), 'id', 'brand_name'),
            ),
            'model_name',
            'code',
            array(
                'name' => 'date',
                'header'=>'Date',
                'value' => 'PurchasePricesManual::model()->activePurchasePriceDate($data->id)',
                'filter' => '',
                'type'=>'raw',
                
            ),

            array(
                'name' => 'activeSellPrice',
                'header'=>'Active Purchase Price',
                'value' => 'PurchasePricesManual::model()->activePurchasePrice($data->id)',
                'filter' => '',
                'type'=>'raw',
                'htmlOptions'=>array('style'=>'color: red;', 'class'=>'customClass'),
            ),
             array(
                'name' => 'activePrice',
                'header'=>'Active Sell Price',
                'value' => 'SellPrice::model()->activeSellPriceOnly($data->id)',  
               
            ),
            array
                (
                'header' => 'Options',
                'template' => '{addSellPrice}',//{view}{sellPriceHistory}
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'sellPriceHistory' => array(
                         'label'=>'Sell Price History',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/history.ico',
                        'url' => 'Yii::app()->controller->createUrl("purchaseItems/priceHistory",array("model_id"=>$data->id))',
                        'click' => "function(){
                                    $('#viewDialog').dialog('open');
                                    $.fn.yiiGridView.update('prod-models-grid', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                             $('#ajaxLoaderView').hide();  
                                              //$('#AjFlash').html(data).fadeIn().animate({opacity: 1.0}, 3000).fadeOut('slow');
                                              $('#AjFlash').html(data).show();
                                              $.fn.yiiGridView.update('prod-models-grid');
                                        },
                                        beforeSend: function(){
                                            $('#ajaxLoaderView').show();
                                        }
                                    })
                                    return false;
                              }
                     ",
                    ),
                     'addSellPrice' => array(
                        'label' => 'Add Sell Price',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/sell_price.ico',
                        'url' => 'Yii::app()->controller->createUrl("purchaseItems/addManualPurchasePrice",array("model_id"=>$data->id))',
                        'click' => "function( e ){
                                e.preventDefault();
                                $( '#addSellPrice-dialog' ).children( ':eq(0)' ).empty(); // Stop auto POST
                                addSellPriceDialog( $( this ).attr( 'href' ) );
                                $( '#addSellPrice-dialog' )
                                  .dialog( { title: 'Purchase Price Form' } )
                                  .dialog( 'open' ); }",
                    ),
                    'view' => array(
                        'url' => 'Yii::app()->controller->createUrl("view",array("id"=>$data->id))',
                        'click' => "function(){
                                    $('#viewDialog').dialog('open');
                                    $.fn.yiiGridView.update('prod-models-grid', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                             $('#ajaxLoaderView').hide();  
                                              //$('#AjFlash').html(data).fadeIn().animate({opacity: 1.0}, 3000).fadeOut('slow');
                                              $('#AjFlash').html(data).show();
                                              $.fn.yiiGridView.update('prod-models-grid');
                                        },
                                        beforeSend: function(){
                                            $('#ajaxLoaderView').show();
                                        }
                                    })
                                    return false;
                              }
                     ",
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
        'resizable'=>false,
    ),
));
?>
<div id="ajaxLoaderView" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
<div id='AjFlash' style="display:none; margin-top: 20px;">

</div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'addSellPrice-dialog',
    'options' => array(
        'title' => 'ADD Purchase Price',
        'autoOpen' => false,
        'modal' => true,
        'width' => 'auto',
        'resizable'=>false,
    ),
));
?>
<div class="addSellPrice-dialog-content"></div>
<?php $this->endWidget(); ?>

<?php
$addSellPriceJS = CHtml::ajax(array(
            'url' => "js:url",
            'data' => "js:form.serialize() + action",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function( data )
  {
    if( data.status == 'failure' )
    {
      $( '#addSellPrice-dialog div.addSellPrice-dialog-content' ).html( data.content );
      $( '#addSellPrice-dialog div.addSellPrice-dialog-content form input[type=submit]' )
        .die() // Stop from re-binding event handlers
        .live( 'click', function( e ){ // Send clicked button value
          e.preventDefault();
          addSellPriceDialog( false, $( this ).attr( 'name' ) );
      });
    }
    else
    {
      $( '#addSellPrice-dialog div.addSellPrice-dialog-content' ).html( data.content );
      if( data.status == 'success' ) // update all grid views on success
      {
        $( 'div.grid-view' ).each( function(){ // Change the selector if you use different class or element
          $.fn.yiiGridView.update( $( this ).attr( 'id' ) );
         });
      }
      setTimeout( \"$( '#addSellPrice-dialog' ).dialog( 'close' ).children( ':eq(0)' ).empty();\", 1000 );
    }
  }"
        ));
?>

<?php Yii::app()->clientScript->registerScript('addSellPriceDialog', "
function addSellPriceDialog( url, act )
{
  var action = '';
  var form = $( '#addSellPrice-dialog div.addSellPrice-dialog-content form' );
  if( url == false )
  {
    action = '&action=' + act;
    url = form.attr( 'action' );
  }
  {$addSellPriceJS}
}"); ?>

<?php
Yii::app()->clientScript->registerScript('addSellPriceDialogCreate', "
jQuery( function($){
    $( 'a.addSellPrice-dialog-create' ).bind( 'click', function( e ){
      e.preventDefault();
      $( '#addSellPrice-dialog' ).children( ':eq(0)' ).empty();
      addSellPriceDialog( $( this ).attr( 'href' ) );
      $( '#addSellPrice-dialog' )
        .dialog( { title: 'Create' } )
        .dialog( 'open' );
    });
});
");
?>

