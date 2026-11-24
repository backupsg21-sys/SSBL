<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'adjustments-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>

<div class="formDiv">
    <fieldset>
        <legend>VAT Adjustment Form</legend>
        <table style="width: 1200px">
            <tr>
                <td style="width: 480px; vertical-align: top">
                    <table style="width: 100%">
                        <tr>
                            <td>SL No.</td>
                            <td>
                                <?php
                                $thisQuickSellNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SERIAL_NO);
                                $max_sl = VoucherNoFormate::model()->maxValOfThis('VatPayment', 'sl_no', 'maxSlNo');
                                ?>
                                <input type="text" value="Serial Will Generate Automatically" style="border: 1px solid #999;
                                        border-radius: 2px;
                                        color: #D50000;
                                        background-color: #D7D7D7;
                                        min-width: 60%;
                                        padding: 4px;
                                        font-weight: 700;">
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'payment_date'); ?></td>
                            <td>
                                <?php
                                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                                $dateTimePickerConfig1 = array(
                                    'model' => $model, //Model object
                                    'attribute' => 'payment_date', //attribute name
                                    'id' => 'payment_date', //attribute name
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
                            <td><?php echo $form->labelEx($model, 'effective_date'); ?></td>
                            <td>
                                <?php
                                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                                $dateTimePickerConfig1 = array(
                                    'model' => $model, //Model object
                                    'attribute' => 'effective_date', //attribute name
                                    'id' => 'effective_date', //attribute name
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
                            <td>
                                <?php echo $form->labelEx($model, 'nine_one_part'); ?>
                            </td>
                            <td>
                                <?php
                                echo $form->dropDownList(
                                    $model, 'nine_one_part', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'nine_one_part' ", 'order' => 'code ASC')), 'code', 'name'), array(
                                    'prompt' => 'Select',
                                    'style' => 'width:310px',
                                    'ajax' => array(
                                        'type' => 'POST',
                                        'dataType' => 'json',
                                        'url' => CController::createUrl('treasuryPurpose/jqueryPurposeByPart'),
                                        'success' => 'function(data) {
                                                $("#Adjustments_treasury_purpose").html(data.subPurposeList);
                                         }',
                                        'data' => array(
                                            'nine_one_part' => 'js:jQuery("#Adjustments_nine_one_part").val()',
                                        ),
                                        'beforeSend' => 'function(){
                                                    //document.getElementById("Services_sub_vat_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                                        'complete' => 'function(){
                                            //document.getElementById("Services_sub_vat_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                        }',
                                    ),
                                ));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo $form->labelEx($model, 'treasury_purpose'); ?>
                            </td>
                            <td>
                                <?php
                                echo $form->dropDownList(
                                    $model, 'treasury_purpose', CHtml::listData(TreasuryPurpose::model()->findAll(array('condition' => "code_key= $model->treasury_purpose", 'order' => 'name ASC')), 'code_key', 'name'), array(
                                    'prompt' => 'Select',
                                    'style' => 'width:286px'
                                ));
                                ?>

                                <!-- PURPOSE CREATE BUTTON -->

                                <?php
                                echo CHtml::link('', "", // the link for open the dialog
                                    array(
                                        'class' => 'add-additional-btn',
                                        'onclick' => "{addPurpose(); $('#addPurposeDialog').dialog('open');}"));
                                ?>

                                <?php
                                $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                                    'id' => 'addPurposeDialog',
                                    'options' => array(

                                        'title' => 'Add Purpose',
                                        'autoOpen' => false,
                                        'modal' => true,
                                        'width' => 984,
                                        'resizable' => false,
                                    ),
                                ));
                                ?>
                                <div class="divForForm">
                                    <div class="ajaxLoaderFormLoad" style="display: none;"><img
                                                src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/>
                                    </div>
                                </div>

                                <?php $this->endWidget(); ?>

                                <script type="text/javascript">
                                    // here is the magic
                                    function addPurpose() {
                                        <?php
                                        echo CHtml::ajax(array(
                                            'url' => array('treasuryPurpose/createPurposeFromOutSide'),
                                            'data' => "js:$(this).serialize()",
                                            'type' => 'post',
                                            'dataType' => 'json',
                                            'beforeSend' => "function(){
                                                    $('.ajaxLoaderFormLoad').show();
                                                    
                                                $('#Adjustments_nine_one_part').val('');
                                                $('#Adjustments_treasury_purpose').html('');
                                                }",
                                            'complete' => "function(){
                                                    $('.ajaxLoaderFormLoad').hide();
                                                }",
                                            'success' => "function(data){
                                            if (data.status == 'failure')
                                            {
                                                $('#addPurposeDialog div.divForForm').html(data.div);
                                                      // Here is the trick: on submit-> once again this function!
                                                $('#addPurposeDialog div.divForForm form').submit(addPurpose);
                                            }
                                            else
                                            {
                                                $('#addPurposeDialog div.divForForm').html(data.div);
                                                setTimeout(\"$('#addPurposeDialog').dialog('close') \",1000);
                                                $('#PurchaseItems_supplier_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
                                            }
                                        }",
                                        ))
                                        ?>
                                        return false;
                                    }
                                </script>
                            </td>
                        </tr>
                        <tr id="purpose-code" style="display: none">
                            <td><?php echo $form->labelEx($model, 'treasury_purpose_code'); ?></td>
                            <td><?php echo $form->textField($model, 'treasury_purpose_code', ['readonly' => true, 'style' => 'background-color:#f9f9f4;']); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'challan_no'); ?></td>
                            <td><?php echo $form->textField($model, 'challan_no'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'amount'); ?></td>
                            <td><?php echo $form->textField($model, 'amount'); ?></td>
                        </tr>

                    </table>
                </td>
                <td style="vertical-align: top;">
                    <table style="width: 100%">
                        <tr>
                            <td><?php echo $form->labelEx($model, 'notes'); ?></td>
                            <td><?php echo $form->textArea($model, 'note', ['rows' => 4, 'cols' => 50]); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'payment_method'); ?></td>
                            <td><?php echo $form->dropDownList($model, 'payment_method', Lookup::items('payment_type'), array('prompt' => 'select', 'style' => 'width:310px;')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'bank_id'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList(
                                    $model, 'bank_id', CHtml::listData(ChartOfAc::model()->findAll(['condition' => 'root = 157', 'order' => 'title asc']), 'id', 'title'), array(
                                    'prompt' => 'Select',
                                    'style' => 'width:310px;'
                                ));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'branch'); ?></td>
                            <td><?php echo $form->textField($model, 'branch'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'cheque_number'); ?></td>
                            <td><?php echo $form->textField($model, 'cheque_number'); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </fieldset>
    <div id="jsPart"></div>
    <div id="formResult" class="ajaxTargetDiv"></div>
    <div id="formResultError" class="ajaxTargetDivErr"></div>
    <fieldset class="tblFooters">
        <span id="ajaxLoaderMR" style="display: none;"><img
                    src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/></span>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Update', array('onclick' => 'loadingDivDisplay();')); ?>
    </fieldset>
