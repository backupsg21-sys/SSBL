<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'sell-items-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
        ));
?>

<?php echo CHtml::link('Back To Sale Orders', array('/sellItems/admin'), array('class' => 'additionalBtn')); ?>
<div class="formDiv">

    <fieldset  style="float: left; width: 70%;" class="someStyle">
        <legend>Sale Order Update Form</legend>
        <table style="width: 100%">
            <tr>
                <td style="width:500px;">
                    <fieldset  style="float: left; width: 600px; height: 150px;" class="someStyle">
                        <legend>Order No</legend>
                        <table>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'so_no'); ?></td>
                                <td>
                                    <div id="orderNo" class="receivedByDiv">
                                        <?php
                                        $thisSellNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SELL_ORDER);
                                        echo $thisSellNoFormat . $model->so_no;
                                        ?>
                                        <?php echo $form->hiddenField($model, 'so_no', array('maxlength' => 255)); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'customer_id'); ?></td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                            $model, 'customer_id', CHtml::listData(Customers::model()->findAll(array('order' => 'id DESC')), 'id', 'company_name'), array(
                                        'prompt' => 'Select',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'dataType' => 'json',
                                            'url' => CController::createUrl('customers/availableBalance'),
                                            'success' => 'function(data) {
                                                //$("#SellItems_available_balance").val(data.ab);
                                         }',
                                            'data' => array(
                                                'customerId' => 'js:jQuery("#SellItems_customer_id").val()',
                                            ),
                                            'beforeSend' => 'function(){
                                                    $("#ajaxLoaderAB").show();
                                             }',
                                            'complete' => 'function(){
                                                    $("#ajaxLoaderAB").hide();
                                             }',
                                        ),
                                    ));
                                    ?>

                                </td>
                            </tr>

                            <?php if ($isMr == 0) { ?>
                                <tr>
                                    <td><?php echo $form->labelEx($model, 'cash_due'); ?></td>
                                    <td>
                                        <?php echo $form->dropDownList($model, 'cash_due', Lookup::items('cash_due'), array('prompt' => 'select', 'disabled' => 'disabled')); ?>
                                        <?php echo $form->error($model, 'cash_due'); ?>
                                    </td>   
                                </tr>
                            <?php } ?>
                        </table>
                    </fieldset> 
                </td>
                <td>
                    <fieldset style="float: left; width: 673px; height: 150px;" class="someStyle">
                        <legend>Date</legend>
                        <table>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'date_of_sell'); ?></td>
                                <td>
                                    <?php
                                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                                    $dateTimePickerConfig1 = array(
                                        'model' => $model, //Model object
                                        'attribute' => 'date_of_sell', //attribute name
                                        'mode' => 'date', //use "time","date" or "datetime" (default)
                                        'language' => 'en-AU',
                                        'options' => array(
                                            'changeMonth' => 'true',
                                            'changeYear' => 'true',
                                            'dateFormat' => 'yy-mm-dd',
                                            'width' => '100',
                                        ) // jquery plugin options
                                    );
                                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                                    ?>
                                </td>            
                            </tr>
                            <tr>
                                <td></td>
                                <td><?php echo $form->error($model, 'date_of_sell'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'expected_delivery_date'); ?></td>
                                <td>
                                    <?php
                                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                                    $dateTimePickerConfig2 = array(
                                        'model' => $model, //Model object
                                        'attribute' => 'expected_delivery_date', //attribute name
                                        'mode' => 'date', //use "time","date" or "datetime" (default)
                                        'language' => 'en-AU',
                                        'options' => array(
//                        'ampm' => true,
                                            'changeMonth' => 'true',
                                            'changeYear' => 'true',
                                            'dateFormat' => 'yy-mm-dd',
                                            'width' => '100',
                                        ) // jquery plugin options
                                    );
                                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig2);
                                    ?>
                                </td>
                                <?php echo $form->error($model, 'expected_delivery_date'); ?>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'initiated_by'); ?></td>
                                <td>
                                    <div class="receivedByDiv"><?php echo CHtml::encode(Users::model()->fullNameOfThis(Yii::app()->user->getId())); ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'authorized_by'); ?></td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                            $model, 'authorized_by', CHtml::listData(Employees::model()->findAll(), 'id', 'full_name'), array(
                                        'prompt' => 'Select',
                                    ));
                                    ?>
                                    <?php echo $form->error($model, 'authorized_by'); ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
            <?php if ($isMr == 0) { ?>
                <tr>
                    <td>
                        <fieldset style="float: left; width: 600px;" class="someStyle">
                            <legend>Product Info</legend>
                            <table>
                                <tr>
                                    <td><?php echo $form->labelEx($model, 'item_id'); ?></td>
                                    <td>
                                        <?php
                                        echo $form->dropDownList(
                                                $model, 'item_id', CHtml::listData(ProdItems::model()->findAll(array('order' => 'id DESC')), 'id', 'item_name'), array(
                                            'prompt' => 'Select',
                                            //'disabled' => 'disabled',
                                            'ajax' => array(
                                                'type' => 'POST',
                                                'dataType' => 'json',
                                                'url' => CController::createUrl('prodModels/subCatOfThisCat'),
                                                'success' => 'function(data) {
                                                $("#SellItems_brand_id").html(data.subCatList);
                                         }',
                                                'data' => array(
                                                    'catId' => 'js:jQuery("#SellItems_item_id").val()',
                                                ),
                                                'beforeSend' => 'function(){
                                                    document.getElementById("SellItems_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                                                'complete' => 'function(){
                                            document.getElementById("SellItems_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px";
                                        }',
                                            ),
                                        ));
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><?php echo $form->error($model, 'item_id'); ?></td>
                                </tr>

                                <tr>
                                    <td><?php echo $form->labelEx($model, 'brand_id'); ?></td>
                                    <td>
                                        <?php
                                        echo $form->dropDownList(
                                                $model, 'brand_id', CHtml::listData(ProdBrands::model()->findAll(array('order' => 'id DESC')), 'id', 'brand_name'), array(
                                            'prompt' => 'Select',
                                            //'disabled' => 'disabled',
                                            'ajax' => array(
                                                'type' => 'POST',
                                                'dataType' => 'json',
                                                'url' => CController::createUrl('prodModels/catProdOfThisSubCat'),
                                                'success' => 'function(data) {
                                                $("#SellItems_model_id").html(data.prodList);
                                                $("#jsPart").html(data.jsPart);
                                         }',
                                                'data' => array(
                                                    'subCatId' => 'js:jQuery("#SellItems_brand_id").val()',
                                                    'idPrefix' => 'SellItems',
                                                ),
                                                'beforeSend' => 'function(){
                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                                                'complete' => 'function(){
                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px";
                                        }',
                                            ),
                                        ));
                                        ?>
                                    </td>  
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><?php echo $form->error($model, 'brand_id'); ?></td>
                                </tr>

                                <tr>
                                    <td><?php echo $form->labelEx($model, 'model_id'); ?></td>
                                    <td>
                                        <input type="hidden" name="SellItems[model_id]" value="<?php echo $model->model_id ?>"/>
                                        <?php
                                        echo $form->dropDownList(
                                                $model, 'model_id', CHtml::listData(ProdModels::model()->findAll(array('order' => 'id DESC')), 'id', 'model_name'), array(
                                            'prompt' => 'Select',
                                            //'disabled' => 'disabled',
                                            'ajax' => array(
                                                'type' => 'POST',
                                                'dataType' => 'json',
                                                'url' => CController::createUrl('prodModels/catSubCatOfThisProd'),
                                                'success' => 'function(data) {
                                                    $("#jsPart").html(data.jsPart);
                                             }',
                                                'data' => array(
                                                    'prodId' => 'js:jQuery("#SellItems_model_id").val()',
                                                    'idPrefix' => 'SellItems',
                                                ),
                                                'beforeSend' => 'function(){
                                                        document.getElementById("SellItems_code").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                             }',
                                                'complete' => 'function(){
                                                        document.getElementById("SellItems_code").style.background="#FFFFFF"; 
                                            }',
                                            ),
                                        ));
                                        ?>
                                    </td>  
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><?php echo $form->error($model, 'model_id'); ?></td>
                                </tr>

                                <tr>
                                    <td><?php echo $form->labelEx($model, 'code'); ?></td>
                                    <td>
                                        <?php echo $form->textField($model, 'code', array('maxlength' => 255, 'readonly' => false)); ?>
                                        <?php echo $form->error($model, 'code'); ?>
                                    </td> 
                                </tr>                        
                                <tr>
                                    <td><?php echo $form->labelEx($model, 'store_id'); ?></td>
                                    <td>
                                        <?php
                                        $storeId = 0;
                                        $modelSellDelivery = SellDelivery::model()->findByAttributes(array('sell_items_id'=>$model->id));
                                        if($modelSellDelivery){
                                            $storeId = $modelSellDelivery->store_id;
                                        }
                                        echo $form->dropDownList($model, 'store_id', CHtml::listData(Stores::model()->findAll(), 'id', 'store_name'), array('options' => array($storeId => array('selected' => 'selected'))));
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <?php
                                        echo CHtml::submitButton('Find', array(
                                            'class' => 'findBtn',
                                            'ajax' => array(
                                                'type' => 'POST',
                                                'dataType' => 'json',
                                                'url' => CController::createUrl('prodModels/catSubCatProdOfThisCode'),
                                                'success' => 'function(data) {
                                                $("#jsPart").html(data.jsPart);
                                         }',
                                                'data' => array(
                                                    'prodCode' => 'js:jQuery("#SellItems_code").val()',
                                                    'idPrefix' => 'SellItems',
                                                    'store_id' => 'js:jQuery("#SellItems_store_id").val()',
                                                ),
                                                'beforeSend' => 'function(){
                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                         }',
                                                'complete' => 'function(){
                                                    document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                        }',
                                            ),
                                                )
                                        );
                                        ?>
                                        <?php
                                        echo CHtml::submitButton('Reset', array(
                                            'class' => 'findBtn',
                                            'ajax' => array(
                                                'type' => 'POST',
                                                'dataType' => 'json',
                                                'url' => CController::createUrl('prodModels/reset'),
                                                'success' => 'function(data) {
                                                $("#SellItems_item_id").html(data.itemValue);
                                                $("#SellItems_brand_id").html(data.brandValue);
                                                $("#SellItems_model_id").html(data.modelValue);
                                                $("#SellItems_code").val("");
                                                $("#SellItems_pmodel").val("").selected=true;
                                                $("#SellItems_pbrand").val("").selected=true;
                                                $("#SellItems_color_id").val("").selected=true;
                                                $("#SellItems_size_id").val("").selected=true;
                                                $("#SellItems_sell_price").val("");
                                                $("#SellItems_discount").val("");
                                            }',
                                                'data' => array(
                                                    'sendingVal' => 'js:jQuery("#SellItems_code").val()',
                                                ),
                                                'beforeSend' => 'function(){
                                                document.getElementById("SellItems_item_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                                document.getElementById("SellItems_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                                document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                                document.getElementById("SellItems_code").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                             }',
                                                'complete' => 'function(){
                                                document.getElementById("SellItems_item_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                document.getElementById("SellItems_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                document.getElementById("SellItems_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                document.getElementById("SellItems_code").style.background="#FFFFFF"; 
                                             }',
                                            ),
                                                )
                                        );
                                        ?>
                                    </td>                     
                                </tr>   
                            </table>
                        </fieldset>
                    </td>
                    <td>
                        <fieldset style="float: left; width: 673px; height: 152px;" class="someStyle">
                            <legend>Quantity & Sale Price</legend>
                            <table>
                                <tr>
                                    <td><?php echo $form->labelEx($model, 'sell_price'); ?></td>
                                    <td><?php echo $form->textField($model, 'sell_price', array('readonly' => false)); ?></td> 
                                    <td>
                                        <div id="ajaxLoaderSellPrice" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><?php echo $form->error($model, 'sell_price'); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $form->labelEx($model, 'closingstock'); ?></td>
                                    <td>
                                        <?php
                                        $closinfStock = Inventory::model()->inventoryInfoOfThisModel($storeId, $model->model_id);
                                        echo $form->textField($model, 'closingstock', array('readonly' => true, 'value' => $closinfStock));
                                        ?>
                                        <?php echo $form->error($model, 'closingstock'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo $form->labelEx($model, 'sell_qty'); ?></td>
                                    <td>
                                        <?php echo $form->textField($model, 'sell_qty'); ?>
                                        <?php echo $form->error($model, 'sell_qty'); ?>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td colspan="2" style="text-align: center; color:red"><b>Money Receipt Already Exist Of This Sales Order</b></td>
                </tr>
            <?php } ?>   
        </table>
    </fieldset>
    <fieldset class="tblFooters" style="width:70%;">
        <?php echo CHtml::submitButton('Update', array('id' => 'btnUpdate')); ?>
    </fieldset>
    <div id="jsPart"></div>
