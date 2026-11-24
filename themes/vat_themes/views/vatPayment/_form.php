<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'vat-payment-form',
    'action' => $this->createUrl('vatPayment/save/'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
$this->pageTitle = "VAT Payment";
?>

<!-- TOOL TIP START -->
<style>
.tiBR{
    padding: 10px 20px; margin-left:10px; max-width: 700px;
    background-color: #9FDAEE; color: #059; border-radius: 3px;
    display: none; box-shadow: 2px 4px 4px rgba(0, 0, 0, 0.1); border: 1px solid #2BB0D7;}
.tiBTNR:hover{ cursor: pointer}
</style>
<div class="tiBR">
    <span><b>Info!</b></span>
    <li>jkltkrletkre kltjrtklrtj rkltre</li>
    <li>jkltkrletkre  rkltre</li>
    <li>the re ar optyyt </li>
</div>
<script>
    var tShow = false;
    function tipsShow(){
        if(tShow){
            $(".tiBR").slideUp(); tShow = false;
        }else{
            $(".tiBR").slideDown(); tShow = true;
        }
    }
</script>
<!-- TOOL TIP END -->

<div class="formDiv">
    <fieldset>
        <legend>VAT Payment Form</legend>
        <table style="width: 70%">
            <tr>
                <td style="width: 50%">
                    <table style="width: 100%">
                        <tr>
                            <td>SL No. </td>
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
                            <td><?php echo $form->labelEx($model, 'payment_month'); ?></td>
                            <td>
                                <?php
                                $this->widget('ext.EJuiMonthPicker.EJuiMonthPicker', array(
                                    'model' => $model,
                                    'attribute' => 'payment_month',
                                    'options' => array(
                                        'yearRange' => '-5:+15',
                                        'dateFormat' => 'M-yy',
                                    ),
                                ));
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td><?php echo $form->labelEx($model, 'purpose'); ?></td>
                            <td><?php echo $form->dropDownList($model, 'purpose', Lookup::items('vat_payment_purpose'), array('prompt' => 'select','style'=>'width:310px;')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'paid_by'); ?></td>
                            <td><?php echo $form->textArea($model, 'paid_by'); ?></td>
                        </tr>
                        <!--
                        <tr>
                            <td><?php echo $form->labelEx($model, 'chalan_no'); ?></td>
                            <td><?php echo $form->textField($model, 'chalan_no'); ?></td>
                        </tr>-->
                        <tr>
                            <td>Payment Perpose</td>
                            <td><?php echo $form->textArea($model, 'note'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'amount'); ?></td>
                            <td><?php echo $form->textField($model, 'amount'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'payment_code_id'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList(
                                    $model, 'payment_code_id', PaymentCode::model()->accountList(), array(
                                    'prompt' => 'Select',
                                    'style' => 'width: 310px;',
                                ));
                                ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top">
                    <table style="width: 100%">
                        <tr>
                            <td><?php echo $form->labelEx($model, 'account_code_id'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList(
                                    $model, 'account_code_id', AccountCode::model()->accountList(), array(
                                    'prompt' => 'Select',
                                    'style' => 'width: 310px;',
                                ));
                                ?>
                            </td>
                        </tr>
                        <tr style="display: none">
                            <td></td>
                            <td>
                                <?php echo $form->textField($model, 'payment_method', ['value' => PurchaseItems::BANK]); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'district_id'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList(
                                    $model, 'district_id', CHtml::listData(District::model()->findAll(['order' => 'name asc']), 'id', 'name'), array(
                                    'prompt' => 'Select',
                                    'style' => 'width:310px;'
                                ));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'bank_id'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList(
                                    $model, 'bank_id', CHtml::listData(ChartOfAc::model()->findAll(['condition' => 'root = 157','order'=>'title asc']), 'id', 'title'), array(
                                    'prompt' => 'Select',
                                    'style'=>'width:310px;'
                                ));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'branch'); ?></td>
                            <td><?php echo $form->textField($model, 'branch'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'account_name'); ?></td>
                            <td><?php echo $form->textField($model, 'account_name'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'account_number'); ?></td>
                            <td><?php echo $form->textField($model, 'account_number'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'cheque_date'); ?></td>
                            <td>
                                <?php
                                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                                $dateTimePickerConfig1 = array(
                                    'model' => $model, //Model object
                                    'attribute' => 'cheque_date', //attribute name
                                    'id' => 'cheque_date', //attribute name
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
                            <td><?php echo $form->labelEx($model, 'cheque_number'); ?></td>
                            <td><?php echo $form->textField($model, 'cheque_number'); ?></td>
                        </tr>
                        <!--
                        <tr>
                            <td><?php $form->labelEx($model, 'bank_remarks'); ?></td>
                            <td><?php $form->textArea($model, 'bank_remarks',['style'=>'width:304px;']); ?></td>
                        </tr>
                        -->
                    </table>
                </td>
            </tr>
        </table>

    </fieldset>
    <div id="jsPart"></div>
    <div id="formResult" class="ajaxTargetDiv"></div>
    <div id="formResultError" class="ajaxTargetDivErr"></div>
    <fieldset class="tblFooters">
        <?php
        echo CHtml::ajaxSubmitButton('Create', CHtml::normalizeUrl(array('vatPayment/save', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                    $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#tbl tr.cartList").remove();
                        sl=0;

                        $("#SellItems_grand_total").val(0);
                        $("#orderNo").html(data.newOrderNo);
                        $("#poForReport").val(data.lastPONO);
                        $("#formResultError").hide();
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#vat-payment-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $("#ajaxLoaderReport").show(); 
                        $("#soReportDialogBox").dialog("open");
                        $("#AjFlashReportPo").html(data.soReportInfo).show();
                        $("#ajaxLoaderReport").hide();
                    }else if(data.status=="errorBalance"){
                        $("#formResultError").show();
                        $("#formResultError").html("You Must Add Items First!");
                    }else{
                        $("#formResult").show();
                        $("#formResultError").html("Data not saved. Please solve the above errors!");
                        $.each(data, function(key, val) {
                            $("#vat-payment-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#vat-payment-form #"+key+"_em_").show();
                        });
                    }       
                }',
            'beforeSend' => 'function(){
                $("#ajaxLoader").show();
             }'
        ), array('class' => 'add-more-btn', 'id' => 'btnSubmit'));
        ?>
        <div id="ajaxLoader" style="display: none; float: left;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" />
        </div>
    </fieldset>
</div>



<style>
    select {
        width: 84%;
    }
</style>
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

<style>
   .formDiv input[type=password], .formDiv input[type=text], .formDiv select {
        width: 310px !important;
    }
    textarea {
        float: left;
        border: 1px solid #AAA;
        width: 310px;
    }
</style>
<script>
    $('input').attr('autocomplete','off');
</script>
<script>
    $("#btnSubmit").click(function () {
        if ($('#VatPayment_customer_name').val() == '') {
            alertify.alert("Please Insert Customer");
            return false;
        }
        if ($('#payment_date').val() == '') {
            alertify.alert("Please Insert Payment Date");
            return false;
        }
		if ($('#VatPayment_payment_month').val() == '') {
            alertify.alert("Please Insert Payment Month");
            return false;
        }
        if ($('#VatPayment_purpose').val() == '') {
            alertify.alert("Please Select Purpose");
            return false;
        }
        /*if ($('#VatPayment_chalan_no').val() == '') {
            alertify.alert("Please Insert Challan No");
            return false;
        }*/
        if ($('#VatPayment_amount').val() == '') {
            alertify.alert("Please Insert Amount");
            return false;
        }
        if ($('#VatPayment_payment_code_id').val() == '') {
            alertify.alert("Please Select Payment Code");
            return false;
        }
        if ($('#VatPayment_account_code_id').val() == '') {
            alertify.alert("Please Select Account Code");
            return false;
        }
    });
</script>