</div>


<?php $this->endWidget(); ?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'soReportDialogBox',
    'options' => array(
        'title' => 'Sales Voucher Preview',
        'autoOpen' => false,
        'modal' => true,
        'width' => 1030,
        'resizable' => false,
    ),
));
?>
<div id='AjFlashReportPo' style="display:none;">
</div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>


<script>
    $("#Adjustments_treasury_purpose").change(function () {
        var purpose_key = $("#Adjustments_treasury_purpose").val();

        $.ajax({
            url: '<?php echo Yii::app()->baseUrl ?>/treasurypurpose/jqueryPurposeCodeSearch',
            cache: false,
            type: "POST",
            data: {
                purpose_key: purpose_key,
            },
            success: function (data) {
                if (data) {
                    $("#purpose-code").show();
                    $("#Adjustments_treasury_purpose_code").val(data);
                } else {
                    $("#purpose-code").hide();
                    $("#Adjustments_treasury_purpose_code").val('');
                }
            }
        });
    });
    $("#btnSubmit").click(function () {
        if ($('#payment_date').val() == '') {
            alertify.alert("Please Insert Payment Date");
            return false;
        }
		if ($('#effective_date').val() == '') {
            alertify.alert("Please Insert Effective Date");
            return false;
        }
        if ($('#Adjustments_nine_one_part').val() == '') {
            alertify.alert("Please Select Part");
            return false;
        }
        if ($('#Adjustments_treasury_purpose').val() == '') {
            alertify.alert("Please Select Purpose");
            return false;
        }
        if ($('#Adjustments_amount').val() == '') {
            alertify.alert("Please Insert Amount");
            return false;
        }
        if ($('#Adjustments_payment_method').val() == '') {
            alertify.alert("Please Select Payment Method");
            return false;
        }
    });
</script>

<style>
    .formDiv input[type=password], .formDiv input[type=text], .formDiv select {
        width: 310px !important;
    }

    .formDiv textarea {
        float: left;
        width: 58% !important;
    }
</style>