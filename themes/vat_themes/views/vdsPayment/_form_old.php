<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'vat-payment-form',
    'action' => $this->createUrl('vatPayment/save/'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>

<div class="formDiv">
    <fieldset style="width: 70%">
        <legend>VAT Payment Form</legend>
        <fieldset class="someStyle" style="float: left; width: 98%;">
            <table style="width: 98%" class="prodAddedTab">
                <tr>
                    <td><?php echo $form->labelEx($model, 'so_no'); ?></td>
                    <td><?php echo $form->labelEx($model, 'customer_id'); ?></td>
                    <td><?php echo $form->labelEx($model, 'amount'); ?></td>
                    <td><?php echo $form->labelEx($model, 'payment_date'); ?></td>
                    <td><?php echo $form->labelEx($model, 'purpose'); ?></td>
                    <td><?php echo $form->labelEx($model, 'payment_method'); ?></td>
                    <td><?php echo $form->labelEx($model, 'notes'); ?></td>
                    <td><label>Action</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="text" id="so_no"/>
                        <script>
                            $(function () {
                                $("#so_no").autocomplete({
                                    source: function (request, response) {
                                        var so_no = request.term;
                                        $.post('<?php echo Yii::app()->baseUrl ?>/index.php/sellItems/jquerySOSearch', {"so_no": so_no},
                                            function (data) {
                                                response(data);
                                            }, "json");
                                    },
                                    minLength: 1,
                                    select: function (event, ui) {
                                        $("#so_no").val(ui.item.label);
                                        $("#customer_name").val(ui.item.customerName);
                                        $("#customer_id").val(ui.item.customerId);
                                        $("#VatPayment_amount").val(ui.item.vat);
                                        $("#sales_id").val(ui.item.salesId);
                                        return false;
                                    }
                                });
                            });
                        </script>
                    </td>
                    <td>
                        <input id="customer_name" class='noWidthInput lineTotalInpt' type='text'>
                        <input id="customer_id" class='noWidthInput lineTotalInpt' type='hidden'>
                        <input id="sales_id" class='noWidthInput lineTotalInpt' type='hidden'>
                    </td>
                    <td><?php echo $form->textField($model, 'amount'); ?></td>
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
                    <td>
                        <?php
                        $dataArray = array(1 => 'VAT', 2 => 'SD');
                        echo $form->dropDownList($model, 'purpose', $dataArray);
                        ?>
                    </td>
                    <td>
                        <?php
                        $pMethod = array(1 => 'Cash', 2 => 'Cheque', 3 => 'Bank Deposit', 4 => 'Card');
                        echo $form->dropDownList($model, 'payment_method', $pMethod);
                        ?>
                    </td>
                    <td><?php echo $form->textField($model, 'note'); ?></td>
                    <td><input class="addDown" style="margin-top: 0; text-align: center" title="Add" type="button"
                               value="" onclick="AddNew()"/></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="width: 98%; float: left; overflow: scroll; height: 360px;" class="someStyle">
            <legend>Added List</legend>
            <table id="tbl" class="prodAddedTab">
                <tr>
                    <th>SL</th>
                    <th>SO No</th>
                    <th>Customer</th>
                    <th>Payment Date</th>
                    <th>Purpose</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
            </table>
            <?php /*
              <table class="prodAddedTab" style="margin-top: 20px;">
              <tr>
              <td><label>Total Vat</label></td>
              <td><label>Total VDS</label></td>
              <td><label>Total</label></td>
              <td><label>Grand Total</label></td>
              </tr>
              <tr>
              <td><input id="SellItems_totalTax" name = 'SellItems[total_tax]' class='noWidthInput lineTotalInpt right' type='text' value='0.00'></td>
              <td><input id="SellItems_totalVDS" name = 'SellItems[total_vds]' class='noWidthInput lineTotalInpt right' type='text' value='0.00'></td>
              <td><input id="SellItems_total" name = 'SellItems[total]' class='noWidthInput lineTotalInpt right' type='text' value='0.00'></td>
              <td><input id="SellItems_grand_total" name = 'SellItems[grand_total]' class='noWidthInput lineTotalInpt right' type='text' value='0.00'></td>
              </tr>
              </table> */ ?>
        </fieldset>
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
        ), array('class' => 'add-more-btn'));
        ?>
        <div id="ajaxLoader" style="display: none; float: left;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/>
        </div>
    </fieldset>
