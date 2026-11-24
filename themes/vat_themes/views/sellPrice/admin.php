<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('sell-price-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php echo CHtml::link('Back', array('/prodModels/adminSellPrice'), array('class'=>'additionalBtn')); ?>
<fieldset style="float: left; width: 98%;">
    <legend>Manage Sale Price</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'sell-price-grid',
        'dataProvider' => $model->search(),
        'mergeColumns' => array('model_id'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'columns' => array( 
            array(
                'name'=>'model_id',
                'value'=>'CHtml::encode(ProdModels::model()->modelName($data->model_id))',
                'filter' => CHtml::listData(ProdModels::model()->findAll(), 'id', 'model_name'),
            ),
            'sell_price',
            'discount',
            'start_date',
            'end_date',
            array(
                'name' => 'is_active',
                'value' => 'SellPrice::model()->statusColor($data->is_active)',
                'filter' => Lookup::items('is_active'),
            ), 
            array(
                'name' => 'add_by',
                'value' => 'CHtml::encode(Users::model()->fullNameOfThis($data->add_by))',
                'filter' => CHtml::listData(Employees::model()->findAll(), "id", "full_name"),
            ),
            'add_time',
            array(
                'name' => 'update_by',
                'value' => 'CHtml::encode(Users::model()->fullNameOfThis($data->update_by))',
                'filter' => CHtml::listData(Employees::model()->findAll(), "id", "full_name"),
            ),
            'update_time',
            array
                (
                'header' => 'Options',
                'template' => '{update}{delete}',
                'class' => 'CButtonColumn',
                'buttons' => array(
                     'update' => array(
                        'click' => "function( e ){
                            e.preventDefault();
                            $( '#update-dialog' ).children( ':eq(0)' ).empty(); // Stop auto POST
                            updateDialog( $( this ).attr( 'href' ) );
                            $( '#update-dialog' )
                              .dialog( { title: 'Update Sell Price Info' } )
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
        'title' => 'Update Sell Price Info',
        'autoOpen' => false,
        'modal' => true,
        'width' => 'auto',
        'resizable'=>false,
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

<?php
Yii::app()->clientScript->registerScript('updateDialogCreate', "
jQuery( function($){
    $( 'a.update-dialog-create' ).bind( 'click', function( e ){
      e.preventDefault();
      $( '#update-dialog' ).children( ':eq(0)' ).empty();
      updateDialog( $( this ).attr( 'href' ) );
      $( '#update-dialog' )
        .dialog( { title: 'Create' } )
        .dialog( 'open' );
    });
});
");
?>

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

