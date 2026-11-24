<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'purchase-items-form',
    'action' => $this->createUrl('purchaseItems/saveDirectPurchaseUpdate/'),
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
//echo $form->hiddenField($model, 'purchase_price', array('value' => '0'));
//echo $form->hiddenField($model, 'order_qty');

$purchase_id = $model->id;
?>

<script>
    var vals = [];
</script>
<div class="formDiv">
    <fieldset>
        <legend>Purchase Requisition Form (Service)</legend>
        <fieldset style="float: left; width:98%;" class="someStyle">
            <legend>ORDER Information</legend>
            <table class='form-box'>
                <tr>
                    <td width="90px"><?php echo $form->labelEx($model, 'po_no'); ?></td>
                    <td>
                        <div id="orderNo" class="receivedByDiv">
                            <?php
                            $thisPurchaseNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::PURCHASE_ORDER);
                            ?>
                            <?=!empty($model->id)?$thisPurchaseNoFormat.'-'.$model->service_no:''?>
                        </div>
                        <?php echo $form->error($model, 'service_no'); ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'issue_date'); ?></td>
                    <td>
                        <?php
                        Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                        $dateTimePickerConfig1 = array(
                            'model' => $model, //Model object
                            'attribute' => 'issue_date', //attribute name
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
                        <?php echo $form->error($model, 'issue_date'); ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'supplier_id'); ?></td>
                    <td width="250px">
                        <input type="text" id="PurchaseItems_supplier_id_text" value="<?=Suppliers::model()->supplierName($model->supplier_id)?>" onkeyup="clearBin()"/>
                        <?php echo $form->hiddenField($model, 'supplier_id'); ?>                        
                        <script>
                            $(function () {
                                $("#PurchaseItems_supplier_id_text").autocomplete({
                                    source: function (request, response) {
                                        var search_customerName = request.term;
                                        $.post('<?php echo Yii::app()->baseUrl ?>/suppliers/jqueryCustomerSearch', {"search_customerName": search_customerName},
                                                function (data) {
                                                    response(data);
                                                }, "json");
                                    },
                                    minLength: 1,
                                    select: function (event, ui) {
                                        $("#PurchaseItems_supplier_id_text").val(ui.item.label);
                                        $("#PurchaseItems_supplier_id").val(ui.item.value);         
                                        $("#bin").val(ui.item.bin);                                                     
                                        return false;
                                    }
                                });
                            });
                        </script>
                        <?php
                        /*echo $form->dropDownList(
                                $model, 'supplier_id', CHtml::listData(Suppliers::model()->findAll(array('order' => 'name ASC')), 'id', 'name'), array(
                            'prompt' => 'Select',
                        ));*/
                        ?>
                        <?php
                        echo CHtml::link('', "", // the link for open the dialog
                                array(
                            'class' => 'add-additional-btn',
                            'onclick' => "{addSupplier(); $('#addSupplierDialog').dialog('open');}"));
                        ?>

                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                            'id' => 'addSupplierDialog',
                            'options' => array(
                                'title' => 'Add Supplier',
                                'autoOpen' => false,
                                'modal' => true,
                                'width' => 984,
                                'resizable' => false,
                            ),
                        ));
                        ?>
                        <div class="divForForm">                         
                            <div class="ajaxLoaderFormLoad" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>

                        </div>

                        <?php $this->endWidget(); ?>

                        <script type="text/javascript">
                            // here is the magic
                            function addSupplier() {
<?php
echo CHtml::ajax(array(
    'url' => array('suppliers/createSupplierFromOutSide'),
    'data' => "js:$(this).serialize()",
    'type' => 'post',
    'dataType' => 'json',
    'beforeSend' => "function(){
                                    $('.ajaxLoaderFormLoad').show();
                                }",
'complete' => "function(){
                                    $('.ajaxLoaderFormLoad').hide();
                                }",
    'success' => "function(data){
                                    if (data.status == 'failure')
                                    {
                                        $('#addSupplierDialog div.divForForm').html(data.div);
                                              // Here is the trick: on submit-> once again this function!
                                        $('#addSupplierDialog div.divForForm form').submit(addSupplier);
                                    }
                                    else
                                    {
                                        $('#addSupplierDialog div.divForForm').html(data.div);
                                        setTimeout(\"$('#addSupplierDialog').dialog('close') \",1000);
                                        $('#PurchaseItems_supplier_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
                                    }
                                }",
))
?>
                                return false;
                            }
                        </script>
                        <?php echo $form->error($model, 'supplier_id'); ?>
                    </td>
                </tr>
                <tr>
                    <td style="color:#014848;">Supplier BIN/NID</td>
                    <td>
                        <input type="text" name="bin" id="bin" value="<?=Suppliers::model()->supplierBin($model->supplier_id)?>" readonly>
                    </td>
                </tr><tr>
                    <td><?php echo $form->labelEx($model, 'ref_no'); ?> (6.3)</td>
                    <td>
                        <?php echo $form->textField($model, 'ref_no'); ?>
                        <?php echo $form->error($model, 'ref_no'); ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'chalan_date'); ?> (6.3)</td>
                    <td>
                        <?php
                        Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                        $dateTimePickerConfig1 = array(
                            'model' => $model, //Model object
                            'attribute' => 'chalan_date', //attribute name
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
                        <?php echo $form->error($model, 'chalan_date'); ?>
                    </td>
                </tr>
                <tr>
                    <td width="100px"><?php echo $form->labelEx($model, 'payment_method'); ?></td>
                    <td>
                        <?php
                        $data = Lookup::model()->findAll(array('condition' => "type= 'payment_type' AND code = 134 OR code=133 "));
                        $dtArray = [];
                        foreach ($data as $dt) {
                            $dtArray[$dt->code] = $dt->name;
                        }
                        echo $form->dropDownList($model, 'payment_method', $dtArray);
                        ?>
                        <?php echo $form->error($model, 'payment_method'); ?>
                    </td>
                </tr>
                <?php
                $dsp = '';
                if($model->payment_method==133){
                    $dsp = 'style="display: none"';
                }
                ?>
                <tr id="bank_option" <?=$dsp?> >
                    <td><?php echo $form->labelEx($model, 'bank_id'); ?></td>
                    <td>
                        <?php
                        echo $form->dropDownList(
                            $model, 'bank_id', CHtml::listData(ChartOfAc::model()->findAll(['condition' => 'root = 157','order'=>'title asc']), 'id', 'title'), array(
                            'prompt' => 'Select',
                        ));
                        ?>
                        <?php echo $form->error($model, 'bank_id'); ?>
                    </td>
                </tr>
            </table>
        </fieldset>

        <fieldset class="someStyle" style="float: left; width: 98%;">
            <legend>Product Informations</legend>
            <table style="width: 98%" class="prodAddedTab">
                <tr>
                    <td><?php echo $form->labelEx($model, 'model_id'); ?></td>
                    <td><?php echo $form->labelEx($model, 'order_qty'); ?></td>
                    <td><label>Action</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="text" id="service_id_text" name="service_id_text"/>
                        <?php echo $form->hiddenField($model, 'service_id'); ?>
                        <script>
                            $(function () {
                                $("#service_id_text").autocomplete({
                                    source: function (request, response) {
                                        var search_serviceName = request.term;
                                        $.post('<?php echo Yii::app()->baseUrl ?>/services/jqueryServiceSearch', {"search_serviceName": search_serviceName},
                                            function (data) {
                                                response(data);
                                            }, "json");
                                    },
                                    minLength: 1,
                                    select: function (event, ui) {
                                        $("#service_id_text").val(ui.item.label);
                                        $("#PurchaseItems_service_id").val(ui.item.value);
                                        $("#unitName").val(ui.item.unitName);
                                        $("#unitId").val(ui.item.unitId);
                                        $("#unitPrice").val(ui.item.unitPrice);
                                        $("#vat").val(ui.item.vat);
                                        $("#vatType").val(ui.item.vatType);
                                        $("#rebatable").val(ui.item.rebatable);
                                        return false;
                                    }
                                });
                            });
                        </script>
                        <?php
                        echo CHtml::link('', "", // the link for open the dialog
                                array(
                            'class' => 'add-additional-btn',
                            'onclick' => "{addService(); $('#addServiceDialog').dialog('open');}"));
                        ?>
                        
                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                            'id' => 'addServiceDialog',
                            'options' => array(
                                'title' => 'Add Service',
                                'autoOpen' => false,
                                'modal' => true,
                                'width' => 984,
                                'resizable' => false,
                            ),
                        ));
                        ?>
                        <div class="divForForm">                         
                            <div class="ajaxLoaderFormLoad" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>

                        </div>

                        <?php $this->endWidget(); ?>

                        <script type="text/javascript">
                            // here is the magic
                            function addService() {
            <?php
            echo CHtml::ajax(array(
                'url' => array('services/createServiceFromOutSide'),
                'data' => "js:$(this).serialize()",
                'type' => 'post',
                'dataType' => 'json',
                'beforeSend' => "function(){
                            $('.ajaxLoaderFormLoad').show();
                        }",
                'complete' => "function(){
                            $('.ajaxLoaderFormLoad').hide();
                        }",
                'success' => "function(data){
                            if (data.status == 'failure')
                            {
                                $('#addServiceDialog div.divForForm').html(data.div);
                                      // Here is the trick: on submit-> once again this function!
                                $('#addServiceDialog div.divForForm form').submit(addService);
                            }
                            else
                            {
                                $('#addServiceDialog div.divForForm').html(data.div);
                                setTimeout(\"$('#addServiceDialog').dialog('close') \",1000);
                                //$('#PurchaseItems_supplier_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
                            }
                        }",
            ))
            ?>
                                return false;
                            }
                        </script>
                        <?php echo $form->error($model, 'service_id'); ?>
                    </td>
                    <td>
                        <?php echo $form->textField($model, 'order_qty'); ?>
                    </td>
                    <td><input class="addDown" style="margin-top: 0; text-align: center" title="Add" type="button" value="" onclick="AddNew()" /></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="width: 98%; float: left; overflow: scroll; height: 360px;" class="someStyle">
            <legend>Added List</legend>
            <table id="tbl" class="prodAddedTab">
                <tr>
                    <th style="width:20px;">SL</th>
                    <th>ITEM</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>VAT(%)</th>
                    <th>VAT</th>
                    <th>Total Tax</th>
                    <th>Net Total</th>
                    <?php if($yourCompanyInfo->company_vat_type==1){?>
                    <th>Rebate</th>
                    <?php } ?>
                    <th>ACTION</th>
                </tr>
                                
                <?php
                $sql = "SELECT * FROM purchase_details WHERE purchase_id  = $purchase_id ORDER BY id ASC";
                $connection = Yii::app()->db;
                $command = $connection->createCommand($sql);
                $purchase_details = $command->queryAll();
                
                if(!empty($purchase_details)){
                    $i = 1;
                    foreach($purchase_details as $po_items){
                        $model_name = Services::prodNameOfThis($po_items["service_id"]);
                        $unit_name = Units::model()->unitLabel($po_items["unit_id"]);
                        ?>
                        <tr class='cartList'>
                            <td class='sno'><?=$i?></td>
                            <td style='text-align: left;'><?=!empty($model_name)?$model_name:''?></td>
                            <td><input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: center;' class='lineQty' type='text' name='PurchaseItems[temp_order_qty][]' value='<?=!empty($po_items["order_qty"])?$po_items["order_qty"]:0?>'></td>
                            <td><?=!empty($unit_name)?$unit_name:''?><input type='hidden' class='fixedVat' value='<?=!empty($po_items["base_vat"])?$po_items["base_vat"]:0?>'></td>
                            <td><input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: right;' class='pprice' type='text' name='PurchaseItems[temp_purchase_price][]' value='<?=!empty($po_items["unit_price"])?number_format($po_items["unit_price"],2,'.',''):0.00?>'></td>
                            <td><input style='width: 90%; text-align: right;' class=' totalAmount' type='text' name='PurchaseItems[temp_total_amount][]' value='<?=!empty($po_items["total"])?number_format($po_items["total"],2,'.',''):0.00?>' readonly></td>
                            <td><input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: right;' class='vatPercent' type='text' name='PurchaseItems[temp_vat_percent][]' value='<?=!empty($po_items["vat_percent"])?number_format($po_items["vat_percent"],2,'.',''):0.00?>'></td>
                            <td><input style='width: 90%; text-align: right;' class=' vatAmount' type='text' name='PurchaseItems[temp_vat_amount][]' value='<?=!empty($po_items["vat"])?number_format($po_items["vat"],2,'.',''):0.00?>' readonly></td>
                            <td><input style='width: 90%; text-align: right;' class=' taxAmount' type='text' name='PurchaseItems[temp_tax_amount][]' value='<?=!empty($po_items["total_tax"])?number_format($po_items["total_tax"],2,'.',''):0.00?>' readonly></td>
                            <td><input style='width: 90%; text-align: right;' class='netTotal' type='text' name='PurchaseItems[temp_net_total][]' value='<?=!empty($po_items["net_total"])?number_format($po_items["net_total"],2,'.',''):0.00?>' readonly></td>
                            <?php if($yourCompanyInfo->company_vat_type==1){?>
                            <td><input type='checkbox' onchange='giveRebateLine($(this))' class='checkRebate'><input style='width: 65%; text-align: right;' class='rebateTotal' type='text' name='PurchaseItems[temp_rebate_amount][]' value='<?=!empty($po_items["rebate"])?number_format($po_items["rebate"],2,'.',''):0.00?>' readonly></td>
                            <?php } ?>
                            <td>
                                <input type='hidden' class='model_id' name='PurchaseItems[temp_model_id][]' value='<?=!empty($po_items["service_id"])?$po_items["service_id"]:''?>'>
                                <input type='hidden' name='PurchaseItems[temp_unit][]' value='<?=!empty($po_items["unit_id"])?$po_items["unit_id"]:''?>'>
                                <input type='hidden' name='PurchaseItems[temp_base_vat][]' value='<?=!empty($po_items["base_vat"])?$po_items["base_vat"]:''?>'>
                                <input type='hidden' name='PurchaseItems[temp_base_vat_percent][]' value='<?=!empty($po_items["base_vat_percent"])?$po_items["base_vat_percent"]:''?>'>
                                <input type='hidden' class='vatType' name='PurchaseItems[temp_vat_calculation_method][]' value='<?=!empty($po_items["vat_calculation_method"])?$po_items["vat_calculation_method"]:''?>'>
                                <input onclick='deleteRows($(this))' title="remove" id='<?=$i?>' style='width: 90%; text-align: center;' type="button" class="rdelete dltBtn"/>
                            </td>
                        </tr>
                        <script>
                            vals.push(<?=!empty($po_items["service_id"])?$po_items["service_id"]:''?>);
                        </script>
                      
                <?php
                $i++;
                    }
                }
                ?>                
            </table>
            <table class="prodAddedTab" style="margin-top: 20px;width: 980px;">
                <tr>
                    <td><label> Total</label></td>
                    <td><label> Total VAT</label></td>
                    <td><label> Grand Total</label></td>
                    <td><label> Total VDS</label></td>
                </tr>
                <tr class="final-grid-tr">
                    <td><input id="PurchaseItems_totalAmouns" name = 'PurchaseItems[total]' type='text' value='<?=!empty($model["total"])?number_format($model["total"],2,'.',''):0.00?>'></td>
                    <td><input id="PurchaseItems_totalTax" name = 'PurchaseItems[total_tax]' type='text' value='<?=!empty($model["total_tax"])?number_format($model["total_tax"],2,'.',''):0.00?>'></td>
                    <td><input id="PurchaseItems_grand_total" name = 'PurchaseItems[grand_total]' type='text' value='<?=!empty($model["grand_total"])?number_format($model["grand_total"],2,'.',''):0.00?>'></td>
                    <td>
                        <input id="PurchaseItems_totalVds" name = 'PurchaseItems[total_vds]' type='text' value='<?=!empty($model["total_vds"])?number_format($model["total_vds"],2,'.',''):0.00?>' style="width: 90px" readonly>
                        <input type="checkbox" id="vd"> Copy VDS
                    </td>
                </tr>
            </table>
            <?php
            if($yourCompanyInfo->company_vat_type==1){?>
                <table class="prodAddedTab" style="margin-top: 20px;width: 250px;">
                    <tr>
                        <td><label> Rebate Amount</label></td>
                    </tr>
                    <tr class="final-grid-tr">
                        <td>
                            <input id="PurchaseItems_totalRebate" name = 'PurchaseItems[total_rebate]'  type='text' value='<?=!empty($model["total_rebate"])?number_format($model["total_rebate"],2,'.',''):0.00?>' readonly="">
                            <!--<input type="checkbox" id="rebate"> Rebate-->
                        </td>
                    </tr>
                </table>
            <?php }
            ?>
        </fieldset>
    </fieldset>    
    
    <div id="jsPart"></div>
    <div id="formResult" class="ajaxTargetDiv"></div>
    <div id="formResultError" class="ajaxTargetDivErr"></div>
    <fieldset class="tblFooters">
        <?php
        echo CHtml::ajaxSubmitButton('Update PR', CHtml::normalizeUrl(array('purchaseItems/updateInfoServices', 'id' => $model->id)), array(
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
                var PurchaseItems_issue_date = $("#PurchaseItems_issue_date").val();
                if (PurchaseItems_issue_date=="") {
                    alertify.alert("Please enter issue date!");
                    return false;
                } 
                var PurchaseItems_supplier_id_text = $("#PurchaseItems_supplier_id_text").val();
                var PurchaseItems_supplier_id = $("#PurchaseItems_supplier_id").val();
                if (PurchaseItems_supplier_id_text=="" || PurchaseItems_supplier_id=="") {
                    alertify.alert("Please enter supplier!");
                    return false;
                } 
                var PurchaseItems_product_type = $("#PurchaseItems_product_type").val();
                if (PurchaseItems_product_type=="") {
                    alertify.alert("Please select product type!");
                    return false;
                }
                var PurchaseItems_ref_no = $("#PurchaseItems_ref_no").val();
                if (PurchaseItems_ref_no=="") {
                    alertify.alert("Please enter challan no!");
                    return false;
                }
                var PurchaseItems_chalan_date = $("#PurchaseItems_chalan_date").val();
                if (PurchaseItems_chalan_date=="") {
                    alertify.alert("Please enter challan date!");
                    return false;
                } 
                var PurchaseItems_payment_method = $("#PurchaseItems_payment_method").val();
                if (PurchaseItems_payment_method=="") {
                    alertify.alert("Please select Cash or Bank!");
                    return false;
                }
                if (PurchaseItems_payment_method == 134) {
                    var PurchaseItems_bank_id = $("#PurchaseItems_bank_id").val();
                    if (PurchaseItems_bank_id=="") {
                        alertify.alert("Please select Bank Name!");
                        return false;
                    }
                }
                if (vals.length<1) {
                    alertify.alert("Please add product in list!");
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
    $("#vd").change(function() {
        if($(this).prop('checked')) {
            calcVds();
        } else {
            calculateGridTotal();
            $("#PurchaseItems_totalVds").val('');
        }
    });
    $("#rebate").change(function() {
        if($(this).prop('checked')) {
            var rebVal = $("#PurchaseItems_totalTax").val();
            $("#PurchaseItems_totalRebate").val(rebVal);
        } else {
            $("#PurchaseItems_totalRebate").val('');
        }
    });

    //$("#bank_option").hide();
    $("#PurchaseItems_payment_method").change(function () {
        var PurchaseItems_payment_method = $("#PurchaseItems_payment_method").val();
        if (PurchaseItems_payment_method == 134) {
            $("#bank_option").show();
        } else if (PurchaseItems_payment_method == 133) {
            $("#bank_option").hide();
        }
    });
    
    function deleteRows(element) {
        var modelId = parseInt(element.parents('tr').find('.model_id').val());
        removeAcTitleId(vals, modelId);
        $(element).parents("tr").remove();
        calculateGridTotal();
    }

    function removeAcTitleId(array, element) {
        var index = array.indexOf(element);
        array.splice(index, 1);
    }

    function AddNew() {
        var service_id_text = $("#service_id_text").val();
        var PurchaseItems_service_id = $("#PurchaseItems_service_id").val();
        if (service_id_text=="" || PurchaseItems_service_id=="") {
            $("#service_id_text").val('');
            alertify.alert("Please select a product!");
            return false;
        }
        
        var PurchaseItems_order_qty = $("#PurchaseItems_order_qty").val();
        if (PurchaseItems_order_qty=="") {
            alertify.alert("Please enter order qty!");
            return false;
        }
        
        if (PurchaseItems_service_id > 0)
            checkDuplicate();
        else {
            $("#service_id_text").val('');
            $("#PurchaseItems_service_id").val('');
            $("#PurchaseItems_order_qty").val('');
        }
    }
    
    function checkDuplicate() {
        var service_id = parseInt($("#PurchaseItems_service_id").val());
        if (vals.indexOf(service_id) > -1) {
            alertify.alert('Sorry! This Product is already added.');
            $("#model_id_text").val('');
            $("#PurchaseItems_service_id").val('');
            $("#PurchaseItems_order_qty").val('');
            return false;
        } else {
            vals.push(service_id);
            add();
            $("#service_id_text").val('');
            $("#PurchaseItems_service_id").val('');
            $("#PurchaseItems_order_qty").val('');
        }
    }

    function add() {
        var slNumber = $('#tbl tr').length;
        var modelId = $("#PurchaseItems_service_id").val();
        var orderQty = parseInt($("#PurchaseItems_order_qty").val());
        var unitName = $("#unitName").val();
        var unitId = $("#unitId").val();        
        var unitPrice;        
        if (isNaN($("#unitPrice").val()) || $("#unitPrice").val()=='' || $("#unitPrice").val() <= 0)
            unitPrice = 0;
        else
            unitPrice = $("#unitPrice").val();
        unitPrice = parseFloat(unitPrice).toFixed(2);  
            
        var _totalAmount = parseFloat(unitPrice * orderQty);
        var totalAmount = _totalAmount.toFixed(2);
        
        var vat;
        var _vat;
        var vatAmount;
        var _vatAmount;
        var vatPercent;
        var baseVat = '';
        var baseVatPercent = '';
        var fixedVat = 0;
        
        if (isNaN($("#vat").val()) || $("#vat").val()=='' || $("#vat").val() <= 0){
            vat = 0;
            _vat = 0;
        }else{
            _vat = $("#vat").val();
            vat = parseFloat(_vat).toFixed(2);
        }
        
        var vatType = $("#vatType").val();
        if (isNaN(vatType) || vatType=='' || vatType < 0)
            vatType = 0;
            
        if(vatType==3){ // 3=Fixed Vat
            baseVat = _vat;
            //vatPercent = (((  totalAmount * _vat) / 100 ) * orderQty).toFixed(2);
            vatPercent = 0;
            _vatAmount = _vat * orderQty;
            fixedVat = _vat;
        }else{
            vatPercent = 0;
            vatAmount = 0;
            baseVatPercent = _vat;
            vatPercent = _vat;
            _vatAmount = (_totalAmount * _vat) / 100;
        }
        vatAmount = _vatAmount.toFixed(2);
        var taxAmount = parseFloat(_vatAmount).toFixed(2);
        var netTotal = parseFloat(_totalAmount + _vatAmount).toFixed(2);
        var appendTxt = "<tr class='cartList'>" +
                "<td class='sno'>" +
                slNumber +
                "</td>" +
                "<td style='text-align: left;'>" + $("#service_id_text").val() + "</td>" +
                "<td>" + "<input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: center;' class='lineQty' type='text' name='PurchaseItems[temp_order_qty][]' value='" + orderQty + "'>" + "</td>" +
                "<td>" + unitName +" <input type='hidden' class='fixedVat' value='"+ fixedVat +"'></td>" +
                "<td>" + "<input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: right;' class='pprice' type='text' name='PurchaseItems[temp_purchase_price][]' value='" + unitPrice + "'>" + "</td>" +
                "<td>" + "<input style='width: 90%; text-align: right;' class='totalAmount' type='text' name='PurchaseItems[temp_total_amount][]' value='" + totalAmount + "' readonly>" + "</td>" +
                "<td>" + "<input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: right;' class='vatPercent' type='text' name='PurchaseItems[temp_vat_percent][]' value='" + vatPercent + "'>" + "</td>" +
                "<td>" + "<input style='width: 90%; text-align: right;' class='vatAmount' type='text' name='PurchaseItems[temp_vat_amount][]' value='" + vatAmount + "' readonly>" + "</td>" +
                "<td>" + "<input style='width: 90%; text-align: right;' class='taxAmount' type='text' name='PurchaseItems[temp_tax_amount][]' value='" + taxAmount + "' readonly>" + "</td>" +
                "<td>" + "<input style='width: 90%; text-align: right;' class='netTotal' type='text' name='PurchaseItems[temp_net_total][]' value='" + netTotal + "' readonly>" + "</td>" +
            <?php if($yourCompanyInfo->company_vat_type==1){?>
                "<td>" + "<input type='checkbox' onchange='giveRebateLine($(this))' class='checkRebate'><input style='width: 65%; text-align: right;' class='rebateTotal' type='text' name='PurchaseItems[temp_rebate_amount][]' value='' readonly>" + "</td>" +
            <?php } ?>
                "<td>" +
                "<input type='hidden' class='model_id' name='PurchaseItems[temp_model_id][]' value='" + modelId + "'>" +
                "<input type='hidden' name='PurchaseItems[temp_unit][]' value='" + unitId + "'>" +
                "<input type='hidden' name='PurchaseItems[temp_base_vat][]' value='" + baseVat + "'>" +
                "<input type='hidden' name='PurchaseItems[temp_base_vat_percent][]' value='" + baseVatPercent + "'>" +
                "<input type='hidden' class='vatType' name='PurchaseItems[temp_vat_calculation_method][]' value='" + vatType + "'>" +
                "<input onclick='deleteRows($(this))' title=\"remove\" id='" + slNumber + "' style='width: 90%; text-align: center;' type=\"button\" class=\"rdelete dltBtn\"/>" +
                "</td>" +
                "</tr>";
        $("#tbl tr:last").after(appendTxt);
        calculateGridTotal();
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

<script>
    
       function calculateGridInLine(element) {
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
        if (isNaN(fixedVat))
            fixedVat = 0;
        
        var totalAmount = linePrice * lineQty;
        if(vatType==3){
            var vatAmount = lineQty * fixedVat;
        }else{
            var vatAmount = (totalAmount * vatPercent) / 100;
        }        
        var taxAmount = vatAmount;
        
        element.parents('tr').find('.vatAmount').val(vatAmount.toFixed(2));
        element.parents('tr').find('.totalAmount').val(totalAmount.toFixed(2));
        element.parents('tr').find('.taxAmount').val(taxAmount.toFixed(2));
        element.parents('tr').find('.netTotal').val((totalAmount + taxAmount).toFixed(2));
        
        if(element.parents('tr').find('.checkRebate').prop('checked')){
            element.parents('tr').find('.rebateTotal').val(vatAmount);
        }
        calculateGridTotal(); 
        calcRebateTotal();
        var isVds = parseFloat($("#PurchaseItems_totalVds").val());
        if(isVds > 0){
            calcVds();
        }
    }

    function calculateGridTotal() {
        var sumtax = 0;
        $(".taxAmount").each(function () {
            var valueTax = $(this).val();
            if (!isNaN(valueTax) && valueTax.length != 0) {
                sumtax += parseFloat(valueTax);
            }
        });
        $("#PurchaseItems_totalTax").val(sumtax.toFixed(2));

        var sumTotal = 0;
        $(".totalAmount").each(function () {
            var valueTotal = $(this).val();
            if (!isNaN(valueTotal) && valueTotal.length != 0) {
                sumTotal += parseFloat(valueTotal);
            }
        });
        var totalWithTax = parseFloat(sumtax + sumTotal).toFixed(2);
        $("#PurchaseItems_totalAmouns").val(sumTotal.toFixed(2));
        $("#PurchaseItems_grand_total").val(totalWithTax);
        
        $("#PurchaseItems_totalRebate").val('');
        $("#rebate").prop("checked", false);
    }
    
    function calcVds(){
        var vatPercent;
        var vatAmount;
        var vatSum =0;
        var vdsSum =0;
        $(".vatPercent").each(function () {
            vatPercent = $(this).val();
            vatAmount = $(this).parents('tr').find('.vatAmount').val();
            if(vatPercent!=15){
                if (!isNaN(vatAmount) && vatAmount.length != 0) {
                    vdsSum += parseFloat(vatAmount);
                }
            }else{            
                if (!isNaN(vatAmount) && vatAmount.length != 0) {
                    vatSum += parseFloat(vatAmount);
                }
            }
        });        
        $("#PurchaseItems_totalVds").val(vdsSum);
        //$("#PurchaseItems_totalTax").val(vatSum);
    }
    function clearBin(){
        $("#bin").val('');
    }
    function giveRebateLine(element){
        if(element.parents('tr').find('.checkRebate').prop('checked')){
            var vatAmount = element.parents('tr').find('.vatAmount').val();
            element.parents('tr').find('.rebateTotal').val(vatAmount);
        }else{
            element.parents('tr').find('.rebateTotal').val(0);
        }
        calcRebateTotal();
    }
    function calcRebateTotal(){
        var sumTotal = 0;
        $(".rebateTotal").each(function () {
            var valueTotal = $(this).val();
            if (!isNaN(valueTotal) && valueTotal.length != 0) {
                sumTotal += parseFloat(valueTotal);
            }
        });
        $("#PurchaseItems_totalRebate").val(sumTotal.toFixed(2));      
    }
</script>    
<script>
$('form').attr('autocomplete','off');
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