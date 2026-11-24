<fieldset>
    <legend>Required Fields</legend>
    <table style="float: left; width: 1200px;">
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