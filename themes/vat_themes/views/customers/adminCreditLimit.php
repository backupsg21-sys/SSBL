<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('customers-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php echo CHtml::link('Manage Credit Limits', array('/creditLimit/admin'), array('class'=>'additionalBtn')); ?>
<fieldset style="float: left; width: 98%;">
    <legend>Customers Credit Limits</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'customers-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'columns' => array(
            array(
                'name'=>'depot_id',
                'value'=>'CHtml::encode(Depot::model()->nameOfThis($data->depot_id))',
                'filter'=>CHtml::listData(Depot::model()->findAll(array('order' => 'id DESC')), "id", "name"),
            ),
            'company_name',
            'opening_amount',
            array(
                'name' => 'activeCreditLimit',
                'value' => 'CHtml::encode(CreditLimit::model()->activeCreditLimitOfThisCustomer($data->id))',
                'filter' => '',
                'type'=>'raw',
                'htmlOptions'=>array('style'=>'color: green;', 'class'=>'customClass'),
            ),
            array(
                'name' => 'totalMoneyReceipt',
                'value' => 'CHtml::encode(MoneyReceipt::model()->totalMoneyReceiptOfThisCustomer($data->id))',
                'filter' => '',
                'type'=>'raw',
                'htmlOptions'=>array('style'=>'color: green;', 'class'=>'customClass'),
            ),
            array(
                'name' => 'totalSalesOrder',
                'value' => 'CHtml::encode(SellDelivery::model()->totalDeliveredQtyOfThisCustomer($data->id))',
                'filter' => '',
                'type'=>'raw',
                'htmlOptions'=>array('style'=>'color: green;', 'class'=>'customClass'),
            ),
            array(
                'name' => 'balance',
                'value' => 'CHtml::encode(Customers::model()->partyLedgerOrBalanceAmount($data->id))',
                'filter' => '',
                'type'=>'raw',
                'htmlOptions'=>array('style'=>'color: red;', 'class'=>'customClass'),
            ),
            array(
                'header' => 'Options',
                'template' => '{creditLimitHistory}{addCreditLimit}',
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'addCreditLimit' => array(
                        'label' => 'Add Credit Limit',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/credit_limit.ico',
                        'url' => 'Yii::app()->controller->createUrl("creditLimit/addCreditLimit",array("customer_id"=>$data->id))',
                        'click' => "function( e ){
                                e.preventDefault();
                                $( '#addCreditLimit-dialog' ).children( ':eq(0)' ).empty(); // Stop auto POST
                                addCreditLimitDialog( $( this ).attr( 'href' ) );
                                $( '#addCreditLimit-dialog' )
                                  .dialog( { title: 'Credit Limit Form' } )
                                  .dialog( 'open' ); }",
                    ),
                    'creditLimitHistory' => array(
                         'label'=>'Credit Limit History',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/history2.ico',
                        'url' => 'Yii::app()->controller->createUrl("creditLimit/creditLimitHistory",array("customer_id"=>$data->id))',
                        'click' => "function(){
                                    $('#viewDialog').dialog('open');
                                    $.fn.yiiGridView.update('customers-grid', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                             $('#ajaxLoaderView').hide();  
                                              //$('#AjFlash').html(data).fadeIn().animate({opacity: 1.0}, 3000).fadeOut('slow');
                                              $('#AjFlash').html(data).show();
                                              $.fn.yiiGridView.update('customers-grid');
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
<!--credit limit dialog box---------------------------------------------------------------------->

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'addCreditLimit-dialog',
    'options' => array(
        'title' => 'ADD Credit Limit',
        'autoOpen' => false,
        'modal' => true,
        'width' => 'auto',
        'resizable'=>false,
    ),
));
?>
<div class="addCreditLimit-dialog-content"></div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<?php
$addCreditLimitJS = CHtml::ajax(array(
            'url' => "js:url",
            'data' => "js:form.serialize() + action",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function( data )
  {
    if( data.status == 'failure' )
    {
      $( '#addCreditLimit-dialog div.addCreditLimit-dialog-content' ).html( data.content );
      $( '#addCreditLimit-dialog div.addCreditLimit-dialog-content form input[type=submit]' )
        .die() // Stop from re-binding event handlers
        .live( 'click', function( e ){ // Send clicked button value
          e.preventDefault();
          addCreditLimitDialog( false, $( this ).attr( 'name' ) );
      });
    }
    else
    {
      $( '#addCreditLimit-dialog div.addCreditLimit-dialog-content' ).html( data.content );
      if( data.status == 'success' ) // update all grid views on success
      {
        $( 'div.grid-view' ).each( function(){ // Change the selector if you use different class or element
          $.fn.yiiGridView.update( $( this ).attr( 'id' ) );
         });
      }
      setTimeout( \"$( '#addCreditLimit-dialog' ).dialog( 'close' ).children( ':eq(0)' ).empty();\", 1000 );
    }
  }"
        ));
?>

<?php Yii::app()->clientScript->registerScript('addCreditLimitDialog', "
function addCreditLimitDialog( url, act )
{
  var action = '';
  var form = $( '#addCreditLimit-dialog div.addCreditLimit-dialog-content form' );
  if( url == false )
  {
    action = '&action=' + act;
    url = form.attr( 'action' );
  }
  {$addCreditLimitJS}
}"); ?>

<?php
Yii::app()->clientScript->registerScript('addCreditLimitDialogCreate', "
jQuery( function($){
    $( 'a.addCreditLimit-dialog-create' ).bind( 'click', function( e ){
      e.preventDefault();
      $( '#addCreditLimit-dialog' ).children( ':eq(0)' ).empty();
      addCreditLimitDialog( $( this ).attr( 'href' ) );
      $( '#addCreditLimit-dialog' )
        .dialog( { title: 'Create' } )
        .dialog( 'open' );
    });
});
");
?>
