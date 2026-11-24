<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'vds-payment-form',
    'action' => $this->createUrl('vdsPayment/create/'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>

<div class="formDiv">
    <fieldset>
        <legend>VDS Certificate Form</legend>
        <table style="width: 70%">
            <tr>
                <td style="width: 50%">
                    <table style="width: 100%">
                        <tr>
                            <td>SL No.</td>
                            <td>
                                <?php
                                $thisQuickSellNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SERIAL_NO);
                                $max_sl = VoucherNoFormate::model()->maxValOfThis('VdsPayment', 'sl_no', 'maxSlNo');
                                ?>
                                <input type="text" value="Serial Will Generate Automatically" style="border: 1px solid #999;
                                        border-radius: 2px;
                                        color: #D50000;
                                        background-color: #D7D7D7;
                                        padding: 4px;
                                        font-weight: 700;">
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'parts_service'); ?></td>
                            <td>
                                <?php
                                $dataArray = array(1 => 'Goods', 2 => 'Service');
                                echo $form->dropDownList($model, 'parts_service', $dataArray, array(
                                    'prompt' => 'Select',
                                    'style' => 'width:310px',
                                    'ajax' => array(
                                        'type' => 'POST',
                                        'dataType' => 'json',
                                        'url' => CController::createUrl('vds/jqueryVDSPOList'),
                                        'success' => 'function(data) {
                                                $("#VdsPayment_po_no").html(data.subVDSPOList);
                                         }',
                                        'data' => array(
                                            'parts_service' => 'js:jQuery("#VdsPayment_parts_service").val()',
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
                            <td><?php echo $form->labelEx($model, 'po_no'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList(
                                    $model, 'po_no', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'no-data'", 'order' => 'code ASC')), 'code', 'name'), array(
                                    'prompt' => 'Select',
                                    'style' => 'width:310px',
                                ));
                                ?>
                                <?php echo $form->hiddenField($model, 'po_no'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'supplier_name'); ?></td>
                            <td>
                                <input id="supplier_name" class='noWidthInput lineTotalInpt' type='text' readonly>
                                <?php echo $form->hiddenField($model, 'supplier_id'); ?>
                                <?php echo $form->hiddenField($model, 'purchase_id'); ?>
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
                            <td><?php echo $form->labelEx($model, 'purpose'); ?></td>
                            <td><?php echo $form->textArea($model, 'purpose', array('rows' => 6, 'cols' => 50)); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'chalan_no'); ?></td>
                            <td><?php echo $form->textField($model, 'chalan_no', ['readonly' => true]); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'challan_date', ['readonly' => true]); ?></td>
                            <td>
                                <?php
                                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                                $dateTimePickerConfig1 = array(
                                    'model' => $model, //Model object
                                    'attribute' => 'challan_date', //attribute name
                                    'id' => 'challan_date', //attribute name
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
                            <td><?php echo $form->labelEx($model, 'tr_challan_no'); ?></td>
                            <td><?php echo $form->textField($model, 'tr_challan_no'); ?></td>
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
                            <td><?php echo $form->labelEx($model, 'vds_total'); ?></td>
                            <td><?php echo $form->textField($model, 'vds_total', ['readonly' => true]); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'vds_remaining'); ?></td>
                            <td><?php echo $form->textField($model, 'vds_remaining', ['readonly' => true]); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'amount'); ?></td>
                            <td><?php echo $form->textField($model, 'amount'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'bank_id'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList(
                                    $model, 'bank_id', CHtml::listData(ChartOfAc::model()->findAll(['condition' => 'root = 157', 'order' => 'title asc']), 'id', 'title'), array(
                                    'prompt' => 'Select',
                                    'style' => 'width: 310px;'
                                ));
                                ?>
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
                            <td></td>
                            <td><?php echo $form->error($model, 'bank_id'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'branch'); ?></td>
                            <td><?php echo $form->textField($model, 'branch'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'account_code_id'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList(
                                    $model, 'account_code_id', AccountCode::model()->accountList(), array(
                                    'prompt' => 'Select',
                                    'style' => 'width: 310px;'
                                ));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'account_code_id'); ?></td>
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
                            <td><?php echo $form->labelEx($model, 'payment_method'); ?></td>
                            <td><?php echo $form->dropDownList($model, 'payment_method', Lookup::items('payment_type'), array('prompt' => 'select', 'style' => 'width:310px;')); ?></td>
                        </tr>
                        -->
                        <?php echo $form->hiddenField($model, 'vds_id'); ?>
                    </table>
                </td>
                <td style="width: 50%">
                    <!--
                    <table style="width: 100%">
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
                            <td><?php echo $form->labelEx($model, 'account_name'); ?></td>
                            <td><?php echo $form->textField($model, 'account_name'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'account_number'); ?></td>
                            <td><?php echo $form->textField($model, 'account_number'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'bank_remarks'); ?></td>
                            <td><?php echo $form->textField($model, 'bank_remarks'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'debit_account_id'); ?></td>
                            <td>
                                <input type="text" id="de_label" name="de_label" />
                                <?php echo $form->hiddenField($model, 'debit_account_id'); ?>
                                <?php
                    $local_source = "";
                    $caWithNoChild = ChartOfAc::model()->caWithNoChild();
                    if ($caWithNoChild) {
                        $count = count($caWithNoChild);
                        $i = 1;
                        foreach ($caWithNoChild as $cwnc) {
                            $value = $cwnc->id;
                            $code = $cwnc->code;
                            $label = $cwnc->title;
                            $local_source .= '{value: ' . $value . ', label: "' . $code . ' - ' . $label . '"}';
                            if ($i == $count)
                                $local_source .= '';
                            else
                                $local_source .= ',';
                            $i++;
                        }
                    }
                    ?>
                                <script>
                                    var local_source = [<?php echo $local_source; ?>];
                                    $(function () {
                                        $( "#de_label" ).autocomplete({
                                            source: local_source,
                                            minLength: 1,
                                            select: function(event, ui) {
                                                $("#de_label").val(ui.item.label);
                                                $("#VdsPayment_debit_account_id").val(ui.item.value);
                                                return false;
                                            }
                                        });
                                    });
                                </script>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'credit_account_id'); ?></td>
                            <td>
                                <input type="text" id="ca_label" name="ca_label" />
                                <?php echo $form->hiddenField($model, 'credit_account_id'); ?>
                                <?php
                    $local_source = "";
                    $caWithNoChild = ChartOfAc::model()->caWithNoChild();
                    if ($caWithNoChild) {
                        $count = count($caWithNoChild);
                        $i = 1;
                        foreach ($caWithNoChild as $cwnc) {
                            $value = $cwnc->id;
                            $code = $cwnc->code;
                            $label = $cwnc->title;
                            $local_source .= '{value: ' . $value . ', label: "' . $code . ' - ' . $label . '"}';
                            if ($i == $count)
                                $local_source .= '';
                            else
                                $local_source .= ',';
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
                                                $("#VdsPayment_credit_account_id").val(ui.item.value);
                                                return false;
                                            }
                                        });
                                    });
                                </script>
                            </td>
                        </tr>
                    </table>
                    -->
                </td>
            </tr>
        </table>

    </fieldset>
    <div id="jsPart"></div>
    <div id="formResult" class="ajaxTargetDiv"></div>
    <div id="formResultError" class="ajaxTargetDivErr"></div>
    <fieldset class="tblFooters">
        <?php
        echo CHtml::ajaxSubmitButton('Create', CHtml::normalizeUrl(array('vdsPayment/create', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                    $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#tbl tr.cartList").remove();
                        sl=0;
                        $("#poForReport").val(data.lastPONO);
                        $("#formResultError").hide();
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#vds-payment-form")[0].reset();
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
                            $("#vds-payment-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#vds-payment-form #"+key+"_em_").show();
                        });
                    }       
                }',
            'beforeSend' => 'function(){
                $("#ajaxLoader").show();
             }'
        ), array('class' => 'add-more-btn', 'id' => 'btnSubmit'));
        ?>
        <div id="ajaxLoader" style="display: none; float: left;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/>
        </div>
    </fieldset>
