<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('production-grid', {
                data: $(this).serialize()
    });
    return false;
});
");
?>
<div style="float: left; width: 100%;">
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <?php echo "<div class='flash-error'>" . Yii::app()->user->getFlash('error') . '</div>'; ?>
    <?php endif; ?>
</div>
<?php /* CHtml::link('Create',array('production/create'),['class'=>'customBtn', 'style'=>'margin-left: 13px;padding: 3px 25px !important;']); */ ?>

<fieldset style="float: left; width: 98%;">
    <legend>Manage Production</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'production-grid',
        'dataProvider' => $model->search(),
        //'mergeColumns' => array('model_id', 'config_date'),
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
        'columns' => array(
            'issue_date',
            array(
                'name' => 'finish_product_id',
                'value' => 'CHtml::encode(ProdModels::model()->modelName($data->finish_product_id))',
            ),
            'qty',
            /*array(
                'name' => 'id',
                'header' => 'Edit',
                'value' => 'MaterialRecipe::model()->updateRecipe($data->id)',
                'filter' => '',
            ),*/
			array(
                'name' => 'created_by',
                'value' => 'CHtml::encode(Users::model()->userNameOfThis($data->created_by))',
                'filter' => CHtml::listData(Users::model()->findAll(['order' => 'username']), "id", "username"),
            ),
            array
            (
                'header' => 'Options',
                'template' => '{preview}',
                'class' => 'CButtonColumn',
                'buttons' => array(

                    'preview' => array(
                        'url' => 'Yii::app()->controller->createUrl("preview",array("id"=>$data->id))',
                        'label'=>'Production Preview',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/view.png',
                        'click' => "function(e){
                                    e.preventDefault();
                                    $('#viewDialog').dialog('open');
                                    $.fn.yiiGridView.update('production-grid', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                             $('#ajaxLoaderView').hide();
                                              //$('#AjFlash').html(data).fadeIn().animate({opacity: 1.0}, 3000).fadeOut('slow');
                                              $('#AjFlash').html(data).show();
                                              $.fn.yiiGridView.update('production-grid');
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

            /*array
                (
                'header' => 'Options',
                'template' => '{approve}{delete}',
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'delete' => array(
                        'visible' => '($data->is_approved == 0) ? true : false',
                    ),
                    'approve' => array(
                        'label' => 'Approval',
                        'visible' => '($data->is_approved == 0) ? true : false',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/images/approved.ico',
                        'url' => 'Yii::app()->controller->createUrl("production/approve",array("id"=>$data->id))',
                        'click' => 'function(){
                            if( confirm("Are you sure want to Approve?") ){
                                return true;
                            }
                            return false;
                        }'
                    ),
                )
            ),*/
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