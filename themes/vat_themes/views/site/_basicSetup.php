<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/monir_parts/css/jquery-ui.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/themes/monir_parts/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/themes/monir_parts/js/jquery-ui-i18n.min.js"></script>

<div class="summaryDiv">
    <div class="basicBtnDiv">
        <div class="basic_setup_wrapper">
            <?php echo CHtml::link('Suppliers', array('/suppliers/admin'), array('id' => 'bSupplier', 'target' => '_blank')); ?>
            <?php echo CHtml::link('Customers', array('/customers/admin'), array('id' => 'bCustomer', 'target' => '_blank')); ?>
            <?php echo CHtml::link('Stores / Locations', array('/stores/admin'), array('id' => 'bStore', 'target' => '_blank')); ?>
            <?php echo CHtml::link('Products', array('/prodModels/admin'), array('id' => 'bProduct', 'target' => '_blank')); ?>
            <?php echo CHtml::link('Sale Price', array('/prodModels/adminSellPrice'), array('id' => 'bSalePrice', 'target' => '_blank')); ?>
            <?php echo CHtml::link('Inventory', array('/inventory/admin'), array('id' => 'bInventory', 'style' => 'margin-right: 0px;', 'target' => '_blank')); ?>
        </div>
        <div class="basic_setup_wrapper">
            <?php echo CHtml::link('Purchase Order', array('/purchaseItems/admin'), array('id' => 'bReqForm', 'style'=>'margin-bottom: 0px;', 'target' => '_blank')); ?>
            <?php echo CHtml::link('Receive', array('/receivedPurchase/adminReceive'), array('id' => 'bRawmReceive', 'style'=>'margin-bottom: 0px;', 'target' => '_blank')); ?>
            <?php echo CHtml::link('Sale Order', array('/sellItems/admin'), array('id' => 'bProdctnOrderForm', 'style'=>'margin-bottom: 0px;', 'target' => '_blank')); ?>
            <?php echo CHtml::link('Deliver', array('/sellDelivery/adminDelivery'), array('id' => 'bRawmDelivery', 'style'=>'margin-bottom: 0px;', 'target' => '_blank')); ?>
            <?php echo CHtml::link('POS', array('/quickSell/Create'), array('id' => 'bPos', 'style'=>'margin-bottom: 0px;', 'target' => '_blank')); ?>
            <div class="basicSetupTitle">
                <div class="titleChart">Profit/Loss Chart</div>
                <?php echo CHtml::link('Open', array('site/profitLossChart'), array('class' => 'myLinkBtn')); ?>
                <?php echo CHtml::link('Close', array('site/dashBoard'), array('class' => 'myLinkBtn2')); ?>
            </div>
        </div>
      
    </div>
</div>
<div class="search-form">

    <?php
    $model =  new ProdModels;
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'prodmodels-form',
        'action' => Yii::app()->createUrl('/site/productStock'),
    ));
    ?>

    <fieldset>
        <legend>Search Conditions</legend>
        <table style="width:40%">
            <tr>
                <td><?php echo $form->labelEx($model, 'model_name'); ?></td>
                <td>
                    <?php echo $form->textField($model, 'model_name'); ?>
                    <?php echo $form->hiddenField($model, 'id'); ?>
                    <script>
                        $(function () {
                            $("#ProdModels_model_name").autocomplete({
                                source: function (request, response) {
                                    var search_prodName = request.term;
                                    $.post('<?php echo Yii::app()->baseUrl ?>/prodModels/jquery_showprodSearch1', {"search_prodName": search_prodName},
                                            function (data) {
                                                response(data);
                                            }, "json");
                                },
                                minLength: 1,
                                select: function (event, ui) {
                                    $("#ProdModels_model_name").val(ui.item.label);
                                    $("#ProdModels_id").val(ui.item.value);
                                    return false;
                                }
                            });
                        });
                    </script>
                </td>
                <td style="width:10%">OR</td>
                <td><label>Code</label></td>
                <td>
                    <?php echo $form->textField($model, 'code'); ?>
                    <script>
                        $(function () {
                            $("#ProdModels_code").autocomplete({
                                source: function (request, response) {
                                    var search_prodCode = request.term;
                                    $.post('<?php echo Yii::app()->baseUrl ?>/prodModels/jquery_showprodSearch2', {"search_prodCode": search_prodCode},
                                        function (data) {
                                            response(data);
                                        }, "json");
                                },
                                minLength: 1,
                                select: function (event, ui) {
                                    $("#ProdModels_code").val(ui.item.label);
                                    $("#ProdModels_id").val(ui.item.value);
                                    return false;
                                }
                            });
                        });
                    </script>
                </td>
                <td colspan="3" style="text-align: left; border: none;">
                    <?php
                    echo CHtml::ajaxSubmitButton('Search', CHtml::normalizeUrl(array('site/productStock', 'render' => true)), array(
                        'dataType' => 'json',
                        'type' => 'post',
                        'success' => 'function(data) {
                    $("#ajaxLoader").hide(); 
                    $("#resultDiv").html(data.content);
                }',
                        'beforeSend' => 'function(){                        
                $("#ajaxLoader").show();
                if($("#ProdModels_id").val()=="" ){
                    alertify.alert("Warning! Please Insert Book");
                    
                }
             }',
                    ));
                    ?>
                </td>
                <td>

                </td>
            </tr>
        </table>
    </fieldset>

    <?php $this->endWidget(); ?>


</div>
<fieldset style="float: left; width: 98%;">
    <legend>Result <span style=" float: right; margin-left: 8px;"><div id="ajaxLoader" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div><span></legend>
        <div id="resultDiv">

        </div>
</fieldset>
