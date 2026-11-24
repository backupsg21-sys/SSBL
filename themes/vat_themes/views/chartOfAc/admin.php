
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
$('.deleteall-button').click(function(){
        var atLeastOneIsChecked = $('input[name=\"chart-of-ac-grid_c0[]\"]:checked').length > 0;
        if (!atLeastOneIsChecked){
                alertify.alert('Please select atleast one Item to delete');
        }else if(window.confirm('Are you sure you want to delete the Selected items?')){
                document.getElementById('chart-of-ac-search-form').action='deleteall';
                document.getElementById('chart-of-ac-search-form').submit();
        }
});
");
?>
<?php if ($model->isNewRecord) { ?>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
<?php } else { ?>
    <?php echo $this->renderPartial('_form2', array('model' => $model)); ?>
<?php } ?>
<div id="statusMsg"></div>
<?php
if (Yii::app()->user->hasFlash('success')) {
    echo '<div class="flash-success">' . Yii::app()->user->getFlash('success') . '</div>';
}
if (Yii::app()->user->hasFlash('error')) {
    echo '<div class="flash-error">' . Yii::app()->user->getFlash('error') . '</div>';
}
?>

<fieldset style="float: left; width: 98%;">
    <legend>Manage CA</legend>
    <?php echo CHtml::button('Delete Selected Items', array('name' => 'btndeleteall', 'class' => 'deleteall-button')); ?>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'chart-of-ac-search-form',
                'enableAjaxValidation' => true,
                'htmlOptions' => array('enctype' => 'multipart/form-data')
            ));
    ?>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'chart-of-ac-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'mergeColumns' => array('root'),
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'selectableRows' => 2,
        'columns' => array(
            array(
                'value' => '$data->id',
                'class' => 'CCheckBoxColumn',
//                'visible'=>'$data->account_type=="2"?TRUE:FALSE',
                'selectableRows'=>'2',
                'disabled'=>'$data->account_type=="1"?TRUE:FALSE',
                'cssClassExpression'=>'$data->account_type=="accept" ? "" : "hidden"',
            ),
            array(
                'name' => 'root',
                'value' => 'ChartOfAc::model()->nameOfThis($data->root)',
                'filter' => CHtml::listData(ChartOfAc::model()->findAll(array('order'=>'title ASC')), "id", "title"),
            ),
            'code',
            array(
                'name' => 'title',
                'htmlOptions' => array('style' => 'text-align: left;'),
            ),
            array(
                'name' => 'bank_or_cash',
                'value' => 'CHtml::encode(ChartOfAc::model()->isBankOrCashAC($data->bank_or_cash))',
                'filter' => array(ChartOfAc::BANK_CASH_AC => "BANK/CASH AC"),
            ),
            array(
                'name' => 'account_type',
                'value' => 'CHtml::encode(ChartOfAc::model()->isEditDeletable($data->account_type))',
                'filter' => LookupAc::items('account_type'),
            ),
            array(
                'name' => 'transaction_type',
                'value' => 'CHtml::encode(ChartOfAc::model()->transTypeColor($data->transaction_type, $data->opening_amount))',
                'filter' => LookupAc::items('transaction_type'),
                'htmlOptions' => array('style' => 'text-align: right;'),
            ),
            'remarks',
            array(
                'name' => 'create_by',
                'value' => 'CHtml::encode(Users::model()->userNameOfThis($data->create_by))',
                'filter' => CHtml::listData(Users::model()->findAll(), "id", "username"),
            ),
            'create_time',
            array(
                'name' => 'update_by',
                'value' => 'CHtml::encode(Users::model()->userNameOfThis($data->update_by))',
                'filter' => CHtml::listData(Users::model()->findAll(), "id", "username"),
            ),
            'update_time',
            array
                (
                'header' => 'Options',
                'template' => '{update}', //{delete}
                'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'update' => array(
                        'visible' => '$data->account_type==2', //2= show
                        'click' => "function( e ){
                            e.preventDefault();
                            $( '#update-dialog' ).children( ':eq(0)' ).empty(); // Stop auto POST
                            updateDialog( $( this ).attr( 'href' ) );
                            $( '#update-dialog' )
                              .dialog( { title: 'Update CA5 Info' } )
                              .dialog( 'open' ); }",
                    ),
                )
            ),
        )
    ));
    ?>
    <?php echo CHtml::button('Delete Selected Items', array('name' => 'btndeleteall', 'class' => 'deleteall-button')); ?>
    <?php $this->endWidget(); ?>
</fieldset>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'update-dialog',
    'options' => array(
        'title' => 'Update CA Info',
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
