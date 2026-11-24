<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'sell-items-form',
    //'action' => $this->createUrl('sellItems/update/'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
        ));
?>
<?php echo CHtml::link('<< Back to Credit Note',array('sellItems/salesReturn'),['style'=>'padding: 5px !important;margin-left: 12px','class'=>'customBtn']); ?>
<br><br>
<div class="formDiv">
    <fieldset>
        <legend>Add Credit Note (Sales Return)</legend>
        <fieldset style="float: left; width:98%;" class="someStyle">
            <legend>Sales Information</legend>
            <table class="form-box">
                <tr>
                    <td width="90px"><?php echo $form->labelEx($model, 'so_no'); ?></td>
                    <td>
                        <div id="orderNo" class="receivedByDiv">
                            <?php
                            $thisFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SELL_ORDER);
                            echo $thisFormat.'-'.$model->so_no;
                            ?>
                        </div>
                        <?php echo $form->error($model, 'so_no'); ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'vehicle_id'); ?></td>
                    <td>
                        <?php echo $form->textField($model, 'vehicle_id', ['disabled' => true]); ?>
                        <?php echo $form->error($model, 'vehicle_id'); ?>
                    </td>   
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'product_type'); ?></td>
                    <td>
                        <input type="text" value="<?=($model->product_type==1)?'Raw Materials':'Finish Goods'?>" disabled/>
                    </td>   
                </tr>

                <tr>
                    <td><?php echo $form->labelEx($model, 'sales_date'); ?></td>
                    <td>
                        <input type="text" value="<?=!empty($model->sales_date)?$model->sales_date:''?>" disabled>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'customer_id'); ?></td>
                    <td width="252px">
                        <input type="text" value="<?= Customers::model()->customerName($model->customer_id) ?>" disabled/>
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
                    <th>GOODS/SERVICE</th>
                    <th>Qty</th>
                    <th>Qty Adj</th>
                    <th>Unit Price</th>
                    <th>VAT(%)</th>
                    <th>VAT</th>
                    <th>VAT Adj</th>
                    <th>Tax</th>
                    <th>Tax Adj</th>
                    <th>Total</th>
                    <th>Total Adj</th>
                    <th>Net Total</th>
                    <th>Net Total Adj</th>
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
                        $totalVat = $item['vat'] -  $returnInfo['totalVat'];
                        $totalTotalTax = $item['total_tax'] -  $returnInfo['totalTotalTax'];
                        $totalTotal = $item['total'] -  $returnInfo['totalTotal'];
                        $totalNetTotal = $item['net_total'] -  $returnInfo['totalNetTotal'];
                        ?>                     
                        <tr class='cartList'> 
                            <td class='sno'><?=$i?></td> 
                            <td style='text-align: left;'><?=!empty($model_name)?$model_name:''?></td> 
                            <td><?=!empty($type)?$type:''?></td> 
                            <td> 
                                <input style='width: 90%; text-align: center;' class='lineQty' type='text' name='SellItems[temp_sales_qty][]' value='<?=!empty($lineQtyOrg)?$lineQtyOrg.' '.(!empty($unit_name)?$unit_name:''):''?>' readonly> 
                                <input type="hidden" class="lineQtyOrg" value="<?= !empty($lineQtyOrg) ? $lineQtyOrg:''?>">
                            </td> 
                            <td><input onkeyup="calculateAdjustment($(this))" style='width: 90%; text-align: center;' class='adjQty' name='temp_adjustment_qty[]' type='text'></td>
                            <td> <input style='width: 90%; text-align: right;' class='pprice' type='text' name='SellItems[temp_purchase_price][]' value='<?=!empty($item['unit_price'])?number_format($item['unit_price'],2,'.',''):0.00?>' readonly> </td> 
                            <td> <input style='width: 90%; text-align: right;' class='vatPercent' type='text' name='SellItems[temp_vat_percent][]' value='<?=!empty($item['vat_percent'])?number_format($item['vat_percent'],2,'.',''):0.00?>' readonly> </td> 
                            <td> <input style='width: 90%; text-align: right;' class='vatAmount' type='text' name='SellItems[temp_vat_amount][]' value='<?=!empty($totalVat)?number_format($totalVat,2,'.',''):0.00?>' readonly> </td> 
                            <td> <input style='width: 90%; text-align: right;' class='vatAmountNew' type='text' name='SellItems[temp_vat_amount_new][]' readonly> </td> 
                            <td> <input style='width: 90%; text-align: right;' class='taxAmount' type='text' name='SellItems[temp_tax_amount][]' value='<?=!empty($totalTotalTax)?number_format($totalTotalTax,2,'.',''):0.00?>' readonly> </td> 
                            <td> <input style='width: 90%; text-align: right;' class='taxAmountNew' type='text' name='SellItems[temp_tax_amount_new][]' readonly> </td> 
                            <td> <input style='width: 90%; text-align: right;' class='totalAmount' type='text' name='SellItems[temp_total_amount][]' value='<?=!empty($totalTotal)?number_format($totalTotal,2,'.',''):0.00?>' readonly> </td> 
                            <td> <input style='width: 90%; text-align: right;' class='totalAmountNew' type='text' name='SellItems[temp_total_amount_new][]' readonly> </td> 
                            <td> <input style='width: 90%; text-align: right;' class='netTotal' type='text' name='SellItems[temp_net_total][]' value='<?=!empty($totalNetTotal)?number_format($totalNetTotal,2,'.',''):0.00?>' readonly> </td> 
                            <td> <input style='width: 90%; text-align: right;' class='netTotalNew' type='text' name='SellItems[temp_net_total_new][]' readonly> </td> 
                            
                            <input type='hidden' name='SellItems[temp_parts_service][]' value='<?=!empty($item['parts_service'])?$item['parts_service']:''?>'> 
                            <input type='hidden' name='SellItems[temp_model_id][]' value='<?=!empty($item['model_id'])?$item['model_id']:''?>'>
                        </tr>
                <?php
                $i++;
                    }
                }
                ?>
            </table>
            <table class="prodAddedTab" style="margin-top: 20px;">
                <tr>
                    <td width="300px"><label>Total Vat</label></td>
                    <td width="300px"><label>Total</label></td>
                    <td width="300px"><label>Grand Total</label></td>
                    <td></td>
                </tr>
                <tr>
                    <td><input id="SellItems_totalTax" name = 'SellItems[total_tax]' class='noWidthInput lineTotalInpt right' type='text' value='<?=!empty($model["total_tax"])?number_format($model["total_tax"],2,'.',''):0.00?>' readonly></td>
                    <td><input id="SellItems_total" name = 'SellItems[total]' class='noWidthInput lineTotalInpt right' type='text' value='<?=!empty($model["total"])?number_format($model["total"],2,'.',''):0.00?>' readonly></td>
                    <td><input id="SellItems_grand_total" name = 'SellItems[grand_total]' class='noWidthInput lineTotalInpt right' type='text' value='<?=!empty($model["grand_total"])?number_format($model["grand_total"],2,'.',''):0.00?>' readonly></td>
                    <td></td>
                </tr>
            </table>
        </fieldset>
    </fieldset>
    <div id="jsPart"></div>
    <div id="formResult" class="ajaxTargetDiv"></div>
    <div id="formResultError" class="ajaxTargetDivErr"></div>
    <fieldset class="tblFooters">
        <?php
        echo CHtml::ajaxSubmitButton('Create', CHtml::normalizeUrl(array('sellItems/salesReturnForm', 'id' => $model->id)), array(
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
                        //alertify.alert("Credit note added successfully.");
                        //$("#sell-items-form")[0].reset();
                        //$("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $("#ajaxLoaderReport").show(); 
                        $("#soReportDialogBox").dialog("open");
                        $("#AjFlashReportPo").html(data.soReportInfo).show();
                        $("#ajaxLoaderReport").hide();
                    }else if(data.status=="errorBalance"){
                        $("#formResultError").show();
                        $("#formResultError").html("You Must Add Items First!");
                    }else{
                        /*$("#formResult").show();
                        $("#formResultError").html("Data not saved. Please solve the above errors!");
                        $.each(data, function(key, val) {
                            $("#sell-items-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#sell-items-form #"+key+"_em_").show();
                        });*/
                    }       
                }',
            'beforeSend' => 'function(){
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
<input type="hidden" id="vatPercent" />
<input type="hidden" id="stockQty" /> 

