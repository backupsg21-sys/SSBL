<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'mushak-form',
    'action' => Yii::app()->createUrl('/purchaseitems/mushakNineOneSave'),
        ));
$this->pageTitle = "Mushak 9.1 (VAT Return)";
?>
<style>
    #start_date, #end_date, #translation_type{ width:170px }
</style>
<fieldset>
    <legend>Mushak 9.1 (VAT Return Save)</legend>
    <table style="text-align: left">
        <tr>
            
            <td style="border: 1px solid #fff;">
                From
                <?php
                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $dateTimePickerConfig1 = array(
                    'model' => $model, //Model object
                    'attribute' => 'start_date', //attribute name
                    'name' => 'start_date', //attribute name
                    'mode' => 'date', //use "time","date" or "datetime" (default)
                    'language' => 'en-AU',
                    'options' => array(
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        'dateFormat' => 'dd-mm-yy',
                        'width' => '100',
                    )
                );
                $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                ?>
                &nbsp;&nbsp;
                To
                <?php
                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $dateTimePickerConfig2 = array(
                    'model' => $model, //Model object
                    'attribute' => 'end_date', //attribute name
                    'name' => 'end_date', //attribute name
                    'mode' => 'date', //use "time","date" or "datetime" (default)
                    'language' => 'en-AU',
                    'options' => array(
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        'dateFormat' => 'dd-mm-yy',
                        'width' => '100',
                    )
                );
                $this->widget('CJuiDateTimePicker', $dateTimePickerConfig2);
                ?>&nbsp;&nbsp;
                <?php
                $translation_type = array( 1 => 'Bangla', 2 => 'English');
                echo $form->dropDownList($model, 'translation_type', $translation_type, ['id'=>'translation_type']);
                ?>
                <?php
                echo CHtml::ajaxSubmitButton('Report', CHtml::normalizeUrl(array('purchaseitems/mushakNineOneSave', 'render' => true)), array(
                    'dataType' => 'json',
                    'type' => 'post',
                    'success' => 'function(data) {
                    $("#ajaxLoader").hide(); 
                    $("#resultDiv").html(data.content);
                }',
                    'beforeSend' => 'function(){   
                if($("#start_date").val()=="" || $("#end_date").val()==""){
                    alertify.alert("Please select from date & to date!");
                    return false;
                }else{
                    $("#ajaxLoader").show();
                }
             }',
                ));
                ?>
            </td>
        </tr>
    </table>
    
    <div id="ajaxLoader" style="display: none;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
    <div id="resultDiv"></div>
</fieldset>
<script>
    $('input').attr('autocomplete','off');
</script>
<?php $this->endWidget(); ?>