<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'purchase-items-form',
    'action' => $this->createUrl('production/create/'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>
<div class="formDiv">
    <fieldset>
        <legend>Add Production Data</legend>
        <fieldset style="float: left; width:98%;" class="">
            <table class="form-box">
                <tr>
                    <td style="width:380px;">
                        <table>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'customer_id'); ?></td>
                                <td width="252px">
                                    <input type="text" id="Production_customer_id_text" />
                                    <?php echo $form->hiddenField($model, 'customer_id'); ?>
                                    <script>
                                        $(function () {
                                            $("#Production_customer_id_text").autocomplete({
                                                source: function (request, response) {
                                                    var search_customerName = request.term;
                                                    $.post('<?php echo Yii::app()->baseUrl ?>/customers/jquery_customerSearch', { "customerName": search_customerName },
                                                        function (data) {
                                                            response(data);
                                                        }, "json");
                                                },
                                                minLength: 1,
                                                select: function (event, ui) {
                                                    $("#Production_customer_id_text").val(ui.item.label);
                                                    $("#Production_customer_id").val(ui.item.value);
                                                    $("#bin").val(ui.item.bin);
                                                    return false;
                                                }
                                            });
                                        });
                                    </script>
                                    <?php
                                    echo CHtml::link(
                                        '',
                                        "", // the link for open the dialog
                                        array(
                                            'class' => 'add-additional-btn',
                                            'onclick' => "{addCustomer(); $('#addCustomerDialog').dialog('open');}"
                                        )
                                    );
                                    ?>

                                    <?php
                                    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                                        'id' => 'addCustomerDialog',
                                        'options' => array(
                                            'title' => 'Add Customer',
                                            'autoOpen' => false,
                                            'modal' => true,
                                            'width' => 984,
                                            'resizable' => false,
                                        ),
                                    ));
                                    ?>
                                    <div class="divForForm">
                                        <div class="ajaxLoaderFormLoad" style="display: none;"><img
                                                src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" />
                                        </div>

                                    </div>

                                    <?php $this->endWidget(); ?>

                                    <script type="text/javascript">
                                        // here is the magic
                                        function addCustomer() {
                                            <?php
                                            echo CHtml::ajax(array(
                                                'url' => array('customers/createCustomerFromOutSide'),
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
                                                                $('#addCustomerDialog div.divForForm').html(data.div);
                                                                      // Here is the trick: on submit-> once again this function!
                                                                $('#addCustomerDialog div.divForForm form').submit(addCustomer);
                                                            }
                                                            else
                                                            {
                                                                $('#addCustomerDialog div.divForForm').html(data.div);
                                                                setTimeout(\"$('#addCustomerDialog').dialog('close') \",1000);
                                                                $('#Production_customer_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
                                                            }
                                                                                    }",
                                            ))
                                                ?>
                                            return false;
                                        }
                                    </script>
                                    <?php echo $form->error($model, 'company_name'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'contractual_status'); ?></td>
                                <td>
                                    <?php
                                    $dataArray = array(1 => 'Yes');
                                    echo $form->dropDownList($model, 'contractual_status', $dataArray, array('prompt' => 'Select'));
                                    ?>
                                    <?php echo $form->error($model, 'contractual_status'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'finish_product_id'); ?></td>
                                <td width="250px">
                                    <input type="text" id="Production_finish_product_id_text" />
                                    <?php echo $form->hiddenField($model, 'finish_product_id'); ?>
                                    <input type="hidden" id="innerData" name="inner_data">
                                    <input type="hidden" id="recipeSlNo" name="recipe_sl_no">
                                    <script>
                                        $(function () {
                                            $("#Production_finish_product_id_text").autocomplete({
                                                source: function (request, response) {
                                                    var search_prodName = request.term;
                                                    $.post('<?php echo Yii::app()->baseUrl ?>/prodModels/jquery_showprodSearch1', { "search_prodName": search_prodName },
                                                        function (data) {
                                                            response(data);
                                                            $('#rawRecipe').empty();
                                                            $("#innerData").val('');
                                                        }, "json");
                                                },
                                                minLength: 1,
                                                select: function (event, ui) {
                                                    $("#Production_finish_product_id_text").val(ui.item.label);
                                                    $("#Production_finish_product_id").val(ui.item.value);
                                                    var finishProduct = ui.item.value;
                                                    $('#rawRecipe').empty();
                                                    if (finishProduct != "") {
                                                        $.ajax({
                                                            url: '<?php echo Yii::app()->baseUrl ?>/materialRecipe/getMaterialOfThis',
                                                            cache: false,
                                                            type: "POST",
                                                            data: {
                                                                finish_product_id: finishProduct,
                                                            },
                                                            success: function (data) {
                                                                var obj = JSON.parse(data);
                                                                $("#rawRecipe").append(obj.rawdata);
                                                                $("#innerData").val(obj.countdata);
                                                                $("#recipeSlNo").val(obj.recipeslno);
                                                                $("#Production_qty").val(1);
                                                                $("#fg_unit_name").html(obj.model_unit_name);
                                                                calcTotal();
                                                            }
                                                        });
                                                    }
                                                    return false;
                                                }
                                            });
                                        });
                                    </script>
                                    <?php echo $form->error($model, 'finish_product_id'); ?>
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
                                <td><?php echo $form->labelEx($model, 'qty'); ?></td>
                                <td><?php echo $form->textField($model, 'qty', ['onkeyup' => 'calcGridInLine()', 'class' => 'onlyNumber']); ?>
                                </td>
                                <td>
                                    <?php echo $form->error($model, 'qty'); ?>
                                    <span id="fg_unit_name"></span>
                                    <?php echo $form->hiddenField($model, 'total_price'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'adjustment_value'); ?></td>
                                <td><?php echo $form->textField($model, 'adjustment_value', ['onkeyup' => 'calcTotal()', 'class' => 'onlyNumber', 'value' => '0']); ?>
                                </td>
                                <td>
                                    <?php echo $form->error($model, 'adjustment_value'); ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="2"></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="width: 98%; float: left; overflow: scroll; height: 280px;" class="">
            <legend>Added List</legend>
            <table id="rawRecipe" class="prodAddedTab">
                <!--<tr>
                    <th style="width:20px;">SL</th>
                    <th>ITEM</th>
                    <th>RM Qty</th>
                    <th>Waste Qty</th>
                    <th>Price</th>
                </tr>
                <tbody></tbody>-->
            </table>
        </fieldset>
        <fieldset class="tblFooters">
            <?php echo CHtml::button('Create', array('submit' => array('production/save'), 'id' => 'create', 'style' => 'float: left;padding: 3px 21px !important;')); ?>
            <div id="ajaxLoader" style="display: none; float: left;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" />
            </div>
        </fieldset>
    </fieldset>
