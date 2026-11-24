<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'vat-receive-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit'=>true),
));
?>
<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'VAT Payment Form' : 'VAT Payment Form'); ?></legend>
        <table>
            <tr>
                <td><?php echo $form->labelEx($model, 'tr_challan_no'); ?></td>
                <td><?php echo $form->textField($model, 'tr_challan_no'); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'tr_challan_no'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'tr_challan_date'); ?></td>
                <td>
                    <?php
                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                    $dateTimePickerConfig1 = array(
                        'model' => $model, //Model object
                        'attribute' => 'tr_challan_date', //attribute name
                        'id' => 'tr_challan_date', //attribute name
                        'mode' => 'date', //use "time","date" or "datetime" (default)
                        'language' => 'en-AU',
                        'options' => array(
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                            'dateFormat' => 'yy-mm-dd',
                            'width' => '100',
                        ) // jquery plugin options
                    );
                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'tr_challan_date'); ?></td>
            </tr>
        </table>
    </fieldset>

    <fieldset class="tblFooters">
        <!--<span id="ajaxLoaderMR" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></span>-->
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Update', array('id'=>'btnSubmit', 'onclick'=>'loadingDivDisplay();')); ?>
    </fieldset>
</div>
<script type="text/javascript">
    function loadingDivDisplay(){
        $("#ajaxLoaderMR").show();
    }

</script>
<script type="text/javascript">
    function stopRKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
    }
    document.onkeypress = stopRKey;
</script>

<?php $this->endWidget(); ?>
