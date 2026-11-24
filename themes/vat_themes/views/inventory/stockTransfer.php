<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'stock-transfer-form',
    'action' => $this->createUrl('StockTransferHistory/stockTransferReqReceive'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>
<div class="formDiv">
    <fieldset>
        <legend>Stock Transfer</legend>
        <fieldset style="float: left; width:98%;" class="someStyle">
            <legend>Store Details</legend>
            <table class="form-box">
                <tr>
                    <td style="width:550px;">
                        <table>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'sent_date'); ?></td>
                                <td>
                                    <?php
                                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                                    $dateTimePickerConfig1 = array(
                                        'model' => $model, //Model object
                                        'attribute' => 'sent_date', //attribute name
                                        'mode' => 'date', //use "time","date" or "datetime" (default)
                                        'language' => 'en-AU',
                                        'options' => array(
                                            'changeMonth' => 'true',
                                            'changeYear' => 'true',
                                            'dateFormat' => 'dd-mm-yy',
                                            'width' => '100',
                                        )
                                    );
                                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                                    ?>
                                    <?php echo $form->error($model, 'sent_date'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'sent_from_store_id'); ?></td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                        $model, 'sent_from_store_id', CHtml::listData(Stores::model()->findAll(), 'id', 'store_name'), array(
                                        'prompt' => 'Select',
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'received_to_store_id'); ?></td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                        $model, 'received_to_store_id', CHtml::listData(Stores::model()->findAll(), 'id', 'store_name'), array(
                                        'prompt' => 'Select',
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'remarks'); ?></td>
                                <td>
                                    <?php echo $form->textArea($model, 'remarks', array('rows'=>4, 'cols'=>11, 'style'=>'width: 300px;height: 80px; border: 1px solid #b7b4b4')); ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset class="someStyle" style="float: left; width: 98%;">
            <legend>Search Criteria</legend>
            <table style="width: 98%" class="prodAddedTab">
                <tr>
                    <td><?php echo $form->labelEx($model, 'model_id'); ?></td>
                    <td><?php echo $form->labelEx($model, 'stock_qty'); ?></td>
                    <td><?php echo $form->labelEx($model, 'sent_qty'); ?></td>
                    <td><label>Action</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="text" id="model_id_text"/>
                        <?php echo $form->hiddenField($model, 'model_id'); ?>
                        <script>
                            $(function () {
                                $("#model_id_text").autocomplete({
                                    source: function (request, response) {

                                        var StockTransferHistory_sent_from_store_id = $("#StockTransferHistory_sent_from_store_id").val();
                                        if (StockTransferHistory_sent_from_store_id == "") {
                                            alertify.alert("Please select store!");
                                            $("#model_id_text").val('');
                                            $("#StockTransferHistory_model_id").val('');
                                            return false;
                                        }

                                        var search_prodName = request.term;
                                        var search_stock = $("#StockTransferHistory_sent_from_store_id").val();
                                        $.post('<?php echo Yii::app()->baseUrl ?>/inventory/jqueryProdStockByStore', {
                                                "search_prodName": search_prodName,
                                                "search_stock": search_stock
                                            },
                                            function (data) {
                                                response(data);
                                            }, "json");
                                    },
                                    minLength: 1,
                                    select: function (event, ui) {
                                        $("#model_id_text").val(ui.item.label);
                                        $("#StockTransferHistory_model_id").val(ui.item.value);
                                        $("#unitName").val(ui.item.unitName);
                                        $("#unitId").val(ui.item.unitId);
                                        $("#StockTransferHistory_stock_qty").val(ui.item.closingStock);
                                        return false;
                                    }
                                });
                            });
                        </script>
                        <?php echo $form->error($model, 'model_id'); ?>
                    </td>
                    <td>
                        <?php echo $form->textField($model, 'stock_qty', ['readonly'=>true]); ?>
                    </td>
                    <td>
                        <?php echo $form->textField($model, 'sent_qty'); ?>
                    </td>
                    <td><input class="addDown" style="margin-top: 0; text-align: center" title="Add" type="button" value="" onclick="AddNew()" /></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="width: 98%; float: left; overflow: scroll; height: 360px;" class="someStyle">
            <legend>Added List</legend>
            <table id="tbl" class="prodAddedTab">
                <tr>
                    <th>SL</th>
                    <th>ITEM</th>
                    <th>Stock Qty</th>
                    <th>Sent Qty</th>
                    <th>Remaining Qty</th>
                    <th>ACTION</th>
                </tr>
            </table>
        </fieldset>
    </fieldset>
    <div id="jsPart"></div>
    <div id="formResult" class="ajaxTargetDiv"></div>
    <div id="formResultError" class="ajaxTargetDivErr"></div>
    <fieldset class="tblFooters">
        <?php
        echo CHtml::ajaxSubmitButton('Transfer', CHtml::normalizeUrl(array('StockTransferHistory/stockTransferReqReceive', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                    $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#tbl tr.cartList").remove();
                        sl=0;
                        //$("#StockTransferHistory_grand_total").val(0);
                        //$("#orderNo").html(data.newOrderNo);
                        //$("#poForReport").val(data.lastPONO);
                        $("#formResultError").hide();
                        $("#formResult").fadeIn();
                        $("#formResult").html("Stock Transferred successfully.");
                        $("#stock-transfer-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $("#ajaxLoaderReport").show(); 
                        //$("#poReportDialogBox").dialog("open");
                        //$("#AjFlashReportPo").html(data.poReportInfo).show();
                        $("#ajaxLoaderReport").hide();
                    }else if(data.status=="errorBalance"){
                        $("#formResultError").show();
                        $("#formResultError").html("You Must Add Items First!");
                    }else{
                        /*$("#formResult").show();
                        $("#formResultError").html("Data not saved. Please solve the above errors!");
                        $.each(data, function(key, val) {
                            $("#stock-transfer-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#stock-transfer-form #"+key+"_em_").show();
                        });*/
                    }       
                }',
            'beforeSend' => 'function(){
            
                var StockTransferHistory_sent_date = $("#StockTransferHistory_sent_date").val();
                if (StockTransferHistory_sent_date=="") {
                    alertify.alert("Please enter issue date!");
                    return false;
                }
                
                var StockTransferHistory_sent_from_store_id = $("#StockTransferHistory_sent_from_store_id").val();
                if (StockTransferHistory_sent_from_store_id=="") {
                    alertify.alert("Please select store!");
                    return false;
                }
                
                var StockTransferHistory_received_to_store_id = $("#StockTransferHistory_received_to_store_id").val();
                if (StockTransferHistory_received_to_store_id=="") {
                    alertify.alert("Please select store!");
                    return false;
                }
                
                if(StockTransferHistory_sent_from_store_id == StockTransferHistory_received_to_store_id){
                    alertify.alert("Store should not be same!");
                    return false;
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
    <div id="formResult" class="ajaxTargetDiv">

    </div>
    <div id="formResultError" class="ajaxTargetDivErr">

    </div>

</div>

</div>
<script>
    var vals = [];
</script>
<script type="text/javascript">
    function deleteRows(element) {
        var modelId = parseInt(element.parents('tr').find('.model_id').val());
        removeAcTitleId(vals, modelId);
        $(element).parents("tr").remove();
    }

    function removeAcTitleId(array, element) {
        var index = array.indexOf(element);
        array.splice(index, 1);
    }

    function AddNew() {
        var model_id_text = $("#model_id_text").val();
        var StockTransferHistory_model_id = $("#StockTransferHistory_model_id").val();

        var StockTransferHistory_sent_from_store_id = $("#StockTransferHistory_sent_from_store_id").val();
        if (StockTransferHistory_sent_from_store_id=="") {
            alertify.alert("Please select store!");
            return false;
        }

        if (model_id_text == "" || StockTransferHistory_model_id == "") {
            $("#model_id_text").val('');
            alertify.alert("Please select a product!");
            return false;
        }

        var inv_stk_qty = $("#StockTransferHistory_stock_qty").val();
        if (isNaN(inv_stk_qty) || inv_stk_qty=='' || inv_stk_qty <= 0){
            alertify.alert("Stock Qty Should Not Be Empty!");
            return false;
        }

        var inv_sent_qty = $("#StockTransferHistory_sent_qty").val();
        if (isNaN(inv_sent_qty) || inv_sent_qty=='' || inv_sent_qty <= 0){
            alertify.alert("Sent Qty Should Not Be Empty!");
            return false;
        }

        if (StockTransferHistory_model_id > 0)
            checkDuplicate();
        else {
            $("#model_id_text").val('');
            $("#StockTransferHistory_model_id").val('');
            $("#StockTransferHistory_stock_qty").val('');
            $("#StockTransferHistory_sent_qty").val('');
        }
    }

    function checkDuplicate() {
        var model_id = parseInt($("#StockTransferHistory_model_id").val());
        if (vals.indexOf(model_id) > -1) {
            alertify.alert('Sorry! This Product is already added.');
            $("#model_id_text").val('');
            $("#StockTransferHistory_model_id").val('');
            $("#StockTransferHistory_stock_qty").val('');
            $("#StockTransferHistory_sent_qty").val('');
            return false;

        } else {
            var stockQty = parseInt($("#StockTransferHistory_stock_qty").val());
            var sentQty = parseInt($("#StockTransferHistory_sent_qty").val());
            if(sentQty > stockQty){
                alertify.alert('Stock Qty is limited.');
                return false;
            }
            vals.push(model_id);
            add();
            $("#model_id_text").val('');
            $("#StockTransferHistory_model_id").val('');
            $("#StockTransferHistory_stock_qty").val('');
            $("#StockTransferHistory_sent_qty").val('');
        }
    }

    function add() {
        var slNumber = $('#tbl tr').length;
        var modelId = $("#StockTransferHistory_model_id").val();
        var stockQty = parseInt($("#StockTransferHistory_stock_qty").val());
        var sentQty = parseInt($("#StockTransferHistory_sent_qty").val());
        var remainingQty = stockQty - sentQty;

        var appendTxt = "<tr class='cartList'>" +
            "<td class='sno'>" +
                slNumber +
            "</td>" +
            "<td style='text-align: left;'>" + $("#model_id_text").val() + "</td>" +
            "<td>" + "<input style='width: 90%; text-align: center;' class='center stockQty' type='text' name='StockTransferHistory[temp_stock_qty][]' value='" + stockQty + "'>" + "</td>" +
            "<td>" + "<input style='width: 90%;' onkeyup='calculateGridInLine($(this))' class='center sentQty' type='text' name='StockTransferHistory[temp_sent_qty][]' value='" + sentQty + "'>" + "</td>" +
            "<td>" + "<input style='width: 90%;' class='center remainingQty' type='text' name='StockTransferHistory[temp_remaining_qty][]' value='" + remainingQty + "'>" + "</td>" +
            "<td>" +
            "<input onclick='deleteRows($(this))' title=\"remove\" id='" + slNumber + "' style='width: 90%; text-align: center;' type=\"button\" class=\"rdelete dltBtn\"/>" +
            "<input type='hidden' class='model_id' name='StockTransferHistory[temp_model_id][]' value='" + modelId + "'>" +
            "</td>" +
            "</tr>";
        $("#tbl tr:last").after(appendTxt);

    }
</script>
<?php $this->endWidget(); ?>

<script>
    $('form').attr('autocomplete', 'off');
</script>
<style>
    .form-box input[type=password], .form-box  input[type=text], .form-box  select{
        width: 215px !important;
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
</style>

<script type="text/javascript">
    function stopRKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
    }
    document.onkeypress = stopRKey;
</script>