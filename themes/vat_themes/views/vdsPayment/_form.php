<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'vds-payment-form',
    'action' => $this->createUrl('vdsPayment/create/'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>
<style>
    .custom-fieldset{
        margin: 0px;
    }
    table.poAddedTab tr th {
        color: #000;
        background-color: #dcdada;
    }
</style>
<div class="formDiv">
    <fieldset>
        <legend>VDS Certificate Form</legend>
        <table style="width: 100%">
            <tr>
                <td style="width: 30%">
                    <table style="width: 100%">
                        <tr>
                            <td>SL No. </td>
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
                                    $model, 'po_no', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'no-data'", 'order'=>'code ASC')), 'code', 'name'), array(
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
                            <td><?php echo $form->textField($model, 'chalan_no', ['readonly'=>true]); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'challan_date', ['readonly'=>true]); ?></td>
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
                            <td><?php echo $form->textField($model, 'vds_total', ['readonly'=>true]); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'vds_remaining'); ?></td>
                            <td><?php echo $form->textField($model, 'vds_remaining', ['readonly'=>true]); ?></td>
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
                                    $model, 'bank_id', CHtml::listData(ChartOfAc::model()->findAll(['condition' => 'root = 157','order'=>'title asc']), 'id', 'title'), array(
                                    'prompt' => 'Select',
                                    'style'=>'width: 310px;'
                                ));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'district_id'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList(
                                    $model, 'district_id', CHtml::listData(District::model()->findAll(['order'=>'name asc']), 'id', 'name'), array(
                                    'prompt' => 'Select',
                                    'style'=>'width:310px;'
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
                                    'style'=>'width: 310px;'
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
                            <td><?php echo $form->dropDownList($model, 'payment_method', Lookup::items('payment_type'), array('prompt' => 'select','style'=>'width:310px;')); ?></td>
                        </tr>
                        -->
                        <?php echo $form->hiddenField($model, 'vds_id'); ?>
                    </table>
                    <a href="#" class="btn btn-primary" onclick="AddNew()">Add New</a>
                </td>
                <td style="width: 70%;vertical-align: top;">
                    <fieldset class="custom-fieldset">
                        <legend>Added List</legend>
                        <table id="tbl" class="table poAddedTab">
                            <tr>
                                <th>PO No</th>
                                <th>Supplier Name</th>
                                <th>Challan No</th>
                                <th>Challan Date</th>
                                <th>VDS Total</th>
                                <th>VDS Remaining</th>
                                <th>VDS Amount</th>
                                <th>ACTION</th>
                            </tr>
                        </table>
<!--                        <table class="prodAddedTab" style="margin-top: 20px; width:100%;">
                            <tr>
                                <td><label>Total</label></td>
                                <td><label>Total SD</label></td>
                                <td><label>Total VAT</label></td>
                                <td><label>Grand Total</label></td>
                                <td><label>Total VDS</label></td>
                            </tr>
                            <tr class="final-grid-tr">
                                <td><input id="PurchaseItems_totalAmouns" name = 'PurchaseItems[total]'  type='text' value='0.00'></td>
                                <td><input id="PurchaseItems_total_sd" name = 'PurchaseItems[total_sd]'  type='text' value='0.00'></td>
                                <td><input id="PurchaseItems_totalTax" name = 'PurchaseItems[total_tax]'  type='text' value='0.00'></td>
                                <td><input id="PurchaseItems_grand_total" name = 'PurchaseItems[grand_total]'  type='text' value='0.00'></td>
                                <td>
                                    <input id="PurchaseItems_totalVds" name = 'PurchaseItems[total_vds]'  type='text' value='0.00' style="width: 90px" readonly="">
                                    <input type="checkbox" id="vd"> Copy VDS
                                </td>
                            </tr>
                        </table>-->
                    </fieldset>
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
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" />
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
    .formDiv input[type=password], .formDiv  input[type=text], .formDiv textarea{
        width: 250px;
    }
    .formDiv select{
        width: 250px !important;
    }
    .right{
        text-align: right;
    }/*
    formDiv select, formDiv input[type=text], formDiv textarea {
        width: 300px;
    }*/
</style>


<script>
    var vals = [];
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
        if (parseFloat($('#VdsPayment_vds_remaining').val())  < parseFloat($('#VdsPayment_amount').val())) {
            alertify.alert("VDS Amount is exceeded");
            return false;
        }
        var nor = $('#tbl tr').length;
        if(nor<2){
            alertify.alert("Please add to grid!");
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
            url: '<?php echo Yii::app()->baseUrl ?>/vds/jqueryVDSDetailsByPO',
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
    $('input').attr('autocomplete','off');
</script>
<script type="text/javascript">
    function stopRKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
    }
    document.onkeypress = stopRKey;
    
    function AddNew() {
        var po_no = $("#VdsPayment_po_no").val();
        if (po_no == "") {
            alertify.alert("Please select a PO No!");
            return false;
        }
        var supplier_id = $("#VdsPayment_supplier_id").val();
        if (supplier_id == "") {
            alertify.alert("Please select a supplier!");
            return false;
        }
        var payment_date = $("#payment_date").val();
        if (payment_date == "") {
            alertify.alert("Please select a Adjustment Date!");
            return false;
        }
        var VdsPayment_tr_challan_no = $("#VdsPayment_tr_challan_no").val();
        if (VdsPayment_tr_challan_no == "") {
            alertify.alert("Please select a TR Challan No!");
            return false;
        }
        var tr_challan_date = $("#tr_challan_date").val();
        if (tr_challan_date == "") {
            alertify.alert("Please select a TR Challan Date !");
            return false;
        }


        var challan_date = $("#challan_date").val();
        if (challan_date == "") {
            alertify.alert("Please select challan date!");
            return false;
        }
        var vds_total = $("#VdsPayment_vds_total").val();
        if (vds_total == "") {
            alertify.alert("Please enter vds total!");
            return false;
        }
        var amount = $("#VdsPayment_amount").val();
        if (amount == "") {
            alertify.alert("Please enter amount!");
            return false;
        }
        checkDuplicate();
        return false;
    }
    function checkDuplicate() {
        var po_no = parseInt($("#VdsPayment_po_no").val());
        if (vals.indexOf(po_no) > -1) {
            alertify.alert('Sorry! This PO is already added.');
            return false;
        } else {
            vals.push(po_no);
            add();
        }
    }
    function add() {
        var po_no = $("#VdsPayment_po_no").val();
        var po_no_txt = $("#VdsPayment_po_no option:selected").text();
        var supplier_id = $("#VdsPayment_supplier_id").val();
        var purchase_id = $("#VdsPayment_purchase_id").val();
        var supplier_id_txt = $("#supplier_name").val();
        var chalan_no = $("#VdsPayment_chalan_no").val();
        var challan_date = $("#challan_date").val();
        var vds_total = $("#VdsPayment_vds_total").val();
        var vds_remaining = $("#VdsPayment_vds_remaining").val();
        var amount = $("#VdsPayment_amount").val();
        var vds_id = $("#VdsPayment_vds_id").val();
        

        var appendTxt = "<tr class='cartList'>" +
            "<td style='text-align: left;'>" + po_no_txt + 
            "<input type='hidden' class='temp_po_no' value='"+ po_no +"' name='temp_po_no[]'/>" +
            "</td>" +
            "<td style='text-align: left;'>" + supplier_id_txt + "</td>" +
            "<td style='text-align: left;'>" + chalan_no + "</td>" +
            "<td style='text-align: left;'>" + challan_date + "</td>" +
            "<td>" + "<input style='width: 150px; text-align: right;' class='vds_total' type='text' name='temp_vds_total[]' value='" + vds_total + "' readonly>" + "</td>" +
            "<td>" + "<input style='width: 150px; text-align: right;' class='vds_total' type='text' name='temp_vds_remaining[]' value='" + vds_remaining + "' readonly>" + "</td>" +
            "<td>" + "<input style='width: 150px; text-align: right;' class='amount' type='text' name='temp_amount[]' value='" + amount + "' >" + "</td>" +         
            "<td>" +
                "<input type='hidden' value='"+ supplier_id +"' name='temp_supplier_id[]'/>" +
                "<input type='hidden' value='"+ chalan_no +"' name='temp_chalan_no[]'/>" +
                "<input type='hidden' value='"+ challan_date +"' name='temp_challan_date[]'/>" +
                "<input type='hidden' value='"+ purchase_id +"' name='temp_purchase_id[]'/>" +
                "<input type='hidden' value='"+ vds_id +"' name='temp_vds_id[]'/>" +
                "<input onclick='deleteRows($(this))' title=\"remove\" type=\"button\" class=\"rdelete dltBtn\"/>" +
            "</td>" +
            "</tr>";
        $("#tbl tr:last").after(appendTxt);
        return false;
    }
    function deleteRows(element) {
        var po_no = parseInt(element.parents('tr').find('.temp_po_no').val());        
        removeAcTitleId(vals, po_no);        
        $(element).parents(".cartList").remove();
        
        return false;
    }

    function removeAcTitleId(array, element) {
        var index = array.indexOf(element);
        array.splice(index, 1);
    }
    
</script>