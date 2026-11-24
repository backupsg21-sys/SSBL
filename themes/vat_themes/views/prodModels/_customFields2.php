<fieldset>
    <legend>Custom Fields</legend>
    <table style="float: left; width: 1200px;">
        <tr>
            <td style="width: 120px;"><?php echo $form->labelEx($modelCustomProdFields, 'vehicle_brand'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                        $modelCustomProdFields, 'vehicle_brand', CHtml::listData(CustomField1::model()->findAll(array('order' => 'id DESC')), 'id', 'title'), array(
                    'prompt' => 'Select',
                ));
                ?>
                <?php
                echo CHtml::link('', "", // the link for open the dialog
                        array(
                    'class' => 'add-additional-btn',
                    'onclick' => "{addVhclBrnd(); $('#dialogAddVhclBrnd').dialog('open');}"));
                ?>

                <?php
                $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                    'id' => 'dialogAddVhclBrnd',
                    'options' => array(
                        'title' => 'Add Vehicle Brand',
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
                    function addVhclBrnd(){
<?php
echo CHtml::ajax(array(
    'url' => array('customField1/createCF1FromOutSide'),
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
                                        $('#dialogAddVhclBrnd div.divForForm').html(data.div);
                                              // Here is the trick: on submit-> once again this function!
                                        $('#dialogAddVhclBrnd div.divForForm form').submit(addVhclBrnd);
                                    }
                                    else
                                    {
                                        $('#dialogAddVhclBrnd div.divForForm').html(data.div);
                                        setTimeout(\"$('#dialogAddVhclBrnd').dialog('close') \",1000);
                                        $('#CustomProdFields_vehicle_brand').append('<option selected value='+data.value+'>'+data.label+'</option>');
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
            <td><?php echo $form->error($modelCustomProdFields, 'vehicle_brand'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($modelCustomProdFields, 'suported_model1'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                        $modelCustomProdFields, 'suported_model1', CHtml::listData(CustomField2::model()->findAll(array('order' => 'id DESC')), 'id', 'title'), array(
                    'prompt' => 'Select',
                ));
                ?>
                <?php
                echo CHtml::link('', "", // the link for open the dialog
                        array(
                    'class' => 'add-additional-btn',
                    'onclick' => "{addSupMdl1(); $('#dialogSupMdl1').dialog('open');}"));
                ?>

                <?php
                $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                    'id' => 'dialogSupMdl1',
                    'options' => array(
                        'title' => 'Add Supported Model',
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
                    function addSupMdl1(){
<?php
echo CHtml::ajax(array(
    'url' => array('customField2/createCF2FromOutSide'),
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
                                        $('#dialogSupMdl1 div.divForForm').html(data.div);
                                              // Here is the trick: on submit-> once again this function!
                                        $('#dialogSupMdl1 div.divForForm form').submit(addSupMdl1);
                                    }
                                    else
                                    {
                                        $('#dialogSupMdl1 div.divForForm').html(data.div);
                                        setTimeout(\"$('#dialogSupMdl1').dialog('close') \",1000);
                                        $('#CustomProdFields_suported_model1').append('<option selected value='+data.value+'>'+data.label+'</option>');
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
            <td><?php echo $form->error($modelCustomProdFields, 'suported_model1'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($modelCustomProdFields, 'suported_model2'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                        $modelCustomProdFields, 'suported_model2', CHtml::listData(CustomField2::model()->findAll(array('order' => 'id DESC')), 'id', 'title'), array(
                    'prompt' => 'Select',
                ));
                ?>
                <?php
                echo CHtml::link('', "", // the link for open the dialog
                        array(
                    'class' => 'add-additional-btn',
                    'onclick' => "{addSupMdl2(); $('#dialogSupMdl2').dialog('open');}"));
                ?>

                <?php
                $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                    'id' => 'dialogSupMdl2',
                    'options' => array(
                        'title' => 'Add Supported Model',
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
                    function addSupMdl2(){
<?php
echo CHtml::ajax(array(
    'url' => array('customField2/createCF2FromOutSide'),
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
                                        $('#dialogSupMdl2 div.divForForm').html(data.div);
                                              // Here is the trick: on submit-> once again this function!
                                        $('#dialogSupMdl2 div.divForForm form').submit(addSupMdl2);
                                    }
                                    else
                                    {
                                        $('#dialogSupMdl1 div.divForForm').html(data.div);
                                        setTimeout(\"$('#dialogSupMdl2').dialog('close') \",1000);
                                        $('#CustomProdFields_suported_model2').append('<option selected value='+data.value+'>'+data.label+'</option>');
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
            <td><?php echo $form->error($modelCustomProdFields, 'suported_model2'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($modelCustomProdFields, 'suported_model3'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                        $modelCustomProdFields, 'suported_model3', CHtml::listData(CustomField2::model()->findAll(array('order' => 'id DESC')), 'id', 'title'), array(
                    'prompt' => 'Select',
                ));
                ?>
                <?php
                echo CHtml::link('', "", // the link for open the dialog
                        array(
                    'class' => 'add-additional-btn',
                    'onclick' => "{addSupMdl3(); $('#dialogSupMdl3').dialog('open');}"));
                ?>

                <?php
                $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                    'id' => 'dialogSupMdl3',
                    'options' => array(
                        'title' => 'Add Supported Model',
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
                    function addSupMdl3(){
<?php
echo CHtml::ajax(array(
    'url' => array('customField2/createCF2FromOutSide'),
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
                                        $('#dialogSupMdl3 div.divForForm').html(data.div);
                                              // Here is the trick: on submit-> once again this function!
                                        $('#dialogSupMdl3 div.divForForm form').submit(addSupMdl3);
                                    }
                                    else
                                    {
                                        $('#dialogSupMdl3 div.divForForm').html(data.div);
                                        setTimeout(\"$('#dialogSupMdl3').dialog('close') \",1000);
                                        $('#CustomProdFields_suported_model3').append('<option selected value='+data.value+'>'+data.label+'</option>');
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
            <td><?php echo $form->error($modelCustomProdFields, 'suported_model3'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($modelCustomProdFields, 'suported_model4'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                        $modelCustomProdFields, 'suported_model4', CHtml::listData(CustomField2::model()->findAll(array('order' => 'id DESC')), 'id', 'title'), array(
                    'prompt' => 'Select',
                ));
                ?>
                <?php
                echo CHtml::link('', "", // the link for open the dialog
                        array(
                    'class' => 'add-additional-btn',
                    'onclick' => "{addSupMdl4(); $('#dialogSupMdl4').dialog('open');}"));
                ?>

                <?php
                $this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
                    'id' => 'dialogSupMdl4',
                    'options' => array(
                        'title' => 'Add Supported Model',
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
                    function addSupMdl4(){
<?php
echo CHtml::ajax(array(
    'url' => array('customField2/createCF2FromOutSide'),
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
                                        $('#dialogSupMdl4 div.divForForm').html(data.div);
                                              // Here is the trick: on submit-> once again this function!
                                        $('#dialogSupMdl4 div.divForForm form').submit(addSupMdl4);
                                    }
                                    else
                                    {
                                        $('#dialogSupMdl4 div.divForForm').html(data.div);
                                        setTimeout(\"$('#dialogSupMdl4').dialog('close') \",1000);
                                        $('#CustomProdFields_suported_model4').append('<option selected value='+data.value+'>'+data.label+'</option>');
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
            <td><?php echo $form->error($modelCustomProdFields, 'suported_model4'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($modelCustomProdFields, 'cc'); ?></td>
            <td><?php echo $form->textField($modelCustomProdFields, 'cc'); ?></td> 
        </tr>
        <tr>
            <td></td>
            <td><?php echo $form->error($modelCustomProdFields, 'cc'); ?></td>
        </tr>
    </table>
</fieldset>