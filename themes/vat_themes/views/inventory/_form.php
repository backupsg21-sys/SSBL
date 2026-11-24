<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'inventory-form',
    'action' => $this->createUrl('inventory/create'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>
<style>
    .formDiv input[type=text], .formDiv select {
        width: 190px !important;
    }
</style>
<div class="formDiv">
    <fieldset>
        <legend>Manual Stock Entry</legend>
        <fieldset style="float: left; width: 665px;" class="someStyle">
            <legend>Product Info</legend>
            <table>
                <tr>
                    <td><?php echo $form->labelEx($model, 'item_id'); ?></td>
                    <td width="230px">
                        <?php
                        echo $form->dropDownList(
                            $model, 'item_id', CHtml::listData(ProdItems::model()->findAll(array('order' => 'item_name ASC')), 'id', 'item_name'), array(
                            'prompt' => 'Select',
                            'ajax' => array(
                                'type' => 'POST',
                                'dataType' => 'json',
                                'url' => CController::createUrl('prodModels/subCatOfThisCat'),
                                'success' => 'function(data) {
                                                $("#Inventory_brand_id").html(data.subCatList);
                                         }',
                                'data' => array(
                                    'catId' => 'js:jQuery("#Inventory_item_id").val()',
                                ),
                                'beforeSend' => 'function(){
                                                    document.getElementById("Inventory_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                                'complete' => 'function(){
                                            document.getElementById("Inventory_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px";   
                                        }',
                            ),
                        ));
                        ?>
                        <?php
                        echo CHtml::link('', "", // the link for open the dialog
                            array(
                                'class' => 'add-additional-btn',
                                'onclick' => "{addProdItem(); $('#dialogAddProdItem').dialog('open');}"));
                        ?>

                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                            'id' => 'dialogAddProdItem',
                            'options' => array(
                                'title' => 'Add Category',
                                'autoOpen' => false,
                                'modal' => true,
                                'width' => 550,
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
                            function addProdItem() {
                                <?php
                                echo CHtml::ajax(array(
                                    'url' => array('prodItems/createProdItemsFromOutSide'),
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
                    $('#dialogAddProdItem div.divForForm').html(data.div);
                          // Here is the trick: on submit-> once again this function!
                    $('#dialogAddProdItem div.divForForm form').submit(addProdItem);
                }
                else
                {
                    $('#dialogAddProdItem div.divForForm').html(data.div);
                    setTimeout(\"$('#dialogAddProdItem').dialog('close') \",1000);
                    $('#Inventory_item_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
                }
            }",
                                ))
                                ?>
                                return false;
                            }
                        </script>
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
                            $model, 'brand_id', CHtml::listData(ProdBrands::model()->findAll(array('order' => 'brand_name ASC')), 'id', 'brand_name'), array(
                            'prompt' => 'Select',
                            'ajax' => array(
                                'type' => 'POST',
                                'dataType' => 'json',
                                'url' => CController::createUrl('prodModels/catProdOfThisSubCat'),
                                'success' => 'function(data) {
                                                $("#Inventory_model_id").html(data.prodList);
                                                $("#jsPart").html(data.jsPart);
                                         }',
                                'data' => array(
                                    'subCatId' => 'js:jQuery("#Inventory_brand_id").val()',
                                    'idPrefix' => 'Inventory',
                                ),
                                'beforeSend' => 'function(){
                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                                'complete' => 'function(){
                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px";
                                        }',
                            ),
                        ));
                        ?>
                        <?php
                        echo CHtml::link('', "", // the link for open the dialog
                            array(
                                'class' => 'add-additional-btn',
                                'onclick' => "{addProdBrand(); $('#dialogAddProdBrand').dialog('open');}"));
                        ?>

                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                            'id' => 'dialogAddProdBrand',
                            'options' => array(
                                'title' => 'Add Sub-Category',
                                'autoOpen' => false,
                                'modal' => true,
                                'width' => 'auto',
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
                            function addProdBrand() {
                                <?php
                                echo CHtml::ajax(array(
                                    'url' => array('prodBrands/createProdBrandsFromOutSide'),
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
                                                $('#dialogAddProdBrand div.divForForm').html(data.div);
                                                      // Here is the trick: on submit-> once again this function!
                                                $('#dialogAddProdBrand div.divForForm form').submit(addProdBrand);
                                            }
                                            else
                                            {
                                                $('#dialogAddProdBrand div.divForForm').html(data.div);
                                                setTimeout(\"$('#dialogAddProdBrand').dialog('close') \",1000);
                                                $('#Inventory_brand_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
                                            }
                                                                    }",
                                ))
                                ?>
                                return false;
                            }
                        </script>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'brand_id'); ?></td>
                </tr>

                <tr>
                    <td><?php echo $form->labelEx($model, 'model_id'); ?></td>
                    <td>


                        <input type="text" id="prod_model_id"/>
                        <?php echo $form->hiddenField($model, 'model_id'); ?>
                        <script>
                            $(function () {
                                $("#prod_model_id").autocomplete({
                                    source: function (request, response) {
                                        var search_prodName = request.term;
                                        $.post('<?php echo Yii::app()->baseUrl ?>/prodModels/jquery_showprodSearchSaleq', {"search_prodName": search_prodName},
                                            function (data) {
                                                response(data);
                                            }, "json");
                                    },
                                    minLength: 1,
                                    select: function (event, ui) {
                                        $("#prod_model_id").val(ui.item.label);
                                        $("#Inventory_model_id").val(ui.item.value);
                                        $("#Inventory_item_id").val(ui.item.item_id);
                                        $("#Inventory_brand_id").val(ui.item.brand_id);
                                        $("#Inventory_code").val(ui.item.code);
                                        $("#Inventory_unit_id").val(ui.item.unit_id);
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
                                                $('#Inventory_model_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
                                            }
                                                                    }",
                                ))
                                ?>
                                return false;
                            }
                        </script>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'model_id'); ?></td>
                </tr>

                <tr>
                    <td><?php echo $form->labelEx($model, 'code'); ?></td>
                    <td><?php echo $form->textField($model, 'code', array('maxlength' => 255)); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'code'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'unit_id'); ?></td>
                    <td><?php
                        echo $form->dropDownList(
                            $model, 'unit_id', CHtml::listData(Units::model()->findAll(array('order' => 'label ASC')), 'id', 'label'), array(
                            'prompt' => 'Select',
                            'style' => 'background-color:#e2deda;pointer-events: none;',
                        ));
                        ?>

                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr style="display: none;">
                    <td><?php echo $form->labelEx($model, 'vat'); ?></td>
                    <td><?php echo $form->textField($model, 'vat'); ?></td>
                </tr>
                <tr style="display: none;">
                    <td></td>
                    <td><?php echo $form->error($model, 'vat'); ?></td>
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
                                        'prodCode' => 'js:jQuery("#Inventory_code").val()',
                                        'idPrefix' => 'Inventory',
                                    ),
                                    'beforeSend' => 'function(){
                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                         }',
                                    'complete' => 'function(){
                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
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
                                                $("#Inventory_item_id").html(data.itemValue);
                                                $("#Inventory_brand_id").html(data.brandValue);
                                                $("#Inventory_model_id").html(data.modelValue);
                                                $("#Inventory_code").val("");
                                                $("#Inventory_pmodel").val("").selected=true;
                                                $("#Inventory_pbrand").val("").selected=true;
                                                $("#Inventory_color_id").val("").selected=true;
                                                $("#Inventory_size_id").val("").selected=true;
                                                $("#Inventory_sell_price").val("");
                                                $("#Inventory_discount").val("");
                                            }',
                                    'data' => array(
                                        'sendingVal' => 'js:jQuery("#Inventory_code").val()',
                                    ),
                                    'beforeSend' => 'function(){
                                                document.getElementById("Inventory_item_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                                document.getElementById("Inventory_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                                document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                                document.getElementById("Inventory_code").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 98% 1px";   
                                             }',
                                    'complete' => 'function(){
                                                document.getElementById("Inventory_item_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                document.getElementById("Inventory_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                document.getElementById("Inventory_code").style.background="#FFFFFF"; 
                                             }',
                                ),
                            )
                        );
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="float: left; width: 530px;" class="someStyle">
            <legend>Product Qty</legend>
            <table>
                <tr style="display: none">
                    <td><?php echo $form->labelEx($model, 't_type'); ?></td>
                    <td>
                        <?php
                        $array = array('1' => 'Stock Out', '2' => 'Damaged');
                        echo $form->dropDownList($model, 't_type', $array, array(
                            'prompt' => 'Select',
                            'style' => "width:144;",
                        ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'store_id'); ?></td>
                    <td>
                        <?php
                        echo $form->dropDownList(
                            $model, 'store_id', CHtml::listData(Stores::model()->findAll(['order' => 'store_name asc']), 'id', 'store_name'), array(
                            'prompt' => 'Select',
                            'ajax' => array(
                                'type' => 'POST',
                                'dataType' => 'json',
                                'url' => CController::createUrl('location/locationforstore'),
                                'success' => 'function(data) {
									$("#Inventory_location_id").html(data.locationList);
								 }',
                                'data' => array(
                                    'storeId' => 'js:jQuery("#Inventory_store_id").val()',
                                ),
                                'beforeSend' => 'function(){
									document.getElementById("Inventory_location_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
								}',
                                'complete' => 'function(){
									document.getElementById("Inventory_location_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
								}',
                            ),
                        ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'store_id'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'is_opening'); ?></td>
                    <td>
                        <?php
                            echo $form->dropDownList(
                                $model, 'is_opening', Lookup::items('yes_no'), array(
                                'prompt' => 'Select',
                            ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'is_opening'); ?></td>
                </tr>
                <tr style="display: none">
                    <td><?php echo $form->labelEx($model, 'location_id'); ?></td>
                    <td>
                        <?php
                        echo $form->dropDownList(
                            $model, 'location_id', CHtml::listData(Location::model()->findAll(['order' => 'title asc']), 'id', 'title'), array(
                            'prompt' => 'Select',
                        ));
                        ?>
                    </td>
                </tr>
                <tr style="display: none">
                    <td></td>
                    <td><?php echo $form->error($model, 'location_id'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'purchase_price'); ?></td>
                    <td><?php echo $form->textField($model, 'purchase_price'); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'purchase_price'); ?></td>
                </tr>
                <tr style="display: none">
                    <td><?php echo $form->labelEx($model, 'sell_price'); ?></td>
                    <td><input type="text" id="Inventory_sell_price"/></td>
                    <td><?php echo $form->labelEx($model, 'discount'); ?></td>
                    <td><input type="text" id="Inventory_discount"/></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'stock_in'); ?></td>
                    <td><?php echo $form->textField($model, 'stock_in'); ?></td>

                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'stock_in'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'transaction_date'); ?></td>
                    <td>
                        <?php
                        Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                        $dateTimePickerConfig1 = array(
                            'model' => $model,
                            'attribute' => 'transaction_date',
                            'mode' => 'date',
                            'language' => 'en-AU',
                            'options' => array(
                                'changeMonth' => 'true',
                                'changeYear' => 'true',
                                'dateFormat' => 'yy-mm-dd',
                                'width' => '100',
                            )
                        );
                        $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'transaction_date'); ?></td>
                </tr>
                <tr style="display: none">
                    <td></td>
                    <td colspan="3">
                        <?php
                        echo CHtml::link('Add sale price of selected product ?', "", // the link for open the dialog
                            array(
                                'class' => 'sellPriceTag',
                                'onclick' => "{addProdSellPrice();}"));
                        ?>

                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                            'id' => 'dialogAddProdPrice',
                            'options' => array(
                                'title' => 'Add Product Sell Price',
                                'autoOpen' => false,
                                'modal' => true,
                                'width' => 'auto',
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
                            function addProdSellPrice() {
                                var selectedProdModelId = $('#Inventory_model_id option:selected').val();
                                var dataString = 'model_id=' + selectedProdModelId;
                                if (selectedProdModelId != '') {
                                    $('#dialogAddProdPrice').dialog('open');
                                    <?php
                                    echo CHtml::ajax(array(
                                        'url' => array('sellPrice/addSellPriceFromOutside'),
                                        'data' => "js:dataString+$(this).serialize()",
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
                                                $('#dialogAddProdPrice div.divForForm').html(data.content);
                                                      // Here is the trick: on submit-> once again this function!
                                                $('#dialogAddProdPrice div.divForForm form').submit(addProdSellPriceAgain);
                                            }
                                            else
                                            {
                                                $('#dialogAddProdPrice div.divForForm').html(data.content);
                                                $('#Inventory_sell_price').val(data.activeSP);
                                                $('#Inventory_discount').val(data.activeDS);
                                                setTimeout(\"$('#dialogAddProdPrice').dialog('close') \",1000);
                                            }
                                                                    }",
                                    ));
                                    ?>
                                } else {
                                    alertify.alert('Please select a product first!');
                                    setTimeout('$("#dialogAddProdPrice").dialog("close")', 1000);
                                }
                                return false;
                            }

                            function addProdSellPriceAgain() {

                                var dataString = '';

                                <?php
                                echo CHtml::ajax(array(
                                    'url' => array('sellPrice/addSellPriceFromOutside'),
                                    'data' => "js:dataString+$(this).serialize()",
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
                                                $('#dialogAddProdPrice div.divForForm').html(data.content);
                                                      // Here is the trick: on submit-> once again this function!
                                                $('#dialogAddProdPrice div.divForForm form').submit(addProdSellPriceAgain);
                                            }
                                            else
                                            {
                                                $('#dialogAddProdPrice div.divForForm').html(data.content);
                                                $('#Inventory_sell_price').val(data.activeSP);
                                                $('#Inventory_discount').val(data.activeDS);
                                                setTimeout(\"$('#dialogAddProdPrice').dialog('close') \",1000);
                                            }
                                                                    }",
                                ));
                                ?>
                                return false;
                            }
                        </script>

                    </td>
                </tr>
            </table>
        </fieldset>
    </fieldset>

    <fieldset class="tblFooters">
        <div id="ajaxLoader" style="display: none; float: left;"><img
                    src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/></div>

        <?php
        echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('inventory/create', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#formResultError").hide();
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#inventory-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $.fn.yiiGridView.update("inventory-grid", {
		data: $(this).serialize()
                    });
                    }else{
                        $("#formResultError").html("Data not saved. Please solve the above errors.");
                        $.each(data, function(key, val) {
                            $("#inventory-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#inventory-form #"+key+"_em_").show();
                        });
                    }       
                }',
            'beforeSend' => 'function(){                        
                $("#ajaxLoader").show();
             }'
        ), array('id' => 'btnSubmit'));
        ?>
    </fieldset>
    <div id="jsPart"></div>
    <div id="formResult" class="ajaxTargetDiv">

    </div>
    <div id="formResultError" class="ajaxTargetDivErr">

    </div>
