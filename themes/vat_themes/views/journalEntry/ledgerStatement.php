<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />
<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'journal-entry-form',
        ));
?>
<fieldset>
    <legend>Ledger Statement</legend>
    <table class="summaryTab">
        <tr>
            <td><?php echo $form->labelEx($model, 'startDate'); ?></td>
            <td><?php echo $form->labelEx($model, 'endDate'); ?></td>
            <td><?php echo $form->labelEx($model, 'ca_id'); ?></td>
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
            <td>
                <input type="text" id="ca_label" name="ca_label" />
                <?php echo $form->hiddenField($model, 'ca_id'); ?>
                <?php
                $local_source="";
                $caWithNoChild= ChartOfAc::model()->caWithNoChild();
                if($caWithNoChild){
                    $count=count($caWithNoChild);
                    $i=1;
                    foreach($caWithNoChild as $cwnc){
                        $value=$cwnc->id;
                        $label=$cwnc->title;
                        $local_source.='{value: '.$value.', label: "'.$label.'"}';
                        if($i==$count)
                            $local_source.='';
                        else
                            $local_source.=',';
                        $i++;
                    }
                }
                ?>
                <script>
                    var local_source = [<?php echo $local_source; ?>];


					$(function () {
					$( "#ca_label" ).autocomplete({
					source: local_source,
					minLength: 1,
					select: function(event, ui) {
					$("#ca_label").val(ui.item.label);
					$("#JournalEntry_ca_id").val(ui.item.value);
					return false;
					}
					});
					});
                </script>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <?php
                echo CHtml::submitButton('Search', array(
                    'ajax' => array(
                        'dataType' => 'json',
                        'type' => 'POST',
                        'url' => CController::createUrl('journalEntry/ledgerStatementView'),
                        'beforeSend' => 'function(){
                                if($("#JournalEntry_startDate").val()=="" || $("#JournalEntry_endDate").val()=="" || $("#JournalEntry_ca_id").val()==""){
                                    alertify.alert("Warning! Please select date range & account title!");
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
    </table>
</fieldset>

<?php $this->endWidget(); ?>

<fieldset>
    <legend>Ledger Statement <div class="ajaxLoaderResultView" style="display: none; float: right;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div></legend>
    <div id="resultDiv"></div>
</fieldset>
<div id="jsPart"></div>