</div>
<?php $this->endWidget(); ?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'soReportDialogBox',
    'options' => array(
        'title' => 'VDS Voucher Preview',
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
    /*input[type=password], input[type=text], select , textarea{
        width: 310px !important;
    }

    textarea {
        float: left;
    }*/
    .formDiv input[type=password], .formDiv input[type=text], .formDiv select, .formDiv textarea {
        width: 250px !important;
    }

    /*
        .formDiv select{
            width: 225px !important;
        }*/
    .right {
        text-align: right;
    }

    /*
        formDiv select, formDiv input[type=text], formDiv textarea {
            width: 300px;
        }*/
</style>


<script>
    $("#btnSubmit").click(function () {
        if ($('#VdsPayment_parts_service').val() == '') {
            alertify.alert("Please Select Goods/Service");
            return false;
        }
        if ($('#VdsPayment_po_no').val() == '') {
            alertify.alert("Please Select PO");
            return false;
        }
        if ($('#VdsPayment_supplier_id').val() == '') {
            alertify.alert("Please Insert Supplier");
            return false;
        }

        if ($('#VdsPayment_po_no').val() == '') {
            alertify.alert("Please Insert PO");
            return false;
        }

        if ($('#payment_date').val() == '') {
            alertify.alert("Please Select Adjustment Date");
            return false;
        }

        if ($('#VdsPayment_amount').val() == '') {
            alertify.alert("Please Insert Amount");
            return false;
        }
        if ($('#VdsPayment_amount').val() <= 0) {
            alertify.alert("VDS Amount can not be 0");
            return false;
        }
        if (parseFloat($('#VdsPayment_vds_remaining').val()) < parseFloat($('#VdsPayment_amount').val())) {
            alertify.alert("VDS Amount is exceeded");
            return false;
        }

        /*
                if ($('#VdsPayment_payment_method').val() == '') {
                    alertify.alert("Please Select Payment Method");
                    return false;
                }*/
    });

    $("#VdsPayment_po_no").change(function () {
        var sl_no_po = $("#VdsPayment_po_no").val();

        $.ajax({
            url: '<?php echo Yii::app()->baseUrl ?>/index.php/vds/jqueryVDSDetailsByPO',
            cache: false,
            type: "POST",
            data: {
                sl_no_po: sl_no_po,
            },
            success: function (data) {
                var obj = JSON.parse(data);
                $("#supplier_name").val(obj.supplierName);
                $("#VdsPayment_supplier_id").val(obj.supplierId);
                $("#VdsPayment_vds_id").val(obj.vds_id);
                $("#VdsPayment_vds_total").val(obj.vds_total);
                $("#VdsPayment_vds_remaining").val(obj.vds_remaining);
                $("#VdsPayment_purchase_id").val(obj.purchaseId);
                $("#VdsPayment_chalan_no").val(obj.challan_no);
                $("#challan_date").val(obj.challan_date);
                return false;
            }
        });
    });
</script>
<script>
    $('input').attr('autocomplete', 'off');
</script>
<script type="text/javascript">
    function stopRKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type == "text")) {
            return false;
        }
    }

    document.onkeypress = stopRKey;
</script>