</div>

<script>
    var vals = [];
    $("#services").hide();
    $("#SellItems_parts_service").change(function () {
        var SellItems_parts_service = $("#SellItems_parts_service").val();
        $("#model_id_text").val('');
        $("#SellItems_model_id").val('');
        $("#service_id_text").val('');
        $("#service_id").val('');
        if (SellItems_parts_service == 1) {
            $("#parts").show();
            $("#services").hide();
        } else if (SellItems_parts_service == 2) {
            $("#parts").hide();
            $("#services").show();
        }
    });

    /**********GRID ADDING FROM HERE*************/

    function AddNew() {

        var soNo = $("#so_no").val();
        if (soNo != "")
            checkDuplicate();
        else {
            alertify.alert('Sorry! something went wrong!');
            return false;
        }
    }

    function checkDuplicate() {
        var soNo = $("#so_no").val();
        if (vals.indexOf(soNo) > -1) {
            alertify.alert('Sorry! this product is already added!');
            return false;
        } else {
            vals.push(soNo);
            addRow();
            $("#so_no").val('');
        }
    }

    function addRow() {
        $("#tbl").each(function () {
            var slNumber = $('#tbl tr').length;
            var appendTxt = "<tr class='cartList'>" +
                "<td class='sno'>" +
                slNumber +
                "</td>" +
                "<td>" + "<input id='vatInpt" + slNumber + "' style='width: 90%; text-align: right;'" + slNumber + "'  type='text' name='SellItems[temp_so_no][]' value='" + $('#so_no').val() + "' readonly>" + "</td>" +
                "<td>" + "<input id='vatInpt" + slNumber + "' style='width: 90%; text-align:;'" + slNumber + "'  type='text' name='SellItems[temp_customer_name][]' value='" + $('#customer_name').val() + "' readonly>" + "</td>" +
                "<td>" + "<input id='vatInpt" + slNumber + "' style='width: 90%; text-align: right;'" + slNumber + "'  type='text' name='SellItems[temp_payment_date][]' value='" + $('#payment_date').val() + "' readonly>" + "</td>" +
                "<td>" + "<input id='vatInpt" + slNumber + "' style='width: 90%; text-align: right;'" + slNumber + "'  type='text' value='" + $("#VatPayment_purpose option:selected").text() + "' readonly>" + "</td>" +
                "<td>" + "<input id='vatInpt" + slNumber + "' style='width: 90%; text-align: right;'" + slNumber + "'  type='text' value='" + $("#VatPayment_payment_method option:selected").text() + "' readonly>" + "</td>" +
                "<td>" + "<input id='vatInpt" + slNumber + "' style='width: 90%; text-align: right;'" + slNumber + "'  type='text' name='SellItems[temp_amount][]' value='" + $('#VatPayment_amount').val() + "' readonly>" + "</td>" +
                "<td>" + "<input id='vatInpt" + slNumber + "' style='width: 90%; text-align: right;'" + slNumber + "'  type='text' name='SellItems[temp_note][]' value='" + $('#VatPayment_note').val() + "' readonly>" + "</td>" +
                "<td>" +
                "<input type='hidden' name='SellItems[temp_customer_id][]' value='" + $('#customer_id').val() + "'>" +
                "<input type='hidden' name='SellItems[temp_sales_id][]' value='" + $('#sales_id').val() + "'>" +
                "<input type='hidden' name='SellItems[temp_purpose][]' value='" + $('#VatPayment_purpose').val() + "'>" +
                "<input type='hidden' name='SellItems[temp_payment_method][]' value='" + $('#VatPayment_payment_method').val() + "'>" +
                "<input onclick='deleteRows($(this))' title=\"remove\" id='" + slNumber + "' style='width: 90%; text-align: center;' type=\"button\" class=\"rdelete\"/>" +
                "</td>" +
                "</tr>";

            if ($('tbody', this).length > 0) {
                $('tbody', this).append(appendTxt);
            } else {
                $(this).append(appendTxt);
            }
        });
        calculateGridTotal();
        $("#so_no").val('');
    }

    function deleteRows(element) {
        var modelId = element.parents('tr').find('.model_text').val();
        removeAcTitleId(vals, modelId);
        $(element).parents("tr").remove();
        calculateGridTotal();
    }

    function removeAcTitleId(array, element) {
        var index = array.indexOf(element);
        array.splice(index, 1);
    }

    $("#tbl td input.rdelete").live("click", function () {
        var idCounter = $(this).attr("id");
        grandTotal = $("#SellItems_grand_total").val() - $('#lineTtlInpt' + idCounter).val();
        $("#SellItems_grand_total").val(grandTotal);
        var srow = $(this).parent().parent();
        srow.remove();
        $("#tbl td.sno").each(function (index, element) {
            $(element).text(index + 1);
        });
        calculateGridTotal();
    });
