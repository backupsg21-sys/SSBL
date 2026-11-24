<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'purchase-items-form',
    //'action' => $this->createUrl('purchaseItems/saveDirectPurchaseUpdate/'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
        ));
$yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
if ($yourCompanyInfo) {
    $yourCompanyName = $yourCompanyInfo->company_name;
    $yourCompanyLocation = $yourCompanyInfo->location;
    $yourCompanyRoad = $yourCompanyInfo->road;
    $yourCompanyHouse = $yourCompanyInfo->house;
    $yourCompanyContact = $yourCompanyInfo->contact;
    $yourCompanyEmail = $yourCompanyInfo->email;
    $yourCompanyWeb = $yourCompanyInfo->web;
} else {
    $yourCompanyName = '';
    $yourCompanyLocation = '';
    $yourCompanyRoad = '';
    $yourCompanyHouse = '';
    $yourCompanyContact = '';
    $yourCompanyEmail = '';
    $yourCompanyWeb = '';
}
$yourCompanyFullAddress = $yourCompanyName . ", " . $yourCompanyRoad . ", " . $yourCompanyHouse . ", " . $yourCompanyLocation;
$purchase_id = $model->id;
?>
<?php echo CHtml::link('<< Back to Debit Note',array('purchaseItems/purchaseReturn'),['style'=>'padding: 5px !important;margin-left: 12px','class'=>'customBtn']); ?>
<br><br>
<div class="formDiv">
    <fieldset>
        <legend>Add Debit Note</legend>
        <fieldset style="float: left; width:98%;" class="someStyle">
            <legend>ORDER Information</legend>
            <table class="form-box">
                <tr>
                    <td style="width:380px;">
                        <table>
                            <tr>
                                <td width="100px"><?php echo $form->labelEx($model, 'po_no'); ?></td>
                                <td>
                                    <div id="orderNo" class="receivedByDiv" style="background-color: #f3f2f2">
                                        <?php
                                        $thisPurchaseNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::PURCHASE_ORDER);
                                        ?>
                                        <?=!empty($model->id)?$thisPurchaseNoFormat.'-'.$model->po_no:''?>
                                    </div>
                                    <?php echo $form->error($model, 'po_no'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'ref_no'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'ref_no', ['style'=>'background-color: #f3f2f2']); ?>
                                    <?php echo $form->error($model, 'ref_no'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Local/Import</td>
                                <td>
                                    <input type="text" value="<?=($model->local_import==54)?'Local':'Import'?>" style="background-color:#f3f2f2;" readonly>
                                    <input type="hidden" id="local_import_flag" value="<?=$model->local_import?>">
                                    <?php echo $form->error($model, 'local_import'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'issue_date'); ?></td>
                                <td>
                                    <input type="text" value="<?=$model->issue_date?>" style="background-color:#f3f2f2;" disabled/>
                                    <?php echo $form->error($model, 'issue_date'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="color:#014848;">Supplier Bin</td>
                                <td>
                                    <input type="text" name="bin" id="bin" value="<?=Suppliers::model()->supplierBin($model->supplier_id)?>" style="background-color:#f3f2f2;" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'return_challan_no'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'return_challan_no'); ?>
                                    <?php echo $form->error($model, 'return_challan_no'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'return_date'); ?></td>
                                <td>
                                    <?php
                                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                                    $dateTimePickerConfig1 = array(
                                        'model' => $model, //Model object
                                        'attribute' => 'return_date', //attribute name
                                        'mode' => 'date', //use "time","date" or "datetime" (default)
                                        'language' => 'en-AU',
                                        'options' => array(
                                            'changeMonth' => 'true',
                                            'changeYear' => 'true',
                                            'dateFormat' => 'dd-mm-yy',
                                            'width' => '100',
                                        ) // jquery plugin options
                                    );
                                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                                    ?>
                                    <?php echo $form->error($model, 'return_date'); ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="vertical-align:top">
                        Reason for return :
                        <table>
                            <tr>
                                <td>
                                    <?php echo $form->textArea($model, 'reason', array('rows' => 4, 'cols' => 100)); ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="width: 98%; float: left; overflow: scroll; height: 360px;" class="someStyle">
            <legend>Added List</legend>
            <table id="tbl" class="prodAddedTab">
                <tr>
                    <th style="width:20px;">SL</th>
                    <th style="width:160px;">ITEM</th>
                    <th>Qty</th>
                    <th>Return Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Total <br>Return</th>
                    <th>SD(%)</th>
                    <th>SD</th>
                    <th>SD <br>Return</th>
                    <th>VAT(%)</th>
                    <th>VAT</th>
                    <th>VAT <br>Return</th>
                    <th>Tax</th>
                    <th>Tax <br>Return</th>
                    <th>Net Total</th>
                    <th>Net Total <br>Return</th>
                </tr>
                                
                <?php
                $sql = "SELECT * FROM purchase_details WHERE purchase_id  = $purchase_id ORDER BY id ASC";
                $purchase_details = Yii::app()->db->createCommand($sql)->queryAll();
                
                if(!empty($purchase_details)){
                    $i = 1;
                    foreach($purchase_details as $po_items){
                        $model_id = $po_items["model_id"];
                        $model_name = ProdModels::model()->prodNameOfThis($po_items["model_id"]);
                        $unit_name = Units::model()->unitLabel($po_items["unit_id"]);

                        $sql2 = "SELECT SUM(qty) AS totalQty, SUM(sd_new) AS totalSd, SUM(vat_new) AS totalVat, SUM(tax_new) AS totalTotalTax, SUM(total_new) AS totalTotal, SUM(net_total_new) AS totalNetTotal FROM purchase_return WHERE purchase_id  = $purchase_id AND model_id=$model_id ORDER BY id";
                        $returnInfo = Yii::app()->db->createCommand($sql2)->queryRow();
                        $lineQtyOrg = $po_items['order_qty'] -  $returnInfo['totalQty'];
                        $totalSd = $po_items['sd'] -  $returnInfo['totalSd'];
                        $totalVat = $po_items['vat'] -  $returnInfo['totalVat'];
                        $totalTotalTax = $po_items['total_tax'] -  $returnInfo['totalTotalTax'];
                        $totalTotal = $po_items['total'] -  $returnInfo['totalTotal'];
                        $totalNetTotal = $po_items['net_total'] -  $returnInfo['totalNetTotal'];
                        ?>                        
                        <tr class='cartList'>
                            <td class='sno'><?=$i?></td>
                            <td style='text-align: left;'><?=!empty($model_name)?$model_name:''?></td>
                            <td>
                                <input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: center;' class='lineQty' type='text' name='PurchaseItems[temp_order_qty][]' value='<?=!empty($lineQtyOrg)?$lineQtyOrg:''?>' readonly>
                                <input type="hidden" class="lineQtyOrg" value="<?= !empty($lineQtyOrg) ? $lineQtyOrg:''?>">
                                <input type="hidden" class="cvRate" value="<?= !empty($po_items['cv_rate']) ? $po_items['cv_rate']:'0'?>">
                                <input type="hidden" class="cd_percent" value="<?= !empty($po_items['cd_percent']) ? $po_items['cd_percent']:'0'?>">
                                <input type="hidden" class="rd_percent" value="<?= !empty($po_items['rd_percent']) ? $po_items['rd_percent']:'0'?>">
                                <input type="hidden" class="ait_percent" value="<?= !empty($po_items['ait_percent']) ? $po_items['ait_percent']:'0'?>">
                                <input type="hidden" class="at_percent" value="<?= !empty($po_items['at_percent']) ? $po_items['at_percent']:'0'?>">
                            </td>
                            <td>
                                <input onkeyup="calculateGridInLine($(this))" style='width: 90%; text-align: center;' class='adjQty' name='temp_adjustment_qty[]' type='text'>
                                <input type='hidden' class='fixedVat' value='<?=!empty($po_items['base_vat'])?$po_items['base_vat']:'0'?>'>
                            </td>
                            <td><input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: right;' class='pprice' type='text' name='PurchaseItems[temp_purchase_price][]' value='<?=!empty($po_items["unit_price"])? $po_items["unit_price"]:0.00?>' readonly></td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='totalAmount' type='text' name='PurchaseItems[temp_total_amount][]' value='<?= !empty($totalTotal) ? $totalTotal:''?>' readonly>
                            </td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='totalAmountNew' type='text' name='PurchaseItems[temp_total_amount_new][]' readonly>
                                <input class='totalAmountNewCdRd' type='hidden' name='totalAmountCdRd[]'>
                            </td>
                            <td><input style='width: 90%; text-align: right;' class='sd_percent' type='text' name='PurchaseItems[temp_sd_percent][]' value='<?=!empty($po_items["sd_percent"])?number_format($po_items["sd_percent"],2,'.',''):0.00?>' readonly></td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='sd' type='text' name='PurchaseItems[temp_sd][]' value='<?= !empty($totalSd) ? $totalSd:''?>' readonly>
                            </td>
                            <td> <input style='width: 90%; text-align: right;' class='sdNew' type='text' name='PurchaseItems[temp_sd_new][]' readonly> </td>
                            <td><input style='width: 90%; text-align: right;' class='vatPercent' type='text' name='PurchaseItems[temp_vat_percent][]' value='<?=!empty($po_items["vat_percent"])?number_format($po_items["vat_percent"],2,'.',''):0.00?>' readonly></td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='vatAmount' type='text' name='PurchaseItems[temp_vat_amount][]' value='<?= !empty($totalVat) ? $totalVat:''?>' readonly>
                            </td>
                            <td> <input style='width: 90%; text-align: right;' class='vatAmountNew' type='text' name='PurchaseItems[temp_vat_amount_new][]' readonly> </td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='taxAmount' type='text' name='PurchaseItems[temp_tax_amount][]' value='<?= !empty($totalTotalTax) ? $totalTotalTax:''?>' readonly>
                            </td>
                            <td> <input style='width: 90%; text-align: right;' class='taxAmountNew' type='text' name='PurchaseItems[temp_tax_amount_new][]' readonly> </td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='netTotal' type='text' name='PurchaseItems[temp_net_total][]' value='<?= !empty($totalNetTotal) ? $totalNetTotal:''?>' readonly>
                            </td>
                            <td> <input style='width: 90%; text-align: right;' class='netTotalNew' type='text' name='PurchaseItems[netTotal_new][]' readonly>
                                <input type='hidden' class='model_id' name='PurchaseItems[temp_model_id][]' value='<?=!empty($po_items["model_id"])?$po_items["model_id"]:''?>'>
                                <input type='hidden' name='PurchaseItems[temp_unit][]' value='<?=!empty($po_items["unit_id"])?$po_items["unit_id"]:''?>'>
                                <input type='hidden' name='PurchaseItems[temp_base_vat][]' value='<?=!empty($po_items["base_vat"])?$po_items["base_vat"]:''?>'>
                                <input type='hidden' name='PurchaseItems[temp_base_vat_percent][]' value='<?=!empty($po_items["base_vat_percent"])?$po_items["base_vat_percent"]:''?>'>
                                <input type='hidden' class='vatType'  name='PurchaseItems[temp_vat_calculation_method][]' value='<?=!empty($po_items["vat_calculation_method"])?$po_items["vat_calculation_method"]:''?>'>
                                <input type='hidden' class='parts_service'  name='PurchaseItems[temp_parts_service][]' value='<?=!empty($po_items["parts_service"])?$po_items["parts_service"]:''?>'>
                            </td>
                        </tr>
                <?php
                $i++;
                    }
                }
                ?>                
            </table>
            <table class="prodAddedTab" style="margin-top: 20px;width: 800px;">
                <tr>
                    <td><label> Total Return</label></td>
                    <td><label> Total SD Return</label></td>
                    <td><label> Total VAT Return</label></td>
                    <td><label> Grand Total Return</label></td>
                </tr>
               <tr class="final-grid-tr">
                    <td><input id="PurchaseItems_totalAmouns" name = 'PurchaseItems[total]'  type='text' value="0.00"></td>
                    <td><input id="PurchaseItems_total_sd" name = 'PurchaseItems[total_sd]'  type='text' value="0.00"></td>
                    <td><input id="PurchaseItems_totalTax" name = 'PurchaseItems[total_tax]'  type='text' value="0.00"></td>
                    <td><input id="PurchaseItems_grand_total" name = 'PurchaseItems[grand_total]'  type='text' value="0.00"></td>
                </tr>
            </table>
        </fieldset>
    </fieldset>   
    
    <div id="jsPart"></div>
    <div id="formResult" class="ajaxTargetDiv"></div>
    <div id="formResultError" class="ajaxTargetDivErr"></div>
    <fieldset class="tblFooters">
        <?php
        echo CHtml::ajaxSubmitButton('Submit', CHtml::normalizeUrl(array('purchaseItems/purchaseReturnForm', 'id' => $model->id)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                    $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#formResultError").hide();
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#purchase-items-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $("#ajaxLoaderReport").show(); 
                        $("#poReportDialogBox").dialog("open");
                        $("#AjFlashReportPo").html(data.poReportInfo).show();
                        $("#ajaxLoaderReport").hide();
                    }else if(data.status=="errorBalance"){
                        $("#formResultError").show();
                        $("#formResultError").html("You Must Add Items First!");
                    }else{
                        $("#formResult").show();
                        $("#formResultError").html("Data not saved. Please solve the above errors!");
                        $.each(data, function(key, val) {
                            $("#purchase-items-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#purchase-items-form #"+key+"_em_").show();
                        });
                    }       
                }',
            'beforeSend' => 'function(){ 
                /*var PurchaseItems_return_challan_no = $("#PurchaseItems_return_challan_no").val();
                if (PurchaseItems_return_challan_no=="") {
                    alertify.alert("Please enter return challan no!");
                    return false;
                } */
                var PurchaseItems_return_date = $("#PurchaseItems_return_date").val();
                if (PurchaseItems_return_date=="") {
                    alertify.alert("Please enter return date!");
                    return false;
                }    
                var sumAdj = 0;
                $(".adjQty").each(function () {
                    var valueAdjQty = $(this).val();
                    if (!isNaN(valueAdjQty) && valueAdjQty.length != 0) {
                        sumAdj += parseFloat(valueAdjQty);
                    }
                });
                if(sumAdj <1){
                    alertify.alert("Please input return qty!");
                    return false;
                }
                $("#ajaxLoader").show();
             }'
                ), array('class' => 'add-more-btn'));
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
    'id' => 'poReportDialogBox',
    'options' => array(
        'title' => 'PR Voucher Preview',
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
<input type="hidden" id="unitName" />
<input type="hidden" id="unitId" />
<input type="hidden" id="unitPrice" />
<input type="hidden" id="vat" />
<input type="hidden" id="vatType" />
<input type="hidden" id="rebatable" />
<input type="hidden" id="sd" />

<script>
$('form').attr('autocomplete','off');
</script>
<script>
    // THIS WILL SET HOW MUCH FLOTING VALUE WILL SHOW (Local=2, Import=4)
    if($("#local_import_flag").val()==54) { // Local = 54, import = 55
        var desiVal = 2;
    }else{
        var desiVal = 4;
    }

    function calculateGridInLine(element) {

        var lineQtyOrg = parseFloat(element.parents('tr').find('.lineQtyOrg').val()); if (isNaN(lineQtyOrg)) lineQtyOrg = 0;
        var adjQty = parseFloat(element.parents('tr').find('.adjQty').val()); if (isNaN(adjQty)) adjQty = 0;
        var lineQty = parseFloat(element.parents('tr').find('.lineQty').val()); if (isNaN(lineQty)) lineQty = 0;
        var linePrice = parseFloat(element.parents('tr').find('.pprice').val()); if (isNaN(linePrice)) linePrice = 0;

        var cvRate = parseFloat(element.parents('tr').find('.cvRate').val()); if (isNaN(cvRate)) cvRate = 0;
        var vatPercent = parseFloat(element.parents('tr').find('.vatPercent').val()); if (isNaN(vatPercent)) vatPercent = 0;
        var vatType = parseFloat(element.parents('tr').find('.vatType').val());
        var fixedVat = parseFloat(element.parents('tr').find('.fixedVat').val()); if (isNaN(fixedVat)) fixedVat = 0;

        var sd_percent = parseFloat(element.parents('tr').find('.sd_percent').val()); if (isNaN(sd_percent)) sd_percent = 0;
        var cd_percent = parseFloat(element.parents('tr').find('.cd_percent').val()); if (isNaN(cd_percent)) cd_percent = 0;
        var rd_percent = parseFloat(element.parents('tr').find('.rd_percent').val()); if (isNaN(rd_percent)) rd_percent = 0;

        var ait_percent = parseFloat(element.parents('tr').find('.ait_percent').val()); if (isNaN(ait_percent)) ait_percent = 0;
        var at_percent = parseFloat(element.parents('tr').find('.at_percent').val()); if (isNaN(at_percent)) at_percent = 0;

        if (adjQty > lineQtyOrg) {
            alertify.alert('Quantity excedeed!');
            element.parents('tr').find('.lineQty').val(lineQtyOrg);
            element.parents('tr').find('.adjQty').val('');
            element.parents('tr').find('.vatAmountNew').val('');
            element.parents('tr').find('.taxAmountNew').val('');
            element.parents('tr').find('.totalAmountNew').val('');
            calculateGridInLine(element);
            return false;
        }
        var setLineQty = lineQtyOrg - adjQty;
        element.parents('tr').find('.lineQty').val(setLineQty);

       if($("#local_import_flag").val()==54){ // Local = 54, import = 55
            var totalAmount = linePrice * setLineQty;
            var totalAmountNew = linePrice * adjQty;
        }else{
            var rowTotal = setLineQty * linePrice;
            var totalAmount = ((rowTotal * cvRate) / 100) + rowTotal;

            var rowTotalNew = adjQty * linePrice;
            var totalAmountNew = ((rowTotalNew * cvRate) / 100) + rowTotalNew;
        }


        if(vatType==3){ //3=fixed vat
            var vatAmount = setLineQty * fixedVat;
            var vatAmountNew = adjQty * fixedVat;
        }else{

            if($("#local_import_flag").val()==54){ // Local = 54, import = 55

                var sd = (totalAmount * sd_percent) / 100;
                var sdNew = (totalAmountNew * sd_percent) / 100;

                var vatAmount = (((totalAmount + sd) * vatPercent) / 100);
                var vatAmountNew = (((totalAmountNew + sdNew) * vatPercent) / 100);

                var taxAmount = vatAmount;
                var taxAmountNew = vatAmountNew;

                var totalAmountNewCdRd = totalAmountNew;

            }else{

                // GIVEN
                var cd = (totalAmount * cd_percent) / 100;
                var rd = (totalAmount * rd_percent) / 100;
                var sd = ((totalAmount + cd + rd) * sd_percent) / 100;
                var vatAmount = ((totalAmount + cd + rd + sd) * vatPercent) / 100;

                var ait = (totalAmount * ait_percent) / 100;
                var at = ((totalAmount + cd + rd + sd) * at_percent) / 100;
                var taxAmount = cd + rd + sd + vatAmount + ait + at;

                // REMAINING
                var cdNew = (totalAmountNew * cd_percent) / 100;
                var rdNew = (totalAmountNew * rd_percent) / 100;
                var sdNew = ((totalAmountNew + cdNew + rdNew) * sd_percent) / 100;
                var vatAmountNew = ((totalAmountNew + cdNew + rdNew + sdNew) * vatPercent) / 100;

                var aitNew = (totalAmountNew * ait_percent) / 100;
                var atNew = ((totalAmountNew + cdNew + rdNew + sdNew) * at_percent) / 100;
                var taxAmountNew = cdNew + rdNew + sdNew + vatAmountNew + aitNew + atNew;

                var totalAmountNewCdRd = totalAmountNew + cdNew + rdNew;

            }
        }
        var netTotal = totalAmount + taxAmount + sd;
        var netTotalNew = totalAmountNew + taxAmountNew + sdNew;

        element.parents('tr').find('.totalAmount').val(totalAmount.toFixed(desiVal));
        element.parents('tr').find('.totalAmountNew').val(totalAmountNew.toFixed(desiVal));
        element.parents('tr').find('.totalAmountNewCdRd').val(totalAmountNewCdRd.toFixed(desiVal));
        element.parents('tr').find('.sd').val(sd.toFixed(desiVal));
        element.parents('tr').find('.sdNew').val(sdNew.toFixed(desiVal));
        element.parents('tr').find('.vatAmount').val(vatAmount.toFixed(desiVal));
        element.parents('tr').find('.vatAmountNew').val(vatAmountNew.toFixed(desiVal));
        element.parents('tr').find('.taxAmount').val(taxAmount.toFixed(desiVal));
        element.parents('tr').find('.taxAmountNew').val(taxAmountNew.toFixed(desiVal));
        element.parents('tr').find('.netTotal').val(netTotal.toFixed(desiVal));
        element.parents('tr').find('.netTotalNew').val(netTotalNew.toFixed(desiVal));
        calculateGridTotal();
    }

    function calculateGridTotal() {
        var sumTotalCdRd = 0;
        $(".totalAmountNewCdRd").each(function () {
            var valueTotal = $(this).val();
            if (!isNaN(valueTotal) && valueTotal.length != 0) {
                sumTotalCdRd += parseFloat(valueTotal);
            }
        });
        $("#PurchaseItems_totalAmouns").val(sumTotalCdRd.toFixed(desiVal));

        var sdTotal = 0;
        $(".sdNew").each(function () {
            var valueSd = $(this).val();
            if (!isNaN(valueSd) && valueSd.length != 0) {
                sdTotal += parseFloat(valueSd);
            }
        });
        $("#PurchaseItems_total_sd").val(sdTotal.toFixed(desiVal));

        var sumtax = 0;
        $(".vatAmountNew").each(function () {
            var valueTax = $(this).val();
            if (!isNaN(valueTax) && valueTax.length != 0) {
                sumtax += parseFloat(valueTax);
            }
        });
        $("#PurchaseItems_totalTax").val(sumtax.toFixed(desiVal));


        var sumTotal = 0;
        $(".totalAmountNew").each(function () {
            var valueTotal = $(this).val();
            if (!isNaN(valueTotal) && valueTotal.length != 0) {
                sumTotal += parseFloat(valueTotal);
            }
        });
        var totalWithTax = parseFloat(sdTotal + sumtax + sumTotal).toFixed(desiVal);
        $("#PurchaseItems_grand_total").val(totalWithTax);
    }
</script> 
<style>
.form-box input[type=password], .form-box  input[type=text], .form-box  select{
    width: 215px !important;
}
.form-box  select{
    width: 225px !important;
}
.right{
    text-align: right;
}
.cartList td{
   padding: 0 !important;
}
.cartList input[type=text] {
    border: 1px solid #eae8e8;
    padding: 4px 2px;
}
.final-grid-tr input[type=text]{
    border: 1px solid #fff;
    text-align: center;
    font-weight: bold;
}
</style>
<script type="text/javascript">
function stopRKey(evt) {
    var evt = (evt) ? evt : ((event) ? event : null);
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
    if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
}
document.onkeypress = stopRKey;
</script>