</div>
<?php
$sellPriceEditableValidationInfo = CustomerAbValidation::model()->findByPk(2);
if ($sellPriceEditableValidationInfo->is_active == CustomerAbValidation::INACTIVE) {
    ?>
    <script>
        $(document).ready(function () {
            $("#SellItems_sell_price").css("background-color", "#D7D7D7");
            $("#SellItems_sell_price").focus(function () {
                $(this).blur();
            });
        });
    </script>
    <?php
}
?>
<script>
    $(document).ready(function () {
        //$("#SellItems_available_balance").css("background-color", "#D7D7D7");
        $("#SellItems_idealQty").css("background-color", "#D7D7D7");
        $("#SellItems_warnQty").css("background-color", "#D7D7D7");
        /*
         $("#SellItems_available_balance").focus(function(){
         $(this).blur();         
         }); 
         */
        $("#SellItems_idealQty").focus(function () {
            $(this).blur();
        });
        $("#SellItems_warnQty").focus(function () {
            $(this).blur();
        });
    });
</script>

<?php
$pcode = ProdModels::model()->findAll();
?>
<script>
    $(document).ready(function () {
        var codeSuggest = [
<?php foreach ($pcode as $pcd): ?>
    <?php echo '"' . $pcd->code . '"'; ?>,
<?php endforeach; ?>
        ];

        $("#SellItems_code").autocomplete({
            minLength: 0,
            source: codeSuggest
        });
    });


</script>
<?php $this->endWidget(); ?>

<script>
    $("#btnUpdate").click(function () {
        var closingStock = parseInt($('#SellItems_closingstock').val());
		if(isNaN(closingStock))
			closingStock = 0;
        var sellQty = parseInt($('#SellItems_sell_qty').val());
		if(isNaN(sellQty))
			sellQty = 0;
        if(sellQty >closingStock){
            alertify.alert('Warnning! Sell Qty already Exceed. ');
            return false;
        }
    });
</script>
