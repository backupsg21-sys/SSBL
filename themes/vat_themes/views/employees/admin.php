<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('employees-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php 
/*
<div class="row-fluid" style="margin: 10px 0;">
    <?php echo CHtml::link('Add Employee', array('employees/create'), array('style' => 'margin-left: 20px; padding:10px; color:#fff; background:green')); ?>
</div>
<?php 
 */
?>
<?php if ($model->isNewRecord) { ?>
    <?php //echo $this->renderPartial('_form', array('model' => $model));  ?>
<?php } else { ?>
    <?php echo $this->renderPartial('_form2', array('model' => $model)); ?>
<?php } ?>
<fieldset style="float: left; width: 98%;">
    <legend>Manage Employees</legend>
    <?php
    echo "<div class='printBtn'>";
    $this->widget('ext.mPrint.mPrint', array(
        'title' => ' ', //the title of the document. Defaults to the HTML title
        'tooltip' => 'Print', //tooltip message of the print icon. Defaults to 'print'
        'text' => '', //text which will appear beside the print icon. Defaults to NULL
        'element' => '.printAllTableForThisReport', //the element to be printed.
        'exceptions' => array(//the element/s which will be ignored
            '.filters',
            '.button-column',
        ),
        'publishCss' => FALSE, //publish the CSS for the whole page?
        'visible' => Yii::app()->user->checkAccess('print'), //should this be visible to the current user?
        'alt' => 'print', //text which will appear if image can't be loaded
        'debug' => FALSE, //enable the debugger to see what you will get
        'id' => 'print-div'         //id of the print link
    ));
    echo "</div>";
    ?>
    <div class="printAllTableForThisReport">
        <?php
        $this->widget('ext.groupgridview.GroupGridView', array(
            'id' => 'employees-grid',
            'dataProvider' => $model->search(),
            'mergeColumns' => array('department_id'),
            'filter' => $model,
            'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
            'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
            'columns' => array(
                array(
                    'name' => 'id',
                    'header' => 'SL',
                    'value' => '++$row',
                    'filter' => ''
                ),
                array(
                    //'class' => 'EImageColumn',
                    'name' => 'photo',
                    'value' => 'imageShow($data->photo)',
                    'type' => 'raw',
                    //'htmlOptions' => array('style' => 'width: 70px; height:70px;'),
                //'alt' => 'Employee-Photo',
                ),
                'emp_id_no',
                'full_name',
                'contact_no',
                'address',
                'join_date',
                'email',
                //'opening_balance',
                //'monthly_installment',
                //'national_id_no',
                //'contact_no_office',
                /*
                  array(
                  'name' => 'blood_group',
                  'value' => 'Lookup::item("blood_group", $data->blood_group)',
                  'filter' => Lookup::items('blood_group'),
                  ),
                  array(
                  'name' => 'religion',
                  'value' => 'Lookup::item("religion", $data->religion)',
                  'filter' => Lookup::items('religion'),
                  ), */
                array(
                    'name' => 'is_active',
                    'value' => 'Employees::model()->statusColor($data->is_active)',
                    'filter' => Lookup::items('is_active'),
                ),
                array
                    (
                    'header' => 'Options',
                    'template' => '{update}',
                    'class' => 'CButtonColumn',
                    'buttons' => array(
                        'delete' => array(
                            'label' => 'Termination Letter',
                            'imageUrl' => Yii::app()->theme->baseUrl . '/images/termination_letter.png',
                            'url' => 'Yii::app()->controller->createUrl("delete",array("id"=>$data->id))',
                            'options' => array("target" => "_blank"),
                        ),
                    )
                ),
            )
        ));
        ?>
    </div>
</fieldset>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'viewDialog',
    'options' => array(
        'title' => 'Viewing Single Data',
        'autoOpen' => false,
        'modal' => true,
        'width' => '1200',
        'resizable' => false,
    ),
));
?>
<div id="ajaxLoaderView" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
<div id='AjFlash' style="display:none; margin-top: 20px;">

</div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>


<style>
    .grid-view table.items {
        background-color: #fff;
        border-collapse: collapse;
        width: 100%;
        border: 1px #D0E3EF solid;
        border: 1px solid gray !important;
    }
    .grid-view table.items td, .grid-view table.items th {
        border: 1px solid #FFF;
        font-size: 11px;
        font-weight: 400;
        padding: 2px 4px;
        border: 1px solid gray !important;
        text-align: center;
    }
    .grid-view table.items th {
        color: #fff;
        font-weight: 700;
        background-color: #626262;
        text-align: center;
    }
    .grid-view table.items th a {
        text-decoration: none;
        color: #FFFFFF;
    }
    .grid-view table.items tr.even {
        background-color: #f9f9f9;
    }
    .grid-view table.items tr.odd {
        background-color: #ffffff;
    }
</style>
<?php

function imageShow($image) {

    $url = Yii::app()->request->baseUrl . '/upload/empPhoto/' . $image;
    $url2 = Yii::app()->request->baseUrl . '/upload/empPhoto/preview_img.png';
    if ($image !='') {
        return '<img style="height:100px;" src="' . $url . '" />';
    } else {
        return '<img style="height:100px;" src="' . $url2 . '" />';
    }
}
?>