<script>
    $('form').attr('autocomplete', 'off');
</script>
<script>
    function calculateAdjustment(element) {
        var lineQtyOrg = parseFloat(element.parents('tr').find('.lineQtyOrg').val());
        if (isNaN(lineQtyOrg))
            lineQtyOrg = 0;
        var lineQty = parseFloat(element.parents('tr').find('.lineQty').val());
        if (isNaN(lineQty))
            lineQty = 0;
        var adjQty = parseFloat(element.parents('tr').find('.adjQty').val());
        if (isNaN(adjQty))
            adjQty = 0;
        var pprice = parseFloat(element.parents('tr').find('.pprice').val());
        if (isNaN(pprice))
            pprice = 0;
        var vatPercent = parseFloat(element.parents('tr').find('.vatPercent').val());
        if (isNaN(vatPercent))
            vatPercent = 0;

        if (adjQty > lineQtyOrg) {
            alertify.alert('Quantity excedeed!');
            element.parents('tr').find('.lineQty').val(lineQtyOrg);
            element.parents('tr').find('.adjQty').val('');
            element.parents('tr').find('.vatAmountNew').val('');
            element.parents('tr').find('.taxAmountNew').val('');
            element.parents('tr').find('.totalAmountNew').val('');            
            element.parents('tr').find('.netTotalNew').val('');            
            calculateGridTotal();
            return false;
        }
        var setLineQty = lineQtyOrg - adjQty;
        element.parents('tr').find('.lineQty').val(setLineQty);
        var vatAmount = (((lineQtyOrg - adjQty) * pprice) * vatPercent) / 100;
        element.parents('tr').find('.vatAmount').val(vatAmount.toFixed(2));
        element.parents('tr').find('.taxAmount').val(vatAmount.toFixed(2));
        var vatAmountNew = ((adjQty * pprice) * vatPercent) / 100;
        element.parents('tr').find('.vatAmountNew').val(vatAmountNew.toFixed(2));
        element.parents('tr').find('.taxAmountNew').val(vatAmountNew.toFixed(2));
        var totalAmount = (lineQtyOrg - adjQty) * pprice;
        element.parents('tr').find('.totalAmount').val(totalAmount.toFixed(2));
        var totalAmountNew = adjQty * pprice;
        element.parents('tr').find('.totalAmountNew').val(totalAmountNew.toFixed(2));
        var netTotal = totalAmount + vatAmount;
        element.parents('tr').find('.netTotal').val(netTotal.toFixed(2));
        var netTotalNew = totalAmountNew + vatAmountNew;
        element.parents('tr').find('.netTotalNew').val(netTotalNew.toFixed(2));
        calculateGridTotal();
    }

    function calculateGridTotal() {
        var sumtax = 0;
        $(".vatAmount").each(function () {
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
        var totalWithTax = sumtax + sumTotal;
        $("#SellItems_total").val(totalWithTax.toFixed(2));
        $("#SellItems_grand_total").val(totalWithTax.toFixed(2));
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
</style>