<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'sell-items-form',
    'action' => $this->createUrl('sellItems/save Sales/'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
$yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
$reg_nature = $yourCompanyInfo->registration_nature;
?>

<div class="formDiv">
    <fieldset>
        <div class="panel-heading">Sales Order Form</div>
        <fieldset style="float: left; width:98%;" class="someStyle">
            <legend>Sales Information</legend>
            <table width="1200px">
                <tr>
                    <td width="380px">
                        <table class="form-box">
                            <tr>
                                <td width="170px"><?php echo $form->labelEx($model, 'so_no'); ?></td>
                                <td>
                                    <div id="orderNo" class="receivedByDiv">
                                        <?php
                                        /* $thisQuickSellNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SELL_ORDER);
                                          $thisSalesNo = VoucherNoFormate::model()->maxValOfThis('SellItems', 'so_no', 'maxSONo');
                                          echo $thisQuickSellNoFormat .'-'. $thisSalesNo; */
                                        echo "Serial Will Generate Automatically";
                                        ?>
                                    </div>
                                    <?php echo $form->error($model, 'so_no'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="170px"><?php echo $form->labelEx($model, 'po_no'); ?></td>
                                <td>
                                    <?php echo $form->textField($model,'po_no',array('size'=>60,'maxlength'=>255)); ?>
                                    <?php echo $form->error($model, 'so_no'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'product_type'); ?></td>
                                <td>
                                    <?php
                                    $dataArray = array(2 => 'Finish Goods');
                                    echo $form->dropDownList($model, 'product_type', $dataArray);
                                    ?>
                                    <?php echo $form->error($model, 'product_type'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'sales_type'); ?></td>
                                <td>
                                    <?php
                                    $dataArray = array(1 => 'Local', 2 => 'Export');
                                    echo $form->dropDownList($model, 'sales_type', $dataArray);
                                    ?>
                                    <?php echo $form->error($model, 'sales_type'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'sales_date'); ?></td>
                                <td>
                                    <?php
                                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                                    $dateTimePickerConfig1 = array(
                                        'model' => $model, //Model object
                                        'attribute' => 'sales_date', //attribute name
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
                                    <?php echo $form->error($model, 'sales_date'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'customer_id'); ?></td>
                                <td width="265px">
                                    <input type="text" id="SellItems_customer_id_text" onkeyup="clearBin()"/>
                                    <?php echo $form->hiddenField($model, 'customer_id'); ?>
                                    <script>
                                        $(function () {
                                            $("#SellItems_customer_id_text").autocomplete({
                                                source: function (request, response) {
                                                    var search_customerName = request.term;
                                                    $.post('<?php echo Yii::app()->baseUrl ?>/customers/jquery_customerSearch', {"customerName": search_customerName},
                                                        function (data) {
                                                            response(data);
                                                        }, "json");
                                                },
                                                minLength: 1,
                                                select: function (event, ui) {
                                                    $("#SellItems_customer_id_text").val(ui.item.label);
                                                    $("#SellItems_customer_id").val(ui.item.value);
                                                    $("#bin").val(ui.item.bin);
                                                    $("#SellItems_delivery_address").val(ui.item.company_address);
                                                    return false;
                                                }
                                            });
                                        });
                                    </script>
                                    <?php
                                    echo CHtml::link('', "", // the link for open the dialog
                                        array(
                                            'class' => 'add-additional-btn',
                                            'onclick' => "{addCustomer(); $('#addCustomerDialog').dialog('open');}"));
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
                                                    src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/>
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
                                                                $('#SellItems_customer_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
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
                                <td style="color:#014848;">Customer BIN/NID</td>
                                <td>
                                    <input type="text" name="bin" id="bin" readonly>
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
                                    echo $form->dropDownList($model, 'payment_method', $dtArray, array('prompt' => 'select'));
                                    ?>
                                    <?php echo $form->error($model, 'payment_method'); ?>
                                </td>
                            </tr>
                            <tr id="bank_option">
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
                                <td><?php echo $form->labelEx($model, 'delivery_address'); ?></td>
                                <td><?php echo $form->textArea($model, 'delivery_address'); ?></td>
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
                    <td style="vertical-align:top;">
                        <table class="form-box" id="export_div">
                            <tr>
                                <td width="110px"><?php echo $form->labelEx($model, 'export_type'); ?></td>
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
                    </td>
                </tr>
            </table>
        </fieldset>

        <fieldset class="someStyle" style="float: left; width: 98%;">
            <legend>Product Informations</legend>
            <table style="width: 98%" class="prodAddedTab">
                <tr>
                    <td><label>Item Type</label></td>
                    <td class="parts"><label>Sent From Store/Depot</label></td>
                    <td><label id="setLabel">Goods</label></td>
					<td><label>Unit</label></td>
                    <td><label id="setLabel">Stock</label></td>
                    <td><?php echo $form->labelEx($model, 'sales_qty'); ?></td>
                    <td><label>Action</label></td>
                </tr>
                <tr>
                    <?php
                    if ($reg_nature == 1) { // 1=central, 2=branch
                        $condition = '';
                        $store_id = '';
                    } else {
                        $condition = "id = 1 ";
                        $store_id = 1;
                    }
                    ?>
                    <td>
                        <?php
                        $dataArray = array(1 => 'Goods', 2 => 'Service');
                        echo $form->dropDownList($model, 'parts_service', $dataArray);
                        ?>
                    </td>
                    <td class="parts">
                        <?php
                        $model->sent_from_store_id = 1;
                        echo $form->dropDownList(
                            $model, 'sent_from_store_id', CHtml::listData(Stores::model()->findAll(["condition" => $condition]), 'id', 'store_name'), array(
                            'prompt' => 'Select',
                            'options' => array(
                                $store_id => array(
                                    'selected' => true
                                ))
                        ));
                        ?>
                    </td>
                    <td>
                        <div class="parts">
                            <input type="text" id="model_id_text"/>
                            <?php echo $form->hiddenField($model, 'model_id'); ?>
                            <script>
                                $(function () {
                                    $("#model_id_text").autocomplete({
                                        source: function (request, response) {

                                            var search_prodName = request.term;
                                            $.post('<?php echo Yii::app()->baseUrl ?>/prodModels/jqueryProdSearchFG', {"search_prodName": search_prodName},
                                                function (data) {
                                                    response(data);
                                                }, "json");
                                        },
                                        minLength: 1,
                                        select: function (event, ui) {
                                            $("#model_id_text").val(ui.item.label);
                                            $("#SellItems_model_id").val(ui.item.value);
                                            $("#unitName").val(ui.item.unitName);
                                            $("#unitLabel").html(ui.item.unitName);
                                            $("#unitId").val(ui.item.unitId);
                                            $("#unitPrice").val(ui.item.unitPrice);
                                            $("#vat").val(ui.item.vat);
                                            $("#vatType").val(ui.item.vatType);
                                            $("#stockQty").val(ui.item.closingStock);
                                            $("#sd").val(ui.item.sd);
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
                                    /* 'left' => '30px', */
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
                        /*$('#SellItems_model_id').val(data.value);
                        $('#model_id_text').val(data.label);
                        $('#SellItems_code').val(data.prodCode);
                        $('#SellItems_brand_id').append('<option selected value='+data.brandId+'>'+data.brandName+'</option>');
                        $('#SellItems_item_id').append('<option selected value='+data.itemId+'>'+data.itemName+'</option>');*/
                    }
                                            }",
                                    ))
                                    ?>
                                    return false;
                                }
                            </script>
                            <?php echo $form->error($model, 'model_id'); ?>
                        </div>
                        <div class="services">
                            <input type="text" id="service_id_text" name="service_id_text"/>
                            <input type="hidden" id="service_id" name="service_id"/>
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
                                            $("#service_id").val(ui.item.value);
                                            $("#unitName").val(ui.item.unitName);
                                            $("#unitId").val(ui.item.unitId);
                                            $("#unitPrice").val(ui.item.unitPrice);
                                            $("#vat").val(ui.item.vat);
                                            $("#vatType").val(ui.item.vatType);
                                            $("#stockQty").val(ui.item.closingStock);
                                            $("#sd").val(ui.item.sd);
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
                                    'width' => 850,
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
                        </div>
                    </td>
					<td style="text-align: center;">
                        <span id="unitLabel"></span>
                    </td>
                    <td>
                        <input type="text" id="stockQty" readonly="true"/>
                    </td>
                    <td>
                        <?php echo $form->textField($model, 'sales_qty', array('class' => 'onlyNumber')); ?>
                        <span id="ajaxLoaderAdd" style="display: none"><img
                                    src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/></span>
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
                    <th>Item</th>
                    <th>Goods/Service</th>
                    <th>Stock</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>SD(%)</th>
                    <th>SD</th>
                    <th>VAT(%)</th>
                    <th>VAT</th>
                    <th>Total Tax</th>
                    <th>Net Total</th>
                    <th>Action</th>
                </tr>
            </table>
            <table class="prodAddedTab" style="margin-top: 20px;width: 100%;">
                <tr>
                    <td><label>Total</label></td>
                    <td><label>Total SD</label></td>
                    <td><label>Total VAT</label></td>
                    <td><label>Grand Total</label></td>
                    <td class="vdsField"><label>Total VDS</label></td>
                </tr>
                <tr class="final-grid-tr">
                    <td><input id="SellItems_total" name='SellItems[total]' type='text' value='0.00'></td>
                    <td><input id="SellItems_total_sd" name='SellItems[total_sd]' type='text' value='0.00'></td>
                    <td><input id="SellItems_totalTax" name='SellItems[total_tax]' type='text' value='0.00'></td>
                    <td><input id="SellItems_grand_total" name='SellItems[grand_total]' type='text' value='0.00'></td>
                    <td class="vdsField">
                        <input id="SellItems_totalVDS" name='SellItems[total_vds]' type='text' value='0.00' readonly>
                        <input type="checkbox" id="vd"> Copy VDS
                    </td>
                </tr>
            </table>
        </fieldset>
    </fieldset>
    <div id="jsPart"></div>
    <div id="formResult" class="ajaxTargetDiv"></div>
    <div id="formResultError" class="ajaxTargetDivErr"></div>
    <fieldset class="tblFooters">
        <?php
        echo CHtml::ajaxSubmitButton('Create SO', CHtml::normalizeUrl(array('sellItems/saveSales', 'render' => true)), array(
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
                var SellItems_product_type = $("#SellItems_product_type").val();
                if (SellItems_product_type == "") {
                    alertify.alert("Please select product type!");
                    return false;
                }
                var SellItems_sales_type = $("#SellItems_sales_type").val();
                if (SellItems_sales_type == "") {
                    alertify.alert("Please select sales type!");
                    return false;
                }
                var SellItems_sales_date = $("#SellItems_sales_date").val();
                if (SellItems_sales_date == "") {
                    alertify.alert("Please select sales date!");
                    return false;
                }
                var SellItems_customer_id_text = $("#SellItems_customer_id_text").val();
                var SellItems_customer_id = $("#SellItems_customer_id").val();
                if (SellItems_customer_id_text=="" || SellItems_customer_id == "") {
                    alertify.alert("Please select customer!");
                    return false;
                } 
                
                var SellItems_payment_method = $("#SellItems_payment_method").val();
                /*
                if (SellItems_payment_method=="") {
                    alertify.alert("Please select Cash or Bank!");
                    return false;
                }*/
                /*if (SellItems_payment_method == 134) {
                    var SellItems_bank_id = $("#SellItems_bank_id").val();
                    if (SellItems_bank_id=="") {
                        alertify.alert("Please select Bank Name!");
                        return false;
                    }
                }*/
                if (vals.length<1) {
                    alertify.alert("Please add product in list!");
                    return false;
                }
                $("#ajaxLoader").show();
		$("#yt0").attr("disabled", "disabled");
             }'
        ), array('class' => 'add-more-btn'));
        ?>
        <div id="ajaxLoader" style="display: none; float: left;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/>
        </div>
    </fieldset>
</div>

<script>

 $(document).ready(function () {

        $('.onlyNumber').keyup(function(){
            var val = $(this).val();
            if(isNaN(val)){
                val = val.replace(/[^0-9\.]/g,'');
                if(val.split('.').length>2)
                    val =val.replace(/\.+$/,"");
            }
            $(this).val(val);
        });

    });


    $('.onlyNumber').keyup(function(){
        var val = $(this).val();
        if(isNaN(val)){
            val = val.replace(/[^0-9\.]/g,'');
            if(val.split('.').length>2)
                val =val.replace(/\.+$/,"");
        }
        $(this).val(val);
    });


    $('#SellItems_sales_qty').keyup(function () {

        let stockQty = $("#stockQty").val();
        stockQty = stockQty != '' && !isNaN(stockQty) ? parseFloat(stockQty) : 0;
        let sellQty = $(this).val();
        sellQty = sellQty != '' && !isNaN(sellQty) ? parseFloat(sellQty) : 0;

        if (stockQty < sellQty) {
            alertify.error("Sales Qty must be less then or equal to stock qty!");
            $(this).val('');
        }
    });


    function stockCheck(e){

        var val = e.val();
        if(isNaN(val)){
            val = val.replace(/[^0-9\.]/g,'');
            if(val.split('.').length>2)
                val =val.replace(/\.+$/,"");
        }
        e.val(val);

        let stockQty = e.parents('tr').find('.stockQty').val();
        stockQty = stockQty != '' && !isNaN(stockQty) ? parseFloat(stockQty) : 0;
        let salesQty = e.val();
        salesQty = salesQty != '' && !isNaN(salesQty) ? parseFloat(salesQty) : 0;

        if (stockQty < salesQty) {
            alertify.error("Sales Qty must be less then or equal to stock qty!");
            e.val('');
        }

        calculateGridInLine(e);
    }

    var vals = [];
    var setZero = 0;

    $("#export_div").hide();
    $("#SellItems_sales_type").change(function () {
        var SellItems_sales_type = $("#SellItems_sales_type").val();
        $("#export_div").hide();
        if (SellItems_sales_type == 1) {
            $("#export_div").hide();
            $(".vdsField").show();
            setZero = 0;
        } else if (SellItems_sales_type == 2) {
            $("#export_div").show();
            $(".vdsField").hide();
            setZero = 1;
        }
    });

    $(".services").hide();
    $("#SellItems_parts_service").change(function () {
        var SellItems_parts_service = $("#SellItems_parts_service").val();
        $("#model_id_text").val('');
        $("#SellItems_model_id").val('');
        $("#service_id_text").val('');
        $("#service_id").val('');
        if (SellItems_parts_service == 1) {
            $(".parts").show();
            $(".services").hide();
            $("#setLabel").html('Goods');
        } else if (SellItems_parts_service == 2) {
            $(".parts").hide();
            $(".services").show();
            $("#setLabel").html('Services');
        }
    });

    $("#vd").change(function () {
        if ($(this).prop('checked')) {
            calcVds();
        } else {
            calculateGridTotal();
            $("#SellItems_totalVDS").val('');
        }
    });

    $("#bank_option").hide();
    $("#SellItems_payment_method").change(function () {
        var SellItems_payment_method = $("#SellItems_payment_method").val();
        if (SellItems_payment_method == <?= PurchaseItems::BANK ?>) {
            $("#bank_option").show();
        } else if (SellItems_payment_method == <?= PurchaseItems::CASH ?>) {
            $("#bank_option").hide();
        }
    });

    /**********GRID ADDING FROM HERE*************/

    function AddNew() {

        var SellItems_sales_type = $("#SellItems_sales_type").val();
        if (SellItems_sales_type == "") {
            alertify.alert("Please select sales type!");
            return false;
        }

        var SellItems_parts_service = $("#SellItems_parts_service").val();
        if (SellItems_parts_service == "") {
            alertify.alert("Please select sales type!");
            return false;
        }

        /*$("#SellItems_parts_service").attr("style", "pointer-events: none;");
         $("#SellItems_parts_service").css("background-color","#F5F2F2");*/

        var model_id_text = $("#model_id_text").val();
        var SellItems_model_id = $("#SellItems_model_id").val();
        var service_id_text = $("#service_id_text").val();
        var service_id = $("#service_id").val();
        if (SellItems_parts_service == 1) { // 1= Goods, 2 = Service var SellItems_parts_service = $("#SellItems_parts_service").val();
            if ($("#SellItems_sent_from_store_id").val() == "") {
                alertify.alert("Please select store!");
                return false;
            }
            if (model_id_text == "" || SellItems_model_id < 1) {
                alertify.alert("Please select goods/service!");
                return false;
            }
        } else if (SellItems_parts_service == 2) { // 1= Goods, 2 = Service var SellItems_parts_service = $("#SellItems_parts_service").val();
            if (service_id_text == "" || service_id < 1) {
                alertify.alert("Please select goods/service!");
                return false;
            }
        } else {
            alertify.alert("Goods/service selection error at first input field!");
            return false;
        }

        var SellItems_sales_qty = $("#SellItems_sales_qty").val();
        if (SellItems_sales_qty == "") {
            alertify.alert("Please select sales qty!");
            return false;
        }

        if (SellItems_parts_service == <?= ProdModels::GOODS ?>) { // 1 = Goods, 2 = Service
            var model_id = $("#SellItems_model_id").val();
        } else if (SellItems_parts_service == <?= ProdModels::SERVICE ?>) {
            var model_id = $("#service_id").val();
        } else {
        }
        if (model_id != "")
            checkDuplicate();
        else {
            alertify.alert('Sorry! something went wrong!');
            return false;
        }
    }

    function checkDuplicate() {

        var stock = 0;
        var SellItems_parts_service = $("#SellItems_parts_service").val();
        if (SellItems_parts_service == <?= ProdModels::GOODS ?>) { // 1 = Goods, 2 = Service
            var model_id = 'parts' + $("#SellItems_model_id").val();
        } else if (SellItems_parts_service == <?= ProdModels::SERVICE ?>) {
            var model_id = 'service' + $("#service_id").val();
        } else {
        }

        if (vals.indexOf(model_id) > -1) {
            alertify.alert('Sorry! this item is already added!');
            return false;
        } else {

            if (SellItems_parts_service == <?= ProdModels::GOODS ?>) {
                $("#ajaxLoaderAdd").show();
                var search_prod = $("#SellItems_model_id").val();
                var search_store = $("#SellItems_sent_from_store_id").val();
                $.ajax({
                    url: '<?php echo Yii::app()->baseUrl ?>/inventory/jqueryProdStockSearch',
                    cache: true,
                    async: false,
                    type: "POST",
                    /*dataType:'json',*/
                    data: {
                        search_prod: search_prod,
                        search_store: search_store,
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        stock = data.closingStock;
                        $("#ajaxLoaderAdd").hide();
                    }
                });
                $("#ajaxLoaderAdd").hide();
            } else {
                var stock = 0;
            }
            var stockQty = parseInt(stock);
            if (isNaN(stockQty) || stockQty == "" || stockQty <= 0)
                stockQty = 0;
            $("#stockQty").val(stockQty);

            var orderQty = parseFloat($("#SellItems_sales_qty").val());
            if (isNaN(orderQty) || orderQty == "" || orderQty <= 0)
                orderQty = 0;

            var SellItems_parts_service = $("#SellItems_parts_service").val();
            if (SellItems_parts_service == <?= ProdModels::GOODS ?>) { // 1 = Goods, 2 = Service

                // check stock qty
                /*if(orderQty > stockQty){
                 alertify.alert("Sorry! Stock Qty is Excedeed! Available Stock = " + stockQty + "");
                 return false;
                 }*/
            }

            vals.push(model_id);
            addRow();
            $("#SellItems_sales_qty").val('');
            $("#SellItems_sales_type").attr("style", "pointer-events: none;");
            $("#SellItems_sales_type").css("background-color", "#F5F2F2");
        }
    }

    function addRow() {
        $("#tbl").each(function () {
            var slNumber = $('#tbl tr').length;

            var storeId = '';
            var sales_vat = parseFloat($("#company_sales_vat").val());
            if (isNaN(sales_vat) || sales_vat == "" || sales_vat <= 0)
                sales_vat = 0;
            if (sales_vat > 0) {
                $("#vat").val(sales_vat);
                $("#vatType").val(2)
            }

            var stockQty = parseInt($("#stockQty").val());
            if (isNaN(stockQty) || stockQty == "" || stockQty <= 0)
                stockQty = 0;

            var orderQty = parseFloat($("#SellItems_sales_qty").val());
            if (isNaN(orderQty) || orderQty == "" || orderQty <= 0)
                orderQty = 0;

            var SellItems_parts_service = $("#SellItems_parts_service").val();
            if (SellItems_parts_service == <?= ProdModels::GOODS ?>) { // 1 = Goods, 2 = Service
                var modelId = $("#SellItems_model_id").val();
                var modelText = $("#model_id_text").val();
                modelTextR = 'parts' + modelId;
                storeId = $("#SellItems_sent_from_store_id").val();

            } else if (SellItems_parts_service == <?= ProdModels::SERVICE ?>) {
                var modelId = $("#service_id").val();
                var modelText = $("#service_id_text").val();
                modelTextR = 'service' + modelId;
            } else {
            }

            var partsServiceId = $("#SellItems_parts_service").val();
            var unitName = $("#unitName").val();
            var unitId = $("#unitId").val();
            var unitPrice;
            if (isNaN($("#unitPrice").val()) || $("#unitPrice").val() <= 0)
                unitPrice = 0;
            else
                unitPrice = $("#unitPrice").val();
            unitPrice = parseFloat(unitPrice).toFixed(4);

            var _totalAmount = parseFloat(unitPrice * orderQty);
            var totalAmount = _totalAmount.toFixed(4);

            var sd = 0;
            var sdPercent = 0;
            var _sdPercent = 0;
            _sdPercent = $("#sd").val();
            if (isNaN(_sdPercent) || _sdPercent == '' || _sdPercent < 0) {
                _sdPercent = 0;
            }

            sdPercent = parseFloat(_sdPercent).toFixed(4);
            sd = (_totalAmount * _sdPercent) / 100;

            var vat;
            var _vat = 0;
            var vatAmount;
            var _vatAmount;
            var vatPercent;
            var baseVat = '';
            var baseVatPercent = '';
            if (isNaN($("#vat").val()) || $("#vat").val() <= 0 || setZero == 1)
                vat = 0;
            else {
                _vat = $("#vat").val();
                vat = parseFloat(_vat).toFixed(4);
            }

            var vatType = $("#vatType").val();
            if (isNaN(vatType) || vatType == '' || vatType < 0)
                vatType = 0;

            var fixedVat = 0;
            if (vatType == 3) { // 2=VAT Percent, 3=Fixed Vat
                baseVat = _vat;
                vatPercent = 0;
                _vatAmount = parseFloat(_vat * orderQty);
                vatAmount = _vatAmount.toFixed(4);
                fixedVat = _vat;
            } else {
                vatPercent = 0;
                vatAmount = 0;
                _vatAmount = 0;
                baseVatPercent = _vat;
                vatPercent = vat;
                //_vatAmount = (_totalAmount * _vat) / 100;
                _vatAmount = ((_totalAmount + sd) * _vat) / 100;
                vatAmount = parseFloat(_vatAmount).toFixed(4);
            }

            var _taxAmount = 0;
            var netTotal = 0;
            var taxAmount = 0;

            if (setZero == 1) {
                _vatAmount = 0;
            }
            _taxAmount = _vatAmount;
            taxAmount = parseFloat(_taxAmount).toFixed(4);
            netTotal = parseFloat(_totalAmount + _taxAmount + sd).toFixed(4);

            var appendTxt = "<tr class='cartList'>" +
                "<td class='sno'>" +
                slNumber +
                "</td>" +
                "<td style='text-align: left;width:110px;'>" + modelText + "</td>" +
                "<td>" + $("#SellItems_parts_service option:selected").html() + "</td>" +
                "<td>" + "<input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: center;' class='stockQty onlyNumber' type='text' name='SellItems[temp_stock][]' value='" + stockQty + "' readonly>" + "</td>" +
                "<td>" + "<input onkeyup='stockCheck($(this))' style='width: 90%; text-align: center;' class='lineQty onlyNumber' type='text' name='SellItems[temp_sales_qty][]' value='" + orderQty + "'>" + "</td>" +
                "<td>" + unitName + " <input type='hidden' class='fixedVat' value='" + fixedVat + "'></td>" +
                "<td>" + "<input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: right;' class='pprice onlyNumber' type='text' name='SellItems[temp_purchase_price][]' value='" + unitPrice + "'>" + "</td>" +
                "<td>" + "<input style='width: 90%; text-align: right;' class='totalAmount' type='text' name='SellItems[temp_total_amount][]' value='" + totalAmount + "' readonly>" + "</td>" +
                "<td>" + "<input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: right;' class='sd_percent onlyNumber' type='text' name='SellItems[temp_sd_percent][]' value='" + sdPercent + "'>" + "</td>" +
                "<td>" + "<input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: right;' class='sd' type='text' name='SellItems[temp_sd][]' value='" + sd + "' readonly>" + "</td>" +
                "<td>" + "<input onkeyup='calculateGridInLine($(this))' style='width: 90%; text-align: right;' class='vatPercent onlyNumber' type='text' name='SellItems[temp_vat_percent][]' value='" + vatPercent + "'>" + "</td>" +
                "<td>" + "<input style='width: 90%; text-align: right;' class='vatAmount' type='text' name='SellItems[temp_vat_amount][]' value='" + vatAmount + "' readonly>" + "</td>" +
                "<td>" + "<input style='width: 90%; text-align: right;' class='taxAmount' type='text' name='SellItems[temp_tax_amount][]' value='" + taxAmount + "' readonly>" + "</td>" +
                "<td>" + "<input style='width: 90%; text-align: right;' class='netTotal' type='text' name='SellItems[temp_net_total][]' value='" + netTotal + "' readonly>" + "</td>" +
                "<td>" +
                "<input type='hidden' name='SellItems[temp_parts_service][]' value='" + partsServiceId + "'>" +
                "<input type='hidden' name='SellItems[temp_model_id][]' value='" + modelId + "'>" +
                "<input type='hidden' class='model_text' name='SellItems[temp_model_text][]' value='" + modelTextR + "'>" +
                "<input type='hidden' name='SellItems[temp_unit][]' value='" + unitId + "'>" +
                "<input type='hidden' name='SellItems[temp_base_vat][]' value='" + baseVat + "'>" +
                "<input type='hidden' name='SellItems[temp_base_vat_percent][]' value='" + baseVatPercent + "'>" +
                "<input type='hidden' class='vatType' name='SellItems[temp_vat_calculation_method][]' value='" + vatType + "'>" +
                "<input type='hidden' class='store' name='SellItems[temp_store_id][]' value='" + storeId + "'>" +
                "<input onclick='deleteRows($(this))' title=\"remove\" id='" + slNumber + "' style='width: 90%; text-align: center;' type=\"button\" class=\"rdelete\"/>" +
                "</td>" +
                "</tr>";

            if ($('tbody', this).length > 0) {
                $('tbody', this).append(appendTxt);

                $("#SellItems_sent_from_store_id").prop('readonly',true).prop('tabindex',-1).css('pointer-events','none').css('background-color', 'rgb(245, 242, 242)');
                $("#SellItems_parts_service").prop('readonly',true).prop('tabindex',-1).css('pointer-events','none').css('background-color', 'rgb(245, 242, 242)');

            } else {
                $(this).append(appendTxt);
            }
        });
        calculateGridTotal();
        $("#model_id_text").val('');
        $("#SellItems_model_id").val('');
        $("#service_id_text").val('');
        $("#service_id").val('');
        $("#SellItems_sales_qty").val('');
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

        var sd_percent = parseFloat(element.parents('tr').find('.sd_percent').val());
        if (isNaN(sd_percent))
            sd_percent = 0;

        var totalAmount = linePrice * lineQty;
        var sd = (totalAmount * sd_percent) / 100;

        if (vatType == 3) {
            var vatAmount = lineQty * fixedVat;
        } else {
            var vatAmount = (((totalAmount + sd) * vatPercent) / 100);
        }

        if (setZero == 1) {
            vatAmount = 0;
        }

        // taxAmount = vatAmount * lineQty;
        taxAmount = vatAmount;
        var netTotal = totalAmount + taxAmount + sd;
        element.parents('tr').find('.sd').val(sd.toFixed(4));
        element.parents('tr').find('.vatAmount').val(vatAmount.toFixed(4));
        element.parents('tr').find('.taxAmount').val(taxAmount.toFixed(4));
        element.parents('tr').find('.totalAmount').val(totalAmount.toFixed(4));
        element.parents('tr').find('.netTotal').val(netTotal.toFixed(4));
        calculateGridTotal();
        var isVds = parseFloat($("#SellItems_totalVDS").val());
        if (isVds > 0) {
            calcVds();
        }
    }

    function calculateGridRebate(element) {
        var rebatePercent = parseFloat(element.parents('tr').find('.rebatePercent').val());
        if (isNaN(rebatePercent))
            rebatePercent = 0;
        var taxAmount = parseFloat(element.parents('tr').find('.taxAmount').val());
        if (isNaN(taxAmount))
            taxAmount = 0;
        var rebateAmouns = (taxAmount / rebatePercent).toFixed(4);
        element.parents('tr').find('.rebateAmount').val(rebateAmouns);

        var sum = 0;
        $(".rebateAmount").each(function () {
            var value = $(this).val();
            if (!isNaN(value) && value.length != 0) {
                sum += parseFloat(value);
            }
        });
        $("#SellItems_totalRebate").val(sum.toFixed(4));
    }

    function calculateGridTotal() {
        var sumtax = 0;
        $(".vatAmount").each(function () {
            var valueTax = $(this).val();
            if (!isNaN(valueTax) && valueTax.length != 0) {
                sumtax += parseFloat(valueTax);
            }
        });

        var sdTotal = 0;
        $(".sd").each(function () {
            var valueSd = $(this).val();
            if (!isNaN(valueSd) && valueSd.length != 0) {
                sdTotal += parseFloat(valueSd);
            }
        });
        $("#SellItems_total_sd").val(sdTotal.toFixed(4));

        $("#SellItems_totalTax").val(sumtax.toFixed(4));
        var sumTotal = 0;
        $(".totalAmount").each(function () {
            var valueTotal = $(this).val();
            if (!isNaN(valueTotal) && valueTotal.length != 0) {
                sumTotal += parseFloat(valueTotal);
            }
        });
        var totalWithTax = 0;
        totalWithTax = parseFloat(sumTotal + sumtax + sdTotal).toFixed(4);
        $("#SellItems_total").val(sumTotal.toFixed(4));
        $("#SellItems_grand_total").val(totalWithTax);
    }

    function calcVds() {
        var vatPercent;
        var vatAmount;
        var vatSum = 0;
        var vdsSum = 0;
        $(".vatPercent").each(function () {
            vatPercent = $(this).val();
            vatAmount = $(this).parents('tr').find('.vatAmount').val();
            if (vatPercent != 15) {
                if (!isNaN(vatAmount) && vatAmount.length != 0) {
                    vdsSum += parseFloat(vatAmount);
                }
            } else {
                if (!isNaN(vatAmount) && vatAmount.length != 0) {
                    vatSum += parseFloat(vatAmount);
                }
            }
        });
        $("#SellItems_totalVDS").val(vdsSum.toFixed(4));
        //$("#SellItems_totalTax").val(vatSum);
    }

    function clearBin() {
        $("#bin").val('');
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
<input type="hidden" id="unitName"/>
<input type="hidden" id="unitId"/>
<input type="hidden" id="unitPrice"/>
<input type="hidden" id="vat"/>
<input type="hidden" id="vatType"/>
<input type="hidden" id="stockQty"/>
<input type="hidden" id="sd"/>
<input type="hidden" id="company_sales_vat" value="<?= $yourCompanyInfo->company_sales_vat ?>"/>

<style>
    select {
        width: 84%;
    }

    .form-box input[type=password], .form-box input[type=text], .form-box select {
        width: 225px !important;
    }

    .receivedByDiv {
        width: 225px !important;
    }
</style>
<script>
    $('input').attr('autocomplete', 'off');
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


    $(document).ready(function () {
        $('.onlyNumber').live('keypress',function(event){
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    });


</script>