<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'prod-models-form',
    'action' => $this->createUrl('prodModels/create'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>

<style>
    input[type=text]{
        width:340px !important;
    }
    select{
        width:350px !important;
    }
</style>
<div class="formDiv">
    <fieldset>
        <table style="width:100%">
            <tr>
                <td style="width: 33%; vertical-align: top">
                    <fieldset style="height: 265px;">
                        <legend>Product Information</legend>
                        <table style="width:90%">
                            <tr>
                                <td style="width:20%"><?php echo $form->labelEx($model, 'item_id'); ?></td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                        $model, 'item_id', CHtml::listData(ProdItems::model()->findAll(array('order' => 'item_name ASC')), 'id', 'item_name'), array(
                                        'prompt' => 'Select',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'dataType' => 'json',
                                            'url' => CController::createUrl('prodModels/subCatOfThisCat'),
                                            'success' => 'function(data) {
                                                $("#ProdModels_brand_id").html(data.subCatList);
                                         }',
                                            'data' => array(
                                                'catId' => 'js:jQuery("#ProdModels_item_id").val()',
                                            ),
                                            'beforeSend' => 'function(){
                                                    document.getElementById("ProdModels_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                                            'complete' => 'function(){
                                            document.getElementById("ProdModels_brand_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
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
                                            'title' => 'Add Quality',
                                            'autoOpen' => false,
                                            'modal' => true,
                                            'width' => 550,
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
                                                                                                        $('#ProdModels_item_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
                                                                                                    }
                                                                                                                            }",
                                            ))
                                            ?>
                                            return false;
                                        }
                                    </script>
                                    <?php echo $form->error($model, 'item_id'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'brand_id'); ?></td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                        $model, 'brand_id', CHtml::listData(ProdBrands::model()->findAll(array('order' => 'brand_name ASC')), 'id', 'brand_name'), array(
                                        'prompt' => 'Select',
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
                                            'title' => 'Add Product Type',
                                            'autoOpen' => false,
                                            'modal' => true,
                                            'width' => 550,
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
                                                            $('#ProdModels_brand_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
                                                        }
                                                                                }",
                                                ))
                                                ?>;
                                            return false;
                                        }
                                    </script>
                                    <div id="ajaxLoaderDrop" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
                                    <?php echo $form->error($model, 'brand_id'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td width='20%'><?php echo $form->labelEx($model, 'model_name'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'model_name', array('maxlength' => 255)); ?>
                                    <?php echo $form->error($model, 'model_name'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'code'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'code', array('maxlength' => 255)); ?>
                                    <?php echo $form->error($model, 'code'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'hs_code'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'hs_code', array('maxlength' => 255)); ?>
                                    <?php echo $form->error($model, 'hs_code'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'unit_id'); ?></td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                        $model, 'unit_id', CHtml::listData(Units::model()->findAll(array('order' => 'label ASC')), 'id', 'label'), array(
                                        'prompt' => 'Select',
                                    ));
                                    ?>
                                    <?php
                                    echo CHtml::link('', "", // the link for open the dialog
                                        array(
                                            'class' => 'add-additional-btn',
                                            'onclick' => "{addUnit(); $('#dialogAddUnit').dialog('open');}"));
                                    ?>

                                    <?php
                                    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                                        'id' => 'dialogAddUnit',
                                        'options' => array(
                                            'title' => 'Add Unit',
                                            'autoOpen' => false,
                                            'modal' => true,
                                            'width' => 550,
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
                                        function addUnit() {
                                            <?php
                                            echo CHtml::ajax(array(
                                                'url' => array('units/createUnitFromOutSide'),
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
                        $('#dialogAddUnit div.divForForm').html(data.div);
                              // Here is the trick: on submit-> once again this function!
                        $('#dialogAddUnit div.divForForm form').submit(addUnit);
                    }
                    else
                    {
                        $('#dialogAddUnit div.divForForm').html(data.div);
                        setTimeout(\"$('#dialogAddUnit').dialog('close') \",1000);
                        $('#ProdModels_unit_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
                    }
                }",
                                            ))
                                            ?>
                                            return false;
                                        }
                                    </script>
                                    <?php echo $form->error($model, 'unit_id'); ?>
                                </td>
                            </tr>

                        </table>
                    </fieldset>
                    <fieldset style="height: 295px;">
                        <legend>Additional Information</legend>
                        <table style="width:90%">
                            <tr>
                                <td style="width:20%"><?php echo $form->labelEx($model, 'rebatable'); ?></td>
                                <td>
                                    <?php echo $form->checkBox($model,'rebatable',array('value' => '1', 'uncheckValue'=>'0')); ?>
                                    <?php echo $form->error($model, 'rebatable'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td width='20%'><?php echo $form->labelEx($model, 'features'); ?></td>
                                <td><?php echo $form->textArea($model, 'features', array('rows' => 1, 'cols' => 55,'style'=>'height:120px;min-width: 320px;')); ?>
                                    <?php echo $form->error($model, 'features'); ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
                <td style="width: 33%">
                    <fieldset style="height: 265px;">
                        <legend>Product Price & Qty</legend>
                        <table style="width: 79%;">
                            <tr>
                                <td><?php echo $form->labelEx($model, 'mrp'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'mrp', array('onkeyup' => 'calculateAmount($(this))', 'class' => 'mrp')); ?>
                                    <?php echo $form->error($model, 'mrp'); ?>
                                    <?php echo $form->hiddenField($model, 'gp_percent', array('class' => 'gp_percent')); ?></td>
                       
                                </td>
                            </tr>
                            <tr style="display: none">
                                <td><?php echo $form->labelEx($model, 'qty'); ?></td>
                                <td><?php echo $form->textField($model, 'qty'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'min_order_qty'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'min_order_qty'); ?>
                                    <?php echo $form->error($model, 'min_order_qty'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'purchase_discount'); ?></td>
                                <td><?php echo $form->textField($model, 'purchase_discount'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'base_tp'); ?></td>
                                <td>
                                    <?php echo $form->textField($model, 'base_tp', array('onkeyup' => 'calculateAmount($(this))', 'class' => 'base_tp')); ?>
                                    <?php echo $form->error($model, 'base_tp'); ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset>
                        <legend>TAX Details</legend>
                        <table style="width: 90%">
                            <tr>
                                <td width='20%'><?php echo $form->labelEx($model, 'vat_calculation_method'); ?></td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                        $model, 'vat_calculation_method', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method'", 'order' => 'code ASC')), 'code', 'name'), array(
                                        //'prompt' => 'Select',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'dataType' => 'json',
                                            'url' => CController::createUrl('prodModels/subVatOfVat'),
                                            'success' => 'function(data) {
                                                $("#ProdModels_sub_vat_id").html(data.subVatList);
                                         }',
                                            'data' => array(
                                                'subVatId' => 'js:jQuery("#ProdModels_vat_calculation_method").val()',
                                            ),
                                            'beforeSend' => 'function(){
                                                    //document.getElementById("ProdModels_sub_vat_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 80% 1px";   
                                         }',
                                            'complete' => 'function(){
                                            //document.getElementById("ProdModels_sub_vat_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                        }',
                                        ),
                                    ));
                                    ?>
                                    <?php echo $form->error($model, 'vat_calculation_method'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo $form->labelEx($model, 'vat'); ?> <span style="color: red;">*</span>
                                </td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                        $model, 'sub_vat_id', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_standard'", 'order'=>'code ASC')), 'code', 'name'), array(
                                        'prompt' => 'Select',
                                    ));
                                    ?>
                                    <?php echo $form->error($model, 'sub_vat_id'); ?>
                                    <?php echo $form->hiddenField($model, 'vat'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'sd'); ?></td>
                                <td><?php echo $form->textField($model, 'sd'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'cd'); ?></td>
                                <td><?php echo $form->textField($model, 'cd'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'rd'); ?></td>
                                <td><?php echo $form->textField($model, 'rd'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'ait'); ?></td>
                                <td><?php echo $form->textField($model, 'ait'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'atv'); ?></td>
                                <td><?php echo $form->textField($model, 'atv'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'tti'); ?></td>
                                <td><?php echo $form->textField($model, 'tti'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'exd'); ?></td>
                                <td><?php echo $form->textField($model, 'exd'); ?></td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset class="tblFooters">
        <div id="ajaxLoader" style="display: none; float: left;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
        <?php
        echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('prodModels/create', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#tab3").hide();
                        $("#tab2").hide();
                        $("#tab1").show();
                        $(".tabs li a").removeClass("active");
                        $(".tabs li:first a").addClass("active");
                        
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#prod-models-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                    }else{
                        $.each(data, function(key, val) {
                            $("#prod-models-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#prod-models-form #"+key+"_em_").show();
                        });
                    }       
                }',
            'beforeSend' => 'function(){   
                // if($("#ProdModels_mrp").val() == ""){                    
                //     alertify.alert("Please give MRP");
                //     return false;
                // }                     
                // if($("#ProdModels_base_tp").val() == ""){                    
                //     alertify.alert("Please give Base TP");
                //     return false;
                // }                     
                // if($("#ProdModels_vat_calculation_method").val() == ""){                    
                //     alertify.alert("Please select VAT calculation Method");
                //     return false;
                // }                     
                // if($("#ProdModels_sub_vat_id").val() == ""){                    
                //     alertify.alert("Please select VAT");
                //     return false;
                // }                     
                $("#ajaxLoader").show();
             }'
        ));
        ?>
    </fieldset>
    <div id="formResult" class="ajaxTargetDiv"></div>
    <div id="formResultError" class="ajaxTargetDivErr"></div>
    </fieldset>
</div>

<?php $this->endWidget(); ?>
<style>
    select {
        color: #708090;
        width: 80%;
    }

    input[type=text] {
        width: 350px !important;
    }

    textarea {
        float: left;
        width: 53% !important;
    }
</style>

<script>
    function calculateAmount(element) {
        var base_tp = parseFloat(element.parents('tr').find('.base_tp').val());
        if (isNaN(base_tp))
            base_tp = 0;
        var mrp = parseFloat(element.parents('tr').find('.mrp').val());
        if (isNaN(mrp))
            mrp = 0;

        var result = mrp - base_tp;
        var gps = ((result / mrp) * 100).toFixed(2);
        if (gps >= 0) {
            element.parents('tr').find('.gp_percent').val((gps));
        }
    }
</script>


<style>

    fieldset legend {
        padding: 3px 5px !important;
    }
    textarea {
        width: 53%;
        border: 1px solid #AAA;
    }

</style>

<script>
    $( "#ProdModels_sub_vat_id" ).change(function() {
        var dataVal =  $( "#ProdModels_sub_vat_id option:selected" ).text();
        $("#ProdModels_vat").val(dataVal);
    });
</script>
