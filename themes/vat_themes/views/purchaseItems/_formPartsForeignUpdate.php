<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'purchase-items-form',
    'action' => $this->createUrl('purchaseItems/savePartsForeign/'),
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
<script>
    var vals = [];
</script>
<div class="formDiv">
    <fieldset>
        <div class="panel-heading">Purchase Requisition Update Form (Import)</div>
        <fieldset style="float: left; width:98%;" class="someStyle">
            <legend>ORDER Information</legend>
            <table class="form-box">
                <tr>
                    <td style="width:350px;">
                        <table>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'po_no'); ?></td>
                                <td>
                                    <div id="orderNo" class="receivedByDiv">
                                        <?php
                                        $thisPurchaseNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::PURCHASE_ORDER);
                                        ?>
                                        <?= !empty($model->id) ? $thisPurchaseNoFormat . '-' . $model->po_no : '' ?>
                                    </div>
                                    <?php echo $form->error($model, 'po_no'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'supplier_id'); ?></td>
                                <td width="253px">
                                    <input type="text" id="PurchaseItems_supplier_id_text"
                                           value="<?= Suppliers::model()->supplierName($model->supplier_id) ?>"
                                           onkeyup="clearBin()"/>
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
                                        <div class="ajaxLoaderFormLoad" style="display: none;"><img
                                                    src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/>
                                        </div>

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
                                    <input type="text" name="bin" id="bin"
                                           value="<?= Suppliers::model()->supplierBin($model->supplier_id) ?>" readonly>
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
                                        ), // jquery plugin options
                                        'htmlOptions' => [
                                            'value' => $model->issue_date != NULL ? date('d-m-Y', strtotime($model->issue_date)) : '',
                                            'placeholder' => 'dd-mm-yyyy',
                                        ]
                                    );
                                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                                    ?>
                                    <?php echo $form->error($model, 'issue_date'); ?>
                                    <script>
                                        $('#PurchaseItems_issue_date').live('change', function () {
                                            $('#PurchaseItems_effective_date').datepicker("option", 'minDate', this.value);
                                        });
                                    </script>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'effective_date'); ?></td>
                                <td>
                                    <?php
                                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                                    $dateTimePickerConfig1 = array(
                                        'model' => $model, //Model object
                                        'attribute' => 'effective_date', //attribute name
                                        'mode' => 'date', //use "time","date" or "datetime" (default)
                                        'language' => 'en-AU',
                                        'options' => array(
                                            'changeMonth' => 'true',
                                            'changeYear' => 'true',
                                            'dateFormat' => 'dd-mm-yy',
                                            'width' => '100',
                                            'minDate' => date('d-m-Y', strtotime($model->issue_date)),
                                        ), // jquery plugin options
                                        'htmlOptions' => [
                                            'value' => $model->effective_date != NULL ? date('d-m-Y', strtotime($model->effective_date)) : '',
                                            'placeholder' => 'dd-mm-yyyy',
                                            'readonly' => true,
                                        ]
                                    );
                                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                                    ?>
                                    <?php echo $form->error($model, 'effective_date'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'product_type'); ?></td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                        $model, 'product_type', CHtml::listData(ProdItems::model()->findAll(array('order' => 'item_name ASC')), 'id', 'item_name'), array(
                                        'prompt' => 'Select',
                                    ));
                                    ?>
                                    <?php echo $form->error($model, 'product_type'); ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 350px">
                        <table>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'boe_no'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'boe_no'); ?>
                                    <?php echo $form->error($model, 'boe_no'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'boe_date'); ?></td>
                                <td>
                                    <?php
                                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                                    $dateTimePickerConfig1 = array(
                                        'model' => $model, //Model object
                                        'attribute' => 'boe_date', //attribute name
                                        'mode' => 'date', //use "time","date" or "datetime" (default)
                                        'language' => 'en-AU',
                                        'options' => array(
                                            'changeMonth' => 'true',
                                            'changeYear' => 'true',
                                            'dateFormat' => 'dd-mm-yy',
                                            'width' => '100',
                                        ), // jquery plugin options
                                        'htmlOptions' => [
                                            'value' => $model->boe_date != NULL ? date('d-m-Y', strtotime($model->boe_date)) : '',
                                            'placeholder' => 'dd-mm-yyyy',
                                        ]
                                    );
                                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                                    ?>
                                    <?php echo $form->error($model, 'boe_date'); ?>
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
                                    $dateTimePickerConfig1 = array(
                                        'model' => $model, //Model object
                                        'attribute' => 'lc_date', //attribute name
                                        'mode' => 'date', //use "time","date" or "datetime" (default)
                                        'language' => 'en-AU',
                                        'options' => array(
                                            'changeMonth' => 'true',
                                            'changeYear' => 'true',
                                            'dateFormat' => 'dd-mm-yy',
                                            'width' => '100',
                                        ), // jquery plugin options
                                        'htmlOptions' => [
                                            'value' => $model->lc_date != NULL ? date('d-m-Y', strtotime($model->lc_date)) : '',
                                            'placeholder' => 'dd-mm-yyyy',
                                        ]
                                    );
                                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                                    ?>
                                    <?php echo $form->error($model, 'lc_date'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'country_id'); ?></td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                        $model, 'country_id', CHtml::listData(Countries::model()->findAll(array('order' => 'country ASC')), 'id', 'country'), array(
                                        'prompt' => 'Select',
                                    ));
                                    ?>
                                    <?php echo $form->error($model, 'country_id'); ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="vertical-align:top">
                        <table>
                            <tr>
                                <td width="100px"><?php echo $form->labelEx($model, 'port_id'); ?></td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                        $model, 'port_id', CHtml::listData(Ports::model()->findAll(array('order' => 'name ASC')), 'id', 'name'), array(
                                        'prompt' => 'Select',
                                    ));
                                    ?>
                                    <?php echo $form->error($model, 'port_id'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="100px"><?php echo $form->labelEx($model, 'port_code'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'port_code', ['readonly' => true]); ?>
                                    <?php echo $form->error($model, 'port_code'); ?>
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
                            if ($model->payment_method == 133) {
                                $dsp = 'style="display: none"';
                            }
                            ?>
                            <tr id="bank_option" <?= $dsp ?> >
                                <td><?php echo $form->labelEx($model, 'bank_id'); ?></td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                        $model, 'bank_id', CHtml::listData(ChartOfAc::model()->findAll(['condition' => 'root = 157', 'order' => 'title asc']), 'id', 'title'), array(
                                        'prompt' => 'Select',
                                    ));
                                    ?>
                                    <?php echo $form->error($model, 'bank_id'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td><?php echo $form->labelEx($model, 'vehicle_type'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'vehicle_type'); ?>
                                    <?php echo $form->error($model, 'vehicle_type'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'vehicle_no'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'vehicle_no'); ?>
                                    <?php echo $form->error($model, 'vehicle_no'); ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </fieldset>

        <fieldset class="someStyle" style="float: left; width: 98%;">
            <legend>Product Informations</legend>
            <table style="width: 98%" class="prodAddedTab">
                <tr>
                    <td><?php echo $form->labelEx($model, 'Raw Material'); ?></td>
                    <td><?php echo $form->labelEx($model, 'order_qty'); ?></td>
                    <td><label>Action</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="text" id="model_id_text" style="width: 70%;"/>
                        <?php echo $form->hiddenField($model, 'model_id'); ?>
                        <script>
                            $(function () {
                                $("#model_id_text").autocomplete({
                                    source: function (request, response) {
                                        var search_prodName = request.term;
                                        $.post('<?php echo Yii::app()->baseUrl ?>/prodModels/jqueryProdSearch', {"search_prodName": search_prodName},
                                            function (data) {
                                                response(data);
                                            }, "json");
                                    },
                                    minLength: 1,
                                    select: function (event, ui) {
                                        $("#model_id_text").val(ui.item.label);
                                        $("#PurchaseItems_model_id").val(ui.item.value);
                                        $("#unitName").val(ui.item.unitName);
                                        $("#unitId").val(ui.item.unitId);
                                        $("#unitPrice").val(ui.item.unitPrice);
                                        $("#vat").val(ui.item.vat);
                                        $("#vatType").val(ui.item.vatType);
                                        $("#rebatable").val(ui.item.rebatable);
                                        $("#cd").val(ui.item.cd);
                                        $("#sd").val(ui.item.sd);
                                        $("#rd").val(ui.item.rd);
                                        $("#ait").val(ui.item.ait);
                                        $("#atv").val(ui.item.atv);
                                        $("#tti").val(ui.item.tti);
                                        $("#hscode").val(ui.item.hscode);
                                        $("#PurchaseItems_order_qty").val(ui.item.orderQty);
                                        return false;
                                    }
                                });
                            });
                        </script>

                        <?php
                        echo CHtml::link('', "", // the link for open the dialog
                            array(
                                'class' => 'add-additional-btn',
                                'onclick' => "{addProdModel(); $('#dialogAddProdModel').dialog('open');}"));
                        ?>

                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                            'id' => 'dialogAddProdModel',
                            'options' => array(
                                'title' => 'Add Product',
                                'autoOpen' => false,
                                'modal' => true,
                                'width' => '650px',
                                /*'left' => '30px',*/
                                'resizable' => false,
                            ),
                        ));
                        ?>
                        <div class="divForForm">
                            <div class="ajaxLoaderFormLoad" style="display: none;"><img
                                        src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/></div>

                        </div>

                        <?php $this->endWidget(); ?>

                        <script type="text/javascript">
                            // here is the magic
                            function addProdModel() {
                                <?php
                                echo CHtml::ajax(array(
                                    'url' => array('prodModels/createProdModelsFromOutSide2'),
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
                                            $('#dialogAddProdModel div.divForForm').html(data.div);
                                                  // Here is the trick: on submit-> once again this function!
                                            $('#dialogAddProdModel div.divForForm form').submit(addProdModel);
                                        }
                                        else
                                        {
                                            $('#dialogAddProdModel div.divForForm').html(data.div);
                                            setTimeout(\"$('#dialogAddProdModel').dialog('close') \",1000);
                                            $('#PurchaseItems_model_id').val(data.value);
                                            $('#model_id_text').val(data.label);
                                            $('#PurchaseItems_code').val(data.prodCode);
                                            $('#PurchaseItems_brand_id').append('<option selected value='+data.brandId+'>'+data.brandName+'</option>');
                                            $('#PurchaseItems_item_id').append('<option selected value='+data.itemId+'>'+data.itemName+'</option>');
                                        }
                                }",
                                ))
                                ?>
                                return false;
                            }
                        </script>
                        <?php echo $form->error($model, 'model_id'); ?>
                    </td>
                    <td>
                        <?php echo $form->textField($model, 'order_qty', ['style' => 'width: 70%;']); ?>
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
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>CV Rate</th>
                    <th>AV (Total)</th>
                    <th>CD(%)</th>
                    <th>CD</th>
                    <th>RD(%)</th>
                    <th>RD</th>
                    <th>SD(%)</th>
                    <th>SD</th>
                    <th>VAT(%)</th>
                    <th>VAT</th>
                    <th>AIT(%)</th>
                    <th>AIT</th>
                    <th>AT(%)</th>
                    <th>AT</th>
                    <th>TT1(Total Tax)</th>
                    <?php if ($yourCompanyInfo->company_vat_type == YourCompany::STANDARD) { ?>
                        <th>Rebate</th>
                    <?php } ?>
                    <th>ACTION</th>
                </tr>

                <?php
                $sql = "SELECT * FROM purchase_details WHERE purchase_id  = '$purchase_id' ORDER BY id ASC";
                $connection = Yii::app()->db;
                $command = $connection->createCommand($sql);
                $purchase_details = $command->queryAll();

                if (!empty($purchase_details)) {
                    $i = 1;
                    foreach ($purchase_details as $po_items) {
                        $model_name = ProdModels::model()->prodNameOfThis($po_items["model_id"]);
                        $hs_code = ProdModels::model()->hsCodeOfThis($po_items["model_id"]);
                        $unit_name = Units::model()->unitLabel($po_items["unit_id"]);
                        ?>
                        <tr class='cartList'>
                            <td class='sno'><?= $i ?></td>
                            <td style='text-align: left;min-width:100px;'><?= !empty($model_name) ? $model_name : '' ?><?= !empty($hs_code) ? "(" . $hs_code . ")" : '' ?></td>
                            <td width='53px'><input onkeyup='calculateGridLineExtCharge($(this))'
                                                    style='width: 90%; text-align: center;' class='lineQty' type='text'
                                                    name='PurchaseItems[temp_order_qty][]'
                                                    value='<?= !empty($po_items["order_qty"]) ? $po_items["order_qty"] : 0 ?>'>
                            </td>
                            <td><?= !empty($unit_name) ? $unit_name : '' ?></td>

                            <td><input onkeyup='calculateGridLineExtCharge($(this))'
                                       style='width: 90%; text-align: right;' class='pprice' type='text'
                                       name='PurchaseItems[temp_purchase_price][]'
                                       value='<?= !empty($po_items["unit_price"]) ? $po_items["unit_price"] : 0 ?>'>
                            </td>
                            <td><input onkeyup='calculateGridLineExtCharge($(this))'
                                       style='width: 90%; text-align: right;' class='cvrate' type='text'
                                       name='PurchaseItems[temp_cv_rate][]'
                                       value='<?= !empty($po_items["cv_rate"]) ? $po_items["cv_rate"] : 0 ?>'></td>
                            <td>
                                <input onkeyup='calculateGridLineExtCharge($(this))'
                                       style='width: 90%; text-align: right;' class='totalAmount' type='text'
                                       name='PurchaseItems[temp_total_amount][]' value='<?= $po_items["total"] ?>'>
                            </td>

                            <td width='53px'><input onkeyup='calculateGridLineExtCharge($(this))'
                                                    style='width: 90%; text-align: right;' class='cd_percent'
                                                    type='text' name='PurchaseItems[temp_cd_percent][]'
                                                    value='<?= !empty($po_items["cd_percent"]) ? $po_items["cd_percent"] : 0 ?>'>
                            </td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='gridVal cd' type='text'
                                       name='PurchaseItems[temp_cd][]'
                                       value='<?= !empty($po_items["cd"]) ? $po_items["cd"] : 0 ?>' readonly>
                                <div class='gridValSpan cdSpan'></div>
                            </td>
                            <td width='53px'><input onkeyup='calculateGridLineExtCharge($(this))'
                                                    style='width: 90%; text-align: right;' class='rd_percent'
                                                    type='text' name='PurchaseItems[temp_rd_percent][]'
                                                    value='<?= !empty($po_items["rd_percent"]) ? $po_items["rd_percent"] : 0 ?>'>
                            </td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='gridVal rd' type='text'
                                       name='PurchaseItems[temp_rd][]'
                                       value='<?= !empty($po_items["rd"]) ? $po_items["rd"] : 0 ?>' readonly>
                                <div class='gridValSpan rdSpan'></div>
                            </td>
                            <td width='53px'><input onkeyup='calculateGridLineExtCharge($(this))'
                                                    style='width: 90%; text-align: right;' class='sd_percent'
                                                    type='text' name='PurchaseItems[temp_sd_percent][]'
                                                    value='<?= !empty($po_items["sd_percent"]) ? $po_items["sd_percent"] : 0 ?>'>
                            </td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='gridVal sd' type='text'
                                       name='PurchaseItems[temp_sd][]'
                                       value='<?= !empty($po_items["sd"]) ? $po_items["sd"] : 0 ?>' readonly>
                                <div class='gridValSpan sdSpan'></div>
                            </td>
                            <td width='53px'><input onkeyup='calculateGridLineExtCharge($(this))'
                                                    style='width: 90%; text-align: right;' class='vatPercent'
                                                    type='text' name='PurchaseItems[temp_vat_percent][]'
                                                    value='<?= !empty($po_items["vat_percent"]) ? $po_items["vat_percent"] : 0 ?>'>
                            </td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='gridVal vatAmount' type='text'
                                       name='PurchaseItems[temp_vat_amount][]'
                                       value='<?= !empty($po_items["vat"]) ? $po_items["vat"] : 0 ?>' readonly>
                                <div class='gridValSpan vatAmountSpan'></div>
                            </td>

                            <td width='53px'><input onkeyup='calculateGridLineExtCharge($(this))'
                                                    style='width: 90%; text-align: right;'
                                                    class='noWidthInput validationInput touchInpt2 ait_percent'
                                                    type='text' name='PurchaseItems[temp_ait_percent][]'
                                                    value='<?= !empty($po_items["ait_percent"]) ? $po_items["ait_percent"] : 0 ?>'>
                            </td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='gridVal ait' type='text'
                                       name='PurchaseItems[temp_ait][]'
                                       value='<?= !empty($po_items["ait"]) ? $po_items["ait"] : 0 ?>' readonly>
                                <div class='gridValSpan aitSpan'></div>
                            </td>
                            <td width='53px'><input onkeyup='calculateGridLineExtCharge($(this))'
                                                    style='width: 90%; text-align: right;'
                                                    class='noWidthInput validationInput touchInpt2 at_percent'
                                                    type='text' name='PurchaseItems[temp_at_percent][]'
                                                    value='<?= !empty($po_items["at_percent"]) ? $po_items["at_percent"] : 0 ?>'>
                            </td>
                            <td>
                                <input style='width: 90%; text-align: right;' class='gridVal at' type='text'
                                       name='PurchaseItems[temp_at][]'
                                       value='<?= !empty($po_items["at"]) ? $po_items["at"] : 0 ?>' readonly>
                                <div class='gridValSpan atSpan'></div>
                            </td>

                            <td>
                                <input style='width: 90%; text-align: right;' class='gridVal taxAmount' type='text'
                                       name='PurchaseItems[temp_tax_amount][]'
                                       value='<?= !empty($po_items["total_tax"]) ? $po_items["total_tax"] : 0 ?>'
                                       readonly>
                                <div class='gridValSpan taxAmountSpan'></div>
                            </td>
                            <?php if ($yourCompanyInfo->company_vat_type == YourCompany::STANDARD) { ?>
                                <td><input type='checkbox' onchange='giveRebateLine($(this))'
                                           class='checkRebate' <?= $po_items["rebate"] > 0 ? "checked" : "" ?>><input
                                            style='width: 65%; text-align: right;' onkeyup='calcRebateTotal()'
                                            class='rebateTotal' type='text' name='PurchaseItems[temp_rebate_amount][]'
                                            value='<?= !empty($po_items["rebate"]) ? number_format($po_items["rebate"], 2, '.', '') : 0.00 ?>'>
                                </td>
                            <?php } ?>
                            <td>
                                <input type='hidden' class='model_id' name='PurchaseItems[temp_model_id][]'
                                       value='<?= !empty($po_items["model_id"]) ? $po_items["model_id"] : '' ?>'>
                                <input type='hidden' name='PurchaseItems[temp_unit][]'
                                       value='<?= !empty($po_items["unit_id"]) ? $po_items["unit_id"] : '' ?>'>

                                <input type='hidden' name='PurchaseItems[temp_base_vat][]'
                                       value='<?= !empty($po_items["base_vat"]) ? $po_items["base_vat"] : '' ?>'>
                                <input type='hidden' name='PurchaseItems[temp_base_vat_percent][]'
                                       value='<?= !empty($po_items["base_vat_percent"]) ? $po_items["base_vat_percent"] : '' ?>'>
                                <input type='hidden' name='PurchaseItems[temp_vat_calculation_method][]'
                                       value='<?= !empty($po_items["vat_calculation_method"]) ? $po_items["vat_calculation_method"] : '' ?>'>
                                <input onclick='deleteRows($(this))' title="remove"
                                       style='width: 90%; text-align: center;' type="button" class="rdelete dltBtn"/>
                            </td>
                        </tr>
                        <script>
                            vals.push(<?=!empty($po_items["model_id"]) ? $po_items["model_id"] : ''?>);
                        </script>
                        <?php
                        $i++;
                    }
                }
                ?>
            </table>
            <table class="prodAddedTab" style="margin-top: 20px;width: 100%;">
                <tr>
                    <td><label> Total (inc. duty)</label></td>
                    <td><label> Total SD</label></td>
                    <td><label> Total VAT</label></td>
                    <td><label> Grand Total</label></td>
                </tr>
                <tr class="final-grid-tr">
                    <td><input id="PurchaseItems_totalAmouns" name='PurchaseItems[total]' type='text'
                               value='<?= !empty($model["total"]) ? number_format($model["total"], 2, '.', '') : 0.00 ?>'>
                    </td>
                    <td><input id="PurchaseItems_totalSd" name='PurchaseItems[total_sd]' type='text'
                               value='<?= !empty($model["total_sd"]) ? number_format($model["total_sd"], 2, '.', '') : 0.00 ?>'>
                    </td>
                    <td><input id="PurchaseItems_totalTax" name='PurchaseItems[total_tax]' type='text'
                               value='<?= !empty($model["total_tax"]) ? number_format($model["total_tax"], 2, '.', '') : 0.00 ?>'>
                    </td>
                    <td><input id="PurchaseItems_grand_total" name='PurchaseItems[grand_total]' type='text'
                               value='<?= !empty($model["grand_total"]) ? number_format($model["grand_total"], 2, '.', '') : 0.00 ?>'>
                    </td>
                </tr>
            </table>
            <?php
            if ($yourCompanyInfo->company_vat_type == YourCompany::STANDARD) {
                ?>
                <table class="prodAddedTab" style="margin-top: 20px;width: 100%;">
                    <tr>
                        <td><label> Rebate Amount</label></td>
                    </tr>
                    <tr class="final-grid-tr">
                        <td>
                            <input id="PurchaseItems_totalRebate" name='PurchaseItems[total_rebate]' type='text'
                                   value='<?= !empty($model["total_rebate"]) ? number_format($model["total_rebate"], 2, '.', '') : 0.00 ?>'
                                   readonly>
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
        echo CHtml::ajaxSubmitButton('Update PR', CHtml::normalizeUrl(array('purchaseItems/updateInfoForeign', 'id' => $model->id)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                    $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#tbl tr.cartList").remove();
                        sl=0;
                        $("#PurchaseItems_grand_total").val(0);
                        $("#orderNo").html(data.newOrderNo);
                        $("#poForReport").val(data.lastPONO);
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
                var PurchaseItems_supplier_id_text = $("#PurchaseItems_supplier_id_text").val();
                var PurchaseItems_supplier_id = $("#PurchaseItems_supplier_id").val();
                if (PurchaseItems_supplier_id_text=="" || PurchaseItems_supplier_id=="") {
                    alertify.alert("Please enter supplier!");
                    return false;
                }
                var PurchaseItems_issue_date = $("#PurchaseItems_issue_date").val();
                if (PurchaseItems_issue_date=="") {
                    alertify.alert("Please enter issue date!");
                    return false;
                }
                var PurchaseItems_product_type = $("#PurchaseItems_product_type").val();
                if (PurchaseItems_product_type=="") {
                    alertify.alert("Please select product type!");
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
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/>
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
<input type="hidden" id="unitName"/>
<input type="hidden" id="unitId"/>
<input type="hidden" id="unitPrice"/>
<input type="hidden" id="vat"/>
<input type="hidden" id="vatType"/>
<input type="hidden" id="rebatable"/>
<input type="hidden" id="cd"/>
<input type="hidden" id="sd"/>
<input type="hidden" id="rd"/>
<input type="hidden" id="ait"/>
<input type="hidden" id="atv"/>
<input type="hidden" id="tti"/>
<input type="hidden" id="exd"/>
<input type="hidden" id="hscode"/>

<script type="text/javascript">
    $("#rebate").change(function () {
        if ($(this).prop('checked')) {
            var rebVal = $("#PurchaseItems_totalTax").val();
            $("#PurchaseItems_totalRebate").val(rebVal);
        } else {
            $("#PurchaseItems_totalRebate").val('');
        }
    });

    $("#PurchaseItems_port_id").change(function () {
        var port_id = $("#PurchaseItems_port_id").val();
        $.post('<?php echo Yii::app()->baseUrl ?>/ports/jqueryPortCodeSearch', {"search_portId": port_id},
            function (data) {
                $("#PurchaseItems_port_code").val(data);
            }, "json");
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
        var model_id_text = $("#model_id_text").val();
        var PurchaseItems_model_id = $("#PurchaseItems_model_id").val();
        if (model_id_text == "" || PurchaseItems_model_id < 1) {
            $("#model_id_text").val('');
            alertify.alert("Please select a product!");
            return false;
        }

        var PurchaseItems_order_qty = $("#PurchaseItems_order_qty").val();
        if (PurchaseItems_order_qty == "") {
            alertify.alert("Please enter order qty!");
            return false;
        }

        if (PurchaseItems_model_id > 0)
            checkDuplicate();
        else {
            $("#model_id_text").val('');
            $("#PurchaseItems_model_id").val('');
            $("#PurchaseItems_order_qty").val('');
        }
    }

    function checkDuplicate() {
        var model_id = parseInt($("#PurchaseItems_model_id").val());
        if (vals.indexOf(model_id) > -1) {
            alertify.alert('Sorry! This Product is already added.');
            $("#model_id_text").val('');
            $("#PurchaseItems_model_id").val('');
            $("#PurchaseItems_order_qty").val('');
            return false;
        } else {
            vals.push(model_id);
            add();
            $("#model_id_text").val('');
            $("#PurchaseItems_model_id").val('');
            $("#PurchaseItems_order_qty").val('');
        }
    }

    function add() {
        var slNumber = $('#tbl tr').length;
        var modelId = $("#PurchaseItems_model_id").val();
        var orderQty = parseFloat($("#PurchaseItems_order_qty").val());
        var unitName = $("#unitName").val();
        var unitId = $("#unitId").val();
        var totalAmount = 0;
        var _totalAmount = 0;
        var unitPrice = 0;
        var _unitPrice = 0;
        var rowTotal = 0;
        var cvRate = 1.01; // default 1.01
        if (isNaN($("#unitPrice").val()) || $("#unitPrice").val() == '' || $("#unitPrice").val() <= 0)
            unitPrice = 0;
        else
            _unitPrice = parseFloat($("#unitPrice").val());
        unitPrice = parseFloat(_unitPrice.toFixed(4));

        rowTotal = orderQty * _unitPrice;
        _totalAmount = ((rowTotal * cvRate) / 100) + rowTotal;
        totalAmount = parseFloat(_totalAmount.toFixed(4));

        var taxAmount = 0;

        var cdPercent = $("#cd").val();
        var sdPercent = $("#sd").val();
        var rdPercent = $("#rd").val();
        var aitPercent = $("#ait").val();
        var atPercent = $("#atv").val();

        var cd = 0;
        var sd = 0;
        var rd = 0;
        var ait = 0;
        var at = 0;
        var _cd = 0;
        var _rd = 0;
        var _sd = 0;
        var _ait = 0;
        var _at = 0;
        var _vatAmount = 0;

        if (isNaN(cdPercent) || cdPercent == '' || cdPercent < 0) {
            cdPercent = 0;
        }
        if (isNaN(sdPercent) || sdPercent == '' || sdPercent < 0) {
            sdPercent = 0;
        }
        if (isNaN(rdPercent) || rdPercent == '' || rdPercent < 0) {
            rdPercent = 0;
        }
        if (isNaN(aitPercent) || aitPercent == '' || aitPercent < 0) {
            aitPercent = 0;
        }
        if (isNaN(atPercent) || atPercent == '' || atPercent < 0) {
            atPercent = 0;
        }

        var vat;
        var vatAmount;
        var vatPercent;
        var baseVat = '';
        var baseVatPercent = '';
        if (isNaN($("#vat").val()) || $("#vat").val() == '' || $("#vat").val() <= 0)
            vat = 0;
        else {
            vat = parseFloat($("#vat").val());
            vat = parseFloat(vat.toFixed(4));
        }

        var vatType = $("#vatType").val();
        if (isNaN(vatType) || vatType == '' || vatType < 0)
            vatType = 0;

        if (vatType == 3) { // 3=Fixed Vat
            baseVat = $("#vat").val();
            vatPercent = (((vat * 100) / _totalAmount) * orderQty).toFixed(4);
            vatAmount = (vat * orderQty).toFixed(4);
        } else {
            vatPercent = 0;
            vatAmount = 0;
            baseVatPercent = $("#vat").val();
            vatPercent = vat;
            vatAmount = ((_totalAmount * vat) / 100).toFixed(4);
        }

        _cd = (_totalAmount * cdPercent) / 100;
        cd = parseFloat(_cd.toFixed(4));

        _rd = (_totalAmount * rdPercent) / 100;
        rd = parseFloat(_rd.toFixed(4));

        _sd = ((_totalAmount + _cd + _rd) * sdPercent) / 100;
        sd = parseFloat(_sd.toFixed(4));

        _vatAmount = ((_totalAmount + _cd + _rd + _sd) * vatPercent) / 100;
        vatAmount = _vatAmount.toFixed(4);

        _ait = (_totalAmount * aitPercent) / 100;
        ait = parseFloat(_ait.toFixed(4));

        /*_at = ((_totalAmount + _cd + _rd + _sd) * atPercent) / 100;
        at = _at.toFixed(4); */

        _at = ((_totalAmount + _cd + _rd + _sd) * atPercent) / 100;
        at = parseFloat(_at.toFixed(4));

        taxAmount = (_cd + _rd + _sd + _vatAmount + _ait + _at).toFixed(4);
        var totalAmountCdRd = (_totalAmount + _cd + _rd).toFixed(4);

        var appendTxt = "<tr class='cartList'>" +
            "<td class='sno'>" +
            slNumber +
            "</td>" +
            "<td style='text-align: left;min-width:100px;'>" + $("#model_id_text").val() + " (" + $("#hscode").val() + ") </td>" +
            "<td width='53px'>" + "<input onkeyup='calculateGridLineExtCharge($(this))' style='width: 90%; text-align: center;' class='lineQty' type='text' name='PurchaseItems[temp_order_qty][]' value='" + orderQty + "'>" + "</td>" +
            "<td>" + unitName + " </td>" +
            "<td>" + "<input onkeyup='calculateGridLineExtCharge($(this))' style='width: 90%; text-align: right;' class='pprice' type='text' name='PurchaseItems[temp_purchase_price][]' value='" + unitPrice + "'>" + "</td>" +
            "<td>" + "<input onkeyup='calculateGridLineExtCharge($(this))' style='width: 90%; text-align: right;' class='cvrate' type='text' name='PurchaseItems[temp_cv_rate][]' value='" + cvRate + "'>" + "</td>" +
            "<td>" +
            "<input type='hidden' class='totalAmountQ' value='" + totalAmount + "'>" +
            "<input onkeyup='calculateGridLineExtCharge($(this))' style='width: 90%; text-align: right;' class='totalAmount' type='text' name='PurchaseItems[temp_total_amount][]' value='" + totalAmount + "'>" +
            "</td>" +

            "<td width='53px'>" + "<input onkeyup='calculateGridLineExtCharge($(this))' style='width: 90%; text-align: right;' class='cd_percent' type='text' name='PurchaseItems[temp_cd_percent][]' value='" + cdPercent + "'>" + "</td>" +
            "<td>" +
            "<input style='width: 90%; text-align: right;' class='gridVal cd' type='text' name='PurchaseItems[temp_cd][]' value='" + cd + "' readonly>" +
            "<div class='gridValSpan cdSpan'></div>" +
            "</td>" +
            "<td width='53px'>" + "<input onkeyup='calculateGridLineExtCharge($(this))' style='width: 90%; text-align: right;' class='rd_percent' type='text' name='PurchaseItems[temp_rd_percent][]' value='" + rdPercent + "'>" + "</td>" +
            "<td>" +
            "<input style='width: 90%; text-align: right;' class='gridVal rd' type='text' name='PurchaseItems[temp_rd][]' value='" + rd + "' readonly>" +
            "<div class='gridValSpan rdSpan'></div>" +
            "</td>" +
            "<td width='53px'>" + "<input onkeyup='calculateGridLineExtCharge($(this))' style='width: 90%; text-align: right;' class='sd_percent' type='text' name='PurchaseItems[temp_sd_percent][]' value='" + sdPercent + "'>" + "</td>" +
            "<td>" +
            "<input style='width: 90%; text-align: right;' class='gridVal sd' type='text' name='PurchaseItems[temp_sd][]' value='" + sd + "' readonly>" +
            "<div class='gridValSpan sdSpan'></div>" +
            "</td>" +
            "<td width='53px'>" + "<input onkeyup='calculateGridLineExtCharge($(this))' style='width: 90%; text-align: right;' class='vatPercent' type='text' name='PurchaseItems[temp_vat_percent][]' value='" + vatPercent + "'>" + "</td>" +
            "<td>" +
            "<input style='width: 90%; text-align: right;' class='gridVal vatAmount' type='text' name='PurchaseItems[temp_vat_amount][]' value='" + vatAmount + "' readonly>" +
            "<div class='gridValSpan vatAmountSpan'></div>" +
            "</td>" +

            "<td width='53px'>" + "<input onkeyup='calculateGridLineExtCharge($(this))' style='width: 90%; text-align: right;' class='ait_percent' type='text' name='PurchaseItems[temp_ait_percent][]' value='" + aitPercent + "'>" + "</td>" +
            "<td>" +
            "<input style='width: 90%; text-align: right;' class='gridVal ait' type='text' name='PurchaseItems[temp_ait][]' value='" + ait + "' readonly>" +
            "<div class='gridValSpan aitSpan'></div>" +
            "</td>" +
            "<td width='53px'>" + "<input onkeyup='calculateGridLineExtCharge($(this))' style='width: 90%; text-align: right;' class='at_percent' type='text' name='PurchaseItems[temp_at_percent][]' value='" + atPercent + "'>" + "</td>" +
            "<td>" +
            "<input style='width: 90%; text-align: right;' class='gridVal at' type='text' name='PurchaseItems[temp_at][]' value='" + at + "' readonly>" +
            "<div class='gridValSpan atSpan'></div>" +
            "</td>" +
            "<td>" +
            "<input style='width: 90%; text-align: right;' class='gridVal taxAmount' type='text' name='PurchaseItems[temp_tax_amount][]' value='" + taxAmount + "' readonly>" +
            "<div class='gridValSpan taxAmountSpan'></div>" +
            "</td>" +
            <?php if($yourCompanyInfo->company_vat_type == YourCompany::STANDARD){?>
            "<td>" + "<input type='checkbox' onchange='giveRebateLine($(this))' class='checkRebate'><input style='width: 58%; text-align: right;' onkeyup='calcRebateTotal()' class='rebateTotal' type='text' name='PurchaseItems[temp_rebate_amount][]' value=''>" + "</td>" +
            <?php } ?>
            "<td>" +
            "<input type='hidden' class='model_id' name='PurchaseItems[temp_model_id][]' value='" + modelId + "'>" +
            "<input type='hidden' name='PurchaseItems[temp_unit][]' value='" + unitId + "'>" +
            "<input type='hidden' name='PurchaseItems[temp_base_vat][]' value='" + baseVat + "'>" +
            "<input type='hidden' name='PurchaseItems[temp_base_vat_percent][]' value='" + baseVatPercent + "'>" +
            "<input type='hidden' name='PurchaseItems[temp_vat_calculation_method][]' value='" + vatType + "'>" +
            "<input onclick='deleteRows($(this))' title='remove' style='width: 90%; text-align: center;' type='button' class='rdelete dltBtn'/>" +
            "</td>" +
            "</tr>";
        $("#tbl tr:last").after(appendTxt);
        calculateGridTotal();
        $("#model_id_text").focus();
    }
</script>
<script>
    function calculateGridLineExtCharge(element) {
        var lineQty = parseFloat(element.parents('tr').find('.lineQty').val());
        if (isNaN(lineQty))
            lineQty = 0;
        var pprice = parseFloat(element.parents('tr').find('.pprice').val());
        if (isNaN(pprice))
            pprice = 0;
        var cvrate = parseFloat(element.parents('tr').find('.cvrate').val());
        if (isNaN(cvrate))
            cvrate = 0;
        var totalAmountQ = parseFloat(element.parents('tr').find('.totalAmountQ').val());
        if (isNaN(totalAmountQ))
            totalAmountQ = 0;
        var totalAmount = parseFloat(element.parents('tr').find('.totalAmount').val());
        if (isNaN(totalAmount))
            totalAmount = 0;
        var cd_percent = parseFloat(element.parents('tr').find('.cd_percent').val());
        if (isNaN(cd_percent))
            cd_percent = 0;
        var cd = parseFloat(element.parents('tr').find('.cd').val());
        if (isNaN(cd))
            cd = 0;
        var rd_percent = parseFloat(element.parents('tr').find('.rd_percent').val());
        if (isNaN(rd_percent))
            rd_percent = 0;
        var rd = parseFloat(element.parents('tr').find('.rd').val());
        if (isNaN(rd))
            rd = 0;
        var sd_percent = parseFloat(element.parents('tr').find('.sd_percent').val());
        if (isNaN(sd_percent))
            sd_percent = 0;
        var sd = parseFloat(element.parents('tr').find('.sd').val());
        if (isNaN(sd))
            sd = 0;
        var vatPercent = parseFloat(element.parents('tr').find('.vatPercent').val());
        if (isNaN(vatPercent))
            vatPercent = 0;
        var vatAmount = parseFloat(element.parents('tr').find('.vatAmount').val());
        if (isNaN(vatAmount))
            vatAmount = 0;
        var ait_percent = parseFloat(element.parents('tr').find('.ait_percent').val());
        if (isNaN(ait_percent))
            ait_percent = 0;
        var ait = parseFloat(element.parents('tr').find('.ait').val());
        if (isNaN(ait))
            ait = 0;
        var at_percent = parseFloat(element.parents('tr').find('.at_percent').val());
        if (isNaN(at_percent))
            at_percent = 0;
        var at = parseFloat(element.parents('tr').find('.at').val());
        if (isNaN(at))
            at = 0;
        var taxAmount = 0;

        var acClass = element.attr('class');
        if (acClass == 'totalAmount') {
            pprice = ((totalAmount / lineQty) / cvrate);
            element.parents('tr').find('.pprice').val(parseFloat(pprice.toFixed(4)));
            element.parents('tr').find('.totalAmountQ').val(parseFloat(totalAmount.toFixed(4)));
        } else if (acClass == 'pprice') {
            var rowTotal = 0;
            rowTotal = lineQty * pprice;
            totalAmount = ((rowTotal * cvrate) / 100) + rowTotal;
            element.parents('tr').find('.totalAmount').val(parseFloat(totalAmount.toFixed(4)));
            element.parents('tr').find('.totalAmountQ').val(parseFloat(totalAmount.toFixed(4)));
        } else if (acClass != "cd_percent" && acClass != "rd_percent" && acClass != "sd_percent" && acClass != "vatPercent" && acClass != "ait_percent" && acClass != "at_percent") {
            var rowTotal = 0;
            rowTotal = lineQty * pprice;
            totalAmount = ((rowTotal * cvrate) / 100) + rowTotal;
            element.parents('tr').find('.totalAmount').val(parseFloat(totalAmount.toFixed(4)));
            element.parents('tr').find('.totalAmountQ').val(parseFloat(totalAmount.toFixed(4)));
        }

        cd = (totalAmount * cd_percent) / 100;
        element.parents('tr').find('.cd').val(parseFloat(cd.toFixed(4)));
        rd = (totalAmount * rd_percent) / 100;
        element.parents('tr').find('.rd').val(parseFloat(rd.toFixed(4)));
        element.parents('tr').find('.totalAmountCdRd').val(parseFloat((totalAmount + cd + rd).toFixed(4)));
        sd = ((totalAmount + cd + rd) * sd_percent) / 100;
        element.parents('tr').find('.sd').val(parseFloat(sd.toFixed(4)));
        vatAmount = ((totalAmount + cd + rd + sd) * vatPercent) / 100;
        element.parents('tr').find('.vatAmount').val(parseFloat(vatAmount.toFixed(4)));
        ait = (totalAmount * ait_percent) / 100;
        element.parents('tr').find('.ait').val(parseFloat(ait.toFixed(4)));
        at = ((totalAmount + cd + rd + sd) * at_percent) / 100;
        element.parents('tr').find('.at').val(parseFloat(at.toFixed(4)));
        taxAmount = cd + rd + sd + vatAmount + ait + at;
        element.parents('tr').find('.taxAmount').val(parseFloat(taxAmount.toFixed(4)));

        /*// Rebate Doner
        if(element.parents('tr').find('.checkRebate').prop('checked')){
            element.parents('tr').find('.rebateTotal').val(parseFloat(vatAmount.toFixed(4)));
        }*/
        calculateGridTotal();
        calcRebateTotal();
    }

    function calculateGridTotal() {
        var sumtax = 0;
        $(".vatAmount").each(function () {
            var valueTax = $(this).val();
            if (!isNaN(valueTax) && valueTax.length != 0) {
                sumtax += parseFloat(valueTax);
            }
            // Rebate Doner - Alter
            var rebateTotal = parseFloat($(this).parents('tr').find('.rebateTotal').val());
            if (rebateTotal > 0)
                $(this).parents('tr').find('.rebateTotal').val(valueTax);
        });
        $("#PurchaseItems_totalTax").val(sumtax.toFixed(4));

        var sumTotal = 0;
        $(".totalAmount").each(function () {
            var valueTotal = $(this).val();
            if (!isNaN(valueTotal) && valueTotal.length != 0) {
                sumTotal += parseFloat(valueTotal);
            }
        });
        var sumCd = 0;
        $(".cd").each(function () {
            var valueCd = $(this).val();
            if (!isNaN(valueCd) && valueCd.length != 0) {
                sumCd += parseFloat(valueCd);
            }
        });
        var sumRd = 0;
        $(".rd").each(function () {
            var valueRd = $(this).val();
            if (!isNaN(valueRd) && valueRd.length != 0) {
                sumRd += parseFloat(valueRd);
            }
        });
        $("#PurchaseItems_totalAmouns").val((sumTotal + sumCd + sumRd).toFixed(4));

        var sumsd = 0;
        $(".sd").each(function () {
            var valueSd = $(this).val();
            if (!isNaN(valueSd) && valueSd.length != 0) {
                sumsd += parseFloat(valueSd);
            }
        });
        $("#PurchaseItems_totalSd").val(sumsd.toFixed(4));

        var totalWithTax = sumtax + sumsd + sumTotal;
        $("#PurchaseItems_grand_total").val(totalWithTax.toFixed(4));

        $("#PurchaseItems_totalRebate").val('');
        $("#rebate").prop("checked", false);

        // set input field real data to span/html
        copyDataToHtmlInline();
    }

    function clearBin() {
        $("#bin").val('');
    }

    function giveRebateLine(element) {
        if (element.parents('tr').find('.checkRebate').prop('checked')) {
            var vatAmount = element.parents('tr').find('.vatAmount').val();
            element.parents('tr').find('.rebateTotal').val(vatAmount);
        } else {
            element.parents('tr').find('.rebateTotal').val(0);
        }
        calcRebateTotal();
    }

    function calcRebateTotal() {
        var sumTotal = 0;
        $(".rebateTotal").each(function () {
            var valueTotal = $(this).val();
            if (!isNaN(valueTotal) && valueTotal.length != 0) {
                sumTotal += parseFloat(valueTotal);
            }
        });
        $("#PurchaseItems_totalRebate").val(sumTotal.toFixed(4));
    }

    // copy grid inline field data to span/html
    function copyDataToHtmlInline() {
        $(".lineQty").each(function () {
            $(this).parents('tr').find('.cdSpan').html($(this).parents('tr').find('.cd').val());
            $(this).parents('tr').find('.rdSpan').html($(this).parents('tr').find('.rd').val());
            $(this).parents('tr').find('.sdSpan').html($(this).parents('tr').find('.sd').val());
            $(this).parents('tr').find('.vatAmountSpan').html($(this).parents('tr').find('.vatAmount').val());
            $(this).parents('tr').find('.aitSpan').html($(this).parents('tr').find('.ait').val());
            $(this).parents('tr').find('.atSpan').html($(this).parents('tr').find('.at').val());
            $(this).parents('tr').find('.taxAmountSpan').html($(this).parents('tr').find('.taxAmount').val());
        });
    }

    // set input field real data to span/html
    copyDataToHtmlInline();
</script>
<script>
    $('form').attr('autocomplete', 'off');
</script>
<style>
    .gridVal {
        display: none;
    }

    .gridValSpan {
        text-align: right;
        width: 100%
    }

    @media (min-width: 1367px) {
        .gridValSpan {
            padding-right: 2px;
            min-width: 70px;
        }

        .cartList input[type=text] {
            min-width: 55px
        }
    }
</style>
<style>
    .form-box input[type=password], .form-box input[type=text], .form-box select {
        width: 225px !important;
    }

    .form-box select {
        width: 225px !important;
    }

    .receivedByDiv {
        width: 225px !important;
    }

    .right {
        text-align: right;
    }

    .cartList td {
        padding: 0 !important;
    }

    .cartList input[type=text] {
        border: 1px solid #eae8e8;
        padding: 3px 1px;
    }

    .final-grid-tr input[type=text] {
        border: 1px solid #fff;
        text-align: center;
        font-weight: bold;
    }
</style>
<script>
    $(document).ready(function () {
        $(".ui-dialog-titlebar-close").click(function () {
            window.location.replace("<?php echo Yii::app()->baseUrl; ?>/purchaseItems/adminPartsForeign");
        });
    });
</script>

<script type="text/javascript">
    function stopRKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type == "text")) {
            return false;
        }
    }

    document.onkeypress = stopRKey;
</script>