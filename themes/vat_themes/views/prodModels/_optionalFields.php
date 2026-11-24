<fieldset>
    <legend>Optional Fields</legend>
    <table style="float: left; width: 1200px;">
        <tr>                     
            <td style="width: 120px;"><?php echo $form->labelEx($model, 'pbrand'); ?></td>
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
?>;
        return false; 
    } 
                </script>
            </td>   
            <td><?php echo $form->labelEx($model, 'country_id'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                        $model, 'country_id', CHtml::listData(Countries::model()->findAll(array('order' => 'country ASC')), 'id', 'country'), array(
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
?>;
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
?>;
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
?>;
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
            <td></td>
            <td></td> 
            <td style="color: red;"><?php echo $form->labelEx($model, 'warranty'); ?></td>
            <td><?php echo $form->textField($model, 'warranty'); ?></td> 
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><?php echo $form->error($model, 'warranty'); ?></td>
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
?>;
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
?>;
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
?>;
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
?>;
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
        <tr>
            <td><?php echo $form->labelEx($model, 'features'); ?></td>
            <td colspan="3"><?php //echo $form->textArea($model, 'features', array('rows' => 11, 'cols' => 25));        ?>
                <?php
                $this->widget('ext.editMe.widgets.ExtEditMe', array(
                    'model' => $model,
                    'attribute' => 'features',
                    'height' => '120',
                    //'width' => '120',
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
            <td colspan="3"><?php echo $form->error($model, 'features'); ?></td>                    
        </tr>  
    </table>
</fieldset>