</div>
<script>
    function calcGridInLine() {

        var Production_qty = parseFloat($("#Production_qty").val());

        if (isNaN($("#Production_qty").val()) || $("#Production_qty").val() <= 0) {
            Production_qty = 0;
        } else {

            let i = 1;
            $("input.rmQtyOrg").each(function () {
                let rmQtyOrg = parseFloat($(this).val());
                if (isNaN(rmQtyOrg))
                    rmQtyOrg = 0;
                let itq = rmQtyOrg * Production_qty;
                $("input.rmQty" + i).val(itq.toFixed(4));
                i++;
            });

            i = 1;
            $("input.wasteQtyOrg").each(function () {
                let wasteQtyOrg = parseFloat($(this).val());
                if (isNaN(wasteQtyOrg))
                    wasteQtyOrg = 0;
                let itqw = wasteQtyOrg * Production_qty;
                $("input.wasteQty" + i).val(itqw.toFixed(4));
                i++;
            });

            i = 1;
            $("input.unitPrice").each(function () {

                let unitPrice = parseFloat($(this).val());
                if (isNaN(unitPrice)) unitPrice = 0;

                let itqt = unitPrice * Production_qty;
                $("input.totalPrice" + i).val(itqt.toFixed(4));
                i++;
            });
            isExceeded();
        }

        calcTotal();

    }

    function calcTotal() {
        // Sum all per-unit prices (material unit prices)
        var sumPerUnit = 0;
        $(".unitPrice").each(function () {
            let val = parseFloat($(this).val());
            if (!isNaN(val)) sumPerUnit += val;
        });

        // Add additional + indirect material costs (per-unit, from hidden input)
        var additionalSum = parseFloat($("#additionalCostSum").val());
        if (!isNaN(additionalSum)) sumPerUnit += additionalSum;

        // Add adjustment value
        var adjValue = parseFloat($("#Production_adjustment_value").val());
        if (isNaN(adjValue)) adjValue = 0;

        // Multiply by qty: total_price = (sumPerUnit + adjustment_value) * qty
        var prodQty = parseFloat($("#Production_qty").val());
        if (isNaN(prodQty)) prodQty = 0;

        var totalPrice = (sumPerUnit + adjValue) * prodQty;
        $("#Production_total_price").val(totalPrice.toFixed(4));
    }

    $("#create").click(function () {
        var inventoryStock = isExceeded();
        if ($("#Production_finish_product_id_text").val() == "" || $("#Production_finish_product_id").val() == "") {
            alertify.alert("Please enter finished good.");
            return false;
        }
        if ($('#innerData').val() < 1) {
            alertify.alert("Material Config not found.");
            return false;
        }
        if ($("#Production_issue_date").val() == "") {
            alertify.alert("Please enter issue date.");
            return false;
        }
        if ($("#Production_qty").val() < 1) {
            alertify.alert("Please enter qty.");
            return false;
        }
        if (inventoryStock == false) {
            alertify.alert("Stock Qty Exceeded.");
            return false;
        }
    });

    function isExceeded() {
        var value = true;
        var i = 1;
        $("input.stockQty").each(function () {
            var stockQty = parseFloat($(this).val());
            if (isNaN(stockQty))
                stockQty = 0;
            var rmQty = parseFloat($("input.rmQty" + i).val());
            if (rmQty > stockQty) {
                $("input.isExceededRm" + i).val(1); //<---need to put it here
                $("input.rmQty" + i).css('background', 'red'); //<---need to put it here
                value = false;
            } else {
                $("input.isExceededRm" + i).val(0); //<---need to put it here
                $("input.rmQty" + i).css('background', 'white'); //<---need to put it here
            }
            i++;
        });
        return value;
    }
    $(document).ready(function () {
        $(document).on('keypress', '.onlyNumber', function (event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    });


</script>
<?php $this->endWidget(); ?>