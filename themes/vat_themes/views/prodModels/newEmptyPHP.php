<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'prod-models-form',
            'enableAjaxValidation' => true,
            'action' => $this->createUrl('prodModels/create'),
            'enableClientValidation' => true,
        ));
?>


<div id="formResult" class="ajaxTargetDiv">

</div>
<div id="formResultError" class="ajaxTargetDivErr">

</div>

<div class="formDiv">
    <fieldset style="margin-bottom: 20px; float: left;">
        <legend><?php echo ($model->isNewRecord ? 'Add New Product Info' : 'Update Product Info'); ?></legend>
        <fieldset style="float: left; width: 279px; height: 220px;">
            <legend>Main Infos</legend>
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
                                'url' => CController::createUrl('prodModels/brandOfThisItem'),
                                'update' => '#' . CHtml::activeId($model, 'brand_id'), /* Works */
                                'beforeSend' => 'function(){
                                                    $("#ajaxLoaderDrop").show();
                                             }',
                                'complete' => 'function(){
                                                    $("#ajaxLoaderDrop").hide();
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
                                                $('#ProdModels_item_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
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
                                $model, 'brand_id', array(), array(
                            'prompt' => 'Select Sub-Category',
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
                                                $('#ProdModels_brand_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
                                            }
                                                                    }",
))
                                ?>
                                return false;
                            }
                        </script>
                    </td>
                    <td>
                        <div id="ajaxLoaderDrop" style="display: none;"><img
                                    src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/></div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'brand_id'); ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'model_name'); ?></td>
                    <td><?php echo $form->textField($model, 'model_name', array('maxlength' => 255)); ?></td>            
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'model_name'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'code'); ?></td>
                    <td>
                        <?php
                        $yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
                        if ($yourCompanyInfo) {
                            $yourCompanyCode = $yourCompanyInfo->code;
                        } else {
                            $yourCompanyCode = '';
                        }
                        $maxProdId = VoucherNoFormate::model()->maxValOfThis('ProdModels', 'id', 'maxProdId');
                        $prodId=str_pad($maxProdId, 6, "0", STR_PAD_LEFT);
                        $prodCodeGenerated = "8" . $yourCompanyCode . $prodId;
                        $model->code = $prodCodeGenerated;
                        ?>
                        <?php echo $form->textField($model, 'code', array('maxlength' => 13)); ?>

                    </td> 
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'code'); ?></td>                    
                </tr>
                <tr>
                    <td>Explanation</td>
                    <td>
                        <span id="help" class="explanation"></span><span style="color: red;position: relative;top: 4px;"><- Click me!</span>

                        <script type="text/javascript">
                            $(document).ready(function(){
                                $(".explanation").click(function(){
                                    $('#explanationDialog').dialog('open');
                                });
                            });
                        </script>
                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                            'id' => 'explanationDialog',
                            'options' => array(
                                'title' => 'Code Explanation',
                                'autoOpen' => false,
                                'modal' => true,
                                'width' => 'auto',
                                'resizable' => false,
                            ),
                        ));
                        ?>
                        <style>
    ul.helpUl li{
        list-style-image: url(<?php echo Yii::app()->theme->baseUrl; ?>/images/list-ico.ico);
        list-style-position: inside;
        line-height: 30px;
        font-size: 14px;
        font-family: -moz-fixed;
    }
