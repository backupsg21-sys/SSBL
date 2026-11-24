<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('purchase-return-grid', {
                        data: $(this).serialize()
	});
	return false;
});
");
?>
<div style="float: left; width: 100%;">
<?php if (Yii::app()->user->hasFlash('error')): ?>
    <?php echo "<div class='flash-error'>".Yii::app()->user->getFlash('error').'</div>'; ?>
<?php endif; ?>
</div>
<fieldset style="float: left; width: 98%;">
    <legend>Manage Debit Note</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'purchase-return-grid',
        'dataProvider' => $model->search(),
        'mergeColumns' => array('sl_no'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
        'columns' => array(
           array(
                'name'=>'sl_no',
                'value'=>'CHtml::encode(VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::PURCHASE_ORDER_CANCEL).("-".$data->sl_no))',
            ),
            'return_date',
			array(
                'name' => 'created_by',
                'value' => 'CHtml::encode(Users::model()->userNameOfThis($data->created_by))',
                'filter' => CHtml::listData(Users::model()->findAll(), "id", "username"),
            ),
            array
                (
                'header' => 'Options',
                'template' => '{voucher}',
                'class' => 'CButtonColumn',
                'buttons' => array(                    
                    
                    'voucher' => array(
                        'url' => 'Yii::app()->controller->createUrl("preview",array("id"=>$data->sl_no))',
                         'label'=>'Debit Note Voucher',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/view.png',
                        'click' => "function(e){
                                    e.preventDefault();                                    
                                    $('#viewDialog').dialog('open');
                                    $.fn.yiiGridView.update('purchase-return-grid', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                             $('#ajaxLoaderView').hide();  
                                              //$('#AjFlash').html(data).fadeIn().animate({opacity: 1.0}, 3000).fadeOut('slow');
                                              $('#AjFlash').html(data).show();
                                              $.fn.yiiGridView.update('purchase-return-grid');
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
        'title' => Yii::t('user', 'Preview'),
        'autoOpen' => false,
        'modal' => true,
        'width' => 'auto',
        'resizable'=>false,
    ),
));
?>
<div id="ajaxLoaderView" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
<div id='AjFlash' style="display:none; margin-top: 20px;"></div>
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
