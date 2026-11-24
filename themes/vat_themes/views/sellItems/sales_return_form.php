<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'sell-items-form',
    //'action' => $this->createUrl('sellItems/update/'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
        ));
?>
<div class="formDiv">
    <fieldset>
        <legend>Add Credit Note</legend>
        <fieldset style="float: left; width:98%;" class="someStyle">
            <legend>Sales Information</legend>
            <table width="1200px">
                <tr>
                    <td width="380px">
                        <table  class="form-box">
                            <tr>
                                <td width="100px"><?php echo $form->labelEx($model, 'so_no'); ?></td>
                                <td>
                                    <div id="orderNo" class="receivedByDiv" style="background-color: #f3f2f2">
                                        <?php
                                        //$thisSalesNo = VoucherNoFormate::model()->maxValOfThis('SellItems', 'so_no', 'maxSONo');
                                        $thisQuickSellNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SELL_ORDER);
                                        echo $thisQuickSellNoFormat .'-'. $model->so_no;
                                        ?>
                                    </div>
                                    <?php echo $form->error($model, 'so_no'); ?>
                                </td>
                            </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'sales_type'); ?></td>
                    <td>
                        <input name="type" id="type" style="background-color:#f3f2f2;" type="text" value="<?=($model->sales_type==1)?'Local':'Export'?>" disabled>
                        <?php echo $form->hiddenField($model, 'sales_type'); ?>
                    </td>   
                </tr> 
                <tr>
                    <td><?php echo $form->labelEx($model, 'sales_date'); ?></td>
                    <td>
                        <input type="text" value="<?=$model->sales_date?>" style="background-color:#f3f2f2;" disabled/>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'customer_id'); ?></td>
                    <td width="252px">
                        <input type="text" value="<?= Customers::model()->customerName($model->customer_id)?>" style="background-color:#f3f2f2;" disabled/>
                    </td>
                </tr>
                <tr>
                    <td style="color:#014848;">Customer Bin</td>
                    <td>
                        <input type="text" name="bin" style="background-color:#f3f2f2;" value="<?= Customers::model()->customerBin($model->customer_id)?>" disabled>
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
                    <td style="vertical-align:top;">                        
                        <?php
                        if($model->sales_type==2){?>
                        <table  class="form-box">
                            <tr>
                                <td><?php echo $form->labelEx($model, 'export_type'); ?></td>
                                <td>
                                    <?php
                                    $dataArray = array(1 => 'Deemed', 2 => 'Direct');
                                    echo $form->dropDownList($model, 'export_type', $dataArray);
                                    ?>
                                    <?php echo $form->error($model, 'export_type'); ?>
                                </td>   
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'challan_no'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'challan_no'); ?>
                                    <?php echo $form->error($model, 'challan_no'); ?>
                                </td>   
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'challan_date'); ?></td>
                                <td>
                                    <?php
                                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                                    $dateTimePickerConfig3 = array(
                                        'model' => $model, //Model object
                                        'attribute' => 'challan_date', //attribute name
                                        'mode' => 'date', //use "time","date" or "datetime" (default)
                                        'language' => 'en-AU',
                                        'options' => array(
                                            'changeMonth' => 'true',
                                            'changeYear' => 'true',
                                            'dateFormat' => 'dd-mm-yy',
                                            'width' => '100',
                                        ) // jquery plugin options
                                    );
                                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig3);
                                    ?>
                                    <?php echo $form->error($model, 'challan_date'); ?>
                                </td>   
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'lc_no'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'lc_no'); ?>
                                    <?php echo $form->error($model, 'lc_no'); ?>
                                </td>   
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'lc_date'); ?></td>
                                <td>
                                    <?php
                                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                                    $dateTimePickerConfig4 = array(
                                        'model' => $model, //Model object
                                        'attribute' => 'lc_date', //attribute name
                                        'mode' => 'date', //use "time","date" or "datetime" (default)
                                        'language' => 'en-AU',
                                        'options' => array(
                                            'changeMonth' => 'true',
                                            'changeYear' => 'true',
                                            'dateFormat' => 'dd-mm-yy',
                                            'width' => '100',
                                        ) // jquery plugin options
                                    );
                                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig4);
                                    ?>
                                    <?php echo $form->error($model, 'lc_date'); ?>
                                </td>   
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'ship_by'); ?></td>
                                <td>
                                    <?php 
                                    echo $form->dropDownList(
                                            $model, 'ship_by', CHtml::listData(ShipBy::model()->findAll(array('order' => 'id ASC')), 'id', 'ship_by'), array(
                                        'prompt' => 'Select',
                                    ));
                                    ?>
                                    <?php echo $form->error($model, 'ship_by'); ?>
                                </td>   
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'dest_country_id'); ?></td>
                                <td>
                                    <?php 
                                    echo $form->dropDownList(
                                            $model, 'dest_country_id', CHtml::listData(Countries::model()->findAll(array('order' => 'country ASC')), 'id', 'country'), array(
                                        'prompt' => 'Select',
                                    ));
                                    ?>
                                    <?php echo $form->error($model, 'dest_country_id'); ?>
                                </td>   
                            </tr>
                        </table> 
                        
                        <?php
                        }
                        ?>
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
                    <th>ITEM</th>
                    <th>GOODS/ SERVICE</th>
                    <th>Qty</th>
                    <th>Qty Return</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Total Return</th>
                    <th>SD(%)</th>
                    <th>SD</th>
                    <th>SD Return</th>
                    <th>VAT(%)</th>
                    <th>VAT</th>
                    <th>VAT Return</th>
                    <th>Tax</th>
                    <th>Tax Return</th>
                    <th>Net Total</th>
                    <th>Net Total Return</th>
                </tr>
                <?php
                $i=1;
                if(!empty($sellDetailsModel)){
                    foreach($sellDetailsModel as $item){

                        $sales_id = $item['sales_id'];
                        $model_id = $item["model_id"];
                        if($item['parts_service']==1){ // 1 = Parts, 2 = Service
                            $model_name = ProdModels::model()->prodNameOfThis($item['model_id']);
                            $type = 'Parts';
                        }else if($item['parts_service']==2){ // 1 = Parts, 2 = Service
                            $model_name = Services::model()->prodNameOfThis($item['model_id']);
                            $type = 'Service';
                        }
                        $unit_name = Units::model()->unitLabel($item['unit_id']);

                        $sql2 = "SELECT SUM(qty) AS totalQty, SUM(sd_new) AS totalSd, SUM(vat_new) AS totalVat, SUM(tax_new) AS totalTotalTax, SUM(total_new) AS totalTotal, SUM(net_total_new) AS totalNetTotal FROM sales_return WHERE sales_id  = $sales_id AND model_id=$model_id ORDER BY id";
                        $connection = Yii::app()->db;
                        $command = $connection->createCommand($sql2);
                        $returnInfo = $command->queryRow();
                        $lineQtyOrg = $item['sales_qty'] -  $returnInfo['totalQty'];
                        $totalSd = $item['sd'] -  $returnInfo['totalSd'];
                        $totalVat = $item['vat'] -  $returnInfo['totalVat'];
                        $totalTotalTax = $item['total_tax'] -  $returnInfo['totalTotalTax'];
                        $totalTotal = $item['total'] -  $returnInfo['totalTotal'];
                        $totalNetTotal = $item['net_total'] -  $returnInfo['totalNetTotal'];


                        if($item['parts_service']==1){ // 1 = Parts, 2 = Service
                            $model_name = ProdModels::model()->prodNameOfThis($item['model_id']);
                            $type = 'Goods';
                            $model_text_r = 'parts'.$item['model_id'];
                        }else if($item['parts_service']==2){ // 1 = Parts, 2 = Service
                            $model_name = Services::model()->prodNameOfThis($item['model_id']);
                            $type = 'Service';
                            $model_text_r = 'service'.$item['model_id'];
                        }
                        $unit_name = Units::model()->unitLabel($item['unit_id']);

                    ?>
                        <tr class='cartList'>
                            <td class='sno'><?=$i?></td>
                            <td style='text-align: left;width:110px;'><?=!empty($model_name)?$model_name:''?></td>
                            <td><?=!empty($type)?$type:''?></td>
                            <td>
                                <input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: center;' class='lineQty' type='text' name='SellItems[temp_sales_qty][]' value='<?=!empty($lineQtyOrg)?$lineQtyOrg:''?>' readonly>
                                <input type="hidden" class="lineQtyOrg" value="<?= !empty($lineQtyOrg) ? $lineQtyOrg:''?>">
                            </td>
                            <td>
                                <input onkeyup="calculateGridInLine($(this))" style='width: 90%; text-align: center;' class='adjQty' name='temp_adjustment_qty[]' type='text'>
                                <input type='hidden' class='fixedVat' value='<?=!empty($item['base_vat'])?$item['base_vat']:'0'?>'>
                            </td>
                            <td> <input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: right;' class='pprice' type='text' name='SellItems[temp_purchase_price][]' value='<?=!empty($item['unit_price'])?number_format($item['unit_price'],2,'.',''):0.00?>' readonly> </td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='totalAmount' type='text' name='SellItems[temp_total_amount][]' value='<?= !empty($totalTotal) ? $totalTotal:''?>' readonly>
                                <!--<input type="text" class="totalAmountOrg" value="<?= !empty($totalTotal) ? $totalTotal:''?>">-->
                            </td>
                            <td> <input style='width: 90%; text-align: right;' class='totalAmountNew' type='text' name='SellItems[temp_total_amount_new][]' readonly> </td>
                            <td><input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: right;' class='sd_percent' type='text' name='SellItems[temp_sd_percent][]' value='<?=!empty($item['sd_percent'])?number_format($item['sd_percent'],2,'.',''):0.00?>' readonly></td>
                            <td>
                                <input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: right;' class='sd' type='text' name='SellItems[temp_sd][]' value='<?= !empty($totalSd) ? $totalSd:''?>' readonly>
                            </td>
                            <td> <input style='width: 90%; text-align: right;' class='sdNew' type='text' name='SellItems[temp_sd_new][]' readonly> </td>
                            <td> <input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: right;' class='vatPercent' type='text' name='SellItems[temp_vat_percent][]' value='<?=!empty($item['vat_percent'])?number_format($item['vat_percent'],2,'.',''):0.00?>' readonly> </td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='vatAmount' type='text' name='SellItems[temp_vat_amount][]' value='<?= !empty($totalVat) ? $totalVat:''?>' readonly>
                            </td>
                            <td> <input style='width: 90%; text-align: right;' class='vatAmountNew' type='text' name='SellItems[temp_vat_amount_new][]' readonly> </td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='taxAmount' type='text' name='SellItems[temp_tax_amount][]' value='<?= !empty($totalTotalTax) ? $totalTotalTax:''?>' readonly>
                            </td>
                            <td> <input style='width: 90%; text-align: right;' class='taxAmountNew' type='text' name='SellItems[temp_tax_amount_new][]' readonly> </td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='netTotal' type='text' name='SellItems[netTotal][]' value='<?= !empty($totalNetTotal) ? $totalNetTotal:''?>' readonly>
                            </td>
                            <td> <input style='width: 90%; text-align: right;' class='netTotalNew' type='text' name='SellItems[netTotal_new][]' readonly>
                                <input type='hidden' name='SellItems[temp_model_id][]' value='<?=!empty($item['model_id'])?$item['model_id']:''?>'>
                                <input type='hidden' class='model_text' name='SellItems[temp_model_text][]' value='<?=!empty($model_text_r)?$model_text_r:''?>'>
                                <input type='hidden' name='SellItems[temp_unit][]' value='<?=!empty($item['unit_id'])?$item['unit_id']:''?>'>
                                <input type='hidden' name='SellItems[temp_base_vat][]' value='<?=!empty($item['base_vat'])?$item['base_vat']:''?>'>
                                <input type='hidden' name='SellItems[temp_base_vat_percent][]' value='<?=!empty($item['base_vat_percent'])?$item['base_vat_percent']:''?>'>
                                <input type='hidden' name='SellItems[temp_vat_calculation_method][]' value='<?=!empty($item['vat_calculation_method'])?$item['vat_calculation_method']:''?>'>
                                <input type='hidden' class='parts_service'  name='SellItems[temp_parts_service][]' value='<?=!empty($item["parts_service"])?$item["parts_service"]:''?>'>
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
                    <td><label>Total Return</label></td>
                    <td><label>Total SD Return</label></td>
                    <td><label>Total VAT Return</label></td>
                    <td><label>Grand Total Return</label></td>
                </tr>
                <tr class="final-grid-tr">
                    <td><input id="SellItems_total" name = 'SellItems[total]' type='text' value='0.00'></td>
                    <td><input id="SellItems_total_sd" name = 'SellItems[total_sd]' type='text' value='0.00'></td>
                    <td><input id="SellItems_totalTax" name = 'SellItems[total_tax]' type='text' value='0.00'></td>
                    <td><input id="SellItems_grand_total" name = 'SellItems[grand_total]' type='text' value='0.00'></td>
                </tr>
            </table>
        </fieldset>
    </fieldset>
    <div id="jsPart"></div>
    <div id="formResult" class="ajaxTargetDiv"></div>
    <div id="formResultError" class="ajaxTargetDivErr"></div>
    <fieldset class="tblFooters">
        <?php
        echo CHtml::ajaxSubmitButton('Submit', CHtml::normalizeUrl(array('sellItems/salesReturnForm', 'id' => $model->id)), array(
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
                        $("#sell-items-form")[0].reset();
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
                            $("#sell-items-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#sell-items-form #"+key+"_em_").show();
                        });
                    }       
                }',
            'beforeSend' => 'function(){
                /*var SellItems_return_challan_no = $("#SellItems_return_challan_no").val();
                if (SellItems_return_challan_no=="") {
                    alertify.alert("Please enter return challan no!");
                    return false;
                } */
                var SellItems_return_date = $("#SellItems_return_date").val();
                if (SellItems_return_date=="") {
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
                if(sumAdj <0){
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

<script>
    function calculateGridInLine(element) {
        var lineQtyOrg = parseFloat(element.parents('tr').find('.lineQtyOrg').val());
        if (isNaN(lineQtyOrg))
            lineQtyOrg = 0;
        var adjQty = parseFloat(element.parents('tr').find('.adjQty').val());
        if (isNaN(adjQty))
            adjQty = 0;
        var lineQty = parseFloat(element.parents('tr').find('.lineQty').val());
        var linePrice = parseFloat(element.parents('tr').find('.pprice').val());
        var vatPercent = parseFloat(element.parents('tr').find('.vatPercent').val());
        var vatType = parseFloat(element.parents('tr').find('.vatType').val());
        var fixedVat = parseFloat(element.parents('tr').find('.fixedVat').val());
        if (isNaN(lineQty))
            lineQty = 0;
        if (isNaN(linePrice))
            linePrice = 0;
        if (isNaN(vatPercent))
            vatPercent = 0;
        if (isNaN(vatType))
            vatType = 0;
        if (isNaN(totalAmount))
            totalAmount = 0;
        if (isNaN(fixedVat))
            fixedVat = 0;

        if (adjQty > lineQtyOrg) {
            alertify.alert('Quantity excedeed!');
            element.parents('tr').find('.lineQty').val(lineQtyOrg);
            element.parents('tr').find('.adjQty').val('');
            element.parents('tr').find('.totalAmountNew').val('');
            element.parents('tr').find('.sdNew').val('');
            element.parents('tr').find('.vatAmountNew').val('');
            element.parents('tr').find('.taxAmountNew').val('');
            element.parents('tr').find('.netTotalNew').val('');
            calculateGridInLine(element);
            return false;
        }
        var setLineQty = lineQtyOrg - adjQty;
        element.parents('tr').find('.lineQty').val(setLineQty);


        var sd_percent = parseFloat(element.parents('tr').find('.sd_percent').val());
        if (isNaN(sd_percent))
            sd_percent = 0;

        var totalAmount = linePrice * setLineQty;
        var totalAmountNew = linePrice * adjQty;

        var sd = (totalAmount * sd_percent) / 100;
        var sdNew = (totalAmountNew * sd_percent) / 100;

        if(vatType==3){
            var vatAmount = setLineQty * fixedVat;
            var vatAmountNew = adjQty * fixedVat;
        }else{
            var vatAmount = (((totalAmount + sd) * vatPercent) / 100);
            var vatAmountNew = (((totalAmountNew + sdNew) * vatPercent) / 100);
        }

        var taxAmount = vatAmount;
        var taxAmountNew = vatAmountNew;
        var netTotal = totalAmount + taxAmount + sd;
        var netTotalNew = totalAmountNew + taxAmountNew + sdNew;

        element.parents('tr').find('.totalAmount').val(totalAmount.toFixed(2));
        element.parents('tr').find('.totalAmountNew').val(totalAmountNew.toFixed(2));
        element.parents('tr').find('.sd').val(sd.toFixed(2));
        element.parents('tr').find('.sdNew').val(sdNew.toFixed(2));
        element.parents('tr').find('.vatAmount').val(vatAmount.toFixed(2));
        element.parents('tr').find('.vatAmountNew').val(vatAmountNew.toFixed(2));
        element.parents('tr').find('.taxAmount').val(taxAmount.toFixed(2));
        element.parents('tr').find('.taxAmountNew').val(taxAmountNew.toFixed(2));
        element.parents('tr').find('.netTotal').val(netTotal.toFixed(2));
        element.parents('tr').find('.netTotalNew').val(netTotalNew.toFixed(2));
        calculateGridTotal();
    }

    function calculateGridTotal() {

        var sumTotal = 0;
        $(".totalAmountNew").each(function () {
            var valueTotal = $(this).val();
            if (!isNaN(valueTotal) && valueTotal.length != 0) {
                sumTotal += parseFloat(valueTotal);
            }
        });
        $("#SellItems_total").val(sumTotal.toFixed(2));

        var sdTotal = 0;
        $(".sdNew").each(function () {
            var valueSd = $(this).val();
            if (!isNaN(valueSd) && valueSd.length != 0) {
                sdTotal += parseFloat(valueSd);
            }
        });
        $("#SellItems_total_sd").val(sdTotal.toFixed(2));

        var sumtax = 0;
        $(".vatAmountNew").each(function () {
            var valueTax = $(this).val();
            if (!isNaN(valueTax) && valueTax.length != 0) {
                sumtax += parseFloat(valueTax);
            }
        });
        $("#SellItems_totalTax").val(sumtax.toFixed(2));

        var totalWithTax = 0;
        totalWithTax = parseFloat(sumTotal + sumtax + sdTotal).toFixed(2);
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
<input type="hidden" id="unitName" />
<input type="hidden" id="unitId" />
<input type="hidden" id="unitPrice" />
<input type="hidden" id="vat" />
<input type="hidden" id="vatType" />
<input type="hidden" id="stockQty" /> 
<input type="hidden" id="sd" /> 

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