<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'purchase-items-form',
    'action' => $this->createUrl('materialRecipe/create/'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>
<div class="formDiv">
    <fieldset>
        <legend>Add Product Config</legend>
        <fieldset style="float: left; width:98%;" class="someStyle">
            <legend>Finished Product</legend>
            <table class="form-box" style="margin-bottom: 5px">
                <tr>
                    <td style="width:380px;">
                        <table>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'model_id'); ?></td>
                                <td width="250px">
                                    <input type="text" id="MaterialRecipe_model_id_text"/>
                                    <?php echo $form->hiddenField($model, 'model_id'); ?>
                                    <script>
                                        $(function () {
                                            $("#MaterialRecipe_model_id_text").autocomplete({
                                                source: function (request, response) {
                                                    var search_prodName = request.term;
                                                    $.post('<?php echo Yii::app()->baseUrl ?>/prodModels/jquery_showprodSearch3', {"search_prodName": search_prodName},
                                                        function (data) {
                                                            response(data);
                                                        }, "json");
                                                },
                                                minLength: 1,
                                                select: function (event, ui) {
                                                    $("#MaterialRecipe_model_id_text").val(ui.item.label);
                                                    $("#MaterialRecipe_model_id").val(ui.item.value);
                                                    $("#MaterialRecipe_model_qty_unit_id").val(ui.item.unit);
                                                    return false;
                                                }
                                            });
                                        });
                                    </script>
                                    <?php echo $form->error($model, 'model_id'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'config_date'); ?></td>
                                <td>
                                    <?php
                                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                                    $dateTimePickerConfig1 = array(
                                        'model' => $model, //Model object
                                        'attribute' => 'config_date', //attribute name
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
                                    <?php echo $form->error($model, 'config_date'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'model_qty'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'model_qty', ['value' => '1', 'readonly' => true, 'style' => 'width:55% !important; background-color:#fffafa;']); ?>
                                    <?php
                                    echo $form->dropDownList(
                                        $model, 'model_qty_unit_id', CHtml::listData(Units::model()->findAll(array('order' => 'label ASC')), 'id', 'label'), array(
                                        'prompt' => 'Select',
                                        'style' => 'width:70px  !important;',
                                    ));
                                    ?>
                                    <?php echo $form->error($model, 'model_qty'); ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="2" style="vertical-align: top">
                        <table>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'remarks'); ?></td>
                                <td><?php echo $form->textArea($model, 'remarks', ['rows' => 5, 'cols' => 80]); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <h3 style="float: right; padding-right: 50px"> Total : <span id="totalPriceAmount">0</span>  </h3>
        </fieldset>
        <fieldset class="someStyle" style="float: left; width: 98%;">
            <legend>Material Information's</legend>
            <table style="width: 98%" class="prodAddedTab">
                <tr>
                    <td>Cost Type</td>
                    <td class="adjustment">Item</td>
                    <td class="material"><?php echo $form->labelEx($model, 'rm_model_id'); ?></td>
                    <td class="material"><?php echo $form->labelEx($model, 'rm_qty'); ?></td>
                    <td class="material"><?php echo $form->labelEx($model, 'waste_qty'); ?></td>
                    <td>Unit Price</td>
                    <td><label>Action</label></td>
                </tr>
                <tr>
                    <td width="16%">
                        <?php
                        $dataArray = array(1 => 'Material', 2 => 'Additional', 3 => 'Additional Indirect Material');
                        echo $form->dropDownList($model, 'type', $dataArray);
                        ?>
                    </td>
                    <td class="adjustment">
                        <?php
                        echo $form->dropDownList(
                            $model, 'adjustment_cost', CHtml::listData(ChartOfAc::model()->findAll(['condition' => 'root = 16', 'order' => 'title asc']), 'id', 'title'), array(
                            'prompt' => 'Select',
                            'style' => 'width:400px',
                        ));
                        ?>
                    </td>
                    <td class="material">
                        <input type="text" id="MaterialRecipe_rm_model_id_text"/>
                        <?php echo $form->hiddenField($model, 'rm_model_id'); ?>
                        <script>
                            $(function () {
                                $("#MaterialRecipe_rm_model_id_text").autocomplete({
                                    source: function (request, response) {
                                        var search_prodName = request.term;
                                        $.post('<?php echo Yii::app()->baseUrl ?>/prodModels/jqueryProdSearchWithPrice', {"search_prodName": search_prodName},
                                            function (data) {
                                                response(data);
                                            }, "json");
                                    },
                                    minLength: 1,
                                    select: function (event, ui) {
                                        console.log(ui.item.unit_id);
                                        $("#MaterialRecipe_rm_model_id_text").val(ui.item.label);
                                        $("#MaterialRecipe_rm_model_id").val(ui.item.value);
                                        $("#MaterialRecipe_total_price").val(ui.item.avg_price);
                                        //$("#MaterialRecipe_rm_qty_unit_id").val(ui.item.unitId);
                                        $("#MaterialRecipe_rm_qty_unit_id").val(ui.item.unit_id);
                                        $("#MaterialRecipe_waste_qty_unit_id").val(ui.item.unit_id);
                                        // $("#MaterialRecipe_waste_qty_unit_id").val(ui.item.unitId);

                                        $("#MaterialRecipe_rm_qty_unit_id").attr("style", "pointer-events: none;");
                                        $("#MaterialRecipe_rm_qty_unit_id").css("background-color", "#F5F2F2");

                                        $("#MaterialRecipe_waste_qty_unit_id").attr("style", "pointer-events: none;");
                                        $("#MaterialRecipe_waste_qty_unit_id").css("background-color", "#F5F2F2");
                                        return false;
                                    }
                                });
                            });
                        </script>
                        <?php echo $form->error($model, 'rm_model_id'); ?>
                    </td>
                    <td class="material">
                        <?php echo $form->textField($model, 'rm_qty', ['style' => 'width:40%;']); ?>
                        <?php
                        echo $form->dropDownList(
                            $model, 'rm_qty_unit_id', CHtml::listData(Units::model()->findAll(array('order' => 'label ASC')), 'id', 'label'), array(
                            'prompt' => 'Select',
                            //'style' => 'width:70px;background-color:#e2deda;pointer-events: none;',
                        ));
                        ?>
                    </td>
                    <td class="material">
                        <?php echo $form->textField($model, 'waste_qty', ['style' => 'width:40%;']); ?>
                        <?php
                        echo $form->dropDownList(
                            $model, 'waste_qty_unit_id', CHtml::listData(Units::model()->findAll(array('order' => 'label ASC')), 'id', 'label'), array(
                            'prompt' => 'Select',
                            'style' => 'width:70px;',
                        ));
                        ?>
                    </td>
                    <td>
                        <?php echo $form->textField($model, 'total_price'); ?>
                    </td>
                    <td><input class="addDown" style="margin-top: 0; text-align: center" title="Add" type="button"
                               value="" onclick="AddNew()"/></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="width: 98%; float: left; overflow: scroll; height: 360px;" class="someStyle">
            <legend>Added List</legend>
            <table id="tbl" class="prodAddedTab">
                <tr>
                    <th style="width:20px;">SL</th>
                    <th>ITEM</th>
                    <th style="display: none;">Production Qty (FG)</th>
                    <th>Item Qty</th>
                    <th>Waste Qty</th>
                    <th>Price</th>
                    <th>ACTION</th>
                </tr>
            </table>
        </fieldset>
    </fieldset>
    <div id="jsPart"></div>
    <div id="formResult" class="ajaxTargetDiv"></div>
    <div id="formResultError" class="ajaxTargetDivErr"></div>
    <fieldset class="tblFooters">
        <?php echo CHtml::button('Create', array('submit' => array('materialRecipe/save'), 'id' => 'create', 'style' => 'float: left;padding: 3px 21px !important;')); ?>
        <div id="ajaxLoader" style="display: none; float: left;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/>
        </div>
    </fieldset>
