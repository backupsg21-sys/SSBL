<?php
if ($model->voucher_type == VoucherNoFormateAc::JURNAL) { // journal entry
    $formTitle = "Journal Voucher";
    $voucherNoType = VoucherNoFormateAc::model()->numberFormatOfThis(VoucherNoFormateAc::JURNAL);
    $bankCashOrNot = ChartOfAc::model()->notBankCashAC();
} else if ($model->voucher_type == VoucherNoFormateAc::CONTRA) { // contra entry
    $formTitle = "Contra Voucher";
    $voucherNoType = VoucherNoFormateAc::model()->numberFormatOfThis(VoucherNoFormateAc::CONTRA);
    $bankCashOrNot = ChartOfAc::model()->bankCashAC();
} else if ($model->voucher_type == VoucherNoFormateAc::PAYMENT) { // payment entry
    $formTitle = "Payment Voucher";
    $voucherNoType = VoucherNoFormateAc::model()->numberFormatOfThis(VoucherNoFormateAc::PAYMENT);
    $bankCashOrNot = ChartOfAc::model()->caWithNoChild();
} else { // receipt entry
    $formTitle = "Receipt Voucher";
    $voucherNoType = VoucherNoFormateAc::model()->numberFormatOfThis(VoucherNoFormateAc::RECEIPT);
    $bankCashOrNot = ChartOfAc::model()->caWithNoChild();
}
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'journal-entry-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
        ));
?>

<div class="formDiv">
    <fieldset class="someStyle">
        <legend>Update <?php echo $formTitle; ?></legend>
        <table>
            <tr>
                <td><?php echo $form->labelEx($model, 'entry_date'); ?></td>
                <td>
                    <?php
                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                    $dateTimePickerConfig1 = array(
                        'model' => $model, //Model object
                        'attribute' => 'entry_date', //attribute name
                        'mode' => 'date', //use "time","date" or "datetime" (default)
                        'language' => '',
                        'options' => array(
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                            'dateFormat' => 'yy-mm-dd',
                        ),
                        'htmlOptions' => array(
                            'style' => 'width: 181px;'
                        ),
                    );
                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'entry_date'); ?></td>   
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'entry_no'); ?></td>
                <td>
                    <div id="orderNo" class="receivedByDiv">
                        <?php
                        $thisPrefix = $voucherNoType;
                        echo $thisPrefix . $model->entry_no;
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'entry_no'); ?></td>   
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'voucher_type'); ?></td>
                <td>
                    <div class="receivedByDiv"><?php echo LookupAc::item('voucher_type', $model->voucher_type); ?></div>
                </td>    
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'voucher_type'); ?></td>
            </tr>
            
            
            <tr>
                <td><?php echo $form->labelEx($model, 'ca_id'); ?></td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'ca_id', CHtml::listData(ChartOfAc::model()->caWithNoChild(), 'id', 'title', 'parentGroup'), array(
                        'prompt' => 'Select',
                        'style' => 'width: 167px;'
                    ));
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'ca_id'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'amount'); ?></td>
                <td>
                    <?php echo $form->dropDownList($model, 'transaction_type', LookupAc::items('transaction_type'), array('style' => 'width: 42px;')); ?>
                    <?php echo $form->textField($model, 'amount'); ?>
                </td>    
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'transaction_type'); ?><?php echo $form->error($model, 'amount'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'narration'); ?></td>
                <td><?php echo $form->textArea($model, 'narration', array( 'style' => 'width: 181px;')); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'narration'); ?></td>   
            </tr>
        </table>
    </fieldset>
    <fieldset class="tblFooters">
        <span id="ajaxLoaderMR" style="display: none; float: left;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></span>
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Update', array('onclick' => 'loadingDivDisplay();')); ?>
    </fieldset>
</div>
<script type="text/javascript">
    function loadingDivDisplay(){
        $("#ajaxLoaderMR").show();
    }
</script>
<?php $this->endWidget(); ?>