</div>

<script>
    $(document).ready(function () {
        $("#Inventory_sell_price").css("background-color", "#D7D7D7");
        $("#Inventory_sell_price").focus(function () {
            $(this).blur();
        });
        $("#Inventory_discount").css("background-color", "#D7D7D7");
        $("#Inventory_discount").focus(function () {
            $(this).blur();
        });
        $("#btnSubmit").click(function () {
            var stockIn = parseFloat($('#Inventory_stock_in').val());
            if (stockIn <= 0) {
                alertify.alert("Invalid Stock In");
                return false;
            }
        });
    });

    $(function () {
        $("#Inventory_code").autocomplete({
            source: function (request, response) {
                var search_prodName = request.term;
                $.post('<?php echo Yii::app()->baseUrl ?>/prodModels/jquery_showprodCodeSearchSaleq', {"search_prodName": search_prodName},
                    function (data) {
                        response(data);
                    }, "json");
            },
            minLength: 1,
            select: function (event, ui) {
                $("#prod_model_id").val(ui.item.model_name);
                $("#Inventory_model_id").val(ui.item.value);
                $("#Inventory_item_id").val(ui.item.item_id);
                $("#Inventory_brand_id").val(ui.item.brand_id);
                $("#Inventory_code").val(ui.item.label);
                return false;
            }
        });
    });
</script>
<?php $this->endWidget(); ?>