</div>
<script>
    var vals = [];
</script>
<script type="text/javascript">

    $(".adjustment").hide();
    $("#MaterialRecipe_type").change(function () {
        var MaterialRecipe_type = $("#MaterialRecipe_type").val();
        if (MaterialRecipe_type == 1) {
            $(".material").show();
            $(".adjustment").hide();
        } else if (MaterialRecipe_type == 2 || MaterialRecipe_type == 3) {
            $(".material").hide();
            $(".adjustment").show();
        }else {
            $(".material").show();
            $(".adjustment").hide();
        }
    });

    function AddNew() {

        var MaterialRecipe_type = $("#MaterialRecipe_type").val();
        
        if (MaterialRecipe_type == 1) {

            var MaterialRecipe_rm_model_id_text = $("#MaterialRecipe_rm_model_id_text").val();
            var MaterialRecipe_rm_model_id = $("#MaterialRecipe_rm_model_id").val();
            if (MaterialRecipe_rm_model_id_text == "" || MaterialRecipe_rm_model_id == "") {
                $("#MaterialRecipe_rm_model_id_text").val('');
                $("#MaterialRecipe_rm_model_id").val('');
                alertify.alert("Please select a material!");
                return false;
            }
            if( $('#MaterialRecipe_rm_qty').val() <= 0 ){
                alertify.alert("Please Select RM Qty!");
                return false;
            }

            if( $('#MaterialRecipe_rm_qty_unit_id').val() <= 0 ){
                alertify.alert("Please Select RM Qty Unit!");
                return false;
            }
        } else if (MaterialRecipe_type == 2 || MaterialRecipe_type == 3) {
            var MaterialRecipe_rm_model_id = $("#MaterialRecipe_adjustment_cost").val();
            if (MaterialRecipe_rm_model_id == "") {
                $("#MaterialRecipe_rm_model_id").val('');
                alertify.alert("Please select an item");
                return false;
            }
        }

        if ( MaterialRecipe_rm_model_id > 0){
            checkDuplicate();
        }else {
            $("#MaterialRecipe_rm_model_id_text").val('');
            $("#MaterialRecipe_rm_model_id").val('');
            $("#MaterialRecipe_rm_qty").val('');
            $("#MaterialRecipe_waste_qty").val('');
            $("#MaterialRecipe_total_price").val('');
        }
    }

    function checkDuplicate() {

        var MaterialRecipe_type = $("#MaterialRecipe_type").val();
        if (MaterialRecipe_type == 1) { // 1 = Material, 2 = Adjustment, 3 = Additional Indirect Material
            var model_id = 'material' + $("#MaterialRecipe_rm_model_id").val();
        } else if (MaterialRecipe_type == 2 || MaterialRecipe_type == 3) {
            var model_id = 'adjustment' + $("#MaterialRecipe_adjustment_cost").val();
        } else {
        }

        if (vals.indexOf(model_id) > -1) {
            alertify.alert('Sorry! this item is already added!');
            return false;
        } else {
            vals.push(model_id);
            addRow();
            $("#MaterialRecipe_adjustment_cost").val('');
            $("#MaterialRecipe_rm_model_id_text").val('');
            $("#MaterialRecipe_rm_model_id").val('');
            $("#MaterialRecipe_rm_qty").val('');
            $("#MaterialRecipe_waste_qty").val('');
            $("#MaterialRecipe_total_price").val('');
        }

    }

    function addRow() {

        var modelQtyUnit = $('#MaterialRecipe_model_qty_unit_id option:selected').text();
        var modelQtyUnitId = $('#MaterialRecipe_model_qty_unit_id').val();
        if (modelQtyUnitId < 1)
            modelQtyUnit = '';

        var rmQtyUnit = $('#MaterialRecipe_rm_qty_unit_id option:selected').text();
        var rmQtyUnitId = $('#MaterialRecipe_rm_qty_unit_id').val();
        if (rmQtyUnitId < 1)
            rmQtyUnit = '';

        var wasteQtyUnit = $('#MaterialRecipe_waste_qty_unit_id option:selected').text();
        var wasteQtyUnitId = $('#MaterialRecipe_waste_qty_unit_id').val();
        if (wasteQtyUnitId < 1)
            wasteQtyUnit = '';

        var slNumber = $('#tbl tr').length;
        var MaterialRecipe_type = $("#MaterialRecipe_type").val();
        if (MaterialRecipe_type == 1) { // 1 = Material, 2 = Adjustment, 3 = Additional Indirect Material
            var rm_model_id = $("#MaterialRecipe_rm_model_id").val();
            var rm_model_id_text = $("#MaterialRecipe_rm_model_id_text").val();
            var modelTextR = 'material' + rm_model_id;
        } else {
            var rm_model_id = $("#MaterialRecipe_adjustment_cost").val();
            var rm_model_id_text = $("#MaterialRecipe_adjustment_cost option:selected").html();
            var modelTextR = 'adjustment' + rm_model_id;
        }

        var modelQty = $("#MaterialRecipe_model_qty").val();
        var rmQty;
        var wasteQty;
        var totalPrice;
        if (isNaN($("#MaterialRecipe_rm_qty").val()) || $("#MaterialRecipe_rm_qty").val() <= 0)
            rmQty = 0;
        else
            rmQty = parseFloat($("#MaterialRecipe_rm_qty").val());

        if (isNaN($("#MaterialRecipe_waste_qty").val()) || $("#MaterialRecipe_waste_qty").val() <= 0)
            wasteQty = 0;
        else
            wasteQty = parseFloat($("#MaterialRecipe_waste_qty").val());

        if (isNaN($("#MaterialRecipe_total_price").val()) || $("#MaterialRecipe_total_price").val() <= 0)
            totalPrice = 0;
        else
            totalPrice = $("#MaterialRecipe_total_price").val();
        totalPrice = parseFloat(totalPrice).toFixed(3);

        var appendTxt = "<tr class='cartList'>" +
            "<td class='sno'>" +
            slNumber +
            "</td>" +
            "<td style='text-align: left;width: 15%;'>" + rm_model_id_text + "</td>" +
            "<td  style='display: none;' >" +
            "<input style='text-align:center;background-color:#F3F3F3;' class='modelQty' type='text' name='MaterialRecipe[temp_model_qty][]' value='" + modelQty + "' readonly>" +
            "<input type='hidden' name='MaterialRecipe[temp_model_qty_unit_id][]' value='" + modelQtyUnitId + "'>" +
            "<span>" + modelQtyUnit + "</span>" +
            "</td>" +
            "<td>" +
            "<input class='rmQty' type='text' name='MaterialRecipe[temp_rm_qty][]' value='" + rmQty + "'>" +
            "<input type='hidden' name='MaterialRecipe[temp_rm_qty_unit_id][]' value='" + rmQtyUnitId + "'>" +
            "<span>" + rmQtyUnit + "</span>" +
            "</td>" +
            "<td>" +
            "<input class='wasteQty' type='text' name='MaterialRecipe[temp_waste_qty][]' value='" + wasteQty + "'>" +
            "<input type='hidden' name='MaterialRecipe[temp_waste_qty_unit_id][]' value='" + wasteQtyUnitId + "'>" +
            "<span>" + wasteQtyUnit + "</span>" +
            "</td>" +
            "<td>" + "<input class='totalPrice' type='text' onkeyup='totalAmountGet($(this))' name='MaterialRecipe[temp_total_price][]' value='" + totalPrice + "'>" + "</td>" +
            "<td><input type='hidden' name='MaterialRecipe[temp_rm_model_id][]' value='" + rm_model_id + "'>" +
            "<input type='hidden' class='model_text' value='" + modelTextR + "'>" +
            "<input type='hidden' name='MaterialRecipe[temp_cost_type][]' class='cost_type' value='" + MaterialRecipe_type + "'>" +
            "<input onclick='deleteRows($(this))' title=\"remove\" style='width: 90%; text-align: center;' type=\"button\" class=\"rdelete dltBtn\"/>" +
            "</td>" +
            "</tr>";
        $("#tbl tr:last").after(appendTxt);

        $("#MaterialRecipe_rm_qty_unit_id").val('');
        $("#MaterialRecipe_waste_qty_unit_id").val('');
        totalAmountGet();
    }

    function deleteRows(element) {
        var modelId = element.parents('tr').find('.model_text').val();
        removeAcTitleId(vals, modelId);
        $(element).parents("tr").remove();
        totalAmountGet();
    }
    function totalAmountGet() {
        var total_price = 0;
        $(".totalPrice").each(function() {
            var price = $(this).val();
            price = price != "" && !isNaN(price) ? Number(price) : 0;
            total_price += price;
        });
        $("#totalPriceAmount").html(total_price.toFixed(3));
    }


    function removeAcTitleId(array, element) {
        var index = array.indexOf(element);
        array.splice(index, 1);
    }

    // change unit
    $('#MaterialRecipe_rm_qty_unit_id').change(function(e){
        let material_id = $(this).val();
        $('#MaterialRecipe_waste_qty_unit_id').val(material_id);
    });
    // rmQty checking
    $(document).keyup('.rmQty', function(e){
        let rmqty = ( parseFloat($(this).val()) ) ? parseFloat($(this).val()) : 0;
        if(rmqty <= 0){
            $(this).val(1);
        }
    });

    

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
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<script>
    $('form').attr('autocomplete', 'off');
</script>
<script>
</script>
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

    #tbl input[type=text] {
        text-align: right;
    }

    input:read-only {
        background-color: #fffdfd;
    }

    .cartList input[type=text] {
        border: 1px solid #eae8e8;
        padding: 4px 2px;
    }

    select {
        height: 24px;
    }
</style>
<script>
    $("#create").click(function () {

        if($('#MaterialRecipe_model_qty_unit_id').val() <=0 ){
            alertify.alert("Please select Finished Goods Unit.");
            return false;
        }

        if (vals.length < 1) {
            alertify.alert("Please add product in list!");
            return false;
        }
    });
</script>