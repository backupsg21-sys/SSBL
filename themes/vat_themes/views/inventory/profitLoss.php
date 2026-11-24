<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />

<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'inventory-form',
            'action' => Yii::app()->createUrl('/inventory/profitLossView'),
        ));
?>
<fieldset>
    <legend>Profit-Loss</legend>
    <table class="summaryTab">
        <tr>
            <td><?php echo $form->label($model, 'startDate'); ?></td>
            <td><?php echo $form->label($model, 'endDate'); ?></td>
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
                        'dateFormat' => 'yy-mm-dd',
                        'width' => '100',
                    )
                );
                $this->widget('CJuiDateTimePicker', $dateTimePickerConfig2);
                ?>
            </td>  
        </tr>  
        <tr style="border: none;">
            <td colspan="2" style="text-align: left; border: none;">
                <?php
                    echo CHtml::ajaxSubmitButton('Search', CHtml::normalizeUrl(array('inventory/profitLossView', 'render' => true)), array(
                        'dataType' => 'json',
                        'type' => 'post',
                        'success' => 'function(data) {
                                        $("#ajaxLoader").hide(); 
                                        $("#resultDiv").html(data.content);
                                    }',
                        'beforeSend' => 'function(){  
                                    if($("#Inventory_startDate").val()=="" && $("#Inventory_endDate").val()==""){
                                        alertify.alert("Warning! Please select time range!");
                                        return false;
                                    }
                                    $("#ajaxLoader").show();
                                 }',
                    ));
                ?>
            </td>
        </tr>
    </table>
</fieldset>

<?php $this->endWidget(); ?>

<fieldset style="float: left; width: 98%;">
    <legend>Profit Loss Report <span style=" float: right; margin-left: 8px;"><div id="ajaxLoader" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div><span></legend>
    <div id="resultDiv">
        
    </div>
</fieldset>

