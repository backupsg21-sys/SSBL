
<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('chart-of-ac-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<fieldset style="float: left; width: 98%;">
    <legend>Depreciation Setup</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'chart-of-ac-grid',
        'dataProvider' => $model->searchDepSetup(),
        'filter' => $model,
        'mergeColumns' => array('root'),
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'columns' => array(
            'code',
            array(
                'name' => 'title',
                'htmlOptions' => array('style' => 'text-align: right;'),
            ),
            array(
                'name' => 'transaction_type',
                'value' => 'CHtml::encode(ChartOfAc::model()->transTypeColor($data->transaction_type, $data->opening_amount))',
                'filter' => LookupAc::items('transaction_type'),
                'htmlOptions' => array('style' => 'text-align: right;'),
            ),
            'dep',
            'dep_start_dt',
            array
                (
                'header' => 'Options',
                'template' => '{depreciationSetup}',
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'depreciationSetup' => array(
                        'label' => 'Depreciation Setup',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/update.png',
                        'url' => 'Yii::app()->controller->createUrl("chartOfAc/updateDepSetup",array("id"=>$data->id))',
                        'click' => "function( e ){
                                e.preventDefault();
                                $( '#update-dialog' ).children( ':eq(0)' ).empty(); // Stop auto POST
                                updateDialog( $( this ).attr( 'href' ) );
                                $( '#update-dialog' )
                                  .dialog( { title: 'Depreciation Setup' } )
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
        'title' => 'Depreciation Setup',
        'autoOpen' => false,
        'modal' => true,
        'width' => 'auto',
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
