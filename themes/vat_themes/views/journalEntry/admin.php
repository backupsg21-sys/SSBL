<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('journal-entry-grid', {
		data: $(this).serialize()
	});
	return false;
});
$('.deleteall-button').click(function(){
        var atLeastOneIsChecked = $('input[name=\"journal-entry-grid_c0[]\"]:checked').length > 0;
        if (!atLeastOneIsChecked){
                alertify.alert('Please select atleast one Item to delete');
        }else if(window.confirm('Are you sure you want to delete the Selected items?')){
                document.getElementById('journal-entry-search-form').action='deleteall';
                document.getElementById('journal-entry-search-form').submit();
        }
});
");
?>
<fieldset style="float: left; width: 98%;">
    <legend>Print Voucher</legend>
    <table>
        <tr>
            <td>
                Voucher No
            </td>
            <td>
                <input type="text" name="voucherNoForReport" id="voucherNoForReport"/>
            </td>
            <td>
                Type
            </td>
            <td>
                <select name="voucherTypeForReport" id="voucherTypeForReport" style="width: 100%;">
                    <option value>select</option>
                    <?php
                        $types=LookupAc::model()->findAll(array('condition'=>'type="voucher_type"'));
                        if($types){
                            foreach($types as $type){
                                ?>
                                <option value="<?php echo $type->code; ?>"><?php echo $type->name; ?></option>
                                <?php
                            }
                        }
                    ?>
                </select>
            </td>
            <td>
                <?php
                echo CHtml::ajaxLink(
                        "Preview", Yii::app()->createUrl('accounting/journalEntry/voucher'), array(
                    'type' => 'POST',
                    'beforeSend' => "function(){
                       $('#ajaxLoaderReport').show();
                     }",
                    'success' => "function( data ){
                        $('#poReportDialogBox').dialog('open'); 
                        $('#AjFlashReportPo').html(data).show();                                                                 
                    }",
                    'complete' => "function(){
                        $('#ajaxLoaderReport').hide(); 
                    }",
                    'data' => array(
                        'voucherNo' => 'js:jQuery("#voucherNoForReport").val()',
                        'voucherType' => 'js:jQuery("#voucherTypeForReport").val()'
                        )
                    ), 
                    array(
                        'href' => Yii::app()->createUrl('journalEntry/poReportOfThis'),
                        'style' => 'padding: 10px; border: 1px dotted tomato; color: tomato; font-weight: bold; box-shadow: -1px -1px 4px blueviolet;'
                    )
                );
                ?>
            </td>        
            <td style="vertical-align: middle;"><div id="ajaxLoaderReport" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div></td>
        </tr>
    </table>
</fieldset>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'poReportDialogBox',
    'options' => array(
        'title' => "Voucher",
        'autoOpen' => false,
        'modal' => true,
        'width' => 1030,
        'resizable'=>false,
    ),
));
?>
<div id='AjFlashReportPo' style="display:none;"></div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<div class="search-form" style="float: left; width: 100%;">
	<?php $this->renderPartial('_search',array(
        'model'=>$model,
    )); ?>
</div>
<?php
if (Yii::app()->user->hasFlash('success')) {
    echo '<div class="flash-success">' . Yii::app()->user->getFlash('success') . '</div>';
}
if (Yii::app()->user->hasFlash('error')) {
    echo '<div class="flash-error">' . Yii::app()->user->getFlash('error') . '</div>';
}
?>

<fieldset style="float: left; width: 98%;">
    <legend>Manage Entries</legend>
    <?php echo CHtml::button('Delete Selected Items', array('name' => 'btndeleteall', 'class' => 'deleteall-button')); ?>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'journal-entry-search-form',
                'enableAjaxValidation' => true,
                'htmlOptions' => array('enctype' => 'multipart/form-data')
            ));
    ?>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'journal-entry-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'mergeColumns' => array('entry_no'),
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'selectableRows' => 2,
        'columns' => array(
            array(
                'value' => '$data->id',
                'class' => 'CCheckBoxColumn',
                'selectableRows'=>'2',
                'disabled'=>'$data->is_deletable==JournalEntry::NOT_DELETABLE?TRUE:FALSE',
                'cssClassExpression'=>'$data->is_deletable==JournalEntry::NOT_DELETABLE ? "" : "hidden"',
            ),
            array(
                'name' => 'voucher_type',
                'value' => 'LookupAc::item("voucher_type", $data->voucher_type)',
                'filter' => LookupAc::items('voucher_type'),
                'htmlOptions' => array('style' => 'text-align: right;'),
            ),
            array(
                'name' => 'entry_no',
                'value' => 'JournalEntry::model()->voucherNoOfThis($data->entry_no, $data->voucher_type)',
            ),
            'entry_date',
            array(
                'name' => 'ca_id',
                'value' => 'ChartOfAc::model()->nameOfThis($data->ca_id)',
                'htmlOptions' => array('style' => 'text-align: right; width: 160px;'),
            ),
//            'ca_id',
//            'opposite_ca_id',
            array(
                'name' => 'transaction_type',
                'header'=>'Amount (Dr/Cr)',
                'value' => 'JournalEntry::model()->transTypeColor($data->transaction_type, $data->amount)',
                'filter' => LookupAc::items('transaction_type'),
                'htmlOptions' => array('style' => 'text-align: right;'),
            ),
            array(
                'name' => 'amount',
                'value' => 'JournalEntry::model()->transTypeColor($data->transaction_type, $data->amount)',
                'htmlOptions' => array('style' => 'text-align: right;'),
            ),
//            'closing_amount',
            array(
                'name' => 'narration',
                'htmlOptions' => array('style' => 'text-align: right;'),
            ),
            'bill_no',
            'mr_no',
            'custom_invoice_no',
            array(
                'name' => 'create_by',
                'value' => 'Users::model()->userNameOfThis($data->create_by)',
                'filter' => CHtml::listData(Users::model()->findAll(), "id", "username"),
            ),
            'create_time',
            array(
                'name' => 'update_by',
                'value' => 'Users::model()->userNameOfThis($data->update_by)',
                'filter' => CHtml::listData(Users::model()->findAll(), "id", "username"),
            ),
            'update_time',
            array
                (
                'header' => 'Options',
                'template' => '{update}', //{delete}
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'update' => array(
                        'visible'=>'$data->is_deletable==JournalEntry::DELETABLE',
                        'click' => "function( e ){
                            e.preventDefault();
                            $( '#update-dialog' ).children( ':eq(0)' ).empty(); // Stop auto POST
                            updateDialog( $( this ).attr( 'href' ) );
                            $( '#update-dialog' )
                              .dialog( { title: 'Update Transaction Info' } )
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
        'title' => 'Update Transaction Info',
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
