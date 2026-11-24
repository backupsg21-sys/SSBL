<tr>
    <td colspan="2">
        <table class="prodAdditionalInfoTab">
            <tr>
                <td><?php echo $form->labelEx($model, 'vehicle_brand'); ?></td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'vehicle_brand', CHtml::listData(CustomProdFields::model()->findAll(array('order' => 'id DESC')), 'id', 'vehicle_brand'), array(
                        'prompt' => 'Select',
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('prodModels/prodInfoOfThisAdditionalOp'),
                            'success' => 'function(data) {
                                                                $("#Inventory_model_id").html(data.prodList);
                                                         }',
                            'data' => array(
                                'vB' => 'js:jQuery("#Inventory_vehicle_brand").val()',
                                'sm1' => 'js:jQuery("#Inventory_suported_model1").val()',
                                'sm2' => 'js:jQuery("#Inventory_suported_model2").val()',
                                'sm3' => 'js:jQuery("#Inventory_suported_model3").val()',
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
                <td><?php echo $form->labelEx($model, 'suported_model1'); ?></td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'suported_model1', CHtml::listData(CustomProdFields::model()->findAll(array('order' => 'id DESC')), 'id', 'suported_model1'), array(
                        'prompt' => 'Select',
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('prodModels/prodInfoOfThisAdditionalOp'),
                            'success' => 'function(data) {
                                                                $("#Inventory_model_id").html(data.prodList);
                                                         }',
                            'data' => array(
                                'vB' => 'js:jQuery("#Inventory_vehicle_brand").val()',
                                'sm1' => 'js:jQuery("#Inventory_suported_model1").val()',
                                'sm2' => 'js:jQuery("#Inventory_suported_model2").val()',
                                'sm3' => 'js:jQuery("#Inventory_suported_model3").val()',
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
                <td><?php echo $form->labelEx($model, 'suported_model2'); ?></td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'suported_model2', CHtml::listData(CustomProdFields::model()->findAll(array('order' => 'id DESC')), 'id', 'suported_model2'), array(
                        'prompt' => 'Select',
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('prodModels/prodInfoOfThisAdditionalOp'),
                            'success' => 'function(data) {
                                                                $("#Inventory_model_id").html(data.prodList);
                                                         }',
                            'data' => array(
                                'vB' => 'js:jQuery("#Inventory_vehicle_brand").val()',
                                'sm1' => 'js:jQuery("#Inventory_suported_model1").val()',
                                'sm2' => 'js:jQuery("#Inventory_suported_model2").val()',
                                'sm3' => 'js:jQuery("#Inventory_suported_model3").val()',
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
                <td><?php echo $form->labelEx($model, 'suported_model3'); ?></td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'suported_model3', CHtml::listData(CustomProdFields::model()->findAll(array('order' => 'id DESC')), 'id', 'suported_model3'), array(
                        'prompt' => 'Select',
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('prodModels/prodInfoOfThisAdditionalOp'),
                            'success' => 'function(data) {
                                                                $("#Inventory_model_id").html(data.prodList);
                                                         }',
                            'data' => array(
                                'vB' => 'js:jQuery("#Inventory_vehicle_brand").val()',
                                'sm1' => 'js:jQuery("#Inventory_suported_model1").val()',
                                'sm2' => 'js:jQuery("#Inventory_suported_model2").val()',
                                'sm3' => 'js:jQuery("#Inventory_suported_model3").val()',
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