</style>
                        <ul class="helpUl">
                            <li>First digit is for product number system (8)</li>
                            <li>Then, 5/6 digit is your company code (<?php echo $yourCompanyCode; ?>)</li>
                            <li>Last 6 digit is product code (<?php echo $prodId; ?>)</li>
                        </ul>
                        <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="float: left; width: 287px; height: 220px;">
            <legend>Additional Features</legend>
            <table>
                <tr>
                    <td><?php echo $form->labelEx($model, 'features'); ?></td>
                    <td><?php //echo $form->textArea($model, 'features', array('rows' => 11, 'cols' => 25));       ?>
                        <?php
                        $this->widget('ext.editMe.widgets.ExtEditMe', array(
                            'model' => $model,
                            'attribute' => 'features',
                            'height' => '120',
                            'resizeMode' => false,
                            'toolbar' => array(
                                array(
                                    'BulletedList',
                                ),
                            ),
                        ));
                        ?>
                    </td>                     
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'features'); ?></td>                    
                </tr>                
            </table>
        </fieldset>
        <fieldset style="float: left; width: 550px; height: 220px;">
            <legend>Optional Infos</legend>
            <table>
                <tr>                     
                    <td><?php echo $form->labelEx($model, 'pbrand'); ?></td>
                    <td>
                        <?php
                        echo $form->dropDownList(
                                $model, 'pbrand', CHtml::listData(PBrand::model()->findAll(array('order' => 'id DESC')), 'id', 'name'), array(
                            'prompt' => 'Select',
                        ));
                        ?>
                        <?php
                        echo CHtml::link('', "", // the link for open the dialog
                                array(
                            'class' => 'add-additional-btn',
                            'onclick' => "{addPBrand(); $('#dialogAddPBrand').dialog('open');}"));
                        ?>

                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                            'id' => 'dialogAddPBrand',
                            'options' => array(
                                'title' => 'Add Brand',
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
                            function addPBrand(){
<?php
echo CHtml::ajax(array(
    'url' => array('pBrand/createPBrandFromOutSide'),
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
                                                $('#dialogAddPBrand div.divForForm').html(data.div);
                                                      // Here is the trick: on submit-> once again this function!
                                                $('#dialogAddPBrand div.divForForm form').submit(addPBrand);
                                            }
                                            else
                                            {
                                                $('#dialogAddPBrand div.divForForm').html(data.div);
                                                setTimeout(\"$('#dialogAddPBrand').dialog('close') \",1000);
                                                $('#ProdModels_pbrand').append('<option selected value='+data.value+'>'+data.label+'</option>');
                                            }
                                                                    }",
))
                                ?>
                                return false;
                            }
                        </script>
                    </td>
                    <td><?php echo $form->labelEx($model, 'country_id'); ?></td>
                    <td>
                        <?php
                        echo $form->dropDownList(
                            $model, 'country_id', CHtml::listData(Countries::model()->findAll(array('order' => 'id DESC')), 'id', 'country'), array(
                            'prompt' => 'Select',
                        ));
                        ?>
                        <?php
                        echo CHtml::link('', "", // the link for open the dialog
                                array(
                            'class' => 'add-additional-btn',
                            'onclick' => "{addCountry(); $('#dialogAddCountry').dialog('open');}"));
                        ?>

                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                            'id' => 'dialogAddCountry',
                            'options' => array(
                                'title' => 'Add Country',
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
                            function addCountry(){
<?php
echo CHtml::ajax(array(
    'url' => array('countries/createCountryFromOutSide'),
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
                                                $('#dialogAddCountry div.divForForm').html(data.div);
                                                      // Here is the trick: on submit-> once again this function!
                                                $('#dialogAddCountry div.divForForm form').submit(addCountry);
                                            }
                                            else
                                            {
                                                $('#dialogAddCountry div.divForForm').html(data.div);
                                                setTimeout(\"$('#dialogAddCountry').dialog('close') \",1000);
                                                $('#ProdModels_country_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
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
                    <td><?php echo $form->error($model, 'pbrand'); ?></td>
                    <td></td>
                    <td><?php echo $form->error($model, 'country_id'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'pmodel'); ?></td>
                    <td>
                        <?php
                        echo $form->dropDownList(
                                $model, 'pmodel', CHtml::listData(PModel::model()->findAll(array('order' => 'id DESC')), 'id', 'name'), array(
                            'prompt' => 'Select',
                        ));
                        ?>
                        <?php
                        echo CHtml::link('', "", // the link for open the dialog
                                array(
                            'class' => 'add-additional-btn',
                            'onclick' => "{addPModel(); $('#dialogAddPModel').dialog('open');}"));
                        ?>

                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                            'id' => 'dialogAddPModel',
                            'options' => array(
                                'title' => 'Add Model',
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
                            function addPModel(){
<?php
echo CHtml::ajax(array(
    'url' => array('pModel/createPModelFromOutSide'),
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
                                                $('#dialogAddPModel div.divForForm').html(data.div);
                                                      // Here is the trick: on submit-> once again this function!
                                                $('#dialogAddPModel div.divForForm form').submit(addPModel);
                                            }
                                            else
                                            {
                                                $('#dialogAddPModel div.divForForm').html(data.div);
                                                setTimeout(\"$('#dialogAddPModel').dialog('close') \",1000);
                                                $('#ProdModels_pmodel').append('<option selected value='+data.value+'>'+data.label+'</option>');
                                            }
                                                                    }",
))
                                ?>
                                return false;
                            }
                        </script>
                    </td>
                    <td><?php echo $form->labelEx($model, 'manufacturer_id'); ?></td>
                    <td>
                        <?php
                        echo $form->dropDownList(
                            $model, 'manufacturer_id', CHtml::listData(Manufacturers::model()->findAll(array('order' => 'id DESC')), 'id', 'manufacturer'), array(
                            'prompt' => 'Select',
                        ));
                        ?>
                        <?php
                        echo CHtml::link('', "", // the link for open the dialog
                                array(
                            'class' => 'add-additional-btn',
                            'onclick' => "{addManufacturer(); $('#dialogAddManufacturer').dialog('open');}"));
                        ?>

                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                            'id' => 'dialogAddManufacturer',
                            'options' => array(
                                'title' => 'Add Manufacturer',
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
                            function addManufacturer(){
<?php
echo CHtml::ajax(array(
    'url' => array('manufacturers/createManufacturerFromOutSide'),
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
                                                $('#dialogAddManufacturer div.divForForm').html(data.div);
                                                      // Here is the trick: on submit-> once again this function!
                                                $('#dialogAddManufacturer div.divForForm form').submit(addManufacturer);
                                            }
                                            else
                                            {
                                                $('#dialogAddManufacturer div.divForForm').html(data.div);
                                                setTimeout(\"$('#dialogAddManufacturer').dialog('close') \",1000);
                                                $('#ProdModels_manufacturer_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
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
                    <td><?php echo $form->error($model, 'pmodel'); ?></td>
                    <td></td>
                    <td><?php echo $form->error($model, 'manufacturer_id'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'serial_no'); ?></td>
                    <td><?php echo $form->textField($model, 'serial_no', array('maxlength' => 255)); ?></td> 
                    <td style="color: red;"><?php echo $form->labelEx($model, 'warranty'); ?></td>
                    <td><?php echo $form->textField($model, 'warranty'); ?></td> 
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $form->error($model, 'serial_no'); ?></td>
                    <td></td>
                    <td><?php echo $form->error($model, 'warranty'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'mfg_date'); ?></td>
                    <td>
                        <?php
                        Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                        $dateTimePickerConfig1 = array(
                            'model' => $model, //Model object
                            'attribute' => 'mfg_date', //attribute name
                            'mode' => 'date', //use "time","date" or "datetime" (default)
                            'language' => 'en-AU',
                            'id' => 'mfg_date',
                            'options' => array(
//                        'ampm' => true,
                                'dateFormat' => 'yy-mm-dd',
                                'width' => '100',
                            ) // jquery plugin options
                        );
                        $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                        ?>
                    </td> 
                    <td><?php echo $form->labelEx($model, 'exp_date'); ?></td>
                    <td>
                        <?php
                        Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                        $dateTimePickerConfig1 = array(
                            'model' => $model, //Model object
                            'attribute' => 'exp_date', //attribute name
                            'mode' => 'date', //use "time","date" or "datetime" (default)
                            'language' => 'en-AU',
                            'id' => 'exp_date',
                            'options' => array(
//                        'ampm' => true,
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
                    <td><?php echo $form->error($model, 'mfg_date'); ?></td>
                    <td></td>
                    <td><?php echo $form->error($model, 'exp_date'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'size_id'); ?></td>
                    <td>
                        <?php
                        echo $form->dropDownList(
                                $model, 'size_id', CriteriaValues::model()->findAllSizeNames(), array(
                            'prompt' => 'Select',
                            'class' => 'unitSelectOpt',
                        ));
                        ?>
                        <?php
                        echo CHtml::link('', "", // the link for open the dialog
                                array(
                            'class' => 'add-additional-btn',
                            'onclick' => "{addSize(); $('#addSizeDialog').dialog('open');}"));
                        ?>

                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                            'id' => 'addSizeDialog',
                            'options' => array(
                                'title' => 'Add Size',
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
                            function addSize(){
<?php
echo CHtml::ajax(array(
    'url' => array('criteriaValues/createSizeFromOutSide'),
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
                                                $('#addSizeDialog div.divForForm').html(data.div);
                                                      // Here is the trick: on submit-> once again this function!
                                                $('#addSizeDialog div.divForForm form').submit(addSize);
                                            }
                                            else
                                            {
                                                $('#addSizeDialog div.divForForm').html(data.div);
                                                setTimeout(\"$('#addSizeDialog').dialog('close') \",1000);
                                                $('#ProdModels_size_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
                                            }
                                                                    }",
))
                                ?>
                                return false;
                            }
                        </script>
                    </td>
                    <td><?php echo $form->labelEx($model, 'color_id'); ?></td>
                    <td>
                        <?php
                        echo $form->dropDownList(
                            $model, 'color_id', CriteriaValues::model()->findAllColorNames(), array(
                            'prompt' => 'Select',
                            'class' => 'unitSelectOpt',
                        ));
                        ?>
                        <?php
                        echo CHtml::link('', "", // the link for open the dialog
                                array(
                            'class' => 'add-additional-btn',
                            'onclick' => "{addColor(); $('#addColorDialog').dialog('open');}"));
                        ?>

                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                            'id' => 'addColorDialog',
                            'options' => array(
                                'title' => 'Add Color',
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
                            function addColor(){
<?php
echo CHtml::ajax(array(
    'url' => array('criteriaValues/createColorFromOutSide'),
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
                                                $('#addColorDialog div.divForForm').html(data.div);
                                                      // Here is the trick: on submit-> once again this function!
                                                $('#addColorDialog div.divForForm form').submit(addColor);
                                            }
                                            else
                                            {
                                                $('#addColorDialog div.divForForm').html(data.div);
                                                setTimeout(\"$('#addColorDialog').dialog('close') \",1000);
                                                $('#ProdModels_color_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
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
                    <td><?php echo $form->error($model, 'size_id'); ?></td>
                    <td></td>
                    <td><?php echo $form->error($model, 'color_id'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'weight'); ?></td>
                    <td><?php echo $form->textField($model, 'weight'); ?></td> 
                    <td><?php echo $form->labelEx($model, 'weight_unit_id'); ?></td>
                    <td>
                        <?php
                        echo $form->dropDownList(
                                $model, 'weight_unit_id', Units::model()->findAllWeightUnitNames(), array(
                            'prompt' => 'Select',
                            'class' => 'unitSelectOpt',
                        ));
                        ?>
                        <?php
                        echo CHtml::link('', "", // the link for open the dialog
                                array(
                            'class' => 'add-additional-btn',
                            'onclick' => "{addWeightUnit(); $('#addWightUnitDialog').dialog('open');}"));
                        ?>

                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                            'id' => 'addWightUnitDialog',
                            'options' => array(
                                'title' => 'Add Weight Unit',
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
                            function addWeightUnit(){
<?php
echo CHtml::ajax(array(
    'url' => array('units/createWeightUnitFromOutSide'),
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
                                                $('#addWightUnitDialog div.divForForm').html(data.div);
                                                      // Here is the trick: on submit-> once again this function!
                                                $('#addWightUnitDialog div.divForForm form').submit(addWeightUnit);
                                            }
                                            else
                                            {
                                                $('#addWightUnitDialog div.divForForm').html(data.div);
                                                setTimeout(\"$('#addWightUnitDialog').dialog('close') \",1000);
                                                $('#ProdModels_weight_unit_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
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
                    <td><?php echo $form->error($model, 'weight'); ?></td>
                    <td></td>
                    <td><?php echo $form->error($model, 'weight_unit_id'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'measurement'); ?></td>
                    <td><?php echo $form->textField($model, 'measurement', array('maxlength' => 255)); ?></td> 
                    <td><?php echo $form->labelEx($model, 'measurement_unit_id'); ?></td>
                    <td>
                        <?php
                        echo $form->dropDownList(
                                $model, 'measurement_unit_id', Units::model()->findAllMeasurementUnitNames(), array(
                            'prompt' => 'Select',
                            'class' => 'unitSelectOpt',
                        ));
                        ?>
                        <?php
                        echo CHtml::link('', "", // the link for open the dialog
                                array(
                            'class' => 'add-additional-btn',
                            'onclick' => "{addMeasurementUnit(); $('#addMeasurementUnitDialog').dialog('open');}"));
                        ?>

                        <?php
                        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                            'id' => 'addMeasurementUnitDialog',
                            'options' => array(
                                'title' => 'Add Measurement Unit',
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
                            function addMeasurementUnit(){
<?php
echo CHtml::ajax(array(
    'url' => array('units/createMeasurementUnitFromOutSide'),
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
                                                $('#addMeasurementUnitDialog div.divForForm').html(data.div);
                                                      // Here is the trick: on submit-> once again this function!
                                                $('#addMeasurementUnitDialog div.divForForm form').submit(addMeasurementUnit);
                                            }
                                            else
                                            {
                                                $('#addMeasurementUnitDialog div.divForForm').html(data.div);
                                                setTimeout(\"$('#addMeasurementUnitDialog').dialog('close') \",1000);
                                                $('#ProdModels_measurement_unit_id').append('<option selected value='+data.value+'>'+data.label+'</option>');
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
                    <td><?php echo $form->error($model, 'measurement'); ?></td>
                    <td></td>
                    <td><?php echo $form->error($model, 'measurement_unit_id'); ?></td>
                </tr>
            </table>
        </fieldset>
        <div id="ajaxLoader" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>

        <fieldset class="tblFooters">
            <?php
            echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('prodModels/create', 'render' => true)), array(
                'dataType' => 'json',
                'type' => 'post',
                'success' => 'function(data) {
                $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#prod-models-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $.fn.yiiGridView.update("prod-models-grid", {
		data: $(this).serialize()
	});
                    }else{
                        //$("#formResultError").html("Data not saved. Please solve the following errors.");
                        $.each(data, function(key, val) {
                            $("#prod-models-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#prod-models-form #"+key+"_em_").show();
                        });
                    }       
                }',
                'beforeSend' => 'function(){                        
                $("#ajaxLoader").show();
             }'
            ));
            ?>
        </fieldset>
    </fieldset>
</div>

<?php $this->endWidget(); ?>