</script>
<script>
    function calculateGridInLine(element) {
        var lineQty = parseFloat(element.parents('tr').find('.lineQty').val());
        var linePrice = parseFloat(element.parents('tr').find('.pprice').val());
        var vatPercent = parseFloat(element.parents('tr').find('.vatPercent').val());
        if (isNaN(lineQty))
            lineQty = 0;
        if (isNaN(linePrice))
            linePrice = 0;
        if (isNaN(vatPercent))
            vatPercent = 0;
        if (isNaN(vatAmount))
            vatAmount = 0;
        if (isNaN(taxAmount))
            taxAmount = 0;
        if (isNaN(totalAmount))
            totalAmount = 0;

        var totalAmount = linePrice * lineQty;
        var vatAmount = ((totalAmount * vatPercent) / 100);
        var taxAmount = vatAmount;
        var netTotal = taxAmount + totalAmount;
        element.parents('tr').find('.vatAmount').val(vatAmount.toFixed(2));
        element.parents('tr').find('.taxAmount').val(taxAmount.toFixed(2));
        element.parents('tr').find('.totalAmount').val(totalAmount.toFixed(2));
        element.parents('tr').find('.netTotal').val(netTotal.toFixed(2));
        calculateGridTotal();
    }

    function calculateGridRebate(element) {
        var rebatePercent = parseFloat(element.parents('tr').find('.rebatePercent').val());
        if (isNaN(rebatePercent))
            rebatePercent = 0;
        var taxAmount = parseFloat(element.parents('tr').find('.taxAmount').val());
        if (isNaN(taxAmount))
            taxAmount = 0;
        var rebateAmouns = (taxAmount / rebatePercent).toFixed(2);
        element.parents('tr').find('.rebateAmount').val(rebateAmouns);

        var sum = 0;
        $(".rebateAmount").each(function () {
            var value = $(this).val();
            if (!isNaN(value) && value.length != 0) {
                sum += parseFloat(value);
            }
        });
        $("#SellItems_totalRebate").val(sum.toFixed(2));
    }

    function calculateGridTotal() {
        var sumtax = 0;
        $(".taxAmount").each(function () {
            var valueTax = $(this).val();
            if (!isNaN(valueTax) && valueTax.length != 0) {
                sumtax += parseFloat(valueTax);
            }
        });

        $("#SellItems_totalTax").val(sumtax.toFixed(2));
        var sumTotal = 0;
        $(".totalAmount").each(function () {
            var valueTotal = $(this).val();
            if (!isNaN(valueTotal) && valueTotal.length != 0) {
                sumTotal += parseFloat(valueTotal);
            }
        });
        var totalWithTax = parseFloat(sumtax + sumTotal).toFixed(2);
        $("#SellItems_total").val(sumTotal.toFixed(2));
        $("#SellItems_grand_total").val(totalWithTax);
    }
</script>
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
    .form-box input[type=password], .form-box input[type=text], .form-box select {
        width: 215px !important;
    }

    .form-box select {
        width: 225px !important;
    }

    .right {
        text-align: right;
    }
</style>