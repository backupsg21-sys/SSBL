<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />
<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'journal-entry-form',
        ));
?>
<fieldset>
    <legend>Income Statement</legend>
    <table class="summaryTab">
        <tr>
            <td colspan="2">Date Range</td>
            <td rowspan="3">
                <?php
                echo CHtml::submitButton('Search', array(
                    'ajax' => array(
                        'dataType' => 'json',
                        'type' => 'POST',
                        'url' => CController::createUrl('journalEntry/incomeStatementView'),
                        'beforeSend' => 'function(){
                                if($("#JournalEntry_startDate").val()=="" || $("#JournalEntry_endDate").val()==""){
                                    alertify.alert("Warning! Please select date range!");
                                    return false;
                                }else{
                                    $(".ajaxLoaderResultView").show();
                                }
                            }',
                        'success' => 'function(data) {
                            $(".ajaxLoaderResultView").hide();
                            $("#resultDiv").html(data.content);
                        }',
                    ),
                    'class' => 'custom_check_button',
                        )
                );
                ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model, 'startDate'); ?></td>
            <td><?php echo $form->labelEx($model, 'endDate'); ?></td>
        </tr>
        <tr>
            <td>
                <?php
                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $dateTimePickerConfig1 = array(
                    'model' => $model,
                    'attribute' => 'startDate',
                    'mode' => 'date',
                    'language' => 'en-AU',
                    'options' => array(
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        'dateFormat' => 'yy-mm-dd',
                        'width' => '100',
                    )
                );
                $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                ?>
            </td>
            <td>
                <?php
                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $dateTimePickerConfig2 = array(
                    'model' => $model,
                    'attribute' => 'endDate',
                    'mode' => 'date',
                    'language' => 'en-AU',
                    'options' => array(
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        'dateFormat' => 'yy-mm-dd',
                        'width' => '100',
                    )
                );
                $this->widget('CJuiDateTimePicker', $dateTimePickerConfig2);
                ?>
            </td>
        </tr>
    </table>
</fieldset>

<?php $this->endWidget(); ?>

<fieldset>
    <legend>Income Statement <div class="ajaxLoaderResultView" style="display: none; float: right;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div></legend>
    <div id="resultDiv"></div>
</fieldset>
<div id="jsPart"></div>