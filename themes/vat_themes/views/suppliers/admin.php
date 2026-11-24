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
<?php if ($model->isNewRecord) { ?>
    <?php echo $this->renderPartial('_form', array('model' => $model)); ?>
<?php } else { ?>
    <?php echo $this->renderPartial('_form2', array('model' => $model)); ?>
<?php } ?>

<fieldset style="float: left; width: 98%;">
    <legend>Manage suppliers</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'suppliers-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
        'columns' => array(
            'name',
            'phone_no',
            'mobile_no',
            'email',
            'bin',
            'opening_amount',
            array(
                'name' => 'created_by',
                'value' => 'CHtml::encode(Users::model()->userNameOfThis($data->created_by))',
                'filter' => CHtml::listData(Users::model()->findAll(['order' => 'username']), "id", "username"),
            ),	
            array
            (
                'header' => 'Options',
                'htmlOptions' => array('style' => 'width:100px'),
                //'template' => '{view}{update}{addContactPerson}{contactPersons}',
                'template' => '{update}',
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'update' => array(
                        'click' => "function( e ){
                            e.preventDefault();
                            $( '#update-dialog' ).children( ':eq(0)' ).empty(); // Stop auto POST
                            updateDialog( $( this ).attr( 'href' ) );
                            $( '#update-dialog' )
                              .dialog( { title: 'Update Supplier Info' } )
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
        'title' => 'Viewing Single Data',
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
    'id' => 'update-dialog',
    'options' => array(
        'title' => 'Update Supplier Info',
        'autoOpen' => false,
        'modal' => true,
        'width' => '900px',
        'resizable' => false,
    ),
));
?>
<div class="update-dialog-content"></div>
<?php //$this->endWidget(); ?>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>


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
        text-align: center;
    }

    /* disable selected for merged cells */
    .grid-view td.merge {
        background: none repeat scroll 0 0 #F8F8F8;
    }
</style>

<!--Contact person dialog box---------------------------------------------------------------------->

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'addContactPerson-dialog',
    'options' => array(
        'title' => 'ADD Contact Person',
        'autoOpen' => false,
        'modal' => true,
        'width' => 'auto',
        'resizable' => false,
    ),
));
?>
<div class="addContactPerson-dialog-content"></div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>


<?php
$addContactPersonJS = CHtml::ajax(array(
    'url' => "js:url",
    'data' => "js:form.serialize() + action",
    'type' => 'post',
    'dataType' => 'json',
    'success' => "function( data )
  {
    if( data.status == 'failure' )
    {
      $( '#addContactPerson-dialog div.addContactPerson-dialog-content' ).html( data.content );
      $( '#addContactPerson-dialog div.addContactPerson-dialog-content form input[type=submit]' )
        .die() // Stop from re-binding event handlers
        .live( 'click', function( e ){ // Send clicked button value
          e.preventDefault();
          addContactPersonDialog( false, $( this ).attr( 'name' ) );
      });
    }
    else
    {
      $( '#addContactPerson-dialog div.addContactPerson-dialog-content' ).html( data.content );
      if( data.status == 'success' ) // update all grid views on success
      {
        $( 'div.grid-view' ).each( function(){ // Change the selector if you use different class or element
          $.fn.yiiGridView.update( $( this ).attr( 'id' ) );
         });
      }
      setTimeout( \"$( '#addContactPerson-dialog' ).dialog( 'close' ).children( ':eq(0)' ).empty();\", 1000 );
    }
  }"
));
?>

<?php Yii::app()->clientScript->registerScript('addContactPersonDialog', "
function addContactPersonDialog( url, act )
{
  var action = '';
  var form = $( '#addContactPerson-dialog div.addContactPerson-dialog-content form' );
  if( url == false )
  {
    action = '&action=' + act;
    url = form.attr( 'action' );
  }
  {$addContactPersonJS}
}"); ?>

<?php
Yii::app()->clientScript->registerScript('addContactPersonDialogCreate', "
jQuery( function($){
    $( 'a.addContactPerson-dialog-create' ).bind( 'click', function( e ){
      e.preventDefault();
      $( '#addContactPerson-dialog' ).children( ':eq(0)' ).empty();
      addContactPersonDialog( $( this ).attr( 'href' ) );
      $( '#addContactPerson-dialog' )
        .dialog( { title: 'Create' } )
        .dialog( 'open' );
    });
});
");
?>

<style>
    select {
        width: 89%;
    }
    input[type=text] {
        width: 86%;
    }
    fieldset legend {
        padding: 3px 5px !important;
    }
</style>