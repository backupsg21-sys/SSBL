<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'inventory-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>
<?php
$prodInfo=ProdModels::model()->findByPk($model->model_id);
$pbrand="";
$pmodel="";
$size_id="";
$color_id="";
if(!empty($prodInfo->pbrand))
    $pbrand=$prodInfo->pbrand;
if(!empty($prodInfo->pmodel))
    $pmodel=$prodInfo->pmodel;
if(!empty($prodInfo->size_id))
    $size_id=$prodInfo->size_id;
if(!empty($prodInfo->color_id))
    $color_id=$prodInfo->color_id;
$model->pbrand=$pbrand;
$model->pmodel=$pmodel;
$model->size_id=$size_id;
$model->color_id=$color_id;
?>

<div class="formDiv">
    <?php echo CHtml::link('Back To Inventory', array('/inventory/admin'), array('class' => 'additionalBtn')); ?>
    <fieldset>
        <legend>Update These Info <span class="caption">Condition: if you change the information, it may / may not cause miscalculation to the reports! Do you agree to change this informations?</span></legend>
        <fieldset style="float: left; width: 606px; height: 290px;" class="someStyle">
            <legend>Product Info</legend>
            <table>
                <tr>
                    <td><?php echo $form->labelEx($model, 'item_id'); ?></td>
                    <td>
                        <?php
                        echo $form->dropDownList(
                                $model, 'item_id', CHtml::listData(ProdItems::model()->findAll(array('order' => 'id DESC')), 'id', 'item_name'), array(
                            'prompt' => 'Select Category',
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
                            <div class="ajaxLoaderFormLoad" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
                        </div>

                        <?php $this->endWidget(); ?>

                        <script type="text/javascript">
                            // here is the magic
                            function addProdItem(){
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
                    <td rowspan="10" style="padding-left: 140px;">
                        <table class="prodAdditionalInfoTab" style="margin-top: -72px;">
                            <tr>
                                <td>Model</td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                            $model, 'pmodel', CHtml::listData(PModel::model()->findAll(array('order' => 'id DESC')), 'id', 'name'), array(
                                        'prompt' => 'Select',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'dataType' => 'json',
                                            'url' => CController::createUrl('prodModels/prodInfoOfThisAdditionalOp'),
                                            'success' => 'function(data) {
                                                                $("#Inventory_model_id").html(data.prodList);
                                                         }',
                                            'data' => array(
                                                'pmodel' => 'js:jQuery("#Inventory_pmodel").val()',
                                                'pbrand' => 'js:jQuery("#Inventory_pbrand").val()',
                                                'color_id' => 'js:jQuery("#Inventory_color_id").val()',
                                                'size_id' => 'js:jQuery("#Inventory_size_id").val()',
                                            ),
                                            'beforeSend' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                                         }',
                                            'complete' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                        }',
                                        ),
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Brand</td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                            $model, 'pbrand', CHtml::listData(PBrand::model()->findAll(array('order' => 'id DESC')), 'id', 'name'), array(
                                        'prompt' => 'Select',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'dataType' => 'json',
                                            'url' => CController::createUrl('prodModels/prodInfoOfThisAdditionalOp'),
                                            'success' => 'function(data) {
                                                                $("#Inventory_model_id").html(data.prodList);
                                                         }',
                                            'data' => array(
                                                'pbrand' => 'js:jQuery("#Inventory_pbrand").val()',
                                                'pmodel' => 'js:jQuery("#Inventory_pmodel").val()',
                                                'color_id' => 'js:jQuery("#Inventory_color_id").val()',
                                                'size_id' => 'js:jQuery("#Inventory_size_id").val()',
                                            ),
                                            'beforeSend' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                                         }',
                                            'complete' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                        }',
                                        ),
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Color</td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                            $model, 'color_id', CriteriaValues::model()->findAllColorNames(), array(
                                        'prompt' => 'Select',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'dataType' => 'json',
                                            'url' => CController::createUrl('prodModels/prodInfoOfThisAdditionalOp'),
                                            'success' => 'function(data) {
                                                                $("#Inventory_model_id").html(data.prodList);
                                                         }',
                                            'data' => array(
                                                'color_id' => 'js:jQuery("#Inventory_color_id").val()',
                                                'pmodel' => 'js:jQuery("#Inventory_pmodel").val()',
                                                'pbrand' => 'js:jQuery("#Inventory_pbrand").val()',
                                                'size_id' => 'js:jQuery("#Inventory_size_id").val()',
                                            ),
                                            'beforeSend' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                                         }',
                                            'complete' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                        }',
                                        ),
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Size</td>
                                <td>
                                    <?php
                                    echo $form->dropDownList(
                                            $model, 'size_id', CriteriaValues::model()->findAllSizeNames(), array(
                                        'prompt' => 'Select',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'dataType' => 'json',
                                            'url' => CController::createUrl('prodModels/prodInfoOfThisAdditionalOp'),
                                            'success' => 'function(data) {
                                                                $("#Inventory_model_id").html(data.prodList);
                                                         }',
                                            'data' => array(
                                                'size_id' => 'js:jQuery("#Inventory_size_id").val()',
                                                'pmodel' => 'js:jQuery("#Inventory_pmodel").val()',
                                                'pbrand' => 'js:jQuery("#Inventory_pbrand").val()',
                                                'color_id' => 'js:jQuery("#Inventory_color_id").val()',
                                            ),
                                            'beforeSend' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                                         }',
                                            'complete' => 'function(){
                                                                    document.getElementById("Inventory_model_id").style.background="url(' . Yii::app()->theme->baseUrl . '/images/downDrop.png) no-repeat #FFFFFF 98% 2px"; 
                                                        }',
                                        ),
                                    ));
                                    ?>
                                </td>
                            </tr>
                        </table>
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
                            'prompt' => 'Select Sub-Category',
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
                            <div class="ajaxLoaderFormLoad" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
                        </div>

                        <?php $this->endWidget(); ?>

                        <script type="text/javascript">
                            // here is the magic
                            function addProdBrand(){
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
                        <?php
                        echo $form->dropDownList(
                                $model, 'model_id', CHtml::listData(ProdModels::model()->findAll(array('order' => 'id DESC')), 'id', 'model_name'), array(
                            'prompt' => 'Select',
                            'ajax' => array(
                                'type' => 'POST',
                                'dataType' => 'json',
                                'url' => CController::createUrl('prodModels/catSubCatOfThisProd'),
                                'success' => 'function(data) {
                                                $("#jsPart").html(data.jsPart);
                                         }',
                                'data' => array(
                                    'prodId' => 'js:jQuery("#Inventory_model_id").val()',
                                    'idPrefix' => 'Inventory',
                                ),
                                'beforeSend' => 'function(){
                                                    document.getElementById("Inventory_code").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #FFFFFF 100% 1px";   
                                         }',
                                'complete' => 'function(){
                                                    document.getElementById("Inventory_code").style.background="#FFFFFF"; 
                                        }',
                            ),
                        ));
                        ?>
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
                                'width' => '1288px',
                                'left' => '30px',
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
                            function addProdModel(){
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
                <tr>
                    <td></td>
                    <td>
                        <?php
                        echo CHtml::link("Additional Info", "", // the link for open the dialog
                                array(
                            'class' => 'findBtn',
                            'onclick' => '{$("#dialogAdditionalInfo").dialog("open");}'));
                        ?>

                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                            'id' => 'dialogAdditionalInfo',
                            'options' => array(
                                'title' => 'Products Additional Info',
                                'autoOpen' => false,
                                'modal' => true,
                                'width' => 'auto',
                                'resizable' => false,
                            ),
                        ));
                        ?>
                        <div id="loadingDivResultProdInfo" style="display: none; float: right;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
                        <div id="prodAdditionalInfoResult">
                            <?php ProdModels::model()->optionalInfos($model->model_id); ?>
                        </div>
                        <?php $this->endWidget(); ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="float: left; width: 530px; height: 290px;" class="someStyle">
            <legend>Product Qty</legend>
            <table> 
                <tr>
                    <td><?php echo $form->labelEx($model, 'store_id'); ?></td>
                    <td>
                        <?php
                        echo $form->dropDownList(
                            $model, 'store_id', CHtml::listData(Stores::model()->findAll(['order' => 'store_name asc']), 'id', 'store_name'), array(
                            'prompt' => 'Select',
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
                                $model, 'is_opening', Lookup::items('yes_no') , array(
                                'prompt' => 'Select',
                            ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'is_opening'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'purchase_price'); ?></td>
                    <td><?php $model->purchase_price = $purchasePrice;
                        echo $form->textField($model, 'purchase_price'); ?></td> 

                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'purchase_price'); ?></td>
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
                            'language' => '',
                            'options' => array(
                                'changeMonth'=>'true', 
                                'changeYear'=>'true',
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
            </table>
        </fieldset> 
    </fieldset>

    <fieldset class="tblFooters">
        <div id="ajaxLoader" style="display: none; float: left;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>

<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </fieldset>
    <div id="jsPart"></div>
    <div id="formResult" class="ajaxTargetDiv">

    </div>
    <div id="formResultError" class="ajaxTargetDivErr">

    </div>
</div>

<?php
$pcode = ProdModels::model()->findAll();
?>
<script>
    $(document).ready(function(){
        var codeSuggest = [
<?php foreach ($pcode as $pcd): ?>
    <?php echo '"' . $pcd->code . '"'; ?>,
<?php endforeach; ?>
        ];
        
        $( "#Inventory_code" ).autocomplete({
            minLength: 0,
            source: codeSuggest
        });
    });
</script>
<?php $this->endWidget(); ?>

