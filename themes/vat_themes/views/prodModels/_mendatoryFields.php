<fieldset>
    <legend>Required Fields</legend>
    <table>
        <tr>
            <td style="width: 120px;"><?php echo $form->labelEx($model, 'item_id'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                        $model, 'item_id', CHtml::listData(ProdItems::model()->findAll(array('order' => 'id DESC')), 'id', 'item_name'), array(
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
?>;
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
                        $model, 'brand_id', CHtml::listData(ProdBrands::model()->findAll(array('order' => 'id DESC')), 'id', 'brand_name'), array(
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
?>;
return false; 
} 
                </script> 
            </td> 
            <td>
                <div id="ajaxLoaderDrop" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
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
                <?php echo $form->textField($model, 'code', array('maxlength' => 255)); ?>
                <span id="resetBtn">Reset</span>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $("#resetBtn").click(function(){
                            $("#ProdModels_code").val('');
                        });
                    });
                </script>
            </td> 
        </tr>
        <tr>
            <td></td>
            <td><?php echo $form->error($model, 'code'); ?></td>                    
        </tr>
    </table>
</fieldset>