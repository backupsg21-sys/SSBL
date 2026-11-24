<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'prod-items-form',
    'action' => $this->createUrl('sellItems/apiData'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>

<div class="formDiv">
    <fieldset>
        <legend><?php echo($model->isNewRecord ? 'Import Sales Information' : 'Import Sales Information'); ?></legend>
        <table>
            <tr>
                <td><?php echo $form->labelEx($model, 'sales_date'); ?></td>
                <td>
                    <?php
                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                    $dateTimePickerConfig1 = array(
                        'model' => $model, //Model object
                        'attribute' => 'sales_date', //attribute name
                        'mode' => 'date', //use "time","date" or "datetime" (default)
                        'language' => 'en-AU',
                        'options' => array(
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                            'dateFormat' => 'yy-mm-dd',
                            'width' => '100',
                        ), // jquery plugin options
                        'htmlOptions' => [
//                            'value' => date('Y-m-d'),
                            'placeholder' => "YYYY-MM-DD",
                        ]
                    );
                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'sales_date'); ?></td>
            </tr>
        </table>
        <div id="statusMsg" style="float: left; width: 100%;">
            <?php if (Yii::app()->user->hasFlash('warning')): ?>
                <div class="flash-notice">
                    <?php echo Yii::app()->user->getFlash('warning'); ?>
                </div>
            <?php endif; ?>
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="flash-success">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php endif; ?>

            <?php if (Yii::app()->user->hasFlash('error')): ?>
                <div class="flash-error">
                    <?php echo Yii::app()->user->getFlash('error'); ?>
                </div>
            <?php endif; ?>
        </div>
    </fieldset>

    <fieldset class="tblFooters">
        <?php
        echo CHtml::submitButton("Import", array('id' => 'btSubmit', 'class' => 'btn', 'name' => 'Import', 'title' => 'Import sales order information'));
        ?>
    </fieldset>
</div>

<?php $this->endWidget(); ?>
