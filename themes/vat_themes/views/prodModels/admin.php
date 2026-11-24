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
<fieldset style="float: left; width: 98%;">
    <legend>Manage Products Info</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'prod-models-grid',
        'dataProvider' => $model->search(),
        'mergeColumns' => array('item_id', 'brand_id'),
        'mergeType' => 'nested',
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
        'columns' => array(
            array(
                'name' => 'item_id',
                'value' => 'ProdItems::model()->itemName($data->item_id)',
                'filter' => CHtml::listData(ProdItems::model()->findAll(['order' => 'item_name asc']), 'id', 'item_name'),
            ),
            array(
                'name' => 'brand_id',
                'value' => 'ProdBrands::model()->brandName($data->brand_id)',
                'filter' => CHtml::listData(ProdBrands::model()->findAll(['order' => 'brand_name asc']), 'id', 'brand_name'),
            ),
            'id',
            'model_name',
            'code',
            'hs_code',
            array(
                'header' => 'VAT Type',
                'name' => 'vat_calculation_method',
                'filter' => ProdModels::VAT_CALCULATION_METHOD_ARR,
                'value' => 'ProdModels::model()->vatCalculationMethodValue($data->vat_calculation_method)',
//                'value' => '($data->vat_calculation_method==1)? "Standard %":(($data->vat_calculation_method==2)? "%":(($data->vat_calculation_method==3)? "Fixed":(($data->vat_calculation_method==4)? "Exempted":(($data->vat_calculation_method==5)? "0 Rate":(($data->vat_calculation_method==6)? "MRP":"")))))',
            ),
            array(
                'name' => 'unit_id',
                'value' => 'Units::model()->unitLabel($data->unit_id)',
                'filter' => CHtml::listData(Units::model()->findAll(['order' => 'label asc']), 'id', 'label'),
            ),
            array(
                'header' => 'VAT/Unit',
                'name' => 'vat'
            ),
            array(
                'name' => 'created_by',
                'value' => 'CHtml::encode(Users::model()->userNameOfThis($data->created_by))',
                'filter' => CHtml::listData(Users::model()->findAll(['order' => 'username asc']), "id", "username"),
            ),
            array
            (
                'header' => 'Options',
                'template' => '{addSellPrice}{sellPriceHistory}{update}{delete}',
                'class' => 'CButtonColumn',
                'htmlOptions' => array('style' => 'width: 150px;'),
                'buttons' => array(
                    'sellPriceHistory' => array(
                        'label' => 'Sell Price History',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/history.ico',
                        'url' => 'Yii::app()->controller->createUrl("sellPrice/priceHistory",array("model_id"=>$data->id))',
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
                        'url' => 'Yii::app()->controller->createUrl("sellPrice/addSellPrice",array("model_id"=>$data->id))',
                        'click' => "function( e ){
                                e.preventDefault();
                                $( '#addSellPrice-dialog' ).children( ':eq(0)' ).empty(); // Stop auto POST
                                addSellPriceDialog( $( this ).attr( 'href' ) );
                                $( '#addSellPrice-dialog' )
                                  .dialog( { title: 'Sell Price Form' } )
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
    'id' => 'addSellPrice-dialog',
    'options' => array(
        'title' => 'ADD Sel Price',
        'autoOpen' => false,
        'modal' => true,
        'width' => 'auto',
        'resizable' => false,
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
