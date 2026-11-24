<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'purchase-items-form',
    'action' => $this->createUrl('materialrecipe/create/'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>
<script>
    var vals = [];
</script>
<div class="formDiv">
    <fieldset>
        <legend>Add Production Data</legend>
        <fieldset style="float: left; width:98%;" class="someStyle">
            <legend>Finished Product</legend>
            <table class="form-box">
                <tr>
                    <td style="width:380px;">
                        <table>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'model_id'); ?></td>
                                <td width="250px">
                                    <input type="text" id="MaterialRecipe_model_id_text" value="<?= ProdModels::model()->prodNameOfThis($model->model_id)?>"/>
                                    <?php echo $form->hiddenField($model, 'model_id'); ?>                        
                                    <script>
                                        $(function () {
                                            $("#MaterialRecipe_model_id_text").autocomplete({
                                                source: function (request, response) {
                                                    var search_prodName = request.term;
                                                        $.post('<?php echo Yii::app()->baseUrl ?>/prodModels/jquery_showprodSearch1', {"search_prodName": search_prodName},
                                                                function (data) {
                                                                    response(data);
                                                                }, "json");
                                                    },
                                                minLength: 1,
                                                select: function (event, ui) {
                                                    $("#MaterialRecipe_model_id_text").val(ui.item.label);
                                                    $("#MaterialRecipe_model_id").val(ui.item.value);
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
                                    <?php echo $form->hiddenField($model, 'sl_no'); ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="2"></td>
                </tr>
            </table>
        </fieldset>
        <fieldset class="someStyle" style="float: left; width: 98%;">
            <legend>Material Informations</legend>
            <table style="width: 98%" class="prodAddedTab">
                <tr>
                    <td><?php echo $form->labelEx($model, 'rm_model_id'); ?></td>
                    <td><?php echo $form->labelEx($model, 'rm_qty'); ?></td>
                    <td><?php echo $form->labelEx($model, 'waste_qty'); ?></td>
                    <td><?php echo $form->labelEx($model, 'total_price'); ?></td>
                    <td><label>Action</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="text" id="MaterialRecipe_rm_model_id_text"/>
                        <?php echo $form->hiddenField($model, 'rm_model_id', array('value'=>'')); ?>
                        <script>
                            $(function () {
                                $("#MaterialRecipe_rm_model_id_text").autocomplete({
                                    source: function (request, response) {
                                        var search_prodName = request.term;
                                            $.post('<?php echo Yii::app()->baseUrl ?>/prodModels/jquery_showprodSearch1', {"search_prodName": search_prodName},
                                                    function (data) {
                                                        response(data);
                                                    }, "json");
                                        },
                                    minLength: 1,
                                    select: function (event, ui) {
                                        $("#MaterialRecipe_rm_model_id_text").val(ui.item.label);
                                        $("#MaterialRecipe_rm_model_id").val(ui.item.value);
                                        return false;
                                    }
                                });
                            });
                        </script>
                        <?php echo $form->error($model, 'rm_model_id', array('value'=>'')); ?>
                    </td>
                    <td>
                        <?php echo $form->textField($model, 'rm_qty', array('value'=>'')); ?>
                    </td>
                    <td>
                        <?php echo $form->textField($model, 'waste_qty', array('value'=>'')); ?>
                    </td>
                    <td>
                        <?php echo $form->textField($model, 'total_price', array('value'=>'')); ?>
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
                    <th>RM Qty</th>
                    <th>Waste Qty</th>
                    <th>Price</th>
                    <th>ACTION</th>
                </tr>
                <?php
                if($model->model_id){
                    $rmDatas = MaterialRecipe::model()->findAllByAttributes(array('model_id' => $model->model_id));
                    if($rmDatas){
                        $i = 1;
                        foreach($rmDatas as $rmData){
                            ?>
                        <tr class='cartList'>
                            <td class='sno'><?=$i?></td>
                            <td style='text-align: left;width: 15%;'><?= ProdModels::model()->prodNameOfThis($rmData['rm_model_id'])?></td>
                            <td><input style='text-align:center;background-color:#F3F3F3;' class='modelQty' type='text' name='MaterialRecipe[temp_model_qty][]' value='<?=$rmData['model_qty']?>' readonly></td>
                            <td><input class='rmQty' type='text' name='MaterialRecipe[temp_rm_qty][]' value='<?=$rmData['rm_qty']?>'></td>
                            <td><input class='wasteQty' type='text' name='MaterialRecipe[temp_waste_qty][]' value='<?=$rmData['waste_qty']?>'></td>
                            <td><input class='totalPrice' type='text' onkeyup="calcTotal()" name='MaterialRecipe[temp_total_price][]' value='<?=$rmData['total_price']?>'></td>
                            <td><input type='hidden' name='MaterialRecipe[temp_rm_model_id][]' class='rm_model_id' value='<?=$rmData['rm_model_id']?>'>
                            <input onclick='deleteRows($(this))' title="remove" style='width: 90%; text-align: center;' type="button" class="rdelete dltBtn"/>
                            </td>
                        </tr>
                        <script>
                            vals.push(<?=!empty($rmData["rm_model_id"])?$rmData["rm_model_id"]:''?>);
                        </script>                         
                <?php
                $i++;
                        }
                    }
                }
                ?>
            </table>
        </fieldset>
    </fieldset>
    <div id="jsPart"></div>
    <div id="formResult" class="ajaxTargetDiv"></div>
    <div id="formResultError" class="ajaxTargetDivErr"></div>
    <fieldset class="tblFooters">
        <?php echo CHtml::button('Update', array('submit' => array('materialrecipe/updateRecipe', 'id' => $model->id), 'id'=>'update', 'style'=>'float: left;padding: 3px 21px !important;'));?>
        <div id="ajaxLoader" style="display: none; float: left;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" />
        </div>
    </fieldset>
</div>
<script type="text/javascript">
    function deleteRows(element) {
        var rmModelId = parseInt(element.parents('tr').find('.rm_model_id').val());
        removeAcTitleId(vals, rmModelId);
        $(element).parents("tr").remove();
        calculateGridTotal();
    }

    function removeAcTitleId(array, element) {
        var index = array.indexOf(element);
        array.splice(index, 1);
    }

    function AddNew() {
        var MaterialRecipe_rm_model_id_text = $("#MaterialRecipe_rm_model_id_text").val();
        var MaterialRecipe_rm_model_id = $("#MaterialRecipe_rm_model_id").val();
        if (MaterialRecipe_rm_model_id_text == "" || MaterialRecipe_rm_model_id == "") {
            $("#MaterialRecipe_rm_model_id_text").val('');
            $("#MaterialRecipe_rm_model_id").val('');
            alertify.alert("Please select a material!");
            return false;
        }

        var MaterialRecipe_rm_qty = $("#MaterialRecipe_rm_qty").val();
        if (MaterialRecipe_rm_qty == "") {
            alertify.alert("Please enter material qty!");
            return false;
        }

        if (MaterialRecipe_rm_model_id > 0)
            checkDuplicate();
        else {
            $("#MaterialRecipe_rm_model_id_text").val('');
            $("#MaterialRecipe_rm_model_id").val('');
            $("#MaterialRecipe_rm_qty").val('');
            $("#MaterialRecipe_waste_qty").val('');
            $("#MaterialRecipe_total_price").val('');
        }
    }

    function checkDuplicate() {
        var rm_model_id = parseInt($("#MaterialRecipe_rm_model_id").val());
        if (vals.indexOf(rm_model_id) > -1) {
            alertify.alert('Sorry! This Material is already added.');
            $("#MaterialRecipe_rm_model_id_text").val('');
            $("#MaterialRecipe_rm_model_id").val('');
            $("#MaterialRecipe_rm_qty").val('');
            $("#MaterialRecipe_waste_qty").val('');
            $("#MaterialRecipe_total_price").val('');
            return false;
        } else {
            vals.push(rm_model_id);
            add();
            $("#MaterialRecipe_rm_model_id_text").val('');
            $("#MaterialRecipe_rm_model_id").val('');
            $("#MaterialRecipe_rm_qty").val('');
            $("#MaterialRecipe_waste_qty").val('');
            $("#MaterialRecipe_total_price").val('');
        }
    }

    function add() {
        var slNumber = $('#tbl tr').length;
        var rm_model_id = $("#MaterialRecipe_rm_model_id").val();
        var rm_model_id_text = $("#MaterialRecipe_rm_model_id_text").val();
        var modelQty = 1;
        var rmQty;
        var wasteQty;
        var totalPrice;
        if (isNaN($("#MaterialRecipe_rm_qty").val()) || $("#MaterialRecipe_rm_qty").val() <= 0)
            rmQty = 0;
        else
            rmQty = parseFloat($("#MaterialRecipe_rm_qty").val()).toFixed(2);
            
        if (isNaN($("#MaterialRecipe_waste_qty").val()) || $("#MaterialRecipe_waste_qty").val() <= 0)
            wasteQty = 0;
        else
            wasteQty = parseFloat($("#MaterialRecipe_waste_qty").val()).toFixed(2);
            
        if (isNaN($("#MaterialRecipe_total_price").val()) || $("#MaterialRecipe_total_price").val() <= 0)
            totalPrice = 0;
        else
            totalPrice = $("#MaterialRecipe_total_price").val();
            totalPrice = parseFloat(totalPrice).toFixed(2);
        
        var appendTxt = "<tr class='cartList'>" +
                "<td class='sno'>" +
                slNumber +
                "</td>" +
                "<td style='text-align: left;width: 15%;'>" + rm_model_id_text + "</td>" +
                "<td>" + "<input style='text-align:center;background-color:#F3F3F3;' class='modelQty' type='text' name='MaterialRecipe[temp_model_qty][]' value='" + modelQty + "' readonly>" + "</td>" +
                "<td>" + "<input class='rmQty' type='text' name='MaterialRecipe[temp_rm_qty][]' value='" + rmQty + "'>" + "</td>" +
                "<td>" + "<input class='wasteQty' type='text' name='MaterialRecipe[temp_waste_qty][]' value='" + wasteQty + "'>" + "</td>" +
                "<td>" + "<input class='totalPrice' type='text' name='MaterialRecipe[temp_total_price][]' value='" + totalPrice + "'>" + "</td>" +
                "<td><input type='hidden' name='MaterialRecipe[temp_rm_model_id][]' class='rm_model_id' value='" + rm_model_id + "'>" +
                "<input onclick='deleteRows($(this))' title=\"remove\" style='width: 90%; text-align: center;' type=\"button\" class=\"rdelete dltBtn\"/>" +
                "</td>" +
                "</tr>";
        $("#tbl tr:last").after(appendTxt);
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
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<script>
    $('form').attr('autocomplete', 'off');
</script>
<script>
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
    #tbl input[type=text]{
        text-align:right;
    }
    input:read-only {
        background-color: #fffdfd;
    }
</style>
<script>
    $("#update").click(function () {
        if (vals.length<1) {
            alertify.alert("Please add product in list!");
            return false;
        }
    });
 </script>