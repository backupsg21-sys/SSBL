<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'vds-receive-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit'=>true),
));
?>
<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'VDS Payment Form' : 'VDS Payment Form'); ?></legend>
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
            <tr>
                <td><?php echo $form->labelEx($model, 'amount'); ?></td>
                <td><?php echo $form->textField($model, 'amount', array('readonly' => true, 'disabled' => true)); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'amount'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'bank_id'); ?></td>
                <td>
                    <?php
                    echo $form->dropDownList(
                        $model, 'bank_id', CHtml::listData(ChartOfAc::model()->findAll(['condition' => 'root = 157','order'=>'title asc']), 'id', 'title'), array(
                        'prompt' => 'Select',
                    ));
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'bank_id'); ?></td>
            </tr>
            <td><?php echo $form->labelEx($model, 'account_code_id'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                    $model, 'account_code_id', AccountCode::model()->accountList(), array(
                    'prompt' => 'Select',
                ));
                ?>
            </td>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'account_code_id'); ?></td>
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
